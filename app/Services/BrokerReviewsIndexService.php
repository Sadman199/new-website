<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use Illuminate\Support\Str;

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
        $regulators = $broker->regulationList();
        $feeScore = $this->scoreOutOfFive($scores['fees'] ?? null);
        $platformScore = $this->scoreOutOfFive($scores['platforms'] ?? null);
        $reviewCount = (int) ($broker->approved_review_count ?? 0);
        $mobileLabel = $this->mobilePlatformLabel($broker);

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'review_slug' => BrokerController::reviewSlugFor($broker),
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'fee_level' => ucfirst((string) ($broker->fee_level ?: 'medium')),
            'fee_score' => $feeScore,
            'platform_score' => $platformScore,
            'withdrawal_fee' => trim((string) ($broker->withdrawal_fee ?: '')) ?: '—',
            'investor_protection' => $broker->investor_protection ? 'Yes' : 'No',
            'mobile_platform' => $mobileLabel,
            'mobile_platform_has_apps' => $mobileLabel !== 'No',
            'review_count' => $reviewCount,
            'top_feature' => trim((string) ($broker->top_feature ?: '')),
            'short_description' => Str::limit(trim(strip_tags((string) ($broker->short_description ?: ''))), 140),
            'regulation_summary' => $this->regulationSummary($regulators),
            'spreads' => trim((string) ($broker->spreads ?: '')) ?: null,
            'is_award_winner' => (bool) $broker->featured_broker,
            'award_label' => trim((string) ($broker->top_feature ?: '')) ?: 'Featured broker',
            'markets' => $markets,
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
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

    protected function scoreOutOfFive(?float $tenScale): ?float
    {
        if ($tenScale === null) {
            return null;
        }

        return min(5.0, round($tenScale / 2, 1));
    }

    protected function mobilePlatformLabel(Broker $broker): string
    {
        $mobile = trim(strip_tags((string) ($broker->mobile_trading ?: '')));

        if ($mobile !== '') {
            return $mobile;
        }

        if ($broker->web_trader) {
            return trim(strip_tags((string) $broker->web_trader)) ?: 'Web platform';
        }

        return 'No';
    }

    /** @param array<int, string> $regulators */
    protected function regulationSummary(array $regulators): ?string
    {
        if ($regulators === []) {
            return null;
        }

        $summary = implode(', ', array_slice($regulators, 0, 3));

        if (count($regulators) > 3) {
            $summary .= ' +' . (count($regulators) - 3);
        }

        return 'Regulated by ' . $summary;
    }
}
