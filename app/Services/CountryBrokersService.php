<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CountryBrokersService
{
    public const CACHE_TTL = 3600;

    private const SELECTOR_KEY = 'forex_markets_selector_v5';

    private const BROKER_COUNTRY_MAP_KEY = 'broker_country_slug_map_v2';

    /**
     * Global plus distinct countries entered in brokers.country.
     *
     * @return array<string, array{name: string, flag: string, code: ?string, shortcode: string, db_name: string, broker_count: int}>
     */
    public function countriesForSelector(): array
    {
        return Cache::remember(self::SELECTOR_KEY, self::CACHE_TTL, function () {
            $brokers = Broker::query()->where('is_scam', false)->get();
            $hqCountries = [];

            foreach ($brokers as $broker) {
                $canonical = BrokerTaxonomy::canonicalFromHeadquarters((string) ($broker->country ?? ''));
                if ($canonical === null) {
                    continue;
                }

                $slug = $canonical['slug'];
                $hqCountries[$slug] = $canonical;
            }

            uasort(
                $hqCountries,
                fn (array $left, array $right) => strnatcasecmp($left['name'], $right['name'])
            );

            $global = BrokerTaxonomy::countriesWithFlags()['global'];
            $countries = [
                'global' => $this->selectorEntry('global', $global, $brokers->count()),
            ];

            foreach ($hqCountries as $slug => $meta) {
                $countries[$slug] = $this->selectorEntry(
                    $slug,
                    $meta,
                    $brokers->filter(
                        fn (Broker $broker) => BrokerListingFilter::isAvailableInCountry($broker, $slug)
                    )->count()
                );
            }

            return $countries;
        });
    }

    /**
     * @param  array{name: string, flag: string, code: ?string}  $meta
     * @return array{name: string, flag: string, code: ?string, shortcode: string, db_name: string, broker_count: int}
     */
    private function selectorEntry(string $slug, array $meta, int $brokerCount): array
    {
        return [
            'name' => $meta['name'],
            'flag' => $meta['flag'],
            'code' => $meta['code'],
            'shortcode' => BrokerTaxonomy::countryShortcode($slug, $meta['code'] ?? null),
            'db_name' => $meta['name'],
            'broker_count' => $brokerCount,
        ];
    }

    /** @return string[] */
    public function selectableCountrySlugs(): array
    {
        return array_keys($this->countriesForSelector());
    }

    /** @return array{name: string, flag: string, code: ?string, shortcode: string, db_name: string, broker_count?: int}|null */
    public function countryMeta(string $slug): ?array
    {
        $selector = $this->countriesForSelector();
        if (isset($selector[$slug])) {
            return $selector[$slug];
        }

        $meta = BrokerTaxonomy::headquartersCountryCatalog()[$slug]
            ?? BrokerTaxonomy::countriesWithFlags()[$slug]
            ?? null;

        if (! $meta) {
            return null;
        }

        return $this->selectorEntry($slug, $meta, $this->countForCountry($slug));
    }

    /** @return Collection<int, Broker> */
    public function forCountry(string $slug, int $limit = 6): Collection
    {
        if ($slug === 'global') {
            return $this->globalTopRated($limit);
        }

        if (! isset($this->countriesForSelector()[$slug])) {
            return collect();
        }

        return Cache::remember("available_brokers_v2_{$slug}_{$limit}", self::CACHE_TTL, function () use ($slug, $limit) {
            return BrokerListingFilter::brokersFor($slug)
                ->filter(fn (Broker $broker) => $broker->rating !== null || (int) $broker->top_broker > 0)
                ->sort(function (Broker $left, Broker $right) {
                    $rank = (int) $right->top_broker <=> (int) $left->top_broker;

                    return $rank !== 0 ? $rank : ((float) $right->rating <=> (float) $left->rating);
                })
                ->take($limit)
                ->values();
        });
    }

    public function countForCountry(string $slug): int
    {
        if ($slug === 'global') {
            return Broker::query()->where('is_scam', false)->count();
        }

        return Cache::remember("available_broker_count_v2_{$slug}", self::CACHE_TTL, function () use ($slug) {
            return BrokerListingFilter::brokersFor($slug)->count();
        });
    }

    public function brokersPageUrl(string $slug): ?string
    {
        if ($slug === 'global') {
            return route('broker.reviews.index');
        }

        if (! isset($this->countriesForSelector()[$slug])) {
            return null;
        }

        return route('brokers.best', ['slug' => $slug]);
    }

    /** @return Collection<int, Broker> */
    public function globalTopRated(int $limit = 4): Collection
    {
        return Cache::remember("hq_brokers_global_v2_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Broker::query()
                ->where('is_scam', false)
                ->where(function (Builder $query) {
                    $query->whereNotNull('rating')->orWhere('top_broker', '>', 0);
                })
                ->orderByDesc('top_broker')
                ->orderByDesc('rating')
                ->take($limit)
                ->get();
        });
    }

    /** @return array{slug: string, name: string, flag: string, code: ?string, shortcode: string} */
    public function resolvePreferredCountry(?string $slug = null): array
    {
        $slug = $slug ?? session('preferred_country') ?? request()->cookie('preferred_country');
        $selector = $this->countriesForSelector();

        if ($slug && isset($selector[$slug])) {
            $meta = $selector[$slug];

            return [
                'slug' => $slug,
                'name' => $meta['name'],
                'flag' => $meta['flag'],
                'code' => $meta['code'],
                'shortcode' => $meta['shortcode'],
            ];
        }

        return [
            'slug' => 'global',
            'name' => 'Global',
            'flag' => '🌍',
            'code' => null,
            'shortcode' => 'GL',
        ];
    }

    /**
     * HQ-only query. Used for headquarters display matching, not trader availability.
     */
    public function headquartersQueryForSlug(string $slug): Builder
    {
        if ($slug === 'global') {
            return Broker::query()->where('is_scam', false);
        }

        $matchValues = $this->rawCountryValuesForSlug($slug);

        if ($matchValues === []) {
            return Broker::query()->whereRaw('0 = 1');
        }

        return Broker::query()
            ->where('is_scam', false)
            ->where(function (Builder $query) use ($matchValues) {
                foreach ($matchValues as $value) {
                    $query->orWhereRaw('LOWER(TRIM(country)) = ?', [Str::lower(trim($value))]);
                }
            });
    }

    /** @return string[] */
    public function rawCountryValuesForSlug(string $slug): array
    {
        if ($slug === 'global') {
            return [];
        }

        $values = BrokerTaxonomy::countryMatchNames($slug);

        foreach ($this->brokerCountrySlugMap() as $raw => $mappedSlug) {
            if ($mappedSlug === $slug) {
                $values[] = $raw;
            }
        }

        return array_values(array_unique(array_filter($values)));
    }

    /**
     * Map each distinct brokers.country value to a forex-market slug.
     *
     * @return array<string, string|null>
     */
    public function brokerCountrySlugMap(): array
    {
        return Cache::remember(self::BROKER_COUNTRY_MAP_KEY, self::CACHE_TTL, function () {
            $map = [];

            Broker::query()
                ->select('country')
                ->whereNotNull('country')
                ->where('country', '!=', '')
                ->distinct()
                ->orderBy('country')
                ->pluck('country')
                ->each(function ($raw) use (&$map) {
                    $raw = trim((string) $raw);
                    if ($raw === '') {
                        return;
                    }

                    $map[$raw] = $this->slugForBrokerCountry($raw);
                });

            return $map;
        });
    }

    private function slugForBrokerCountry(string $raw): ?string
    {
        return BrokerTaxonomy::canonicalFromHeadquarters($raw)['slug'] ?? null;
    }

    public static function flush(): void
    {
        Cache::forget(self::SELECTOR_KEY);
        Cache::forget(self::BROKER_COUNTRY_MAP_KEY);

        foreach (BrokerTaxonomy::countrySlugs() as $slug) {
            Cache::forget("hq_broker_count_{$slug}");
            Cache::forget("available_broker_count_{$slug}");
            Cache::forget("available_broker_count_v2_{$slug}");
            foreach ([4, 5, 6, 8, 9] as $limit) {
                Cache::forget("hq_brokers_{$slug}_{$limit}");
                Cache::forget("available_brokers_{$slug}_{$limit}");
                Cache::forget("available_brokers_v2_{$slug}_{$limit}");
            }
        }

        foreach ([4, 5, 6, 8] as $limit) {
            Cache::forget("hq_brokers_global_{$limit}");
            Cache::forget("hq_brokers_global_v2_{$limit}");
        }
    }
}
