<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\ForexBonus;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class BlogPostDetailService
{
    public function __construct(
        private readonly PromotionsIndexService $promotionsIndexService,
    ) {}

    /** @return Collection<int, array<string, mixed>> */
    public function recommendedBrokersFor(?Post $post = null, int $limit = 5): Collection
    {
        if ($post && Schema::hasTable('post_broker')) {
            $related = $post->relationLoaded('brokers')
                ? $post->brokers->where('is_scam', false)->sortBy('name')->take($limit)->values()
                : $post->brokers()->where('is_scam', false)->orderBy('name')->limit($limit)->get();

            if ($related->isNotEmpty()) {
                return $this->mapBrokers($related);
            }
        }

        return $this->recommendedBrokers($limit);
    }

    /** @return Collection<int, array<string, mixed>> */
    public function recommendedBrokers(int $limit = 5): Collection
    {
        return $this->mapBrokers(
            Broker::query()
                ->where('is_scam', false)
                ->orderByDesc('rating')
                ->orderBy('name')
                ->limit($limit)
                ->get()
        );
    }

    /** @param Collection<int, Broker> $brokers */
    protected function mapBrokers(Collection $brokers): Collection
    {
        return $brokers
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
