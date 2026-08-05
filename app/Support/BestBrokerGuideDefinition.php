<?php

namespace App\Support;

use Illuminate\Support\Str;

class BestBrokerGuideDefinition
{
    /** @return array<string, mixed>|null */
    public static function forSlug(string $slug): ?array
    {
        $type = BrokerListingFilter::slugType($slug);

        if ($type === null) {
            return null;
        }

        $label = BrokerListingFilter::labelFor($slug);
        $topic = self::topicLabel($type, $label);
        $scoreLabel = self::scoreLabel($type, $label);

        return [
            'type' => $type,
            'title' => self::title($type, $label),
            'meta_title' => self::metaTitle($type, $label),
            'meta_description' => self::metaDescription($type, $label),
            'breadcrumb' => self::breadcrumb($type, $label),
            'score_label' => $scoreLabel,
            'score_key' => 'guide_score',
            'topic' => $topic,
            'topic_label' => $topic,
            'winner_intro' => self::winnerIntro($type, $label),
            'strengths_intro' => self::strengthsIntro($type, $label),
            'spotlight_title' => self::spotlightTitle($type, $label),
            'cta_title' => self::ctaTitle($type, $label),
            'sections' => self::sections($type, $label, $scoreLabel),
            'comparison_tables' => self::comparisonTables($scoreLabel),
            'methodology' => [
                'title' => self::methodologyTitle($type, $label),
                'intro' => 'Our analysts evaluate brokers using verified fee schedules, regulation checks, and hands-on platform review. For these rankings we weighted the criteria below most heavily.',
                'points' => [
                    'Compared spreads, commissions, and overall fee class on major FX pairs',
                    'Reviewed platform availability, usability, and mobile trading support',
                    'Checked minimum deposit, withdrawal fees, and funding methods',
                    'Verified regulation, investor protection, and operating history',
                    'Assessed leverage, instrument coverage, and account types',
                    'Cross-checked broker data against live listings in our database',
                ],
            ],
            'faqs' => self::faqs($type, $label),
            'recommended_for' => [
                'default' => self::recommendedDefault($type, $label),
                'winner' => self::recommendedWinner($type, $label),
            ],
        ];
    }

