<?php

namespace App\Support;

use App\Models\Broker;

/**
 * Category relevance for broker listings.
 *
 * Two jobs, both driven off {@see BrokerFacts}:
 *  - membership: does this broker actually belong on a category list, even when nobody
 *    ticked the taxonomy box in the admin?
 *  - fit: how well does it serve that specific category, scored per pillar so the page
 *    can show why a broker ranks where it does instead of a bare number.
 *
 * Pillars a broker has no data for are dropped and the remaining weights renormalised,
 * so a missing column lowers confidence rather than silently scoring zero.
 */
class BrokerCategorySignals
{
    /**
     * Pillar weights per category slug. Keys must exist in {@see pillarLibrary()}.
     *
     * @var array<string, array<string, float>>
     */
    private const CATEGORY_PILLARS = [
        'micro-accounts-brokers' => [
            'account_fit' => 0.28,
            'entry_cost' => 0.28,
            'trading_cost' => 0.16,
            'flexibility' => 0.11,
            'trust' => 0.17,
        ],
        'brokers-for-beginners' => [
            'entry_cost' => 0.24,
            'education' => 0.22,
            'platform' => 0.14,
            'trading_cost' => 0.12,
            'trust' => 0.28,
        ],
        'low-spread-brokers' => [
            'trading_cost' => 0.46,
            'execution' => 0.14,
            'platform' => 0.10,
            'trust' => 0.30,
        ],
        'scalping-brokers' => [
            'trading_cost' => 0.34,
            'execution' => 0.26,
            'automation' => 0.14,
            'trust' => 0.26,
        ],
        'high-leverage' => [
            'leverage' => 0.44,
            'trading_cost' => 0.16,
            'flexibility' => 0.10,
            'trust' => 0.30,
        ],
        'mt4-brokers' => ['platform' => 0.40, 'trading_cost' => 0.20, 'automation' => 0.10, 'trust' => 0.30],
        'mt5-brokers' => ['platform' => 0.40, 'trading_cost' => 0.20, 'automation' => 0.10, 'trust' => 0.30],
        'copytrading-brokers' => ['social' => 0.40, 'entry_cost' => 0.15, 'trading_cost' => 0.15, 'trust' => 0.30],
        'social-trading-brokers' => ['social' => 0.40, 'entry_cost' => 0.15, 'trading_cost' => 0.15, 'trust' => 0.30],
        'ea-brokers' => ['automation' => 0.40, 'platform' => 0.16, 'trading_cost' => 0.16, 'trust' => 0.28],
        'trading-apps-brokers' => ['mobile' => 0.38, 'platform' => 0.18, 'entry_cost' => 0.14, 'trust' => 0.30],
        'free-withdrawal-brokers' => ['funding' => 0.40, 'entry_cost' => 0.16, 'trading_cost' => 0.14, 'trust' => 0.30],
        'trading-signals-brokers' => ['research' => 0.38, 'platform' => 0.16, 'trading_cost' => 0.14, 'trust' => 0.32],
        'mam-brokers' => ['managed' => 0.38, 'entry_cost' => 0.14, 'trading_cost' => 0.14, 'trust' => 0.34],
        'pamm-brokers' => ['managed' => 0.38, 'entry_cost' => 0.14, 'trading_cost' => 0.14, 'trust' => 0.34],
    ];

    /** Used for country and region guides, and any category without a bespoke profile. */
    private const DEFAULT_PILLARS = [
        'trust' => 0.30,
        'trading_cost' => 0.24,
        'entry_cost' => 0.18,
        'platform' => 0.16,
        'flexibility' => 0.12,
    ];

