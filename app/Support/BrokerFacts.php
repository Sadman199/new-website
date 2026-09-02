<?php

namespace App\Support;

use App\Models\Broker;

/**
 * Turns the loosely-typed broker columns into typed, comparable facts.
 *
 * Broker rows carry a lot of free text ("From 0.0 pips (Raw/Zero accounts) to 0.3 pips"),
 * multi-encoded JSON arrays, and columns that were never filled in. Listing pages need
 * numbers they can rank and a reliable "do we actually know this?" flag so unknown values
 * are omitted rather than rendered as a dash or, worse, as a confident "No".
 */
class BrokerFacts
{
    /** Regulator acronyms we recognise, longest-first so "CySEC" wins over "SEC". */
    private const REGULATORS = [
        'CySEC' => 'Cyprus',
        'FINTRAC' => 'Canada',
        'CONSOB' => 'Italy',
        'FINMA' => 'Switzerland',
        'IIROC' => 'Canada',
        'BaFin' => 'Germany',
        'FSCA' => 'South Africa',
        'CFTC' => 'United States',
        'CIRO' => 'Canada',
        'DFSA' => 'Dubai',
        'JFSA' => 'Japan',
        'CNMV' => 'Spain',
        'VFSC' => 'Vanuatu',
        'SEBI' => 'India',
        'ASIC' => 'Australia',
        'FCA' => 'United Kingdom',
        'FSA' => 'Seychelles',
        'FSC' => 'Mauritius',
        'MAS' => 'Singapore',
        'NFA' => 'United States',
        'SCA' => 'United Arab Emirates',
        'SFC' => 'Hong Kong',
        'CMA' => 'Kenya',
        'FMA' => 'New Zealand',
        'CBI' => 'Ireland',
    ];

    /** Tier 1 regulators carry the strongest client-money protections. */
    private const TIER_ONE = ['FCA', 'ASIC', 'CFTC', 'NFA', 'BaFin', 'FINMA', 'JFSA', 'MAS', 'IIROC', 'CIRO'];

    private const TIER_TWO = ['CySEC', 'FSCA', 'DFSA', 'SCA', 'CNMV', 'CONSOB', 'SFC', 'FMA', 'CBI', 'SEBI'];

    /** Platform names we can recover from prose when the JSON column is empty. */
    private const PLATFORM_PATTERNS = [
        'MetaTrader 4' => '/\b(?:mt\s?4|metatrader\s?4)\b/i',
        'MetaTrader 5' => '/\b(?:mt\s?5|metatrader\s?5)\b/i',
        'cTrader' => '/\bc\s?trader\b/i',
        'TradingView' => '/\btrading\s?view\b/i',
        'WebTrader' => '/\bweb\s?trader\b/i',
        'Mobile app' => '/\b(?:mobile app|ios and android|android and ios)\b/i',
    ];

    /**
     * Phrases that mean "explicitly unregulated". Without this guard the acronym scan
     * happily reads "does not hold any regulatory license from any recognized financial
     * authority" as evidence of regulation.
     */
    private const UNREGULATED_MARKERS = [
        'does not operate under any regulated',
        'does not hold any regulatory',
        'not regulated by any',
        'unregulated',
        'no regulatory license',
    ];

    /** @var array<int, array<string, mixed>> */
    private static array $cache = [];

    /** @return array<string, mixed> */
    public static function for(Broker $broker): array
    {
        $key = $broker->id ?? spl_object_id($broker);

        return self::$cache[$key] ??= self::build($broker);
    }

    public static function flush(): void
    {
        self::$cache = [];
    }

