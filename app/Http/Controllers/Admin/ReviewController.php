<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Broker;
use App\Models\Review;
use App\Services\BrokerReviewCommunityService;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function __construct(
        private UserNotificationService $notifications,
        private BrokerReviewCommunityService $community
    ) {}

    public function store(Request $request, Broker $broker)
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('user.login')
                ->with('error', 'Please log in to write a review.');
        }

        $user = Auth::guard('web')->user();
        $allowedAccountTypes = $this->community->activeAccountTypes($broker);

        $validated = $request->validate([
            'description' => 'required|string|min:20|max:5000',
            'rating_cost' => 'required|integer|min:1|max:5',
            'rating_platforms' => 'required|integer|min:1|max:5',
            'rating_customer_support' => 'required|integer|min:1|max:5',
            'length_of_use' => ['required', Rule::in(array_keys(Review::LENGTH_OF_USE_OPTIONS))],
            'account_type' => ['required', 'string', Rule::in($allowedAccountTypes)],
            'country' => 'nullable|string|max:255',
        ]);

        $existingReview = $broker->reviews()
            ->roots()
            ->where('user_id', $user->id)
            ->first();

        if ($existingReview) {
            if ($existingReview->isPending()) {
                return redirect()->back()->with('error', 'You have a pending review for this broker. Update it in the form below.');
            }

            return redirect()->back()->with('error', 'You have already submitted a review for this broker.');
        }

        $overall = Review::overallFromDimensions(
            (int) $validated['rating_cost'],
            (int) $validated['rating_platforms'],
            (int) $validated['rating_customer_support'],
        );

        $review = $broker->reviews()->create([
            'user_id' => $user->id,
            'parent_id' => null,
            'name' => $user->name,
            'email' => $user->email,
            'description' => $validated['description'],
            'rating' => $overall,
            'rating_cost' => (int) $validated['rating_cost'],
            'rating_platforms' => (int) $validated['rating_platforms'],
            'rating_customer_support' => (int) $validated['rating_customer_support'],
            'length_of_use' => $validated['length_of_use'],
            'account_type' => $validated['account_type'],
            'country' => $validated['country'] ?: ($user->country ?: 'N/A'),
            'status' => 0,
        ]);

        ActivityLog::record('review_submitted', 'Submitted a review for ' . $broker->name, $user->id);

        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewSubmitted($review);

        session(['review_submitted_' . $broker->id => $user->email]);

        return redirect()->to(url()->previous() . '#voices')
            ->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function pending(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'status' => (string) $request->get('status', 'pending'),
            'kind' => (string) $request->get('kind', ''),
            'broker_id' => (string) $request->get('broker_id', ''),
        ];

        if (! in_array($filters['status'], ['pending', 'approved', 'declined'], true)) {
            $filters['status'] = 'pending';
        }

        $statusMap = ['pending' => 0, 'approved' => 1, 'declined' => -1];

        $query = Review::query()
            ->with(['broker', 'parent', 'user'])
            ->where('status', $statusMap[$filters['status']])
            ->latest();

        if ($filters['q'] !== '') {
            $search = $filters['q'];
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($filters['kind'] === 'review') {
            $query->whereNull('parent_id');
        } elseif ($filters['kind'] === 'reply') {
            $query->whereNotNull('parent_id');
        }

        if ($filters['broker_id'] !== '') {
            $query->where('broker_id', $filters['broker_id']);
        }

        $reviews = $query->paginate(12)->withQueryString();

        return view('admin.reviews.pending', [
            'reviews' => $reviews,
            'filters' => $filters,
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name']),
            'stats' => [
                'pending' => Review::query()->where('status', 0)->count(),
                'approved' => Review::query()->where('status', 1)->count(),
                'declined' => Review::query()->where('status', -1)->count(),
            ],
        ]);
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 1]);
        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewApproved($review);

        $label = $review->isReply() ? 'Reply' : 'Review';

        return redirect()->back()->with('success', $label . ' approved.');
    }

    public function decline(Review $review)
    {
        $review->update(['status' => -1]);
        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewDeclined($review);

        $label = $review->isReply() ? 'Reply' : 'Review';

        return redirect()->back()->with('success', $label . ' declined.');
    }
}