    /**
     * @return array<string, array{label: string, description: string}>
     */
    public static function pillarLibrary(): array
    {
        return [
            'account_fit' => ['label' => 'Account fit', 'description' => 'Micro, cent, or nano accounts that let you trade fractional lot sizes.'],
            'entry_cost' => ['label' => 'Entry cost', 'description' => 'How much you need to fund the account before you can place a trade.'],
            'trading_cost' => ['label' => 'Trading cost', 'description' => 'Headline spread on major FX pairs and the broker’s overall fee class.'],
            'leverage' => ['label' => 'Leverage', 'description' => 'Maximum leverage offered to retail clients outside capped jurisdictions.'],
            'flexibility' => ['label' => 'Flexibility', 'description' => 'Swap-free options, demo access, VPS hosting, and leverage headroom.'],
            'trust' => ['label' => 'Trust & regulation', 'description' => 'Licence quality, number of regulators, and our published trust score.'],
            'platform' => ['label' => 'Platforms', 'description' => 'Breadth of trading platforms, including MetaTrader and cTrader support.'],
            'execution' => ['label' => 'Execution', 'description' => 'Pricing model, fee class, and infrastructure that affects fill quality.'],
            'automation' => ['label' => 'Automation', 'description' => 'Expert Advisor support, VPS hosting, and MetaTrader availability.'],
            'social' => ['label' => 'Copy trading', 'description' => 'Copy, social, and managed-account programmes available to clients.'],
            'mobile' => ['label' => 'Mobile trading', 'description' => 'Quality and availability of mobile and web trading apps.'],
            'education' => ['label' => 'Education', 'description' => 'Learning material, webinars, and research aimed at newer traders.'],
            'research' => ['label' => 'Research & signals', 'description' => 'Market analysis, trading signals, and economic calendar tooling.'],
            'funding' => ['label' => 'Funding costs', 'description' => 'Deposit and withdrawal charges, and the range of payment methods.'],
            'managed' => ['label' => 'Managed accounts', 'description' => 'MAM, PAMM, and account-manager programmes for pooled capital.'],
        ];
    }

    /**
     * Pillar definitions for a category, in descending weight order.
     *
     * @return array<int, array{key: string, label: string, description: string, weight: float}>
     */
    public static function pillarsFor(string $slug): array
    {
        $weights = self::CATEGORY_PILLARS[$slug] ?? self::DEFAULT_PILLARS;
        $library = self::pillarLibrary();

        arsort($weights);

        $pillars = [];

        foreach ($weights as $key => $weight) {
            $pillars[] = [
                'key' => $key,
                'label' => $library[$key]['label'] ?? ucfirst($key),
                'description' => $library[$key]['description'] ?? '',
                'weight' => $weight,
            ];
        }

        return $pillars;
    }

    /**
     * Weighted fit score for a broker on one category list.
     *
     * @return array{score: float, confidence: float, grade: string, breakdown: array<int, array<string, mixed>>}
     */
    public static function fit(Broker $broker, string $slug): array
    {
        $facts = BrokerFacts::for($broker);
        $pillars = self::pillarsFor($slug);

        $breakdown = [];
        $weighted = 0.0;
        $usedWeight = 0.0;

        foreach ($pillars as $pillar) {
            $score = self::scorePillar($pillar['key'], $broker, $facts);

            $breakdown[] = [
                'key' => $pillar['key'],
                'label' => $pillar['label'],
                'description' => $pillar['description'],
                'weight' => $pillar['weight'],
                'score' => $score,
                'known' => $score !== null,
            ];

            if ($score !== null) {
                $weighted += $score * $pillar['weight'];
                $usedWeight += $pillar['weight'];
            }
        }

        // Fall back to the editorial rating when we could not score a single pillar.
        $score = $usedWeight > 0
            ? $weighted / $usedWeight
            : min(10.0, (float) $broker->rating * 2);

        return [
            'score' => round($score, 1),
            'confidence' => round($usedWeight, 2),
            'grade' => self::grade($score),
            'breakdown' => $breakdown,
        ];
    }

