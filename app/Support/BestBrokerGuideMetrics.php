<?php

namespace App\Support;

use App\Models\Broker;

class BestBrokerGuideMetrics
{
    public static function guideScore(Broker $broker, string $slug, string $type): float
    {
        $scores = is_array($broker->category_scores) ? $broker->category_scores : [];

        if (isset($scores[$slug])) {
            return min(5.0, round((float) $scores[$slug], 1));
        }

        if (isset($scores['overall'])) {
            return min(5.0, round((float) $scores['overall'], 1));
        }

        $score = (float) $broker->rating;

        if ($type === 'category') {
            if (in_array($slug, $broker->brokerCategoryList(), true)) {
                $score += 0.1;
            }

            if ($slug === 'low-spread-brokers' && str_contains(strtolower((string) $broker->spreads), '0.0')) {
                $score += 0.15;
            }

            if ($slug === 'scalping-brokers') {
                if ($broker->fee_level === 'low') {
                    $score += 0.15;
                }
                if (str_contains(strtolower((string) $broker->spreads), '0.0')) {
                    $score += 0.1;
                }
                if ($broker->vps_hosting) {
                    $score += 0.05;
                }
            }

            if (in_array($slug, ['mt4-brokers', 'mt5-brokers'], true)) {
                $needles = $slug === 'mt4-brokers' ? ['mt4', 'metatrader 4'] : ['mt5', 'metatrader 5'];
                foreach ($broker->platformList() as $platform) {
                    $platform = strtolower($platform);
                    foreach ($needles as $needle) {
                        if (str_contains($platform, $needle)) {
                            $score += 0.15;
                            break 2;
                        }
                    }
                }
            }

            if ($slug === 'brokers-for-beginners' && (float) $broker->minimum_deposit <= 10) {
                $score += 0.1;
            }

            if ($slug === 'high-leverage') {
                $maxLeverage = BrokerListingFilter::maxLeverageFor($broker);

                if ($maxLeverage >= 2000) {
                    $score += 0.25;
                } elseif ($maxLeverage >= 1000) {
                    $score += 0.15;
                } elseif ($maxLeverage >= 500) {
                    $score += 0.1;
                }
            }
        }

        if ($broker->fee_level === 'low') {
            $score += 0.05;
        }

        if ((int) $broker->regulatory_tier === 1) {
            $score += 0.05;
        }

        return min(5.0, round($score, 1));
    }

    /** @return array<string, string> */
    public static function values(Broker $broker, string $slug, string $type): array
    {
        $platforms = $broker->platformList();
        $regulators = $broker->regulationList();

        return [
            'guide_score' => number_format(self::guideScore($broker, $slug, $type), 1),
            'spreads' => $broker->spreads ?: '—',
            'commission' => $broker->commission ?: '—',
            'fee_level' => ucfirst((string) ($broker->fee_level ?: 'medium')),
            'minimum_deposit' => $broker->minimum_deposit !== null
                ? '$'.number_format((float) $broker->minimum_deposit, 0)
                : '—',
            'leverage' => $broker->leverage ?: '—',
            'platform_count' => (string) count($platforms),
            'instrument_count' => $broker->instrument_count ? (string) $broker->instrument_count : '—',
            'vps_hosting' => $broker->vps_hosting ? 'Yes' : 'No',
            'pricing' => $broker->pricing ?: '—',
            'withdrawal_fee' => $broker->withdrawal_fee ?: '—',
            'deposit_methods' => self::shortList($broker->deposit_methods, 28),
            'regulatory_tier' => self::regulatoryTierLabel($broker->regulatory_tier),
            'regulator_count' => (string) count($regulators),
            'investor_protection' => $broker->investor_protection ? 'Yes' : 'No',
            'year_founded' => $broker->year_founded ? (string) $broker->year_founded : '—',
            'negative_balance_protection' => $broker->negative_balance_protection ? 'Yes' : 'No',
        ];
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

    public static function oneLiner(Broker $broker): string
    {
        $parts = array_filter([
            $broker->top_feature,
            $broker->spreads ? 'Spreads '.$broker->spreads : null,
            count($broker->regulationList()) ? 'Multi-regulated' : null,
        ]);

        return implode('. ', array_slice($parts, 0, 2)).(count($parts) ? '.' : '');
    }

    public static function recommendedFor(Broker $broker, array $guide, bool $isWinner = false): string
    {
        if ($isWinner && $broker->top_feature) {
            return $broker->top_feature;
        }

        $key = $isWinner ? 'winner' : 'default';

        return $guide['recommended_for'][$key]
            ?? 'Traders looking for competitive costs and reliable platform access';
    }

    /** @return array<int, string> */
    public static function prosList(Broker $broker, int $limit = 3): array
    {
        $html = (string) $broker->pros;

        if ($html === '') {
            return array_values(array_filter([
                $broker->top_feature,
                $broker->spreads ? 'Competitive spreads: '.$broker->spreads : null,
            ]));
        }

        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches);

        $items = array_map(static fn ($item) => trim(strip_tags($item)), $matches[1] ?? []);

        return array_values(array_filter(array_slice($items, 0, $limit)));
    }

    private static function shortList(?string $value, int $limit): string
    {
        $value = trim(strip_tags((string) $value));

        if ($value === '') {
            return '—';
        }

        return strlen($value) > $limit ? substr($value, 0, $limit - 1).'…' : $value;
    }
}
