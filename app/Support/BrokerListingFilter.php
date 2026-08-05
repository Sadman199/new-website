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
        $brokers = $brokers ?? Broker::all();

        return $brokers
            ->filter(fn (Broker $broker) => self::matches($broker, $slug))
            ->values();
    }

    public static function matches(Broker $broker, string $slug): bool
    {
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
