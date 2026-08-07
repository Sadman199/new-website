<?php

namespace App\Support;

class BrokerTaxonomy
{
    /** @return array<string, string> slug => label */
    public static function categories(): array
    {
        return [
            'low-spread-brokers' => 'Low Spread Brokers',
            'free-withdrawal-brokers' => 'Free Withdrawal Brokers',
            'mt4-brokers' => 'MetaTrader 4',
            'mt5-brokers' => 'MetaTrader 5',
            'micro-accounts-brokers' => 'Micro Account',
            'copytrading-brokers' => 'CopyTrading',
            'social-trading-brokers' => 'Social Trading',
            'scalping-brokers' => 'Scalping',
            'trading-apps-brokers' => 'Brokers with Trading Apps',
            'brokers-for-beginners' => 'Forex Brokers for Beginners',
            'high-leverage' => 'High Leverage',
        ];
    }

    /** @return array<string, string> slug => label */
    public static function regions(): array
    {
        return [
            'asia' => 'Asian Brokers',
            'australia' => 'Australian Brokers',
            'africa' => 'African Brokers',
            'canada' => 'Canadian Brokers',
            'middle-east' => 'Middle East',
            'united-states' => 'US Brokers',
            'united-kingdom' => 'UK Brokers',
            'global' => 'Global',
        ];
    }

    /** @return string[] */
    public static function categorySlugs(): array
    {
        return array_keys(self::categories());
    }

    /** @return string[] */
    public static function regionSlugs(): array
    {
        return array_keys(self::regions());
    }

    /**
     * Country slugs for residence-based broker listings (slug => label).
     *
     * @return array<string, string>
     */
    public static function countries(): array
    {
        return array_map(
            fn (array $meta) => $meta['name'],
            self::countriesWithFlags()
        );
    }

    /**
     * @return array<string, array{name: string, flag: string, code: ?string}>
     */
    public static function countriesWithFlags(): array
    {
        return [
            'global' => ['name' => 'Global', 'flag' => '🌍', 'code' => null],
            'united-kingdom' => ['name' => 'United Kingdom', 'flag' => '🇬🇧', 'code' => 'gb'],
            'united-states' => ['name' => 'United States', 'flag' => '🇺🇸', 'code' => 'us'],
            'india' => ['name' => 'India', 'flag' => '🇮🇳', 'code' => 'in'],
            'australia' => ['name' => 'Australia', 'flag' => '🇦🇺', 'code' => 'au'],
            'canada' => ['name' => 'Canada', 'flag' => '🇨🇦', 'code' => 'ca'],
            'singapore' => ['name' => 'Singapore', 'flag' => '🇸🇬', 'code' => 'sg'],
            'malaysia' => ['name' => 'Malaysia', 'flag' => '🇲🇾', 'code' => 'my'],
            'uae' => ['name' => 'United Arab Emirates', 'flag' => '🇦🇪', 'code' => 'ae'],
            'south-africa' => ['name' => 'South Africa', 'flag' => '🇿🇦', 'code' => 'za'],
            'nigeria' => ['name' => 'Nigeria', 'flag' => '🇳🇬', 'code' => 'ng'],
            'bangladesh' => ['name' => 'Bangladesh', 'flag' => '🇧🇩', 'code' => 'bd'],
            'germany' => ['name' => 'Germany', 'flag' => '🇩🇪', 'code' => 'de'],
            'france' => ['name' => 'France', 'flag' => '🇫🇷', 'code' => 'fr'],
            'brazil' => ['name' => 'Brazil', 'flag' => '🇧🇷', 'code' => 'br'],
            'philippines' => ['name' => 'Philippines', 'flag' => '🇵🇭', 'code' => 'ph'],
            'pakistan' => ['name' => 'Pakistan', 'flag' => '🇵🇰', 'code' => 'pk'],
            'indonesia' => ['name' => 'Indonesia', 'flag' => '🇮🇩', 'code' => 'id'],
            'mexico' => ['name' => 'Mexico', 'flag' => '🇲🇽', 'code' => 'mx'],
        ];
    }

    public static function countryFlagUrl(?string $code, int $width = 40): ?string
    {
        if (! $code) {
            return null;
        }

        $validWidths = [20, 40, 80, 160, 320, 640];
        $closest = 40;
        $smallestDiff = PHP_INT_MAX;

        foreach ($validWidths as $candidate) {
            $diff = abs($candidate - $width);
            if ($diff < $smallestDiff) {
                $smallestDiff = $diff;
                $closest = $candidate;
            }
        }

        return 'https://flagcdn.com/w'.$closest.'/'.strtolower($code).'.png';
    }

    /** @return string[] */
    public static function countrySlugs(): array
    {
        return array_keys(self::countriesWithFlags());
    }

    public static function countryShortcode(string $slug, ?string $code = null): string
    {
        if ($slug === 'global') {
            return 'GL';
        }

        $overrides = [
            'united-kingdom' => 'UK',
            'uae' => 'UAE',
        ];

        if (isset($overrides[$slug])) {
            return $overrides[$slug];
        }

        if ($code) {
            return strtoupper($code);
        }

        return strtoupper(substr(str_replace('-', '', $slug), 0, 2));
    }

    /**
     * Resolve preferred country from session/cookie.
     *
     * @return array{slug: string, name: string, flag: string, code: ?string, shortcode: string}
     */
    public static function resolvePreferredCountry(?string $slug = null): array
    {
        $countries = self::countriesWithFlags();
        $slug = $slug ?? session('preferred_country') ?? request()->cookie('preferred_country');

        if ($slug && isset($countries[$slug])) {
            $code = $countries[$slug]['code'];

            return [
                'slug' => $slug,
                'name' => $countries[$slug]['name'],
                'flag' => $countries[$slug]['flag'],
                'code' => $code,
                'shortcode' => self::countryShortcode($slug, $code),
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
     * Split legacy account_types values into listing categories vs account labels.
     *
     * @param  array<int, string>|null  $accountTypes
     * @return array{0: array<int, string>, 1: array<int, string>}
     */
    public static function splitLegacyAccountTypes(?array $accountTypes): array
    {
        $accountTypes = is_array($accountTypes) ? $accountTypes : [];
        $known = self::categorySlugs();

        $categories = [];
        $labels = [];

        foreach ($accountTypes as $value) {
            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }

            if (in_array($value, $known, true)) {
                $categories[] = $value;
            } else {
                $labels[] = $value;
            }
        }

        return [array_values(array_unique($categories)), array_values(array_unique($labels))];
    }
}
