<?php

namespace App\Support;

use App\Models\Broker;

/**
 * Comparable metrics for the best-broker guides.
 *
 * Every metric resolves to a fact carrying both a display string and, where it makes
 * sense, a number the page can rank on. Facts also report whether the value is actually
 * known: a broker with no data recorded is rendered as "Not disclosed" rather than as a
 * dash or a confident "No", and metrics nobody has data for drop out of the comparison
 * entirely via {@see coverage()}.
 */
class BestBrokerGuideMetrics
{
    public const GROUP_COSTS = 'costs';

    public const GROUP_TRADING = 'trading';

    public const GROUP_TRUST = 'trust';

    /**
     * @return array<string, array{label: string, short: string, group: string, icon: string, better: ?string, hint: string}>
     */
    public static function definitions(): array
    {
        return [
            'min_deposit' => [
                'label' => 'Minimum deposit', 'short' => 'Min. deposit', 'group' => self::GROUP_COSTS,
                'icon' => 'fa-wallet', 'better' => 'low', 'hint' => 'Smallest amount you can fund the account with.',
            ],
            'spread' => [
                'label' => 'Spread from', 'short' => 'Spread', 'group' => self::GROUP_COSTS,
                'icon' => 'fa-bolt', 'better' => 'low', 'hint' => 'Tightest advertised spread on major FX pairs.',
            ],
            'fee_level' => [
                'label' => 'Overall fee class', 'short' => 'Fees', 'group' => self::GROUP_COSTS,
                'icon' => 'fa-tags', 'better' => null, 'hint' => 'Our editorial verdict on total trading costs.',
            ],
            'commission' => [
                'label' => 'Commission', 'short' => 'Commission', 'group' => self::GROUP_COSTS,
                'icon' => 'fa-receipt', 'better' => null, 'hint' => 'Per-lot commission charged on raw-spread accounts.',
            ],
            'withdrawal_fee' => [
                'label' => 'Withdrawal fee', 'short' => 'Withdrawals', 'group' => self::GROUP_COSTS,
                'icon' => 'fa-exchange-alt', 'better' => null, 'hint' => 'Charge applied when you take money out.',
            ],
            'leverage' => [
                'label' => 'Maximum leverage', 'short' => 'Leverage', 'group' => self::GROUP_TRADING,
                'icon' => 'fa-tachometer-alt', 'better' => 'high', 'hint' => 'Highest leverage offered outside capped jurisdictions.',
            ],
            'platforms' => [
                'label' => 'Trading platforms', 'short' => 'Platforms', 'group' => self::GROUP_TRADING,
                'icon' => 'fa-desktop', 'better' => null, 'hint' => 'Platforms you can trade the account on.',
            ],
            'account_types' => [
                'label' => 'Account types', 'short' => 'Accounts', 'group' => self::GROUP_TRADING,
                'icon' => 'fa-layer-group', 'better' => null, 'hint' => 'Account tiers the broker offers.',
            ],
            'markets' => [
                'label' => 'Markets', 'short' => 'Markets', 'group' => self::GROUP_TRADING,
                'icon' => 'fa-chart-line', 'better' => null, 'hint' => 'Asset classes available to trade.',
            ],
            'swap_free' => [
                'label' => 'Swap-free option', 'short' => 'Swap-free', 'group' => self::GROUP_TRADING,
                'icon' => 'fa-moon', 'better' => null, 'hint' => 'Islamic accounts with no overnight interest.',
            ],
            'regulators' => [
                'label' => 'Regulators', 'short' => 'Regulation', 'group' => self::GROUP_TRUST,
                'icon' => 'fa-shield-alt', 'better' => 'high', 'hint' => 'Authorities the broker holds licences with.',
            ],
            'regulatory_tier' => [
                'label' => 'Regulatory tier', 'short' => 'Tier', 'group' => self::GROUP_TRUST,
                'icon' => 'fa-award', 'better' => null, 'hint' => 'Tier 1 covers the strictest regulators.',
            ],
            'trust_score' => [
                'label' => 'Trust score', 'short' => 'Trust', 'group' => self::GROUP_TRUST,
                'icon' => 'fa-certificate', 'better' => 'high', 'hint' => 'Our published trust rating out of 99.',
            ],
            'year_founded' => [
                'label' => 'Year founded', 'short' => 'Founded', 'group' => self::GROUP_TRUST,
                'icon' => 'fa-history', 'better' => 'low', 'hint' => 'How long the broker has been operating.',
            ],
            'rating' => [
                'label' => 'Editorial rating', 'short' => 'Rating', 'group' => self::GROUP_TRUST,
                'icon' => 'fa-star', 'better' => 'high', 'hint' => 'Our published score out of 5.',
            ],
        ];
    }

