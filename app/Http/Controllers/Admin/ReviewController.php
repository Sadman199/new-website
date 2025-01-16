<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Review;
use App\Models\Broker;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Broker $broker)
{
    // Validate request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'description' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'country' => 'required|string|max:255',
    ]);

    // Check if a review already exists for this email and broker
    $existingReview = $broker->reviews()->where('email', $request->email)->first();
    if ($existingReview) {
        return redirect()->back()->with('error', 'You have already submitted a review for this broker.');
    }

    // Store the review
    $broker->reviews()->create([
        'name' => $request->name,
        'email' => $request->email,
        'description' => $request->description,
        'rating' => $request->rating,
        'country' => $request->country,
        'status' => 0, // Pending approval
    ]);

    // Add session flag for this specific broker
    session(['review_submitted_' . $broker->id => $request->email]);

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