    /** @return array<string, mixed> */
    private static function build(Broker $broker): array
    {
        $prose = self::prose($broker);
        $accountTypes = self::accountTypes($broker);
        $regulators = self::regulators($broker, $prose);
        $spread = self::spread($broker, $prose);
        $leverage = self::leverage($broker, $prose);
        $minDeposit = self::minDeposit($broker);

        return [
            'account_types' => $accountTypes,
            'countries' => self::decodeMangledList($broker->getRawOriginal('associated_countries')),
            'platforms' => self::platforms($broker, $prose),
            'regulators' => $regulators,
            'regulatory_tier' => self::regulatoryTier($broker, $regulators),
            'spread' => $spread,
            'leverage' => $leverage,
            'min_deposit' => $minDeposit,
            'trust_score' => self::trustScore($broker),
            'markets' => $broker->marketList(),
            'has_micro_account' => self::detectMicroAccount($accountTypes, $prose),
            'has_swap_free' => self::matchesAny($prose, ['/\bswap[\s-]?free\b/i', '/\bislamic\b/i']),
            'has_copy_trading' => self::matchesAny($prose, ['/\bcopy[\s-]?trad/i', '/\bsocial[\s-]?trad/i', '/\bpamm\b/i', '/\bmam\b/i']),
            'allows_ea' => self::matchesAny($prose, ['/\bexpert advisor/i', '/\bea[s]?\b(?=.*trad)/i', '/\balgorithmic trad/i', '/\bautomated trad/i']),
            'has_demo' => (bool) $broker->demo_account_available || self::matchesAny($prose, ['/\bdemo account\b/i']),
            'has_vps' => (bool) $broker->vps_hosting || self::matchesAny($prose, ['/\bvps\b/i']),
            'has_negative_balance_protection' => (bool) $broker->negative_balance_protection
                || self::matchesAny($prose, ['/negative balance protection/i']),
            'year_founded' => $broker->year_founded ? (int) $broker->year_founded : null,
        ];
    }

    /**
     * Every free-text column a fact might be hiding in, lower-cased and tag-stripped.
     */
    private static function prose(Broker $broker): string
    {
        $parts = [
            $broker->getRawOriginal('pros'),
            $broker->getRawOriginal('cons'),
            $broker->getRawOriginal('short_description'),
            $broker->getRawOriginal('top_feature'),
            $broker->getRawOriginal('description'),
            $broker->getRawOriginal('pricing'),
            $broker->getRawOriginal('regulated_jurisdictions'),
            $broker->getRawOriginal('regulatory_licenses'),
            $broker->getRawOriginal('account_types'),
            $broker->getRawOriginal('leverage'),
            $broker->getRawOriginal('spreads'),
        ];

        $text = strip_tags(implode(' ', array_filter($parts, 'is_string')));

        return preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')) ?? '';
    }

    /**
     * Legacy imports re-encoded these arrays on every save, so a single value can arrive as
     * ["\"[\\\"Standard Accounts\\\"", "\\\"Micro Accounts\\\"]\""]. Nothing sane survives
     * json_decode, so strip the structural characters and split on the separators instead.
     *
     * @return array<int, string>
     */
    public static function decodeMangledList(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if (is_array($value)) {
            $value = json_encode($value);
        }

        $flat = str_replace(['\\', '"', '[', ']'], '', (string) $value);
        $parts = preg_split('/\s*,\s*/', $flat) ?: [];

        $items = [];

        foreach ($parts as $part) {
            $part = trim($part);

            if ($part === '' || $part === 'null') {
                continue;
            }

            $items[mb_strtolower($part)] = $part;
        }

        return array_values($items);
    }

    /** Acronyms that must not be title-cased when a legacy slug is expanded. */
    private const ACCOUNT_ACRONYMS = ['Ecn' => 'ECN', 'Vip' => 'VIP', 'Cfd' => 'CFD', 'Pamm' => 'PAMM', 'Mam' => 'MAM', 'Ea' => 'EA'];

    /** @return array<int, string> */
    private static function accountTypes(Broker $broker): array
    {
        $items = self::decodeMangledList($broker->getRawOriginal('account_types'));

        $labels = [];

        foreach ($items as $item) {
            // Older rows stored taxonomy slugs here ("ecn-raw", "vip"), newer ones store labels.
            if (preg_match('/^[a-z0-9-]+$/', $item)) {
                $item = strtr(ucwords(str_replace('-', ' ', $item)), self::ACCOUNT_ACRONYMS);
            }

            if ($item !== '') {
                $labels[mb_strtolower($item)] = $item;
            }
        }

        return array_values($labels);
    }

