<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Broker;
use App\Models\Review;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewReplyController extends Controller
{
    public function __construct(
        private UserNotificationService $notifications
    ) {}

    public function store(Request $request, Broker $broker, Review $review)
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);
        abort_unless((int) $review->broker_id === (int) $broker->id, 404);
        abort_unless($review->isRoot() && $review->isApproved(), 422);

        $validated = $request->validate([
            'description' => 'required|string|min:2|max:2000',
        ]);

        $reply = $broker->reviews()->create([
            'parent_id' => $review->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'description' => $validated['description'],
            'rating' => 0,
            'country' => $user->country ?: 'N/A',
            'status' => 0,
        ]);

        ActivityLog::record(
            'review_reply_submitted',
            'Replied to a review on ' . $broker->name,
            $user->id,
        );

        $reply->load(['broker', 'user', 'parent']);
        $this->notifications->notifyReviewSubmitted($reply);

        return redirect()->to(url()->previous() . '#voices')
            ->with('success', 'Your reply has been submitted and is pending approval.');
    }
}
