<?php

namespace App\Support;

use App\Models\Broker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BrokerListingFilter
{
    public static function slugType(string $slug): ?string
    {
        if (isset(BrokerTaxonomy::categories()[$slug])) {
            return 'category';
        }

        if (isset(BrokerTaxonomy::countriesWithFlags()[$slug])
            || isset(BrokerTaxonomy::headquartersCountryCatalog()[$slug])) {
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
            ?? BrokerTaxonomy::headquartersCountryCatalog()[$slug]['name']
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

        if (isset(BrokerTaxonomy::countriesWithFlags()[$slug])
            || isset(BrokerTaxonomy::headquartersCountryCatalog()[$slug])) {
            return self::isAvailableInCountry($broker, $slug);
        }

        $categories = $broker->brokerCategoryList();
        $regions = $broker->regionList();

        if (in_array($slug, $categories, true) || in_array($slug, $regions, true)) {
            return true;
        }

        // Category tags are only filled in on a handful of rows, so fall back to the
        // broker's own data rather than returning an empty list.
        if (isset(BrokerTaxonomy::categories()[$slug])) {
            return BrokerCategorySignals::derivedMatch($broker, $slug);
        }

        if (isset(BrokerTaxonomy::regions()[$slug]) || isset(BrokerTaxonomy::categories()[$slug])) {
            return false;
        }

        return self::isAvailableInCountry($broker, $slug);
    }

    /**
     * Trader-country availability: associated_countries contains the slug, or HQ country matches.
     * Region tags are ignored. Scam brokers never match a specific market.
     */
    public static function isAvailableInCountry(Broker $broker, string $slug): bool
    {
        if ($slug === 'global') {
            return ! $broker->is_scam;
        }

        if ($broker->is_scam) {
            return false;
        }

        foreach (JsonList::normalize($broker->associated_countries) as $value) {
            if (self::associatedValueMatchesCountry((string) $value, $slug)) {
                return true;
            }
        }

        $canonical = BrokerTaxonomy::canonicalFromHeadquarters((string) ($broker->country ?? ''));

        return $canonical !== null && $canonical['slug'] === $slug;
    }

    /** Was this broker tagged for the slug in the admin, rather than matched from its data? */
    public static function isTagged(Broker $broker, string $slug): bool
    {
        return in_array($slug, $broker->brokerCategoryList(), true)
            || in_array($slug, $broker->regionList(), true)
            || in_array($slug, self::normalizeList($broker->associated_countries), true);
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

    private static function associatedValueMatchesCountry(string $value, string $slug): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }

        $normalized = Str::slug($value);
        if ($normalized === $slug) {
            return true;
        }

        foreach (BrokerTaxonomy::countryMatchNames($slug) as $name) {
            if (Str::lower($value) === Str::lower($name) || Str::slug($name) === $normalized) {
                return true;
            }
        }

        return false;
    }

    private static function headquartersMatchesCountry(string $country, string $slug): bool
    {
        $hq = Str::lower(trim($country));
        if ($hq === '') {
            return false;
        }

        if (Str::slug($country) === $slug) {
            return true;
        }

        foreach (BrokerTaxonomy::countryMatchNames($slug) as $name) {
            if ($hq === Str::lower($name)) {
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