    /** @return array<int, string> */
    private static function platforms(Broker $broker, string $prose): array
    {
        $platforms = [];

        foreach ($broker->platformList() as $platform) {
            $platform = trim(RichText::toPlainText($platform) ?? '');

            if ($platform !== '') {
                $platforms[mb_strtolower($platform)] = $platform;
            }
        }

        if ($platforms !== []) {
            return array_values($platforms);
        }

        foreach (self::PLATFORM_PATTERNS as $label => $pattern) {
            if (preg_match($pattern, $prose)) {
                $platforms[mb_strtolower($label)] = $label;
            }
        }

        return array_values($platforms);
    }

    /**
     * @return array<int, array{code: string, region: string}>
     */
    private static function regulators(Broker $broker, string $prose): array
    {
        $found = [];

        foreach ($broker->regulationList() as $entry) {
            $entry = RichText::toPlainText($entry) ?? '';

            // Stored as "CySEC (Cyprus)" — keep the acronym, drop the parenthetical.
            $code = trim(preg_replace('/\(.*$/', '', $entry) ?? $entry);

            if ($code !== '') {
                $found[mb_strtoupper($code)] = [
                    'code' => $code,
                    'region' => trim(preg_match('/\(([^)]+)\)/', $entry, $m) ? $m[1] : (self::REGULATORS[$code] ?? '')),
                ];
            }
        }

        if ($found !== [] || self::matchesAny($prose, array_map(
            static fn (string $marker) => '/'.preg_quote($marker, '/').'/i',
            self::UNREGULATED_MARKERS
        ))) {
            return array_values($found);
        }

        // The JSON column is empty for a chunk of the table, but the licence and
        // jurisdiction columns usually spell the regulators out in prose.
        foreach (self::REGULATORS as $code => $region) {
            if (preg_match('/\b'.preg_quote($code, '/').'\b/i', $prose)) {
                $found[mb_strtoupper($code)] = ['code' => $code, 'region' => $region];
            }
        }

        return array_values($found);
    }

    /**
     * @param  array<int, array{code: string, region: string}>  $regulators
     * @return array{tier: int|null, label: string|null, known: bool}
     */
    private static function regulatoryTier(Broker $broker, array $regulators): array
    {
        $codes = array_map(static fn (array $r) => mb_strtoupper($r['code']), $regulators);

        $tier = null;

        if (array_intersect($codes, array_map('mb_strtoupper', self::TIER_ONE)) !== []) {
            $tier = 1;
        } elseif (array_intersect($codes, array_map('mb_strtoupper', self::TIER_TWO)) !== []) {
            $tier = 2;
        } elseif ($codes !== []) {
            $tier = 3;
        }

        // The stored tier is editorial and only present on a third of rows; prefer it
        // when it is stricter than what the licence list alone suggests.
        if ($broker->regulatory_tier !== null) {
            $tier = $tier === null ? (int) $broker->regulatory_tier : min($tier, (int) $broker->regulatory_tier);
        }

        return [
            'tier' => $tier,
            'label' => $tier === null ? null : 'Tier '.$tier,
            'known' => $tier !== null,
        ];
    }

    /**
     * @return array{value: float|null, label: string|null, raw: string|null, known: bool}
     */
    private static function spread(Broker $broker, string $prose): array
    {
        $raw = RichText::toPlainText($broker->spreads);

        $value = self::parsePips((string) $raw);

        if ($value === null) {
            $value = self::parsePips($prose);
        }

        return [
            'value' => $value,
            'label' => $value === null ? null : self::formatPips($value),
            'raw' => $raw,
            'known' => $value !== null,
        ];
    }

