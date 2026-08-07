<?php

namespace App\Support;

use App\Models\Broker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BrokerListingFilter
{
    /** @var array<string, string[]> */
    private const COUNTRY_REGION_MAP = [
        'india' => ['asia'],
        'bangladesh' => ['asia'],
        'singapore' => ['asia'],
        'malaysia' => ['asia'],
        'pakistan' => ['asia'],
        'indonesia' => ['asia'],
        'philippines' => ['asia'],
        'australia' => ['australia'],
        'canada' => ['canada'],
        'united-kingdom' => ['united-kingdom', 'global'],
        'united-states' => ['united-states', 'global'],
        'uae' => ['middle-east', 'global'],
        'south-africa' => ['africa'],
        'nigeria' => ['africa'],
        'germany' => ['united-kingdom', 'global'],
        'france' => ['global'],
        'brazil' => ['global'],
        'mexico' => ['global'],
    ];

    public static function slugType(string $slug): ?string
    {
        if (isset(BrokerTaxonomy::categories()[$slug])) {
            return 'category';
        }

        if (isset(BrokerTaxonomy::countriesWithFlags()[$slug])) {
            return 'country';
        }

        if (isset(BrokerTaxonomy::regions()[$slug])) {
            return 'region';
        }

        return null;
    }

    public static function labelFor(string $slug): string
    {
        return BrokerTaxonomy::categories()[$slug]
            ?? BrokerTaxonomy::countriesWithFlags()[$slug]['name']
            ?? BrokerTaxonomy::regions()[$slug]
            ?? Str::headline(str_replace('-', ' ', $slug));
    }

    /** @return Collection<int, Broker> */
    public static function brokersFor(string $slug, ?Collection $brokers = null): Collection
    {
        if ($brokers === null) {
            $query = Broker::query()->where('is_scam', false);

            if ($slug === 'high-leverage') {
                $query->with('accountOptions');
            }

            $brokers = $query->get();
        }

        return $brokers
            ->filter(fn (Broker $broker) => self::matches($broker, $slug))
            ->values();
    }

    public static function matches(Broker $broker, string $slug): bool
    {
        if ($slug === 'high-leverage') {
            return self::hasHighLeverage($broker);
        }

        if (in_array($slug, ['mt4-brokers', 'mt5-brokers'], true)) {
            return self::hasPlatform($broker, $slug);
        }

        $categories = $broker->brokerCategoryList();
        $regions = $broker->regionList();
        $countries = self::normalizeList($broker->associated_countries);

        if (in_array($slug, $categories, true) || in_array($slug, $regions, true) || in_array($slug, $countries, true)) {
            return true;
        }

        if (isset(BrokerTaxonomy::countriesWithFlags()[$slug])) {
            return self::matchesCountry($broker, $slug, $regions, $countries);
        }

        return false;
    }

    private static function hasHighLeverage(Broker $broker): bool
    {
        if (in_array('high-leverage', $broker->brokerCategoryList(), true)) {
            return true;
        }

        if ($broker->relationLoaded('accountOptions')) {
            $maxOptionLeverage = $broker->accountOptions
                ->pluck('max_leverage')
                ->filter()
                ->max();

            if ($maxOptionLeverage !== null && (int) $maxOptionLeverage >= 500) {
                return true;
            }
        }

        return self::parseLeverageRatio((string) $broker->leverage) >= 500;
    }

    private static function hasPlatform(Broker $broker, string $slug): bool
    {
        if (in_array($slug, $broker->brokerCategoryList(), true)) {
            return true;
        }

        $needles = $slug === 'mt4-brokers'
            ? ['mt4', 'metatrader 4']
            : ['mt5', 'metatrader 5'];

        foreach ($broker->platformList() as $platform) {
            $platform = strtolower($platform);
            foreach ($needles as $needle) {
                if (str_contains($platform, $needle)) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function parseLeverageRatio(string $value): int
    {
        if (preg_match('/1\s*:\s*(\d+)/i', $value, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/(\d+)/', $value, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    public static function maxLeverageFor(Broker $broker): int
    {
        $fromField = self::parseLeverageRatio((string) $broker->leverage);

        if ($broker->relationLoaded('accountOptions')) {
            $fromOptions = (int) ($broker->accountOptions
                ->pluck('max_leverage')
                ->filter()
                ->max() ?? 0);

            return max($fromField, $fromOptions);
        }

        return $fromField;
    }

    /** @param  array<int, string>|null  $associatedCountries */
    private static function matchesCountry(Broker $broker, string $slug, array $regions, array $associatedCountries): bool
    {
        $countryName = BrokerTaxonomy::countriesWithFlags()[$slug]['name'] ?? null;

        if ($countryName && Str::lower((string) $broker->country) === Str::lower($countryName)) {
            return true;
        }

        foreach (self::COUNTRY_REGION_MAP[$slug] ?? [] as $region) {
            if (in_array($region, $regions, true)) {
                return true;
            }
        }

        foreach ($associatedCountries as $value) {
            $normalized = Str::slug($value);

            if ($normalized === $slug || Str::contains(Str::lower($value), Str::lower($countryName ?? ''))) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int, string> */
    private static function normalizeList(mixed $values): array
    {
        if (is_string($values)) {
            $values = json_decode($values, true);
        }

        return is_array($values) ? array_values($values) : [];
    }
}
