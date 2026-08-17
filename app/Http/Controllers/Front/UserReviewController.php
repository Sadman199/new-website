<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use App\Services\BrokerReviewCommunityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserReviewController extends Controller
{
    public function __construct(
        private BrokerReviewCommunityService $community
    ) {}

    public function update(Request $request, Review $review)
    {
        $user = Auth::guard('web')->user();
        abort_unless($review->canBeEditedBy($user), 403);

        $broker = $review->broker;
        abort_unless($broker, 404);

        $allowedAccountTypes = $this->community->activeAccountTypes($broker);

        $validated = $request->validate([
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'rating_cost' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_platforms' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_customer_support' => ['required', 'integer', 'min:1', 'max:5'],
            'length_of_use' => ['required', Rule::in(array_keys(Review::LENGTH_OF_USE_OPTIONS))],
            'account_type' => ['required', 'string', Rule::in($allowedAccountTypes)],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $overall = Review::overallFromDimensions(
            (int) $validated['rating_cost'],
            (int) $validated['rating_platforms'],
            (int) $validated['rating_customer_support'],
        );

        $review->update([
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

        ActivityLog::record(
            'review_updated',
            'Updated pending review for ' . ($broker->name ?? 'broker'),
            $user->id,
        );

        return redirect()
            ->to(url()->previous() . '#voices')
            ->with('success', 'Your review has been updated and is still pending approval.');
    }

    public function destroy(Review $review)
    {
        $user = Auth::guard('web')->user();
        abort_unless(
            $user
            && (int) $review->user_id === (int) $user->id
            && $review->isPending(),
            403
        );

        $brokerName = $review->broker->name ?? 'broker';
        $review->delete();

        ActivityLog::record('review_deleted', 'Deleted pending review for ' . $brokerName, $user->id);

        return redirect()
            ->route('user.profile', ['tab' => 'overview'])
            ->with('success', 'Your pending review has been deleted.');
    }
}