    private static function parsePips(string $text): ?float
    {
        if ($text === '') {
            return null;
        }

        // "from 0.2–0.3 pips" advertises 0.2; the range's low end is the headline number.
        if (preg_match_all('/(\d+(?:\.\d+)?)\s*[–—-]\s*(\d+(?:\.\d+)?)\s*pip/i', $text, $ranges)) {
            return (float) min(array_map('floatval', $ranges[1]));
        }

        if (preg_match_all('/(\d+(?:\.\d+)?)\s*pip/i', $text, $matches)) {
            $values = array_filter(array_map('floatval', $matches[1]), static fn (float $v) => $v <= 20);

            if ($values !== []) {
                return (float) min($values);
            }
        }

        return null;
    }

    private static function formatPips(float $value): string
    {
        // Spreads are quoted to one decimal in the industry, and "0.0 pips" is the headline
        // claim brokers actually make — never collapse it to "0 pips".
        $decimals = fmod($value * 10, 1.0) === 0.0 ? 1 : 2;

        return number_format($value, $decimals, '.', '').' pips';
    }

    /**
     * @return array{value: int|null, unlimited: bool, label: string|null, known: bool}
     */
    private static function leverage(Broker $broker, string $prose): array
    {
        $raw = RichText::toPlainText($broker->leverage) ?? '';

        $parsed = self::parseLeverage($raw);

        if ($parsed === null) {
            $parsed = self::parseLeverage($prose);
        }

        if ($parsed === null) {
            return ['value' => null, 'unlimited' => false, 'label' => null, 'known' => false];
        }

        if ($parsed === 'unlimited') {
            return ['value' => null, 'unlimited' => true, 'label' => '1:Unlimited', 'known' => true];
        }

        return [
            'value' => $parsed,
            'unlimited' => false,
            'label' => '1:'.number_format($parsed),
            'known' => true,
        ];
    }

    private static function parseLeverage(string $text): int|string|null
    {
        if ($text === '') {
            return null;
        }

        if (preg_match('/1\s*:\s*unlimited/i', $text)) {
            return 'unlimited';
        }

        $values = [];

        if (preg_match_all('/1\s*:\s*(\d[\d,]*)/i', $text, $matches)) {
            $values = array_map(static fn (string $v) => (int) str_replace(',', '', $v), $matches[1]);
        }

        // A couple of rows write the ratio backwards, e.g. "1000:1".
        if ($values === [] && preg_match_all('/(\d[\d,]*)\s*:\s*1\b/', $text, $matches)) {
            $values = array_map(static fn (string $v) => (int) str_replace(',', '', $v), $matches[1]);
        }

        return $values === [] ? null : max($values);
    }

    /**
     * @return array{value: float|null, label: string|null, known: bool}
     */
    private static function minDeposit(Broker $broker): array
    {
        if ($broker->minimum_deposit === null) {
            return ['value' => null, 'label' => null, 'known' => false];
        }

        $value = (float) $broker->minimum_deposit;

        return [
            'value' => $value,
            // "$0" reads unambiguously next to "$5" and "$10" in a comparison column;
            // the "no minimum deposit" phrasing belongs in the signal chips instead.
            'label' => '$'.number_format(max($value, 0), 0),
            'known' => true,
        ];
    }

    /** @return array{value: int|null, known: bool} */
    private static function trustScore(Broker $broker): array
    {
        $value = $broker->trust_score !== null ? (int) $broker->trust_score : null;

        return ['value' => $value, 'known' => $value !== null];
    }

    /**
     * @param  array<int, string>  $accountTypes
     */
    private static function detectMicroAccount(array $accountTypes, string $prose): bool
    {
        foreach ($accountTypes as $type) {
            if (preg_match('/\b(micro|cent|nano)\b/i', $type)) {
                return true;
            }
        }

        return self::matchesAny($prose, [
            '/\bmicro account/i',
            '/\bcent account/i',
            '/\bnano account/i',
            '/\bmicro lot/i',
        ]);
    }

    /** @param  array<int, string>  $patterns */
    private static function matchesAny(string $subject, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $subject)) {
                return true;
            }
        }

        return false;
    }
}
