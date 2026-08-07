<?php

namespace App\Services;

use App\Models\AccountOption;
use App\Models\Broker;

class BrokerAssessmentService
{
    /** @var array<int, array<string, mixed>> */
    protected array $metricDefinitions = [
        [
            'key' => 'speed',
            'label' => 'Execution speed',
            'subtitle' => 'Platform response time',
            'suffix' => ' ms',
            'lower_is_better' => true,
            'min' => 75,
            'max' => 400,
        ],
        [
            'key' => 'stability',
            'label' => 'Connection stability',
            'subtitle' => 'Reliability index',
            'suffix' => ' /day',
            'lower_is_better' => true,
            'min' => 0.1,
            'max' => 2.0,
        ],
        [
            'key' => 'liquidity',
            'label' => 'Market liquidity',
            'subtitle' => 'Quote availability',
            'suffix' => ' /min',
            'lower_is_better' => false,
            'min' => 25,
            'max' => 120,
        ],
        [
            'key' => 'execution',
            'label' => 'Price execution',
            'subtitle' => 'Estimated slippage',
            'suffix' => ' pts',
            'lower_is_better' => true,
            'min' => 0.5,
            'max' => 4.0,
        ],
        [
            'key' => 'spreads',
            'label' => 'Trading spreads',
            'subtitle' => 'Lowest published spread',
            'suffix' => ' pips',
            'lower_is_better' => true,
            'min' => 0.4,
            'max' => 3.5,
        ],
        [
            'key' => 'swap',
            'label' => 'Overnight costs',
            'subtitle' => 'Swap fee estimate',
            'suffix' => ' USD/lot',
            'lower_is_better' => false,
            'min' => -4.0,
            'max' => -0.3,
        ],
    ];

    /** @return array<int, array<string, mixed>> */
    public function cardMetrics(Broker $broker): array
    {
        $scores = is_array($broker->category_scores) ? $broker->category_scores : [];
        $accounts = $broker->relationLoaded('accountOptions')
            ? $broker->accountOptions
            : $broker->accountOptions()->ordered()->get();
        $primaryAccount = $accounts->first();

        $computed = [
            'speed' => $this->speedMetric($broker, $scores, $primaryAccount),
            'stability' => $this->stabilityMetric($broker, $scores),
            'liquidity' => $this->liquidityMetric($broker, $scores),
            'execution' => $this->executionMetric($broker, $scores),
            'spreads' => $this->spreadsMetric($broker, $accounts),
            'swap' => $this->swapMetric($broker, $accounts, $scores),
        ];

        $metrics = [];

        foreach ($this->metricDefinitions as $definition) {
            $key = $definition['key'];
            $value = $computed[$key];

            $metrics[] = [
                'key' => $key,
                'label' => $definition['label'],
                'subtitle' => $definition['subtitle'],
                'display' => $value['display'] . $definition['suffix'],
                'percent' => $this->scorePercent(
                    (float) $value['raw'],
                    (float) $definition['min'],
                    (float) $definition['max'],
                    (bool) $definition['lower_is_better']
                ),
            ];
        }

        return $metrics;
    }

    protected function scorePercent(float $raw, float $min, float $max, bool $lowerIsBetter): int
    {
        if ($max <= $min) {
            return 50;
        }

        $clamped = max($min, min($max, $raw));
        $ratio = ($clamped - $min) / ($max - $min);

        if ($lowerIsBetter) {
            $ratio = 1 - $ratio;
        }

        return (int) round(max(8, min(100, $ratio * 100)));
    }

    /** @param  array<string, float|null>  $scores */
    protected function speedMetric(Broker $broker, array $scores, ?AccountOption $account): array
    {
        $platformScore = isset($scores['platforms']) ? (float) $scores['platforms'] : null;
        $ms = $platformScore !== null
            ? (int) round(max(80, min(380, 420 - ($platformScore * 34))))
            : 210;

        $ms += match (strtolower((string) optional($account)->execution_model)) {
            'ecn' => -35,
            'stp' => -10,
            'market_maker' => 45,
            'hybrid' => 15,
            default => 0,
        };

        if ($broker->vps_hosting) {
            $ms -= 12;
        }

        $ms = max(75, $ms);

        return $this->metricValue($ms, number_format($ms, 0));
    }

