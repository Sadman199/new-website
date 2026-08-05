<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\ActivityLog;
use App\Models\Review;
use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Broker $broker)
{
    // Only authenticated front-end users may submit reviews.
    if (! Auth::guard('web')->check()) {
        return redirect()->route('user.login')
            ->with('error', 'Please log in to write a review.');
    }

    $user = Auth::guard('web')->user();

    // Validate request data (reviewer identity comes from the account).
    $request->validate([
        'description' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'country' => 'nullable|string|max:255',
    ]);

    // Prevent the same user reviewing the same broker twice.
    $existingReview = $broker->reviews()->where('user_id', $user->id)->first();
    if ($existingReview) {
        return redirect()->back()->with('error', 'You have already submitted a review for this broker.');
    }

    // Store the review, auto-linked to the account.
    $broker->reviews()->create([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'description' => $request->description,
        'rating' => $request->rating,
        'country' => $request->country ?: ($user->country ?: 'N/A'),
        'status' => 0, // Pending approval
    ]);

    ActivityLog::record('review_submitted', 'Submitted a review for ' . $broker->name, $user->id);

    // Add session flag for this specific broker
    session(['review_submitted_' . $broker->id => $user->email]);

    return redirect()->back()->with('success', 'Your review has been submitted and is pending approval.');
}

    // Show pending reviews for admin

    public function pending()
{
    $reviews = Review::where('status', 0)->with('broker')->get(); // Fetch pending reviews with broker details
    return view('admin.reviews.pending', compact('reviews'));
}

    

    // Approve a review
    public function approve(Review $review)
    {
        $review->update(['status' => 1]); // 1: Approved
        return redirect()->back()->with('success', 'Review approved.');
    }

    // Decline a review
    public function decline(Review $review)
    {
        $review->update(['status' => -1]); // -1: Declined
        return redirect()->back()->with('success', 'Review declined.');
    }
}