    /**
     * Specs shown on every guide listing, independent of the comparison table.
     *
     * @return array<int, array{key: string, label: string, icon: string, chips?: bool, count?: bool}>
     */
    public static function listingFields(): array
    {
        return [
            ['key' => 'account_types', 'label' => 'Account types', 'icon' => 'fa-layer-group', 'count' => true],
            ['key' => 'customer_support', 'label' => 'Support', 'icon' => 'fa-headset'],
            ['key' => 'spread', 'label' => 'Spread', 'icon' => 'fa-bolt'],
            ['key' => 'commission', 'label' => 'Commission', 'icon' => 'fa-receipt'],
            ['key' => 'swap_free', 'label' => 'Swap-free', 'icon' => 'fa-moon'],
            ['key' => 'markets', 'label' => 'Markets', 'icon' => 'fa-chart-line', 'chips' => true],
        ];
    }

    /**
     * Compact columns for the side-by-side comparison table.
     *
     * @return array<int, array{key: string, label: string, chips?: bool}>
     */
    public static function compareFields(): array
    {
        return [
            ['key' => 'customer_support', 'label' => 'Support'],
            ['key' => 'spread', 'label' => 'Spread'],
            ['key' => 'commission', 'label' => 'Commission'],
            ['key' => 'swap_free', 'label' => 'Swap-free'],
            ['key' => 'markets', 'label' => 'Markets', 'chips' => true],
        ];
    }

    /**
     * @return array<string, array{id: string, label: string, caption: string}>
     */
    public static function groups(): array
    {
        return [
            self::GROUP_COSTS => ['id' => self::GROUP_COSTS, 'label' => 'Costs', 'caption' => 'What it costs to open the account and keep a position on.'],
            self::GROUP_TRADING => ['id' => self::GROUP_TRADING, 'label' => 'Trading', 'caption' => 'Platforms, account tiers, and the markets you can reach.'],
            self::GROUP_TRUST => ['id' => self::GROUP_TRUST, 'label' => 'Trust', 'caption' => 'Licensing, oversight, and operating history.'],
        ];
    }

