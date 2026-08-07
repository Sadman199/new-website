<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BrokerReport;
use App\Services\BrokerSafetyScoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrokerScamCheckerController extends Controller
{
    public function index(BrokerSafetyScoreService $safetyService)
    {
        $query = trim((string) request('q', ''));
        $analysis = null;

        if ($query !== '') {
            $broker = $safetyService->findBroker($query);
            if ($broker) {
                return redirect()->route('broker.scam_checker.show', ['slug' => $broker->listingSlug()]);
            }

            return view('front.broker-scam-checker.index', [
                'analysis' => null,
                'unknownQuery' => $query,
                'examples' => collect(['XM', 'IG Markets', 'IC Markets']),
                'issueTypes' => BrokerReport::REPORT_ISSUE_TYPES,
            ]);
        }

        $examples = BrokerReport::query()
            ->whereNotNull('broker_id')
            ->with('broker')
            ->latest()
            ->limit(3)
            ->get()
            ->pluck('broker.name')
            ->filter()
            ->values();

        if ($examples->isEmpty()) {
            $examples = collect(['XM', 'IG Markets', 'IC Markets']);
        }

        return view('front.broker-scam-checker.index', [
            'analysis' => $analysis,
            'examples' => $examples,
            'issueTypes' => BrokerReport::REPORT_ISSUE_TYPES,
        ]);
    }

    public function show(string $slug, BrokerSafetyScoreService $safetyService)
    {
        $analysis = $safetyService->analyzeBySlug($slug);

        abort_if($analysis === null, 404);

        $year = date('Y');
        $brokerName = $analysis['broker']['name'];

        return view('front.broker-scam-checker.index', [
            'analysis' => $analysis,
            'examples' => collect([$brokerName]),
            'issueTypes' => BrokerReport::REPORT_ISSUE_TYPES,
            'pageTitle' => "Is {$brokerName} Safe? Scam Check & Safety Review {$year}",
            'metaDescription' => "Check {$brokerName} regulation, trust score, risk factors and safety rating before trading.",
        ]);
    }

    public function search(Request $request, BrokerSafetyScoreService $safetyService)
    {
        $query = trim((string) $request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        return response()->json([
            'results' => $safetyService->searchSuggestions($query),
        ]);
    }

    public function compare(Request $request, BrokerSafetyScoreService $safetyService)
    {
        $validated = $request->validate([
            'brokers' => ['required', 'array', 'min:2', 'max:3'],
            'brokers.*' => ['required', 'string', 'max:120'],
        ]);

        $results = collect($validated['brokers'])
            ->map(fn (string $query) => $safetyService->findBroker($query))
            ->filter()
            ->unique(fn ($broker) => $broker->id)
            ->map(fn ($broker) => $safetyService->analyze($broker))
            ->values()
            ->all();

        if (count($results) < 2) {
            return response()->json(['message' => 'Could not find at least two brokers to compare.'], 422);
        }

        return response()->json(['brokers' => $results]);
    }

    public function storeReport(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'broker_id' => ['required', 'integer', 'exists:brokers,id'],
            'issue_type' => ['required', Rule::in(array_keys(BrokerReport::REPORT_ISSUE_TYPES))],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
        ], [
            'broker_id.required' => 'Broker information is missing. Please refresh and try again.',
            'broker_id.exists' => 'The selected broker could not be found.',
            'issue_type.required' => 'Please select an issue type.',
            'issue_type.in' => 'Please choose a valid issue type.',
            'message.required' => 'Please describe the issue in the message field.',
            'message.min' => 'Your message must be at least 20 characters.',
            'message.max' => 'Your message may not exceed 5000 characters.',
        ]);

        $broker = \App\Models\Broker::findOrFail($validated['broker_id']);

        $existingReport = BrokerReport::query()
            ->where('broker_id', $broker->id)
            ->where('reporter_email', $user->email)
            ->where('status', 'pending')
            ->exists();

        if ($existingReport) {
            $message = 'You already have a pending report for this broker. Our team is reviewing it.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message)->with('open_report_modal', true);
        }

        BrokerReport::create([
            'broker_id' => $broker->id,
            'broker_name' => $broker->name,
            'reporter_name' => $user->name,
            'reporter_email' => $user->email,
            'issue_type' => $validated['issue_type'],
            'message' => $validated['message'],
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        $successMessage = 'Thank you. Your report has been submitted and will be reviewed by our editorial team.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $successMessage,
                'issue_label' => BrokerReport::REPORT_ISSUE_TYPES[$validated['issue_type']],
            ]);
        }

        return back()->with('success', $successMessage);
    }

    public function requestReview(Request $request)
    {
        $validated = $request->validate([
            'broker_name' => ['required', 'string', 'max:190'],
            'reporter_name' => ['required', 'string', 'max:120'],
            'reporter_email' => ['required', 'email', 'max:190'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        BrokerReport::create([
            'broker_name' => $validated['broker_name'],
            'reporter_name' => $validated['reporter_name'],
            'reporter_email' => $validated['reporter_email'],
            'issue_type' => 'verification_request',
            'message' => $validated['message'] ?: 'Broker verification requested via scam checker.',
            'status' => 'pending',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Verification request received. Our team will review this broker.');
    }
}
