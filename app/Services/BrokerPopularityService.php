<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Collection;

class BrokerPopularityService
{
    private const GRID_SIZE = 9;

    private const LEADERBOARD_SIZE = 6;

    public function forHomepage(): array
    {
        $reviewCounts = $this->reviewCountsByBroker();

        $recommended = $this->recommended($reviewCounts);
        $scam = $this->scamBrokers($reviewCounts);
        $ranking = $this->popularityRanking($reviewCounts);

        return [
            'recommended' => $recommended,
            'scam' => $scam,
            'ranking' => $ranking,
        ];
    }

    /** @return Collection<int, int> */
    private function reviewCountsByBroker(): Collection
    {
        return Broker::query()
            ->withCount([
                'reviews as approved_reviews_count' => fn ($query) => $query->where('status', 1),
            ])
            ->pluck('approved_reviews_count', 'id');
    }

    private function recommended(Collection $reviewCounts): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->where('top_broker', '>', 0)
            ->orderByDesc('top_broker')
            ->take(self::GRID_SIZE)
            ->get()
            ->map(fn (Broker $broker) => $this->formatBroker(
                $broker,
                (int) $broker->top_broker,
                (int) ($reviewCounts[$broker->id] ?? 0),
            ));
    }

    private function scamBrokers(Collection $reviewCounts): Collection
    {
        return Broker::query()
            ->where('is_scam', true)
            ->orderByDesc('scam_reported_date')
            ->orderBy('name')
            ->take(self::GRID_SIZE)
            ->get()
            ->values()
            ->map(fn (Broker $broker, int $index) => $this->formatBroker(
                $broker,
                $index + 1,
                (int) ($reviewCounts[$broker->id] ?? 0),
                true,
            ));
    }

    private function popularityRanking(Collection $reviewCounts): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->whereNotNull('rating')
            ->get()
            ->map(function (Broker $broker) use ($reviewCounts) {
                $reviews = (int) ($reviewCounts[$broker->id] ?? 0);

                return $this->formatBroker($broker, 0, $reviews);
            })
            ->sortByDesc(fn (array $item) => $item['popularity_score'])
            ->take(self::LEADERBOARD_SIZE)
            ->values()
            ->map(function (array $item, int $index) {
                $item['rank'] = $index + 1;

                return $item;
            });
    }

    private function formatBroker(
        Broker $broker,
        int $displayRank,
        int $approvedReviews,
        bool $isScamListing = false,
    ): array {
        $rating = $broker->rating !== null ? round((float) $broker->rating, 1) : null;
        $trustScore = $broker->trust_score !== null ? (int) $broker->trust_score : null;
        $popularityScore = $this->popularityScore($broker, $approvedReviews);

        return [
            'broker' => $broker,
            'rank' => $displayRank,
            'top_broker' => (int) ($broker->top_broker ?? 0),
            'rating' => $rating,
            'trust_score' => $trustScore,
            'review_count' => $approvedReviews,
            'popularity_score' => $popularityScore,
            'review_url' => $isScamListing
                ? route('scam_broker_detail', ['slug' => $broker->scam_slug])
                : route('broker_detail', $broker->slug),
            'scam_reason' => $broker->scam_reason
                ? \Illuminate\Support\Str::limit(trim(strip_tags($broker->scam_reason)), 120)
                : null,
            'reported_label' => $broker->scam_reported_date
                ? $broker->scam_reported_date->format('M j, Y')
                : null,
        ];
    }

    private function popularityScore(Broker $broker, int $approvedReviews): int
    {
        $rating = (float) ($broker->rating ?? 0);
        $trust = min(100, max(0, (int) ($broker->trust_score ?? 0)));
        $editorRank = min(100, max(0, (int) ($broker->top_broker ?? 0)));
        $reviews = min(10, $approvedReviews);
        $featured = $broker->featured_broker ? 10 : 0;

        return (int) round(
            ($rating / 5 * 35)
            + ($trust / 100 * 25)
            + ($editorRank / 100 * 20)
            + ($reviews / 10 * 10)
            + $featured
        );
    }
}
