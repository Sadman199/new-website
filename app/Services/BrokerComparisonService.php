<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Collection;

class BrokerComparisonService
{
    /** @return array<string, array{label: string, rows: array<int, array{key: string, label: string}>}> */
    public function tabGroups(): array
    {
        return [
            'overall' => [
                'label' => 'Overall',
                'rows' => [
                    ['key' => 'rating', 'label' => 'Reputation and Quality'],
                    ['key' => 'regulation', 'label' => 'Regulation and Compliance'],
                    ['key' => 'platforms', 'label' => 'Trading Platforms'],
                    ['key' => 'spreads', 'label' => 'Trading Cost (Spreads)'],
                    ['key' => 'regulation', 'label' => 'Regulator'],
                    ['key' => 'broker_type', 'label' => 'Broker Type'],
                    ['key' => 'country', 'label' => 'Headquarters'],
                    ['key' => 'year_founded', 'label' => 'Founded'],
                    ['key' => 'minimum_deposit', 'label' => 'Min Deposit'],
                    ['key' => 'leverage', 'label' => 'Max Lev'],
                    ['key' => 'top_feature', 'label' => 'Label'],
                ],
            ],
            'regulation' => [
                'label' => 'Regulation',
                'rows' => [
                    ['key' => 'regulation', 'label' => 'Regulators'],
                    ['key' => 'regulatory_tier', 'label' => 'Regulatory Tier'],
                    ['key' => 'investor_protection', 'label' => 'Investor Protection'],
                    ['key' => 'segregation_of_funds', 'label' => 'Segregation of Funds'],
                    ['key' => 'negative_balance_protection', 'label' => 'Negative Balance Protection'],
                    ['key' => 'trust_score', 'label' => 'Trust Score'],
                ],
            ],
            'account' => [
                'label' => 'Account & Cost',
                'rows' => [
                    ['key' => 'minimum_deposit', 'label' => 'Minimum Deposit'],
                    ['key' => 'spreads', 'label' => 'Average Spreads'],
                    ['key' => 'commission', 'label' => 'Commission'],
                    ['key' => 'leverage', 'label' => 'Maximum Leverage'],
                    ['key' => 'account_types', 'label' => 'Account Types'],
                    ['key' => 'instrument_count', 'label' => 'Tradable Instruments'],
                    ['key' => 'markets', 'label' => 'Markets'],
                ],
            ],
            'deposit' => [
                'label' => 'Deposit & Withdrawal',
                'rows' => [
                    ['key' => 'deposit_methods', 'label' => 'Deposit Methods'],
                    ['key' => 'withdrawal_method', 'label' => 'Withdrawal Methods'],
                    ['key' => 'withdrawal_fee', 'label' => 'Withdrawal Fee'],
                    ['key' => 'payment_methods', 'label' => 'Payment Methods'],
                ],
            ],
            'company' => [
                'label' => 'Company and Service',
                'rows' => [
                    ['key' => 'country', 'label' => 'Headquarters'],
                    ['key' => 'year_founded', 'label' => 'Year Founded'],
                    ['key' => 'languages', 'label' => 'Languages'],
                    ['key' => 'customer_support', 'label' => 'Customer Support'],
                    ['key' => 'mobile_trading', 'label' => 'Mobile Trading'],
                    ['key' => 'web_trader', 'label' => 'Web Trader'],
                    ['key' => 'vps_hosting', 'label' => 'VPS Hosting'],
                    ['key' => 'social_trading', 'label' => 'Social Trading'],
                ],
            ],
            'reviews' => [
                'label' => 'User Reviews',
                'rows' => [
                    ['key' => 'rating', 'label' => 'Overall Rating'],
                    ['key' => 'review_count', 'label' => 'User Reviews'],
                    ['key' => 'trust_score', 'label' => 'Trust Score'],
                ],
            ],
        ];
    }

    public function suggestedBrokers(int $limit = 6): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->orderByDesc('rating')
            ->orderByDesc('featured_broker')
            ->limit($limit)
            ->get();
    }

    /** @return Collection<int, Broker> */
    public function allBrokersForCompare(): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->withCount(['reviews as approved_review_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->orderBy('name')
            ->get();
    }

    /** @return array<string, mixed> */
    public function serializeBroker(Broker $broker): array
    {
        $accountTypes = is_array($broker->account_types)
            ? $broker->account_types
            : (is_string($broker->account_types) && $broker->account_types !== ''
                ? json_decode($broker->account_types, true) ?? []
                : []);

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'rating' => $broker->rating !== null ? (float) $broker->rating : null,
            'regulation' => implode(', ', $broker->regulationList()) ?: '—',
            'regulatory_tier' => $broker->regulatory_tier ? 'Tier ' . $broker->regulatory_tier : '—',
            'platforms' => implode(', ', $broker->platformList()) ?: '—',
            'markets' => implode(', ', $broker->marketList()) ?: '—',
            'minimum_deposit' => $broker->minimum_deposit !== null
                ? '$' . number_format((float) $broker->minimum_deposit, 0)
                : '—',
            'spreads' => $broker->spreads ?: '—',
            'leverage' => $broker->leverage ?: '—',
            'commission' => $broker->commission ?: 'None',
            'country' => $broker->country ?: '—',
            'year_founded' => $broker->year_founded ?: '—',
            'broker_type' => $broker->isRegulated() ? 'Regulated' : 'Unregulated',
            'top_feature' => $broker->top_feature ?: '—',
            'deposit_methods' => $broker->deposit_methods ?: '—',
            'withdrawal_method' => $broker->withdrawal_method ?: '—',
            'withdrawal_fee' => $broker->withdrawal_fee ?: '—',
            'payment_methods' => $broker->payment_methods ?: '—',
            'languages' => $broker->languages ?: '—',
            'customer_support' => $broker->customer_support ?: '—',
            'instrument_count' => $broker->instrument_count ? $broker->instrument_count . '+' : '—',
            'account_types' => $accountTypes ? implode(', ', $accountTypes) : '—',
            'trust_score' => $broker->trust_score ?: '—',
            'review_count' => (int) ($broker->approved_review_count ?? $broker->reviews()->where('status', 1)->count()),
            'investor_protection' => $this->boolLabel($broker->investor_protection),
            'segregation_of_funds' => $this->boolLabel($broker->segregation_of_funds),
            'negative_balance_protection' => $this->boolLabel($broker->negative_balance_protection),
            'mobile_trading' => $this->boolLabel($broker->mobile_trading),
            'web_trader' => $this->boolLabel($broker->web_trader),
            'vps_hosting' => $this->boolLabel($broker->vps_hosting),
            'social_trading' => $this->boolLabel($broker->social_trading),
            'review_url' => route('broker_detail', $broker->slug),
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
        ];
    }

    protected function boolLabel(?bool $value): string
    {
        if ($value === null) {
            return '—';
        }

        return $value ? 'Yes' : 'No';
    }
}