    /** @param  array<string, float|null>  $scores */
    protected function stabilityMetric(Broker $broker, array $scores): array
    {
        $trust = $broker->trust_score;
        $safetyScore = isset($scores['safety']) ? (float) $scores['safety'] : null;

        if ($trust !== null) {
            $raw = max(0.1, round((100 - (int) $trust) / 45, 1));
        } elseif ($safetyScore !== null) {
            $raw = max(0.1, round((10 - $safetyScore) / 4, 1));
        } else {
            $raw = 1.0;
        }

        $tier = (int) ($broker->regulatory_tier ?: 0);
        if ($tier > 0) {
            $raw = max(0.1, round($raw - ((6 - min(6, $tier)) * 0.08), 1));
        }

        if ($broker->investor_protection) {
            $raw = max(0.1, round($raw - 0.15, 1));
        }

        return $this->metricValue($raw, number_format($raw, 1));
    }

    /** @param  array<string, float|null>  $scores */
    protected function liquidityMetric(Broker $broker, array $scores): array
    {
        $instrumentCount = (int) ($broker->instrument_count ?: 0);
        $productsScore = isset($scores['products']) ? (float) $scores['products'] : null;

        if ($instrumentCount > 0) {
            $raw = (int) round(min(120, max(25, $instrumentCount / 4)));
        } elseif ($productsScore !== null) {
            $raw = (int) round(max(25, min(110, $productsScore * 9)));
        } else {
            $raw = 55;
        }

        return $this->metricValue($raw, number_format($raw, 0));
    }

    /** @param  array<string, float|null>  $scores */
    protected function executionMetric(Broker $broker, array $scores): array
    {
        $feesScore = isset($scores['fees']) ? (float) $scores['fees'] : null;
        $raw = match (strtolower((string) ($broker->fee_level ?: 'medium'))) {
            'low' => 0.9,
            'high' => 3.6,
            default => 2.1,
        };

        if ($feesScore !== null) {
            $raw = max(0.5, round((10 - $feesScore) / 3.2, 1));
        }

        return $this->metricValue($raw, '+' . number_format($raw, 1));
    }

    /** @param  iterable<int, AccountOption>  $accounts */
    protected function spreadsMetric(Broker $broker, iterable $accounts): array
    {
        $minSpread = null;

        foreach ($accounts as $account) {
            $pips = $account->spread_from_pips ?? $account->spread_value;
            if ($pips !== null && $pips !== '') {
                $value = (float) $pips;
                $minSpread = $minSpread === null ? $value : min($minSpread, $value);
            }
        }

        if ($minSpread === null && $broker->spreads) {
            if (preg_match('/(\d+(?:\.\d+)?)\s*pips?/i', (string) $broker->spreads, $matches)) {
                $minSpread = (float) $matches[1];
            } elseif (preg_match('/from\s*(\d+(?:\.\d+)?)/i', (string) $broker->spreads, $matches)) {
                $minSpread = (float) $matches[1];
            }
        }

        if ($minSpread === null) {
            $minSpread = match (strtolower((string) ($broker->fee_level ?: 'medium'))) {
                'low' => 0.8,
                'high' => 2.8,
                default => 1.6,
            };
        }

        return $this->metricValue($minSpread, number_format($minSpread, 1));
    }

    /**
     * @param  iterable<int, AccountOption>  $accounts
     * @param  array<string, float|null>  $scores
     */
    protected function swapMetric(Broker $broker, iterable $accounts, array $scores): array
    {
        $hasSwapFree = false;
        $commissionPerLot = null;

        foreach ($accounts as $account) {
            if ($account->swap_free) {
                $hasSwapFree = true;
            }

            if ($account->commission_per_lot !== null && $account->commission_per_lot !== '') {
                $value = (float) $account->commission_per_lot;
                $commissionPerLot = $commissionPerLot === null ? $value : min($commissionPerLot, $value);
            }
        }

        if ($hasSwapFree) {
            $raw = -0.45;
        } elseif ($commissionPerLot !== null) {
            $raw = round(-1.2 - ($commissionPerLot * 0.35), 2);
        } else {
            $feesScore = isset($scores['fees']) ? (float) $scores['fees'] : null;
            $raw = match (strtolower((string) ($broker->fee_level ?: 'medium'))) {
                'low' => -0.85,
                'high' => -3.4,
                default => -1.75,
            };

            if ($feesScore !== null) {
                $raw = round(-0.6 - ((10 - $feesScore) * 0.28), 2);
            }
        }

        return $this->metricValue($raw, number_format($raw, 2));
    }

    /** @return array{raw: float, display: string} */
    protected function metricValue(float $raw, string $display): array
    {
        return [
            'raw' => $raw,
            'display' => $display,
        ];
    }
}
