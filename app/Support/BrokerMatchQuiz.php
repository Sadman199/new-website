<?php

namespace App\Support;

class BrokerMatchQuiz
{
    /** @return array<int, array<string, mixed>> */
    public static function steps(): array
    {
        return [
            [
                'id' => 'country',
                'icon' => '🌍',
                'title' => 'Where are you based?',
                'subtitle' => 'We prioritise brokers licensed to serve clients in your region.',
                'type' => 'single',
                'field' => 'country',
                'searchable' => true,
            ],
            [
                'id' => 'markets',
                'icon' => '📊',
                'title' => 'Which assets will you trade?',
                'subtitle' => 'Select every market you care about — we match coverage from our database.',
                'type' => 'multi',
                'field' => 'markets',
                'min' => 1,
            ],
            [
                'id' => 'experience',
                'icon' => '🎓',
                'title' => 'Your trading experience',
                'subtitle' => 'Beginners need simplicity; pros need depth — we weight features accordingly.',
                'type' => 'single',
                'field' => 'experience',
            ],
            [
                'id' => 'cost_focus',
                'icon' => '💰',
                'title' => 'How cost-sensitive are you?',
                'subtitle' => 'Spreads, commissions, swaps, inactivity and withdrawal fees all add up.',
                'type' => 'single',
                'field' => 'cost_focus',
            ],
            [
                'id' => 'activity',
                'icon' => '⏱️',
                'title' => 'How often will you trade?',
                'subtitle' => 'Day traders and long-term investors prioritise different platform traits.',
                'type' => 'single',
                'field' => 'activity',
            ],
            [
                'id' => 'deposit',
                'icon' => '💳',
                'title' => 'Your starting deposit',
                'subtitle' => 'We only surface brokers whose minimum deposit fits your budget.',
                'type' => 'single',
                'field' => 'deposit',
            ],
            [
                'id' => 'extras',
                'icon' => '✨',
                'title' => 'Must-have extras',
                'subtitle' => 'Optional — select any deal-breakers or skip to finish.',
                'type' => 'multi',
                'field' => 'extras',
                'min' => 0,
                'optional' => true,
            ],
        ];
    }

    /** @return array<string, array<int, array<string, mixed>>> */
    public static function options(): array
    {
        $countries = collect(BrokerTaxonomy::countriesWithFlags())
            ->except('global')
            ->map(fn (array $meta, string $slug) => [
                'value' => $slug,
                'label' => $meta['name'],
                'flag_url' => BrokerTaxonomy::countryFlagUrl($meta['code'] ?? null, 40),
            ])
            ->values()
            ->all();

        return [
            'country' => array_merge(
                [['value' => 'global', 'label' => 'Worldwide / not sure', 'icon' => '🌍', 'featured' => true]],
                $countries
            ),
            'markets' => [
                ['value' => 'forex', 'label' => 'Forex', 'icon' => '💱', 'desc' => 'Major & minor currency pairs'],
                ['value' => 'gold', 'label' => 'Gold & metals', 'icon' => '🥇', 'desc' => 'XAU, silver & commodities'],
                ['value' => 'crypto', 'label' => 'Crypto', 'icon' => '₿', 'desc' => 'BTC, ETH & digital assets'],
                ['value' => 'stocks', 'label' => 'Stocks', 'icon' => '📈', 'desc' => 'Shares & equities'],
                ['value' => 'indices', 'label' => 'Indices', 'icon' => '📉', 'desc' => 'S&P, NASDAQ, DAX & more'],
            ],
            'experience' => [
                ['value' => 'beginner', 'label' => 'New to trading', 'hint' => 'Demo accounts, education & low minimums'],
                ['value' => 'intermediate', 'label' => 'Some experience', 'hint' => 'Balanced platforms & solid regulation'],
                ['value' => 'active', 'label' => 'Active trader', 'hint' => 'Tight spreads & fast execution'],
                ['value' => 'professional', 'label' => 'Professional', 'hint' => 'Raw/ECN pricing & advanced tools'],
            ],
            'cost_focus' => [
                ['value' => 'low', 'label' => 'Maximum savings', 'hint' => 'Lowest all-in trading costs'],
                ['value' => 'balanced', 'label' => 'Balanced value', 'hint' => 'Fair fees with strong oversight'],
                ['value' => 'premium', 'label' => 'Premium quality', 'hint' => 'Tier-1 regulation over lowest fees'],
            ],
            'activity' => [
                ['value' => 'daily', 'label' => 'Daily', 'hint' => 'Intraday charts & mobile alerts'],
                ['value' => 'weekly', 'label' => 'Weekly', 'hint' => 'Swing trading & periodic reviews'],
                ['value' => 'monthly', 'label' => 'Monthly', 'hint' => 'Occasional position management'],
                ['value' => 'investor', 'label' => 'Long-term', 'hint' => 'Stability & investor protection'],
            ],
            'deposit' => [
                ['value' => '10', 'label' => '$10', 'hint' => 'Micro & cent accounts'],
                ['value' => '50', 'label' => '$50', 'hint' => 'Low-barrier entry'],
                ['value' => '100', 'label' => '$100', 'hint' => 'Standard retail'],
                ['value' => '500', 'label' => '$500+', 'hint' => 'Full feature access'],
            ],
            'extras' => [
                ['value' => 'copy_trading', 'label' => 'Copy trading', 'icon' => '👥'],
                ['value' => 'islamic', 'label' => 'Islamic account', 'icon' => '☪️'],
                ['value' => 'vps', 'label' => 'VPS hosting', 'icon' => '🖥️'],
                ['value' => 'mobile', 'label' => 'Mobile app', 'icon' => '📱'],
                ['value' => 'education', 'label' => 'Education', 'icon' => '📚'],
                ['value' => 'bonuses', 'label' => 'Bonuses', 'icon' => '🎁'],
            ],
        ];
    }

    /** @return array<string, string> */
    public static function dimensionLabels(): array
    {
        return [
            'safety' => 'Safety & regulation',
            'costs' => 'Trading costs',
            'markets' => 'Market coverage',
            'access' => 'Account access',
            'features' => 'Platform fit',
        ];
    }
}