    /**
     * @return array<string, array{key: string, value: ?string, numeric: ?float, known: bool, note: ?string}>
     */
    public static function factsFor(Broker $broker): array
    {
        $f = BrokerFacts::for($broker);

        $regulatorCodes = array_column($f['regulators'], 'code');

        return [
            'min_deposit' => self::fact('min_deposit', $f['min_deposit']['label'], $f['min_deposit']['value']),
            'spread' => self::fact('spread', $f['spread']['label'], $f['spread']['value'], $f['spread']['raw']),
            'fee_level' => self::fact('fee_level', $broker->fee_level ? ucfirst($broker->fee_level).' fees' : null),
            'commission' => self::fact('commission', RichText::toPlainText($broker->commission)),
            'withdrawal_fee' => self::fact('withdrawal_fee', RichText::toPlainText($broker->withdrawal_fee)),
            'leverage' => self::fact(
                'leverage',
                $f['leverage']['label'],
                // Unlimited leverage has no number, but it should still win a "highest" comparison.
                $f['leverage']['unlimited'] ? PHP_INT_MAX : $f['leverage']['value']
            ),
            'platforms' => self::listFact('platforms', $f['platforms']),
            'account_types' => self::listFact('account_types', $f['account_types']),
            'markets' => self::listFact('markets', array_map('ucfirst', $f['markets'])),
            'swap_free' => self::fact(
                'swap_free',
                $f['has_swap_free'] ? 'Available' : 'Not available',
                $f['has_swap_free'] ? 1 : 0
            ),
            'regulators' => self::listFact('regulators', $regulatorCodes, count($regulatorCodes)),
            'regulatory_tier' => self::fact('regulatory_tier', $f['regulatory_tier']['label']),
            'trust_score' => self::fact(
                'trust_score',
                $f['trust_score']['known'] ? $f['trust_score']['value'].'/99' : null,
                $f['trust_score']['value']
            ),
            'year_founded' => self::fact('year_founded', $f['year_founded'] ? (string) $f['year_founded'] : null, $f['year_founded']),
            'customer_support' => self::fact('customer_support', self::supportSummary($broker)),
            'rating' => self::ratingFact($broker),
        ];
    }

    /**
     * @return array{key: string, value: ?string, numeric: ?float, known: bool, note: ?string}
     */
    private static function fact(string $key, ?string $value, float|int|null $numeric = null, ?string $note = null): array
    {
        $value = $value !== null ? trim($value) : null;

        return [
            'key' => $key,
            'value' => $value === '' ? null : $value,
            'numeric' => $numeric === null ? null : (float) $numeric,
            'known' => $value !== null && $value !== '',
            'note' => $note !== null && $note !== $value ? $note : null,
        ];
    }

    /**
     * @param  array<int, string>  $items
     * @return array{key: string, value: ?string, numeric: ?float, known: bool, note: ?string, items: array<int, string>}
     */
    private static function listFact(string $key, array $items, float|int|null $numeric = null): array
    {
        $items = array_values(array_filter(array_map('trim', $items)));

        return self::fact($key, $items === [] ? null : implode(', ', $items), $numeric ?? (count($items) ?: null))
            + ['items' => $items];
    }

    /**
     * Share of entries that actually have a value for each metric.
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<string, float>
     */
    public static function coverage(array $entries): array
    {
        $total = count($entries);

        if ($total === 0) {
            return [];
        }

        $coverage = [];

        foreach (array_keys(self::definitions()) as $key) {
            $known = 0;

            foreach ($entries as $entry) {
                if (($entry['facts'][$key]['known'] ?? false) === true) {
                    $known++;
                }
            }

            $coverage[$key] = $known / $total;
        }

        return $coverage;
    }

    /**
     * How many distinct values a metric holds across the shortlist. A metric where every
     * broker reports the same thing tells the reader nothing, so the table can drop it.
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<string, int>
     */
    public static function distinctValues(array $entries): array
    {
        $distinct = [];

        foreach (array_keys(self::definitions()) as $key) {
            $seen = [];

            foreach ($entries as $entry) {
                $fact = $entry['facts'][$key] ?? null;

                if ($fact === null || ! $fact['known']) {
                    continue;
                }

                $seen[$fact['numeric'] !== null ? 'n:'.$fact['numeric'] : 'v:'.$fact['value']] = true;
            }

            $distinct[$key] = count($seen);
        }

        return $distinct;
    }

    /**
     * Which entry wins each comparable metric, so the table can highlight best-in-class.
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<string, array{entry_id: int, value: string}>
     */
    public static function bestInClass(array $entries): array
    {
        $definitions = self::definitions();
        $best = [];

        foreach ($definitions as $key => $definition) {
            if ($definition['better'] === null) {
                continue;
            }

            $winner = null;

            foreach ($entries as $entry) {
                $fact = $entry['facts'][$key] ?? null;

                if ($fact === null || ! $fact['known'] || $fact['numeric'] === null) {
                    continue;
                }

                if ($winner === null) {
                    $winner = $entry;

                    continue;
                }

                $current = $winner['facts'][$key]['numeric'];
                $candidate = $fact['numeric'];

                if ($definition['better'] === 'low' ? $candidate < $current : $candidate > $current) {
                    $winner = $entry;
                }
            }

            if ($winner !== null) {
                $best[$key] = ['entry_id' => $winner['id'], 'value' => $winner['facts'][$key]['value']];
            }
        }

        return $best;
    }

