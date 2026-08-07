<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\ForexBonus;
use Illuminate\Support\Collection;

class BlogPostDetailService
{
    public function __construct(
        private readonly PromotionsIndexService $promotionsIndexService,
    ) {}

    /** @return Collection<int, array<string, mixed>> */
    public function recommendedBrokers(int $limit = 5): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->orderByDesc('rating')
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (Broker $broker) => [
                'name' => $broker->name,
                'logo' => $broker->logo ? asset($broker->logo) : null,
                'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
                'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            ])
            ->values();
    }

    /** @return Collection<int, ForexBonus> */
    public function latestDepositBonuses(int $limit = 4): Collection
    {
        return $this->promotionsIndexService
            ->latestActivePromotions('Forex Deposit Bonus', $limit);
    }
}
