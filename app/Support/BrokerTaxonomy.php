<?php

namespace App\Support;

use Illuminate\Support\Str;

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
            'ea-brokers' => 'Expert Advisors (EAs)',
            'trading-signals-brokers' => 'Trading Signals',
            'mam-brokers' => 'MAM Accounts',
            'pamm-brokers' => 'PAMM Accounts',
        ];
    }

    /** @return array<string, string> slug => guide/list heading without year */
    public static function categoryGuideHeadings(): array
    {
        return [
            'ea-brokers' => 'Best Forex Brokers with Expert Advisors (EAs)',
            'trading-signals-brokers' => 'Best Forex Brokers with Trading Signals',
            'mam-brokers' => 'Best Forex Brokers with MAM Accounts',
            'pamm-brokers' => 'Best Forex Brokers with PAMM Accounts',
        ];
    }

    public static function categoryGuideHeading(string $slug): string
    {
        if (isset(self::categoryGuideHeadings()[$slug])) {
            return self::categoryGuideHeadings()[$slug];
        }

        $label = self::categories()[$slug] ?? Str::headline(str_replace('-', ' ', $slug));

        return Str::endsWith(Str::lower($label), 'brokers')
            ? "Best {$label}"
            : "Best {$label} Brokers";
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
     * Admin region slugs with display metadata and flags for navigation.
     *
     * @return array<string, array{name: string, flag: string, code: ?string}>
     */
    public static function regionsWithFlags(): array
    {
        $countries = self::countriesWithFlags();
        $overrides = [
            'asia' => ['name' => 'Asian Brokers', 'flag' => '🌏', 'code' => null],
            'africa' => ['name' => 'African Brokers', 'flag' => '🌍', 'code' => null],
            'middle-east' => ['name' => 'Middle East', 'flag' => '🇦🇪', 'code' => 'ae'],
        ];

        $listed = [];

        foreach (self::regions() as $slug => $label) {
            $meta = $countries[$slug] ?? $overrides[$slug] ?? ['name' => $label, 'flag' => '🌍', 'code' => null];
            $listed[$slug] = [
                'name' => $meta['name'] ?? $label,
                'flag' => $meta['flag'] ?? '🌍',
                'code' => $meta['code'] ?? null,
            ];
        }

        return $listed;
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
            'cyprus' => ['name' => 'Cyprus', 'flag' => '🇨🇾', 'code' => 'cy'],
            'netherlands' => ['name' => 'Netherlands', 'flag' => '🇳🇱', 'code' => 'nl'],
            'switzerland' => ['name' => 'Switzerland', 'flag' => '🇨🇭', 'code' => 'ch'],
            'japan' => ['name' => 'Japan', 'flag' => '🇯🇵', 'code' => 'jp'],
            'thailand' => ['name' => 'Thailand', 'flag' => '🇹🇭', 'code' => 'th'],
        ];
    }

    /**
     * Extra HQ jurisdictions that appear in brokers.country but are not
     * part of the curated residence taxonomy.
     *
     * @return array<string, array{name: string, flag: string, code: ?string}>
     */
    public static function additionalHqCountriesWithFlags(): array
    {
        return [
            'seychelles' => ['name' => 'Seychelles', 'flag' => '🇸🇨', 'code' => 'sc'],
            'belize' => ['name' => 'Belize', 'flag' => '🇧🇿', 'code' => 'bz'],
            'vanuatu' => ['name' => 'Vanuatu', 'flag' => '🇻🇺', 'code' => 'vu'],
            'dominica' => ['name' => 'Dominica', 'flag' => '🇩🇲', 'code' => 'dm'],
            'new-zealand' => ['name' => 'New Zealand', 'flag' => '🇳🇿', 'code' => 'nz'],
            'ireland' => ['name' => 'Ireland', 'flag' => '🇮🇪', 'code' => 'ie'],
            'malta' => ['name' => 'Malta', 'flag' => '🇲🇹', 'code' => 'mt'],
            'mauritius' => ['name' => 'Mauritius', 'flag' => '🇲🇺', 'code' => 'mu'],
            'israel' => ['name' => 'Israel', 'flag' => '🇮🇱', 'code' => 'il'],
            'cayman-islands' => ['name' => 'Cayman Islands', 'flag' => '🇰🇾', 'code' => 'ky'],
            'marshall-islands' => ['name' => 'Marshall Islands', 'flag' => '🇲🇭', 'code' => 'mh'],
            'british-virgin-islands' => ['name' => 'British Virgin Islands', 'flag' => '🇻🇬', 'code' => 'vg'],
            'st-vincent-and-the-grenadines' => ['name' => 'St. Vincent and the Grenadines', 'flag' => '🇻🇨', 'code' => 'vc'],
            'saint-lucia' => ['name' => 'Saint Lucia', 'flag' => '🇱🇨', 'code' => 'lc'],
        ];
    }

    /**
     * Taxonomy + extra HQ jurisdictions, excluding Global.
     *
     * @return array<string, array{name: string, flag: string, code: ?string}>
     */
    public static function headquartersCountryCatalog(): array
    {
        $catalog = self::countriesWithFlags();
        unset($catalog['global']);

        return $catalog + self::additionalHqCountriesWithFlags();
    }

    /** @return string[] */
    public static function countryMatchNames(string $slug): array
    {
        $catalog = self::headquartersCountryCatalog();
        if (! isset($catalog[$slug])) {
            return [];
        }

        $name = $catalog[$slug]['name'];
        $aliases = [
            'united-kingdom' => ['UK', 'U.K.', 'Great Britain', 'England'],
            'united-states' => ['USA', 'U.S.A.', 'U.S.', 'Usa', 'America'],
            'uae' => ['UAE', 'U.A.E.', 'United Arab Emirates', 'Dubai'],
            'south-africa' => ['SA'],
            'netherlands' => ['Holland'],
            'czechia' => ['Czech Republic'],
            'seychelles' => ['Seychelles'],
            'british-virgin-islands' => ['BVI', 'British Virgin Islands'],
            'st-vincent-and-the-grenadines' => [
                'St. Vincent and the Grenadines',
                'Saint Vincent and the Grenadines',
                'St Vincent and the Grenadines',
            ],
        ];

        $names = [$name];
        foreach ($aliases[$slug] ?? [] as $alias) {
            $names[] = $alias;
        }

        return array_values(array_unique($names));
    }

    /**
     * Map a free-text brokers.country value to a canonical selector country.
     *
     * @return array{slug: string, name: string, flag: string, code: ?string}|null
     */
    public static function canonicalFromHeadquarters(?string $raw): ?array
    {
        $raw = trim(html_entity_decode(strip_tags((string) $raw), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $raw = trim($raw, " \t\n\r\0\x0B.,;:-");

        if ($raw === '') {
            return null;
        }

        $catalog = self::headquartersCountryCatalog();

        if ($meta = self::catalogMatch($raw, $catalog)) {
            return $meta;
        }

        $firstClause = trim((string) preg_split('/[\/(]/', $raw)[0]);
        $firstClause = trim($firstClause, " \t.,;:-");
        if ($firstClause !== '' && $firstClause !== $raw && ($meta = self::catalogMatch($firstClause, $catalog))) {
            return $meta;
        }

        if (str_contains($raw, ',')) {
            $lastComma = trim((string) substr($raw, (int) strrpos($raw, ',') + 1));
            $lastComma = trim($lastComma, " \t.,;:()-");
            if ($lastComma !== '' && ($meta = self::catalogMatch($lastComma, $catalog))) {
                return $meta;
            }
        }

        if ($meta = self::containedCatalogMatch($raw, $catalog)) {
            return $meta;
        }

        $candidate = $firstClause !== '' ? $firstClause : $raw;
        if (! self::looksLikeCountryName($candidate)) {
            return null;
        }

        $slug = Str::slug($candidate);
        if ($slug === '' || $slug === 'global' || isset(self::categories()[$slug]) || isset(self::regions()[$slug])) {
            return null;
        }

        if (isset($catalog[$slug])) {
            return self::catalogMeta($slug, $catalog[$slug]);
        }

        return [
            'slug' => $slug,
            'name' => Str::title($candidate),
            'flag' => '🌍',
            'code' => null,
        ];
    }

    /**
     * @param  array<string, array{name: string, flag: string, code: ?string}>  $catalog
     * @return array{slug: string, name: string, flag: string, code: ?string}|null
     */
    private static function catalogMatch(string $value, array $catalog): ?array
    {
        $normalized = Str::lower(trim($value));
        if ($normalized === '' || $normalized === 'global') {
            return null;
        }

        foreach ($catalog as $slug => $meta) {
            if (Str::lower($meta['name']) === $normalized || Str::slug($meta['name']) === Str::slug($value)) {
                return self::catalogMeta($slug, $meta);
            }

            foreach (self::countryMatchNames($slug) as $label) {
                if (Str::lower($label) === $normalized) {
                    return self::catalogMeta($slug, $meta);
                }
            }
        }

        return null;
    }

    /**
     * @param  array<string, array{name: string, flag: string, code: ?string}>  $catalog
     * @return array{slug: string, name: string, flag: string, code: ?string}|null
     */
    private static function containedCatalogMatch(string $raw, array $catalog): ?array
    {
        $haystack = Str::lower($raw);
        $hits = [];

        foreach ($catalog as $slug => $meta) {
            foreach (self::countryMatchNames($slug) as $label) {
                if (mb_strlen($label) < 4) {
                    continue;
                }

                $position = self::labelPosition($haystack, $label);
                if ($position === null) {
                    continue;
                }

                $hits[$slug] = min($hits[$slug] ?? PHP_INT_MAX, $position);
            }
        }

        if ($hits === [] || count($hits) >= 3) {
            return null;
        }

        asort($hits);
        $slug = (string) array_key_first($hits);

        return self::catalogMeta($slug, $catalog[$slug]);
    }

    private static function labelPosition(string $haystack, string $label): ?int
    {
        $needle = Str::lower($label);
        $pattern = '/(?<![[:alnum:]])'.preg_quote($needle, '/').'(?![[:alnum:]])/u';

        if (! preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        return (int) $matches[0][1];
    }

    private static function looksLikeCountryName(string $value): bool
    {
        $value = trim($value);
        if ($value === '' || mb_strlen($value) > 48 || preg_match('/\d/', $value)) {
            return false;
        }

        $blocked = [
            'operates', 'entities', 'regulated', 'offices', 'relocated',
            'headquarters', 'headquartered', 'licensed', 'operational',
            'multiple', 'regions', 'global', 'vincent',
        ];

        $lower = Str::lower($value);
        foreach ($blocked as $word) {
            if (str_contains($lower, $word)) {
                return false;
            }
        }

        $words = preg_split('/\s+/', $value) ?: [];

        return count($words) >= 1 && count($words) <= 5;
    }

    /**
     * @param  array{name: string, flag: string, code: ?string}  $meta
     * @return array{slug: string, name: string, flag: string, code: ?string}
     */
    private static function catalogMeta(string $slug, array $meta): array
    {
        return [
            'slug' => $slug,
            'name' => $meta['name'],
            'flag' => $meta['flag'],
            'code' => $meta['code'],
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
    public static function splitLegacyAccountTypes(mixed $accountTypes): array
    {
        $accountTypes = JsonList::normalize($accountTypes);
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