    /**
     * Headline superlatives for the hero strip, built from whichever metrics have data.
     *
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array{key: string, label: string, value: string, broker: string, icon: string}>
     */
    public static function highlights(array $entries): array
    {
        $best = self::bestInClass($entries);
        $definitions = self::definitions();

        $wanted = [
            'min_deposit' => 'Lowest entry cost',
            'spread' => 'Tightest spread',
            'leverage' => 'Highest leverage',
            'regulators' => 'Most regulated',
        ];

        $highlights = [];

        foreach ($wanted as $key => $label) {
            if (! isset($best[$key])) {
                continue;
            }

            $entry = null;

            foreach ($entries as $candidate) {
                if ($candidate['id'] === $best[$key]['entry_id']) {
                    $entry = $candidate;
                    break;
                }
            }

            if ($entry === null) {
                continue;
            }

            $value = $key === 'regulators'
                ? count($entry['facts']['regulators']['items'] ?? []).' regulators'
                : $best[$key]['value'];

            $highlights[] = [
                'key' => $key,
                'label' => $label,
                'value' => $value,
                'broker' => $entry['name'],
                'icon' => $definitions[$key]['icon'],
            ];
        }

        return $highlights;
    }

    public static function oneLiner(Broker $broker): string
    {
        return RichText::toPlainText($broker->short_description)
            ?: RichText::toPlainText($broker->top_feature)
            ?: '';
    }

    /**
     * @return array{key: string, value: ?string, numeric: ?float, known: bool, note: ?string}
     */
    private static function ratingFact(Broker $broker): array
    {
        $rating = BrokerRating::outOfFive($broker->rating);

        if ($rating === null || $rating <= 0) {
            return self::fact('rating', null);
        }

        return self::fact(
            'rating',
            number_format($rating, 1).' / 5',
            $rating
        );
    }

    /**
     * Collapse free-text support copy into channel labels the listing can scan.
     */
    private static function supportSummary(Broker $broker): ?string
    {
        $plain = RichText::toPlainText($broker->customer_support);

        if ($plain === null) {
            return null;
        }

        $found = [];
        $needles = [
            '24/7' => '/\b24\s*[\/x×]\s*7\b/i',
            'Live Chat' => '/\blive\s*chat\b/i',
            'Phone' => '/\b(?:phone|telephone|call centre|call center)\b/i',
            'Email' => '/\be-?mail\b/i',
            'WhatsApp' => '/\bwhatsapp\b/i',
            'Ticket' => '/\b(?:ticket|helpdesk|help desk)\b/i',
        ];

        foreach ($needles as $label => $pattern) {
            if (preg_match($pattern, $plain)) {
                $found[] = $label;
            }
        }

        if ($found !== []) {
            return implode(', ', $found);
        }

        return mb_strlen($plain) > 72 ? mb_substr($plain, 0, 69).'…' : $plain;
    }

    /** @return array<int, string> */
    public static function prosList(Broker $broker, int $limit = 4): array
    {
        return array_slice(RichText::listItems($broker->pros), 0, $limit);
    }

    /** @return array<int, string> */
    public static function consList(Broker $broker, int $limit = 3): array
    {
        return array_slice(RichText::listItems($broker->cons), 0, $limit);
    }

    public static function regulatoryTierLabel(?int $tier): string
    {
        return match ($tier) {
            1 => 'Tier 1',
            2 => 'Tier 2',
            3 => 'Tier 3',
            default => '—',
        };
    }
}
