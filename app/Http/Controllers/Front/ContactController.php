<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use App\Services\ContactIndexService;
use App\Support\ContactBotGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function index(ContactIndexService $contactIndexService, ContactBotGuard $botGuard)
    {
        $botGuard->stampFormSession();

        $languageId = $contactIndexService->resolveLanguageId();
        $indexData = $contactIndexService->buildIndex($languageId);

        return view('front.contact.index', $indexData);
    }

    public function submitForm(Request $request, ContactBotGuard $botGuard)
    {
        if ($botGuard->isBot($request)) {
            return $this->fakeSuccessResponse();
        }

        $key = 'contact_form:' . $request->ip();
        $maxAttempts = 3;
        $decaySeconds = 300;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return back()->withErrors([
                'message' => 'You are submitting too many requests. Please try again later.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'terms' => 'accepted',
        ]);

        RateLimiter::hit($key, $decaySeconds);

        ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => ContactInquiry::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        session()->forget('contact_form_started_at');

        return redirect()
            ->route('contact')
            ->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }

    private function fakeSuccessResponse()
    {
        session()->forget('contact_form_started_at');

        return redirect()
            ->route('contact')
            ->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
}
