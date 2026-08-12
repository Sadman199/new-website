<?php

namespace App\Services;

use App\Models\ForexBonus;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PromotionsIndexService
{
    public const TAB_ALL = 'all';

    public const INITIAL_CARDS = 12;

    public const LOAD_MORE_SIZE = 12;

    public const FEATURED_ROW_LIMIT = 4;

    private const CACHE_TTL = 900;

    /** @var array<string, string> */
    public const SORT_OPTIONS = [
        'featured' => 'Featured first',
        'ending_soon' => 'Ending soon',
        'min_deposit' => 'Lowest min. deposit',
        'rating' => 'Highest broker rating',
    ];

    /** @return array<string, array{name: string, promo_type: string}> */
    public static function tabs(): array
    {
        return self::TABS;
    }

    /** @var array<string, array{name: string, promo_type: string}> */
    private const TABS = [
        'deposit-bonuses' => ['name' => 'Deposit Bonuses', 'promo_type' => 'Forex Deposit Bonus'],
        'no-deposit-bonuses' => ['name' => 'No Deposit Bonus', 'promo_type' => 'Forex No Deposit Bonus'],
        'live-contests' => ['name' => 'Live Contests', 'promo_type' => 'Forex Live Contest'],
        'demo-contests' => ['name' => 'Demo Contests', 'promo_type' => 'Forex Demo Contest'],
        'cashback-rebates' => ['name' => 'Cashback', 'promo_type' => 'Forex Cashback Rebate'],
        'crypto-bonuses' => ['name' => 'Crypto Contests', 'promo_type' => 'Crypto Bonus Promotion'],
    ];

    /** @return array<string, mixed> */
    public function buildIndex(
        ?string $type = null,
        ?string $sort = null,
        bool $featuredOnly = false,
        ?string $search = null,
    ): array {
        $activeType = $this->resolveTabSlug($type);
        $sortKey = $this->resolveSort($sort);
        $searchTerm = $this->normalizeSearch($search);
        $catalog = $this->activePromotionsCatalog();
        $cacheKey = sprintf(
            'promotions_index_v6_%s_%s_%d_%s',
            $activeType,
            $sortKey,
            $featuredOnly ? 1 : 0,
            md5($searchTerm ?? '')
        );

        $payload = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($activeType, $sortKey, $featuredOnly, $searchTerm, $catalog) {
            $tabs = $this->tabsFromCatalog($catalog);
            $allPromotions = $this->filteredPromotionsForTab($catalog, $activeType, $sortKey, $featuredOnly, $searchTerm);
            $totalCount = $allPromotions->count();
            $initialCards = $allPromotions
                ->take(self::INITIAL_CARDS)
                ->map(fn (ForexBonus $bonus) => $this->serializeCard($bonus))
                ->values();

            $activeTabMeta = collect($tabs)->firstWhere('slug', $activeType) ?? $tabs[0];

            return [
                'tabs' => $tabs,
                'activeTab' => $activeType,
                'activeTabName' => $activeTabMeta['name'] ?? 'All offers',
                'activeSort' => $sortKey,
                'featuredOnly' => $featuredOnly,
                'search' => $searchTerm,
                'sortOptions' => self::SORT_OPTIONS,
                'stats' => $this->statsFromCatalog($catalog),
                'refreshedAt' => now()->format('M j, Y'),
                'featuredCards' => $this->featuredCardsFromCatalog($catalog),
                'cards' => $initialCards,
                'totalCount' => $totalCount,
                'loadedCount' => $initialCards->count(),
                'hasMore' => $totalCount > self::INITIAL_CARDS,
            ];
        });

        $payload['catalog'] = $catalog;
        $payload['filterQuery'] = $this->buildFilterQuery($sortKey, $featuredOnly, $searchTerm);

        return $payload;
    }

    public function tabUrl(
        string $slug,
        ?string $sort = null,
        bool $featuredOnly = false,
        ?string $search = null,
    ): string {
        $params = $this->buildFilterQuery(
            $this->resolveSort($sort),
            $featuredOnly,
            $this->normalizeSearch($search),
        );

        if ($slug === self::TAB_ALL) {
            return route('promotions.index', $params);
        }

        return route('promotions.tab', array_merge(['type' => $slug], $params));
    }

    /**
     * @return array<string, string>
     */
    public function buildFilterQuery(string $sortKey, bool $featuredOnly, ?string $searchTerm): array
    {
        return array_filter([
            'sort' => $sortKey !== 'featured' ? $sortKey : null,
            'featured' => $featuredOnly ? '1' : null,
            'q' => $searchTerm,
        ], fn ($value) => $value !== null && $value !== '');
    }

    /** @return array<string, mixed> */
    public function loadMore(
        string $type,
        int $offset,
        ?string $sort = null,
        bool $featuredOnly = false,
        ?string $search = null,
    ): array {
        $activeType = $this->resolveTabSlug($type);
        $sortKey = $this->resolveSort($sort);
        $searchTerm = $this->normalizeSearch($search);
        $catalog = $this->activePromotionsCatalog();
        $allPromotions = $this->filteredPromotionsForTab($catalog, $activeType, $sortKey, $featuredOnly, $searchTerm);
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

    /**
     * Active promotions grouped by promo_type — loaded once and cached.
     *
     * @return Collection<string, Collection<int, ForexBonus>>
     */
    public function activePromotionsCatalog(): Collection
    {
        return Cache::remember('promotions_active_catalog_v5', self::CACHE_TTL, function () {
            return $this->activePromotionsQuery()
                ->get()
                ->filter(fn (ForexBonus $bonus) => $bonus->isActivePromotion())
                ->groupBy('promo_type');
        });
    }

    /** @param Collection<string, Collection<int, ForexBonus>> $catalog */
    public function promotionsForTab(Collection $catalog, string $activeType): Collection
    {
        if ($activeType === self::TAB_ALL) {
            return $catalog
                ->flatten(1)
                ->unique('id')
                ->values();
        }

        $promoType = self::TABS[$activeType]['promo_type'] ?? null;

        if (! $promoType) {
            return collect();
        }

        return ($catalog->get($promoType) ?? collect())->values();
    }

    /** @return array<string, mixed> */
    public function serializeCard(ForexBonus $bonus): array
    {
        $broker = $bonus->broker;
        $expiryBadge = $bonus->expiryBadge();

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
            'regulation_short' => $this->regulationShort($broker),
            'region_note' => $this->regionNote($bonus),
            'eligibility_teaser' => $this->eligibilityTeaser($bonus),
            'feature_image' => $bonus->feature_image ? asset($bonus->feature_image) : null,
            'min_deposit' => $bonus->minDepositLabel(),
            'expiry' => $bonus->expiryLabel(),
            'expiry_badge' => $expiryBadge,
            'expiry_tone' => $bonus->expiryTone(),
            'is_featured' => (bool) $bonus->is_featured,
            'is_limited' => $bonus->promotion_status === 'limited-time',
            'is_urgent' => $bonus->isExpiryUrgent(),
        ];
    }

    /** @return Collection<int, ForexBonus> */
    public function latestActivePromotions(string $promoType, int $limit = 4): Collection
    {
        return ($this->activePromotionsCatalog()->get($promoType) ?? collect())
            ->take($limit)
            ->values();
    }

    public function resolveTabSlug(?string $type): string
    {
        if ($type === null || $type === '' || $type === self::TAB_ALL) {
            return self::TAB_ALL;
        }

        return isset(self::TABS[$type]) ? $type : self::TAB_ALL;
    }

    public function resolveSort(?string $sort): string
    {
        return array_key_exists($sort ?? '', self::SORT_OPTIONS) ? (string) $sort : 'featured';
    }

    /**
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<int, array<string, mixed>>
     */
    private function tabsFromCatalog(Collection $catalog): array
    {
        $allCount = $catalog->flatten(1)->unique('id')->count();

        $tabs = [[
            'slug' => self::TAB_ALL,
            'name' => 'All offers',
            'count' => $allCount,
            'url' => route('promotions.index'),
        ]];

        foreach (self::TABS as $slug => $meta) {
            $count = ($catalog->get($meta['promo_type']) ?? collect())->count();

            $tabs[] = [
                'slug' => $slug,
                'name' => $meta['name'],
                'count' => $count,
                'url' => route('promotions.tab', ['type' => $slug]),
            ];
        }

        return $tabs;
    }

    /**
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return array<string, int|bool>
     */
    private function statsFromCatalog(Collection $catalog): array
    {
        $all = $catalog->flatten(1)->unique('id');
        $endingSoon = $all->filter(fn (ForexBonus $bonus) => $bonus->isExpiryUrgent())->count();
        $endingThisMonth = $all->filter(function (ForexBonus $bonus) {
            $days = $bonus->daysUntilExpiry();

            return $days !== null && $days >= 0 && $days <= 30;
        })->count();

        return [
            'total_active' => $all->count(),
            'featured' => $all->where('is_featured', true)->count(),
            'ending_this_month' => $endingThisMonth,
            'ending_soon' => $endingSoon,
            'show_ending_soon' => $endingSoon > 0,
        ];
    }

    /**
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    private function featuredCardsFromCatalog(Collection $catalog): Collection
    {
        return $catalog
            ->flatten(1)
            ->unique('id')
            ->filter(fn (ForexBonus $bonus) => (bool) $bonus->is_featured)
            ->sortByDesc(fn (ForexBonus $bonus) => $bonus->publish_date?->timestamp ?? 0)
            ->take(self::FEATURED_ROW_LIMIT)
            ->map(fn (ForexBonus $bonus) => $this->serializeCard($bonus))
            ->values();
    }

    /**
     * @param  Collection<string, Collection<int, ForexBonus>>  $catalog
     * @return Collection<int, ForexBonus>
     */
    private function filteredPromotionsForTab(
        Collection $catalog,
        string $activeType,
        string $sortKey,
        bool $featuredOnly,
        ?string $searchTerm,
    ): Collection {
        $promotions = $this->promotionsForTab($catalog, $activeType);

        if ($featuredOnly) {
            $promotions = $promotions->filter(fn (ForexBonus $bonus) => (bool) $bonus->is_featured)->values();
        }

        if ($searchTerm) {
            $needle = Str::lower($searchTerm);
            $promotions = $promotions->filter(function (ForexBonus $bonus) use ($needle) {
                $brokerName = Str::lower($bonus->brokerDisplayName() ?? '');
                $title = Str::lower($bonus->title ?? '');

                return str_contains($brokerName, $needle) || str_contains($title, $needle);
            })->values();
        }

        return $this->sortPromotions($promotions, $sortKey);
    }

    /** @param Collection<int, ForexBonus> $promotions */
    private function sortPromotions(Collection $promotions, string $sortKey): Collection
    {
        return match ($sortKey) {
            'ending_soon' => $promotions->sortBy(function (ForexBonus $bonus) {
                $days = $bonus->daysUntilExpiry();

                return $days === null ? PHP_INT_MAX : max(0, $days);
            })->values(),
            'min_deposit' => $promotions->sortBy(function (ForexBonus $bonus) {
                if ($bonus->min_deposit === null) {
                    return PHP_INT_MAX;
                }

                return (float) $bonus->min_deposit;
            })->values(),
            'rating' => $promotions->sortByDesc(function (ForexBonus $bonus) {
                return (float) ($bonus->broker?->rating ?? -1);
            })->values(),
            default => $promotions->sort(function (ForexBonus $a, ForexBonus $b) {
                $featuredCompare = ((int) $b->is_featured) <=> ((int) $a->is_featured);
                if ($featuredCompare !== 0) {
                    return $featuredCompare;
                }

                return ($b->publish_date?->timestamp ?? 0) <=> ($a->publish_date?->timestamp ?? 0);
            })->values(),
        };
    }

    private function normalizeSearch(?string $search): ?string
    {
        $search = trim((string) $search);

        return $search !== '' ? Str::limit($search, 80, '') : null;
    }

    /** @return array<int, string> */
    private function regulationShort(?\App\Models\Broker $broker): array
    {
        if (! $broker) {
            return [];
        }

        $licenses = $broker->regulationList();

        return array_slice(array_map(fn ($item) => Str::limit(strip_tags((string) $item), 24, ''), $licenses), 0, 2);
    }

    private function regionNote(ForexBonus $bonus): ?string
    {
        $country = BrokerTaxonomy::resolvePreferredCountry();
        $slug = $country['slug'] ?? 'global';

        if ($slug === 'global') {
            return null;
        }

        $broker = $bonus->broker;
        $regions = $broker?->regionList() ?? [];

        if ($regions !== []) {
            $normalizedRegions = array_map(fn ($region) => Str::slug((string) $region), $regions);
            if (in_array($slug, $normalizedRegions, true)) {
                return null;
            }
        }

        return 'Verify eligibility in '.$country['name'];
    }

    private function eligibilityTeaser(ForexBonus $bonus): ?string
    {
        $raw = trim(strip_tags((string) ($bonus->eligibility_criteria ?? '')));
        if ($raw === '') {
            return null;
        }

        return Str::limit($raw, 90);
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
}
