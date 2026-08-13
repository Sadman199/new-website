<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\BrokerTaxonomy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CountryBrokersService
{
    public const CACHE_TTL = 3600;

    private const SELECTOR_KEY = 'forex_markets_selector_v3';

    private const BROKER_COUNTRY_MAP_KEY = 'broker_country_slug_map_v2';

    /**
     * Global + major forex trading markets for the residence selector.
     *
     * @return array<string, array{name: string, flag: string, code: ?string, shortcode: string, db_name: string, broker_count: int}>
     */
    public function countriesForSelector(): array
    {
        return Cache::remember(self::SELECTOR_KEY, self::CACHE_TTL, function () {
            $countries = [];

            foreach (BrokerTaxonomy::countriesWithFlags() as $slug => $meta) {
                $countries[$slug] = [
                    'name' => $meta['name'],
                    'flag' => $meta['flag'],
                    'code' => $meta['code'],
                    'shortcode' => BrokerTaxonomy::countryShortcode($slug, $meta['code']),
                    'db_name' => $meta['name'],
                    'broker_count' => $this->countForCountry($slug),
                ];
            }

            return $countries;
        });
    }

    /** @return string[] */
    public function selectableCountrySlugs(): array
    {
        return BrokerTaxonomy::countrySlugs();
    }

    /** @return array{name: string, flag: string, code: ?string, shortcode: string, db_name: string, broker_count?: int}|null */
    public function countryMeta(string $slug): ?array
    {
        $meta = BrokerTaxonomy::countriesWithFlags()[$slug] ?? null;

        if (! $meta) {
            return null;
        }

        return [
            'name' => $meta['name'],
            'flag' => $meta['flag'],
            'code' => $meta['code'],
            'shortcode' => BrokerTaxonomy::countryShortcode($slug, $meta['code']),
            'db_name' => $meta['name'],
            'broker_count' => $this->countForCountry($slug),
        ];
    }

    /** @return Collection<int, Broker> */
    public function forCountry(string $slug, int $limit = 6): Collection
    {
        if ($slug === 'global') {
            return $this->globalTopRated($limit);
        }

        if (! isset(BrokerTaxonomy::countriesWithFlags()[$slug])) {
            return collect();
        }

        return Cache::remember("hq_brokers_{$slug}_{$limit}", self::CACHE_TTL, function () use ($slug, $limit) {
            return $this->headquartersQueryForSlug($slug)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take($limit)
                ->get();
        });
    }

    public function countForCountry(string $slug): int
    {
        if ($slug === 'global') {
            return Broker::query()->where('is_scam', false)->count();
        }

        if (! isset(BrokerTaxonomy::countriesWithFlags()[$slug])) {
            return 0;
        }

        return Cache::remember("hq_broker_count_{$slug}", self::CACHE_TTL, function () use ($slug) {
            return $this->headquartersQueryForSlug($slug)->count();
        });
    }

    public function brokersPageUrl(string $slug): ?string
    {
        if ($slug === 'global') {
            return route('broker.reviews.index');
        }

        $meta = BrokerTaxonomy::countriesWithFlags()[$slug] ?? null;

        if (! $meta) {
            return null;
        }

        return route('brokers.best', ['slug' => $slug]);
    }

    /** @return Collection<int, Broker> */
    public function globalTopRated(int $limit = 4): Collection
    {
        return Cache::remember("hq_brokers_global_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Broker::query()
                ->where('is_scam', false)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take($limit)
                ->get();
        });
    }

    /** @return array{slug: string, name: string, flag: string, code: ?string, shortcode: string} */
    public function resolvePreferredCountry(?string $slug = null): array
    {
        return BrokerTaxonomy::resolvePreferredCountry($slug);
    }

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
        $normalized = Str::lower(trim($raw));

        foreach (BrokerTaxonomy::countriesWithFlags() as $slug => $meta) {
            if ($slug === 'global') {
                continue;
            }

            if (Str::lower($meta['name']) === $normalized) {
                return $slug;
            }
        }

        foreach (BrokerTaxonomy::countriesWithFlags() as $slug => $meta) {
            if ($slug === 'global') {
                continue;
            }

            foreach (BrokerTaxonomy::countryMatchNames($slug) as $alias) {
                if (Str::lower($alias) === $normalized) {
                    return $slug;
                }
            }
        }

        $slug = Str::slug($raw);

        return isset(BrokerTaxonomy::countriesWithFlags()[$slug]) ? $slug : null;
    }

    public static function flush(): void
    {
        Cache::forget(self::SELECTOR_KEY);
        Cache::forget(self::BROKER_COUNTRY_MAP_KEY);

        foreach (BrokerTaxonomy::countrySlugs() as $slug) {
            Cache::forget("hq_broker_count_{$slug}");
            foreach ([4, 5, 6, 8, 9] as $limit) {
                Cache::forget("hq_brokers_{$slug}_{$limit}");
            }
        }

        foreach ([4, 5, 6, 8] as $limit) {
            Cache::forget("hq_brokers_global_{$limit}");
        }
    }
}
