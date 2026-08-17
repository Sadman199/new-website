<?php

namespace App\Services;

use App\Models\AccountOption;
use App\Models\Broker;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BrokerReviewCommunityService
{
    /**
     * @return array{
     *     approved_reviews: Collection<int, Review>,
     *     reviewStats: array{count: int, average: float|null, average10: float|null},
     *     userReview: ?Review,
     *     reviewFilters: array{score: string, length: string, account_type: string},
     *     reviewFilterOptions: array{scores: array<string, string>, lengths: array<string, string>, account_types: array<int, string>},
     *     reviewAccountTypes: array<int, string>
     * }
     */
    public function forBrokerDetail(Broker $broker, Request $request, ?int $userId = null): array
    {
        $accountTypes = $this->activeAccountTypes($broker);
        $filters = $this->normalizeFilters($request);

        $statsQuery = $broker->reviews()->approvedRoots();
        $count = (clone $statsQuery)->count();
        $average = $count > 0 ? round((float) (clone $statsQuery)->avg('rating'), 1) : null;

        $approved = $broker->reviews()
            ->approvedRoots()
            ->with(['user', 'approvedReplies.user'])
            ->latest()
            ->get();

        $filtered = $approved->filter(fn (Review $review) => $this->matchesFilters($review, $filters))->values();

        foreach ($filtered as $review) {
            $review->formatted_date = $review->created_at->format('M d, Y');
            $review->time_ago = $review->created_at->diffForHumans();
            foreach ($review->approvedReplies as $reply) {
                $reply->formatted_date = $reply->created_at->format('M d, Y');
                $reply->time_ago = $reply->created_at->diffForHumans();
            }
        }

        $userReview = $userId
            ? $broker->reviews()->roots()->where('user_id', $userId)->latest('id')->first()
            : null;

        return [
            'approved_reviews' => $filtered,
            'reviewStats' => [
                'count' => $count,
                'average' => $average,
                'average10' => $average !== null ? round($average * 2, 1) : null,
            ],
            'userReview' => $userReview,
            'reviewFilters' => $filters,
            'reviewFilterOptions' => [
                'scores' => Review::SCORE_FILTER_OPTIONS,
                'lengths' => array_merge(['all' => 'All Length of Use'], Review::LENGTH_OF_USE_OPTIONS),
                'account_types' => $accountTypes,
            ],
            'reviewAccountTypes' => $accountTypes,
        ];
    }

    /**
     * @return array<int, string>
     */
    public function activeAccountTypes(Broker $broker): array
    {
        return AccountOption::query()
            ->where('broker_id', $broker->id)
            ->active()
            ->ordered()
            ->pluck('account_type')
            ->filter(fn ($type) => is_string($type) && trim($type) !== '')
            ->map(fn ($type) => trim($type))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array{score: string, length: string, account_type: string}
     */
    public function normalizeFilters(Request $request): array
    {
        $score = (string) $request->query('score', 'all');
        $length = (string) $request->query('length', 'all');
        $accountType = (string) $request->query('account_type', 'all');

        if (! array_key_exists($score, Review::SCORE_FILTER_OPTIONS)) {
            $score = 'all';
        }

        if ($length !== 'all' && ! array_key_exists($length, Review::LENGTH_OF_USE_OPTIONS)) {
            $length = 'all';
        }

        return [
            'score' => $score,
            'length' => $length,
            'account_type' => $accountType !== '' ? $accountType : 'all',
        ];
    }

    /**
     * @param  array{score: string, length: string, account_type: string}  $filters
     */
    public function matchesFilters(Review $review, array $filters): bool
    {
        if ($filters['length'] !== 'all' && $review->length_of_use !== $filters['length']) {
            return false;
        }

        if ($filters['account_type'] !== 'all' && $review->account_type !== $filters['account_type']) {
            return false;
        }

        $score10 = $review->score10();

        return match ($filters['score']) {
            'outstanding' => $score10 >= 9,
            'good' => $score10 >= 7 && $score10 < 9,
            'average' => $score10 >= 5 && $score10 < 7,
            'poor' => $score10 < 5,
            default => true,
        };
    }
}
