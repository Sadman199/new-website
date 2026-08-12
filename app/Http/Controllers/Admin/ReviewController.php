<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Broker;
use App\Models\Review;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        private UserNotificationService $notifications
    ) {}

    public function store(Request $request, Broker $broker)
    {
        if (! Auth::guard('web')->check()) {
            return redirect()->route('user.login')
                ->with('error', 'Please log in to write a review.');
        }

        $user = Auth::guard('web')->user();

        $request->validate([
            'description' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'country' => 'nullable|string|max:255',
        ]);

        $existingReview = $broker->reviews()->where('user_id', $user->id)->first();
        if ($existingReview) {
            if ($existingReview->isPending()) {
                return redirect()->back()->with('error', 'You have a pending review for this broker. Update it in the form below.');
            }

            return redirect()->back()->with('error', 'You have already submitted a review for this broker.');
        }

        $review = $broker->reviews()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'description' => $request->description,
            'rating' => $request->rating,
            'country' => $request->country ?: ($user->country ?: 'N/A'),
            'status' => 0,
        ]);

        ActivityLog::record('review_submitted', 'Submitted a review for ' . $broker->name, $user->id);

        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewSubmitted($review);

        session(['review_submitted_' . $broker->id => $user->email]);

        return redirect()->back()->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function pending()
    {
        $reviews = Review::where('status', 0)->with('broker')->get();

        return view('admin.reviews.pending', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 1]);
        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewApproved($review);

        return redirect()->back()->with('success', 'Review approved.');
    }

    public function decline(Review $review)
    {
        $review->update(['status' => -1]);
        $review->load(['broker', 'user']);
        $this->notifications->notifyReviewDeclined($review);

        return redirect()->back()->with('success', 'Review declined.');
    }
}
