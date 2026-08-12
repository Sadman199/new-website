<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function update(Request $request, Review $review)
    {
        $user = Auth::guard('web')->user();
        abort_unless($review->canBeEditedBy($user), 403);

        $validated = $request->validate([
            'description' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $review->update([
            'name' => $user->name,
            'email' => $user->email,
            'description' => $validated['description'],
            'rating' => $validated['rating'],
            'country' => $validated['country'] ?: ($user->country ?: 'N/A'),
            'status' => 0,
        ]);

        ActivityLog::record(
            'review_updated',
            'Updated pending review for ' . ($review->broker->name ?? 'broker'),
            $user->id,
        );

        return redirect()
            ->back()
            ->with('success', 'Your review has been updated and is still pending approval.');
    }

    public function destroy(Review $review)
    {
        $user = Auth::guard('web')->user();
        abort_unless($review->canBeEditedBy($user), 403);

        $brokerName = $review->broker->name ?? 'broker';
        $review->delete();

        ActivityLog::record('review_deleted', 'Deleted pending review for ' . $brokerName, $user->id);

        return redirect()
            ->route('user.profile', ['tab' => 'overview'])
            ->with('success', 'Your pending review has been deleted.');
    }
}
