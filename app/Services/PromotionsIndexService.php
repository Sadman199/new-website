<?php

namespace App\Services;

use App\Models\ForexBonus;
use Illuminate\Support\Collection;

class PromotionsIndexService
{
    public const INITIAL_CARDS = 12;

    public const LOAD_MORE_SIZE = 12;

    /** @var array<string, array{name: string, promo_type: string}> */
    private const TABS = [
        'deposit-bonuses' => ['name' => 'Deposit Bonuses', 'promo_type' => 'Forex Deposit Bonus'],
        'no-deposit-bonuses' => ['name' => 'No Deposit Bonus', 'promo_type' => 'Forex No Deposit Bonus'],
        'live-contests' => ['name' => 'Live Contests', 'promo_type' => 'Forex Live Contest'],
        'cashback-rebates' => ['name' => 'Cashback', 'promo_type' => 'Forex Cashback Rebate'],
        'crypto-bonuses' => ['name' => 'Crypto Contests', 'promo_type' => 'Crypto Bonus Promotion'],
    ];

    /** @return array<string, mixed> */
    public function buildIndex(?string $type = null): array
    {
        $activeType = $type && isset(self::TABS[$type]) ? $type : 'deposit-bonuses';
        $tabs = $this->tabs();
        $allPromotions = $this->getActivePromotionsForTab($activeType);
        $totalCount = $allPromotions->count();
        $initialCards = $allPromotions
            ->take(self::INITIAL_CARDS)
            ->map(fn (ForexBonus $bonus) => $this->serializeCard($bonus))
            ->values();

        $activeTabMeta = collect($tabs)->firstWhere('slug', $activeType) ?? $tabs[0];

        return [
            'tabs' => $tabs,
            'activeTab' => $activeType,
            'activeTabName' => $activeTabMeta['name'] ?? 'Deposit Bonuses',
            'stats' => $this->stats(),
            'cards' => $initialCards,
            'totalCount' => $totalCount,
            'loadedCount' => $initialCards->count(),
            'hasMore' => $totalCount > self::INITIAL_CARDS,
        ];
    }

    /** @return array<string, mixed> */
    public function loadMore(string $type, int $offset): array
    {
        $activeType = isset(self::TABS[$type]) ? $type : 'deposit-bonuses';
        $allPromotions = $this->getActivePromotionsForTab($activeType);
        $totalCount = $allPromotions->count();
        $batch = $allPromotions
            ->slice($offset, self::LOAD_MORE_SIZE)
            ->map(fn (ForexBonus $bonus) => $this->serializeCard($bonus))
            ->values();

        $loadedCount = min($offset + $batch->count(), $totalCount);

        return [
            'activeTab' => $activeType,
            'cards' => $batch,
            'totalCount' => $totalCount,
            'loadedCount' => $loadedCount,
            'hasMore' => $loadedCount < $totalCount,
            'nextOffset' => $offset + $batch->count(),
        ];
    }

    /** @return Collection<int, ForexBonus> */
    protected function getActivePromotionsForTab(string $activeType): Collection
    {
        $promoType = self::TABS[$activeType]['promo_type'];

        return $this->activePromotionsQuery($promoType)
            ->get()
            ->filter(fn (ForexBonus $bonus) => $bonus->isActivePromotion())
            ->values();
    }

    /** @return array<int, array<string, mixed>> */
    private function tabs(): array
    {
        $tabs = [];

        foreach (self::TABS as $slug => $meta) {
            $count = $this->activePromotionsQuery($meta['promo_type'])
                ->get()
                ->filter(fn (ForexBonus $bonus) => $bonus->isActivePromotion())
                ->count();

            $tabs[] = [
                'slug' => $slug,
                'name' => $meta['name'],
                'count' => $count,
                'url' => $slug === 'deposit-bonuses'
                    ? route('promotions.index')
                    : route('promotions.tab', ['type' => $slug]),
            ];
        }

        return $tabs;
    }

    /** @return array<string, int> */
    private function stats(): array
    {
        $active = $this->activePromotionsQuery()->count();

        return [
            'total_active' => $active,
            'categories' => count(self::TABS),
            'featured' => $this->activePromotionsQuery()->where('is_featured', true)->count(),
            'ending_soon' => $this->activePromotionsQuery()
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now()->addDays(14))
                ->count(),
        ];
    }

    /** @return array<string, mixed> */
    public function serializeCard(ForexBonus $bonus): array
    {
        $broker = $bonus->broker;

        return [
            'id' => $bonus->id,
            'title' => $bonus->title,
            'url' => $bonus->cardUrl(),
            'offer' => $bonus->headlineOffer(),
            'type_short' => $bonus->promoTypeShort(),
            'type_tone' => $bonus->promoTypeTone(),
            'broker_name' => $bonus->brokerDisplayName(),
            'broker_logo' => $broker?->logo ? asset($broker->logo) : null,
            'broker_rating' => $broker?->rating !== null ? round((float) $broker->rating, 1) : null,
            'broker_review_url' => $broker
                ? route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($broker)])
                : null,
            'feature_image' => $bonus->feature_image ? asset($bonus->feature_image) : null,
            'min_deposit' => $bonus->minDepositLabel(),
            'expiry' => $bonus->expiryLabel(),
            'is_featured' => (bool) $bonus->is_featured,
            'is_limited' => $bonus->promotion_status === 'limited-time',
            'is_urgent' => $bonus->expiry_date && $bonus->expiry_date->lte(now()->addDays(14)),
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Builder<ForexBonus> */
    private function activePromotionsQuery(?string $promoType = null)
    {
        $query = ForexBonus::query()
            ->with('broker')
            ->where(function ($query) {
                $query->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('promotion_status')
                    ->orWhereIn('promotion_status', ['ongoing', 'limited-time']);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('publish_date');

        if ($promoType) {
            $query->where('promo_type', $promoType);
        }

        return $query;
    }

    /** @return \Illuminate\Support\Collection<int, ForexBonus> */
    public function latestActivePromotions(string $promoType, int $limit = 4): Collection
    {
        return $this->activePromotionsQuery($promoType)
            ->limit($limit)
            ->get()
            ->filter(fn (ForexBonus $bonus) => $bonus->isActivePromotion())
            ->values();
    }
}
