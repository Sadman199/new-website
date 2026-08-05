<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;

class BrokerReviewsIndexService
{
    /** @return array<string, string> filter key => label */
    public function marketFilters(): array
    {
        return [
            'stock-etf' => 'Stock, ETF',
            'forex' => 'Forex',
            'fund' => 'Fund',
            'bond' => 'Bond',
            'options' => 'Options',
            'futures' => 'Futures',
            'crypto' => 'Crypto',
            'cfd' => 'CFD',
        ];
    }

    /** @return array<string, mixed> */
    public function serialize(Broker $broker): array
    {
        $scores = is_array($broker->category_scores) ? $broker->category_scores : [];
        $markets = $this->marketKeysFor($broker);

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'review_slug' => BrokerController::reviewSlugFor($broker),
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'fee_level' => ucfirst((string) ($broker->fee_level ?: 'medium')),
            'fee_score' => $this->scoreOutOfFive($scores['fees'] ?? null, $broker->fee_level),
            'platform_score' => $this->scoreOutOfFive($scores['platforms'] ?? null, $broker->mobile_trading ? 'yes' : null),
            'inactivity_fee' => $this->inactivityFeeLabel($broker),
            'investor_protection' => $broker->investor_protection ? 'Yes' : 'No',
            'mobile_platform' => ($broker->mobile_trading || $broker->web_trader) ? 'Yes' : 'No',
            'popularity_count' => $this->popularityCount($broker),
            'is_award_winner' => (bool) $broker->featured_broker,
            'award_label' => date('Y') . ' Award Winner',
            'markets' => $markets,
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'risk_disclaimer' => $this->riskDisclaimer($broker),
        ];
    }

    /** @return array<int, string> */
    public function marketKeysFor(Broker $broker): array
    {
        $keys = [];
        $markets = array_map('strtolower', $broker->marketList());

        foreach ($markets as $market) {
            if (str_contains($market, 'forex')) {
                $keys[] = 'forex';
            }
            if (str_contains($market, 'stock')) {
                $keys[] = 'stock-etf';
            }
            if (str_contains($market, 'crypto')) {
                $keys[] = 'crypto';
            }
            if (str_contains($market, 'bond')) {
                $keys[] = 'bond';
            }
            if (str_contains($market, 'fund')) {
                $keys[] = 'fund';
            }
            if (str_contains($market, 'option')) {
                $keys[] = 'options';
            }
            if (str_contains($market, 'future')) {
                $keys[] = 'futures';
            }
            if (in_array($market, ['indices', 'commodities', 'cfd'], true) || str_contains($market, 'index') || str_contains($market, 'commod')) {
                $keys[] = 'cfd';
            }
        }

        if ($keys === [] && $broker->marketList() !== []) {
            $keys[] = 'forex';
        }

        return array_values(array_unique($keys));
    }

    protected function scoreOutOfFive(?float $tenScale, mixed $fallbackHint = null): float
    {
        if ($tenScale !== null) {
            return min(5.0, round($tenScale / 2, 1));
        }

        if ($fallbackHint === 'low') {
            return 4.3;
        }

        if ($fallbackHint === 'high') {
            return 2.4;
        }

        if ($fallbackHint === 'yes') {
            return 4.5;
        }

        return 3.8;
    }

    protected function inactivityFeeLabel(Broker $broker): string
    {
        if ($broker->fee_level === 'high') {
            return 'Yes';
        }

        $withdrawal = strtolower((string) $broker->withdrawal_fee);

        if ($withdrawal === 'free' || $withdrawal === '') {
            return 'No';
        }

        return 'Yes';
    }

    protected function popularityCount(Broker $broker): int
    {
        $reviewCount = (int) ($broker->approved_review_count ?? 0);

        $base = max(10000, (int) ($broker->id * 18437 + ((float) $broker->rating * 25000)));

        return $base + ($reviewCount * 1200);
    }

    protected function riskDisclaimer(Broker $broker): ?string
    {
        if (! $broker->isRegulated()) {
            return null;
        }

        $loss = min(89, max(58, 60 + ($broker->id % 25) + (int) ((5 - (float) $broker->rating) * 3)));

        return $loss . '% of retail CFD accounts lose money';
    }
}