    private static function methodologyTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "How did BrokersCourt select the best brokers in {$label}?",
            'region' => "How did BrokersCourt select the best brokers for {$label}?",
            default => self::categoryHasBrokersSuffix($label)
                ? "How did BrokersCourt select the best {$label}?"
                : "How did BrokersCourt select the best {$label} brokers?",
        };
    }

    private static function recommendedDefault(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Traders in {$label} looking for a regulated broker with competitive costs",
            'region' => "Traders in {$label} looking for a regulated broker with competitive costs",
            default => self::categoryHasBrokersSuffix($label)
                ? "Traders looking for a regulated broker suited to {$label}"
                : "Traders looking for a regulated {$label} broker with competitive costs",
        };
    }

    private static function recommendedWinner(string $type, string $label): string
    {
        return match ($type) {
            'country' => "The strongest overall broker for clients in {$label}",
            'region' => "The strongest overall broker serving {$label}",
            default => "The strongest overall package for {$label}",
        };
    }

    private static function title(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Best Forex Brokers in {$label} {year}",
            'region' => "Best {$label} {year}",
            default => self::categoryHasBrokersSuffix($label)
                ? "Best {$label} in {year}"
                : "Best {$label} Brokers in {year}",
        };
    }

    private static function categoryHasBrokersSuffix(string $label): bool
    {
        return Str::endsWith(Str::lower($label), 'brokers');
    }

    private static function metaTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Best Forex Brokers in {$label} {year} – Fees & Regulation Compared",
            default => self::categoryHasBrokersSuffix($label)
                ? "Best {$label} {year} – Fees, Platforms & Trust Compared"
                : "Best {$label} Brokers {year} – Fees, Platforms & Trust Compared",
        };
    }

    private static function metaDescription(string $type, string $label): string
    {
        $focus = match ($type) {
            'country' => "traders in {$label}",
            'region' => "traders in {$label}",
            default => $label,
        };

        return "Compare the best forex brokers for {$focus} in {year}. Rankings based on fees, regulation, platforms, deposits, and trust metrics from our broker database.";
    }

    private static function breadcrumb(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Best brokers in {$label}",
            default => $label,
        };
    }

    private static function topicLabel(string $type, string $label): string
    {
        return match ($type) {
            'country', 'region' => $label,
            default => Str::lower($label),
        };
    }

    private static function winnerIntro(string $type, string $label): string
    {
        return match ($type) {
            'country' => "After reviewing fees, regulation, platforms, and trading conditions across our broker database, we found {winner} to be the best forex broker for clients in {$label} in {year}.",
            'region' => "After reviewing fees, regulation, platforms, and trading conditions across our broker database, we found {winner} to be the best match for {$label} in {year}.",
            default => self::categoryHasBrokersSuffix($label)
                ? "After reviewing fees, regulation, platforms, and trading conditions across our broker database, we found {winner} to be the best match for {$label} in {year}."
                : "After reviewing fees, regulation, platforms, and trading conditions across our broker database, we found {winner} to be the best {$label} broker in {year}.",
        };
    }

    private static function strengthsIntro(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Here are the main strengths of the best forex brokers in {$label}, updated for {year}:",
            'region' => "Here are the main strengths of the best forex brokers in {$label}, updated for {year}:",
            default => self::categoryHasBrokersSuffix($label)
                ? "Here are the main strengths of the best {$label}, updated for {year}:"
                : "Here are the main strengths of the best {$label} brokers, updated for {year}:",
        };
    }

    private static function spotlightTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Our top broker pick for {$label}",
            'region' => "Our top broker pick for {$label}",
            default => self::categoryHasBrokersSuffix($label)
                ? "A top {$label} pick"
                : "Our top {$label} broker pick",
        };
    }

    private static function ctaTitle(string $type, string $label): string
    {
        return match ($type) {
            'country' => "Need help finding the right broker in {$label}?",
            'region' => "Need help finding the right broker for {$label}?",
            default => self::categoryHasBrokersSuffix($label)
                ? "Need help finding the right {$label} for you?"
                : "Need help finding the right {$label} broker for you?",
        };
    }

    private static function scoreLabel(string $type, string $label): string
    {
        return match ($type) {
            'country' => 'Overall score',
            default => rtrim($label, ' Brokers').' score',
        };
    }

    /** @return array<int, array<string, mixed>> */
    private static function sections(string $type, string $label, string $scoreLabel): array
    {
        $standoutTitle = match ($type) {
            'country' => "What makes the best forex brokers in {$label} stand out?",
            'region' => "What makes the best forex brokers in {$label} stand out?",
            default => self::categoryHasBrokersSuffix($label)
                ? "What makes the best {$label} stand out?"
                : "What makes the best {$label} brokers stand out?",
        };

        return [
            [
                'id' => 'standout',
                'title' => $standoutTitle,
                'description' => 'The interactive table below compares key features of our top brokers, including fees, minimum deposit, leverage, platform count, and our editorial score.',
                'caption' => "Key data for top brokers — updated {month} {year}",
                'table' => 'features',
            ],
            [
                'id' => 'trading-fees',
                'title' => 'Are trading fees low at the top brokers?',
                'description' => 'Trading costs matter on every position. Compare headline spreads, commissions, and fee class across the shortlisted brokers.',
                'caption' => "Trading fees at top brokers — {month} {year}",
                'table' => 'trading_fees',
            ],
            [
                'id' => 'service-fees',
                'title' => 'Are non-trading fees low at the top brokers?',
                'description' => 'Withdrawal charges and account minimums can add up. Compare the main non-trading costs below.',
                'caption' => "Non-trading fees — {month} {year}",
                'table' => 'service_fees',
            ],
            [
                'id' => 'trust',
                'title' => 'Can I trust these trading platforms?',
                'description' => 'Trust comes from strong regulation, investor protection, and a long operating history. Explore how each broker scores on safety metrics.',
                'caption' => 'Trust metrics for top brokers in {year}',
                'table' => 'trust',
            ],
            [
                'id' => 'broker-details',
                'title' => 'Where can I find more details on the top brokers?',
                'description' => 'The summaries below give a concise overview of what each broker offers — starting with our top pick, {winner}.',
                'table' => null,
            ],
        ];
    }

    /** @return array<string, array<int, array<string, string>>> */
    private static function comparisonTables(string $scoreLabel): array
    {
        return [
            'features' => [
                ['key' => 'guide_score', 'label' => $scoreLabel],
                ['key' => 'spreads', 'label' => 'Typical spreads'],
                ['key' => 'commission', 'label' => 'Commission'],
                ['key' => 'fee_level', 'label' => 'Fee level'],
                ['key' => 'minimum_deposit', 'label' => 'Minimum deposit'],
                ['key' => 'leverage', 'label' => 'Max leverage'],
                ['key' => 'platform_count', 'label' => 'Platforms (#)'],
                ['key' => 'instrument_count', 'label' => 'Instruments (#)'],
            ],
            'trading_fees' => [
                ['key' => 'spreads', 'label' => 'EUR/USD spread'],
                ['key' => 'commission', 'label' => 'Commission'],
                ['key' => 'fee_level', 'label' => 'Overall fee class'],
                ['key' => 'pricing', 'label' => 'Pricing model'],
            ],
            'service_fees' => [
                ['key' => 'minimum_deposit', 'label' => 'Minimum deposit'],
                ['key' => 'withdrawal_fee', 'label' => 'Withdrawal fee'],
                ['key' => 'deposit_methods', 'label' => 'Deposit methods'],
            ],
            'trust' => [
                ['key' => 'regulatory_tier', 'label' => 'Regulatory tier'],
                ['key' => 'regulator_count', 'label' => 'Regulators (#)'],
                ['key' => 'investor_protection', 'label' => 'Investor protection'],
                ['key' => 'year_founded', 'label' => 'Year founded'],
                ['key' => 'negative_balance_protection', 'label' => 'Negative balance protection'],
            ],
        ];
    }

    /** @return array<int, array{question: string, answer: string}> */
    private static function faqs(string $type, string $label): array
    {
        return [
            [
                'question' => match ($type) {
                    'country' => "How did BrokersCourt rank the best brokers in {$label}?",
                    default => self::categoryHasBrokersSuffix($label)
                        ? "How did BrokersCourt rank the best {$label}?"
                        : "How did BrokersCourt rank the best {$label} brokers?",
                },
                'answer' => 'We combine editorial scores, verified broker data, regulation checks, and fee comparisons. Brokers are sorted by our guide score, which weights overall rating, trading costs, platform breadth, and safety metrics.',
            ],
            [
                'question' => 'Are the brokers on this page regulated?',
                'answer' => 'We prioritize regulated brokers with transparent licensing. Each listing shows regulatory tier, investor protection, and the number of regulators so you can compare safety before opening an account.',
            ],
            [
                'question' => 'Which broker is cheapest to trade with?',
                'answer' => 'The cheapest broker depends on your strategy, account type, and market. Use the trading fees table to compare spreads and commission, then read the full review for account-specific pricing.',
            ],
            [
                'question' => $type === 'country'
                    ? "Can traders in {$label} use these brokers?"
                    : 'How do I choose the right broker from this list?',
                'answer' => $type === 'country'
                    ? "Brokers shown here accept clients from or actively serve {$label}, but product availability still varies by entity. Confirm local regulation, payment methods, and leverage limits on the broker's site before signing up."
                    : 'Start with the broker that matches your platform preference and fee sensitivity, then verify regulation for your country, minimum deposit, and available markets on the broker review page.',
            ],
        ];
    }
}