    private static function grade(float $score): string
    {
        return match (true) {
            $score >= 9.0 => 'Outstanding',
            $score >= 8.0 => 'Excellent',
            $score >= 7.0 => 'Very good',
            $score >= 6.0 => 'Good',
            $score >= 5.0 => 'Fair',
            default => 'Limited',
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scorePillar(string $key, Broker $broker, array $facts): ?float
    {
        return match ($key) {
            'entry_cost' => self::scoreEntryCost($facts),
            'account_fit' => self::scoreAccountFit($facts),
            'trading_cost' => self::scoreTradingCost($broker, $facts),
            'leverage' => self::scoreLeverage($facts),
            'trust' => self::scoreTrust($facts),
            'platform' => self::scorePlatform($facts),
            'flexibility' => self::scoreFlexibility($facts),
            'execution' => self::scoreExecution($broker, $facts),
            'automation' => self::scoreAutomation($facts),
            'social' => self::scoreSocial($facts),
            'mobile' => self::scoreMobile($broker, $facts),
            'education' => self::scoreText($broker->educational_resources, $broker->research_tools),
            'research' => self::scoreText($broker->research_tools, $broker->news_and_analysis),
            'funding' => self::scoreFunding($broker),
            'managed' => self::scoreManaged($broker, $facts),
            default => null,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreEntryCost(array $facts): ?float
    {
        if (! $facts['min_deposit']['known']) {
            return null;
        }

        $value = $facts['min_deposit']['value'];

        return match (true) {
            $value <= 0 => 10.0,
            $value <= 5 => 9.6,
            $value <= 10 => 9.2,
            $value <= 25 => 8.4,
            $value <= 50 => 7.4,
            $value <= 100 => 6.0,
            $value <= 250 => 4.4,
            $value <= 500 => 3.2,
            default => 2.0,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreAccountFit(array $facts): ?float
    {
        if ($facts['has_micro_account']) {
            // A confirmed micro account plus a token minimum is the ideal combination.
            return $facts['min_deposit']['known'] && $facts['min_deposit']['value'] <= 25 ? 10.0 : 8.8;
        }

        if (! $facts['min_deposit']['known']) {
            return null;
        }

        // No micro account on record, but a very low minimum still lets you size down.
        return match (true) {
            $facts['min_deposit']['value'] <= 10 => 6.4,
            $facts['min_deposit']['value'] <= 50 => 5.0,
            default => 3.0,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreTradingCost(Broker $broker, array $facts): ?float
    {
        if (! $facts['spread']['known']) {
            return $broker->fee_level === null ? null : self::feeLevelScore($broker->fee_level);
        }

        $spread = $facts['spread']['value'];

        $score = match (true) {
            $spread <= 0.0 => 10.0,
            $spread <= 0.2 => 9.4,
            $spread <= 0.4 => 8.6,
            $spread <= 0.6 => 7.8,
            $spread <= 0.8 => 7.0,
            $spread <= 1.0 => 6.2,
            $spread <= 1.5 => 5.2,
            default => 4.2,
        };

        if ($broker->fee_level !== null) {
            $score = ($score * 0.75) + (self::feeLevelScore($broker->fee_level) * 0.25);
        }

        return round($score, 2);
    }

    private static function feeLevelScore(string $level): float
    {
        return match (strtolower($level)) {
            'low' => 9.0,
            'medium' => 6.5,
            'high' => 4.0,
            default => 6.0,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreLeverage(array $facts): ?float
    {
        if (! $facts['leverage']['known']) {
            return null;
        }

        if ($facts['leverage']['unlimited']) {
            return 10.0;
        }

        $value = $facts['leverage']['value'];

        return match (true) {
            $value >= 3000 => 9.8,
            $value >= 2000 => 9.4,
            $value >= 1000 => 8.6,
            $value >= 500 => 7.6,
            $value >= 200 => 6.0,
            $value >= 100 => 5.0,
            default => 3.5,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreTrust(array $facts): ?float
    {
        $parts = [];

        if ($facts['trust_score']['known']) {
            $parts[] = ['value' => min(10.0, $facts['trust_score']['value'] / 10), 'weight' => 0.45];
        }

        $count = count($facts['regulators']);

        if ($count > 0 || $facts['regulatory_tier']['known']) {
            $parts[] = ['value' => min(10.0, 4.5 + ($count * 1.35)), 'weight' => 0.30];
        }

        if ($facts['regulatory_tier']['known']) {
            $parts[] = [
                'value' => match ($facts['regulatory_tier']['tier']) {
                    1 => 10.0,
                    2 => 7.5,
                    default => 5.0,
                },
                'weight' => 0.25,
            ];
        }

        if ($parts === []) {
            return null;
        }

        $total = array_sum(array_column($parts, 'weight'));
        $sum = array_sum(array_map(static fn (array $p) => $p['value'] * $p['weight'], $parts));

        return round($sum / $total, 2);
    }

    /** @param  array<string, mixed>  $facts */
    private static function scorePlatform(array $facts): ?float
    {
        $platforms = $facts['platforms'];

        if ($platforms === []) {
            return null;
        }

        $joined = mb_strtolower(implode(' ', $platforms));
        $score = 4.5 + (min(count($platforms), 5) * 0.8);

        foreach (['metatrader 4' => 0.7, 'metatrader 5' => 0.7, 'ctrader' => 0.5, 'tradingview' => 0.5] as $needle => $bonus) {
            if (str_contains($joined, $needle)) {
                $score += $bonus;
            }
        }

        return round(min(10.0, $score), 2);
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreFlexibility(array $facts): ?float
    {
        $score = 4.0;
        $known = false;

        foreach (['has_swap_free' => 1.6, 'has_demo' => 1.4, 'has_vps' => 1.2, 'has_negative_balance_protection' => 1.0] as $flag => $bonus) {
            if ($facts[$flag]) {
                $score += $bonus;
                $known = true;
            }
        }

        $leverage = self::scoreLeverage($facts);

        if ($leverage !== null) {
            $score += ($leverage / 10) * 1.6;
            $known = true;
        }

        return $known ? round(min(10.0, $score), 2) : null;
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreExecution(Broker $broker, array $facts): ?float
    {
        $parts = [];

        if ($broker->fee_level !== null) {
            $parts[] = self::feeLevelScore($broker->fee_level);
        }

        if ($facts['spread']['known']) {
            $parts[] = $facts['spread']['value'] <= 0.2 ? 9.5 : ($facts['spread']['value'] <= 0.6 ? 8.0 : 6.5);
        }

        if ($facts['has_vps']) {
            $parts[] = 8.5;
        }

        return $parts === [] ? null : round(array_sum($parts) / count($parts), 2);
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreAutomation(array $facts): ?float
    {
        $metaTrader = false;

        foreach ($facts['platforms'] as $platform) {
            if (preg_match('/metatrader/i', $platform)) {
                $metaTrader = true;
                break;
            }
        }

        if (! $facts['allows_ea'] && ! $metaTrader && ! $facts['has_vps']) {
            return null;
        }

        $score = 4.0
            + ($facts['allows_ea'] ? 2.6 : 0)
            + ($metaTrader ? 2.4 : 0)
            + ($facts['has_vps'] ? 1.6 : 0);

        return round(min(10.0, $score), 2);
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreSocial(array $facts): ?float
    {
        if (! $facts['has_copy_trading']) {
            return null;
        }

        $hasCopyAccount = false;

        foreach ($facts['account_types'] as $type) {
            if (preg_match('/copy|social|pamm|mam/i', $type)) {
                $hasCopyAccount = true;
                break;
            }
        }

        return $hasCopyAccount ? 9.4 : 7.6;
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreMobile(Broker $broker, array $facts): ?float
    {
        $text = RichText::toPlainText($broker->mobile_trading);

        if ($text === null && ! $facts['platforms']) {
            return null;
        }

        $score = $text === null ? 5.0 : min(9.0, 6.0 + (mb_strlen($text) / 160));

        foreach ($facts['platforms'] as $platform) {
            if (preg_match('/app|mobile|web/i', $platform)) {
                $score += 0.8;
                break;
            }
        }

        return round(min(10.0, $score), 2);
    }

    private static function scoreText(?string ...$values): ?float
    {
        $length = 0;

        foreach ($values as $value) {
            $length += mb_strlen(RichText::toPlainText($value) ?? '');
        }

        if ($length === 0) {
            return null;
        }

        return round(min(10.0, 5.0 + ($length / 90)), 2);
    }

    private static function scoreFunding(Broker $broker): ?float
    {
        $fee = mb_strtolower(RichText::toPlainText($broker->withdrawal_fee) ?? '');
        $methods = RichText::toPlainText($broker->deposit_methods) ?? '';

        if ($fee === '' && $methods === '') {
            return null;
        }

        $score = 5.5;

        if ($fee !== '') {
            $score = preg_match('/free|no fee|zero|0/', $fee) ? 9.4 : 5.5;
        }

        if ($methods !== '') {
            $score += min(1.5, mb_strlen($methods) / 140);
        }

        return round(min(10.0, $score), 2);
    }

    /** @param  array<string, mixed>  $facts */
    private static function scoreManaged(Broker $broker, array $facts): ?float
    {
        $managed = (bool) $broker->account_managers;
        $hasProgramme = $facts['has_copy_trading'];

        if (! $managed && ! $hasProgramme) {
            return null;
        }

        return round(min(10.0, 5.0 + ($managed ? 2.4 : 0) + ($hasProgramme ? 2.6 : 0)), 2);
    }

    /**
     * Does this broker belong on the list even though nobody tagged it in the admin?
     *
     * Category tags are only populated on a handful of rows, which leaves most guides
     * ranking the entire database. Deriving membership from the underlying facts keeps
     * the listings honest without waiting on a data backfill.
     */
    public static function derivedMatch(Broker $broker, string $slug): bool
    {
        $facts = BrokerFacts::for($broker);

        return match ($slug) {
            'micro-accounts-brokers' => $facts['has_micro_account']
                || ($facts['min_deposit']['known'] && $facts['min_deposit']['value'] <= 10),
            'brokers-for-beginners' => $facts['has_demo']
                && $facts['min_deposit']['known']
                && $facts['min_deposit']['value'] <= 50,
            'low-spread-brokers' => $facts['spread']['known']
                && $facts['spread']['value'] <= 0.2
                && $broker->fee_level !== 'high',
            'scalping-brokers' => $facts['spread']['known']
                && $facts['spread']['value'] <= 0.5
                && ($facts['allows_ea'] || $facts['has_vps']),
            'copytrading-brokers', 'social-trading-brokers' => $facts['has_copy_trading'],
            'ea-brokers' => $facts['allows_ea'] && self::hasMetaTrader($facts),
            'trading-apps-brokers' => self::hasMobileApp($broker, $facts),
            'free-withdrawal-brokers' => self::hasFreeWithdrawals($broker),
            'trading-signals-brokers' => self::mentions($broker, '/\btrading signal|\bsignals?\b/i'),
            'mam-brokers' => self::mentions($broker, '/\bmam\b/i'),
            'pamm-brokers' => self::mentions($broker, '/\bpamm\b/i'),
            default => false,
        };
    }

    /** @param  array<string, mixed>  $facts */
    private static function hasMetaTrader(array $facts): bool
    {
        foreach ($facts['platforms'] as $platform) {
            if (preg_match('/metatrader/i', $platform)) {
                return true;
            }
        }

        return false;
    }

    /** @param  array<string, mixed>  $facts */
    private static function hasMobileApp(Broker $broker, array $facts): bool
    {
        foreach ($facts['platforms'] as $platform) {
            if (preg_match('/app|mobile/i', $platform)) {
                return true;
            }
        }

        // Nearly every row has something in mobile_trading, so require an actual
        // named app or store rather than treating any prose as evidence.
        $text = RichText::toPlainText($broker->mobile_trading) ?? '';

        return (bool) preg_match('/\b(ios|android|app store|google play|mobile app|proprietary app)\b/i', $text);
    }

    private static function hasFreeWithdrawals(Broker $broker): bool
    {
        $fee = mb_strtolower(RichText::toPlainText($broker->withdrawal_fee) ?? '');

        if ($fee !== '' && preg_match('/free|no fee|zero/', $fee)) {
            return true;
        }

        return self::mentions($broker, '/free withdrawal|no withdrawal fee|withdrawals? (?:are |is )?free/i');
    }

    private static function mentions(Broker $broker, string $pattern): bool
    {
        $text = strip_tags(implode(' ', array_filter([
            $broker->getRawOriginal('pros'),
            $broker->getRawOriginal('short_description'),
            $broker->getRawOriginal('top_feature'),
            $broker->getRawOriginal('description'),
            $broker->getRawOriginal('account_types'),
        ], 'is_string')));

        return (bool) preg_match($pattern, $text);
    }

    /**
     * Short, human-readable evidence chips explaining the ranking.
     *
     * @return array<int, array{tone: string, icon: string, label: string}>
     */
    public static function signals(Broker $broker, string $slug): array
    {
        $facts = BrokerFacts::for($broker);
        $signals = [];

        if ($slug === 'micro-accounts-brokers' && $facts['has_micro_account']) {
            $signals[] = ['tone' => 'strong', 'icon' => 'fa-coins', 'label' => 'Micro / cent account'];
        }

        if ($facts['min_deposit']['known'] && $facts['min_deposit']['value'] <= 25) {
            $signals[] = [
                'tone' => 'strong',
                'icon' => 'fa-wallet',
                'label' => $facts['min_deposit']['value'] <= 0
                    ? 'No minimum deposit'
                    : $facts['min_deposit']['label'].' to start',
            ];
        }

        if ($facts['spread']['known'] && $facts['spread']['value'] <= 0.3) {
            $signals[] = ['tone' => 'strong', 'icon' => 'fa-bolt', 'label' => 'Spreads from '.$facts['spread']['label']];
        }

        if ($facts['leverage']['known'] && ($facts['leverage']['unlimited'] || $facts['leverage']['value'] >= 1000)) {
            $signals[] = ['tone' => 'neutral', 'icon' => 'fa-tachometer-alt', 'label' => $facts['leverage']['label'].' leverage'];
        }

        if (count($facts['regulators']) >= 2) {
            $signals[] = [
                'tone' => 'strong',
                'icon' => 'fa-shield-alt',
                'label' => count($facts['regulators']).' regulators',
            ];
        }

        if ($facts['has_swap_free']) {
            $signals[] = ['tone' => 'neutral', 'icon' => 'fa-moon', 'label' => 'Swap-free available'];
        }

        if ($facts['regulators'] === []) {
            $signals[] = ['tone' => 'caution', 'icon' => 'fa-exclamation-triangle', 'label' => 'No licence on record'];
        }

        if ($slug === 'micro-accounts-brokers'
            && ! $facts['has_micro_account']
            && $facts['min_deposit']['known']
            && $facts['min_deposit']['value'] > 25) {
            $signals[] = ['tone' => 'caution', 'icon' => 'fa-info-circle', 'label' => 'No micro account confirmed'];
        }

        return array_slice($signals, 0, 5);
    }
}
