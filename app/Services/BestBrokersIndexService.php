<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BestBrokersIndexService
{
    /** @return array<string, array{label: string, options: array<string, string>}> */
    public function filterGroups(): array
    {
        return [
            'markets' => [
                'label' => 'Markets & assets',
                'options' => [
                    'general' => 'General online brokers',
                    'forex' => 'Forex',
                    'cfd' => 'CFD',
                    'stocks' => 'Stocks',
                    'crypto' => 'Crypto',
                    'options' => 'Options',
                    'futures' => 'Futures',
                ],
            ],
            'profiles' => [
                'label' => 'Profiles',
                'options' => [
                    'beginners' => 'Beginners',
                    'professionals' => 'Professionals',
                    'country-residence' => 'By country of residence',
                ],
            ],
            'trading_styles' => [
                'label' => 'Trading styles',
                'options' => [
                    'scalping' => 'Scalping',
                    'day-trading' => 'Day trading',
                    'long-term' => 'Long-term investing',
                    'copy-trading' => 'Copy trading',
                    'social-trading' => 'Social trading',
                ],
            ],
            'platform_features' => [
                'label' => 'Platform & features',
                'options' => [
                    'platform-mt4' => 'MetaTrader 4',
                    'platform-mt5' => 'MetaTrader 5',
                    'mobile-apps' => 'Mobile / apps',
                    'low-spreads' => 'Low spreads',
                    'free-withdrawal' => 'Free withdrawal',
                    'micro-account' => 'Micro accounts',
                ],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function toplists(?Collection $brokers = null): array
    {
        $brokers = $brokers ?? Broker::query()->where('is_scam', false)->orderByDesc('rating')->get();
        $year = (int) date('Y');
        $lists = [];

        foreach ($this->categoryDefinitions($year) as $slug => $meta) {
            $lists[] = $this->buildListEntry($slug, 'category', $meta, $brokers);
        }

        foreach (BrokerTaxonomy::countriesWithFlags() as $slug => $country) {
            if ($slug === 'global') {
                continue;
            }

            $lists[] = $this->buildListEntry($slug, 'country', [
                'title' => "Best Forex Brokers in {$country['name']} in {$year}",
                'description' => "Compare regulated brokers serving clients in {$country['name']} with competitive fees, strong platforms, and transparent trading conditions.",
                'popular' => in_array($slug, ['india', 'united-kingdom', 'united-states', 'australia', 'singapore'], true),
                'filters' => ['forex', 'general', 'country-residence'],
            ], $brokers);
        }

        usort($lists, function (array $a, array $b) {
            if ($a['popular'] !== $b['popular']) {
                return $a['popular'] ? -1 : 1;
            }

            return strcmp($a['title'], $b['title']);
        });

        return $lists;
    }

    /** @param  array<string, mixed>  $meta */
    private function buildListEntry(string $slug, string $type, array $meta, Collection $brokers): array
    {
        $topBrokers = BrokerListingFilter::brokersFor($slug, $brokers)
            ->sortByDesc('rating')
            ->take(5)
            ->values();

        return [
            'slug' => $slug,
            'type' => $type,
            'title' => $meta['title'],
            'description' => $meta['description'],
            'url' => route('brokers.best', ['slug' => $slug]),
            'popular' => (bool) ($meta['popular'] ?? false),
            'filters' => $meta['filters'] ?? [],
            'updated_label' => 'Updated: ' . now()->format('F j, Y'),
            'broker_logos' => $topBrokers->map(fn (Broker $broker) => [
                'name' => $broker->name,
                'logo' => $broker->logo ? asset($broker->logo) : null,
            ])->all(),
            'broker_count' => BrokerListingFilter::brokersFor($slug, $brokers)->count(),
        ];
    }

    /** @return array<string, array{title: string, description: string, popular?: bool, filters: string[]}> */
    private function categoryDefinitions(int $year): array
    {
        return [
            'brokers-for-beginners' => [
                'title' => "Best Brokers for Beginners in {$year}",
                'description' => 'Find the most user-friendly brokers with low fees and strong educational resources.',
                'popular' => true,
                'filters' => ['forex', 'general', 'beginners'],
            ],
            'low-spread-brokers' => [
                'title' => "Lowest Spread Forex Brokers in {$year}",
                'description' => 'Compare top forex brokers offering low spreads, fast execution, and a safe trading environment.',
                'popular' => true,
                'filters' => ['forex', 'cfd', 'general', 'low-spreads'],
            ],
            'scalping-brokers' => [
                'title' => "Best Scalping Brokers in {$year}",
                'description' => 'Find top scalping brokers with low fees, fast execution, and reliable platform stability.',
                'popular' => true,
                'filters' => ['forex', 'cfd', 'scalping', 'day-trading'],
            ],
            'mt4-brokers' => [
                'title' => "Best MetaTrader 4 Brokers in {$year}",
                'description' => 'Find the best MT4 brokers with speed, stability, and powerful analytical tools.',
                'popular' => true,
                'filters' => ['forex', 'platform-mt4'],
            ],
            'mt5-brokers' => [
                'title' => "Best MetaTrader 5 Brokers in {$year}",
                'description' => 'Compare brokers offering MetaTrader 5 with multi-asset access and advanced charting.',
                'popular' => true,
                'filters' => ['forex', 'cfd', 'platform-mt5'],
            ],
            'trading-apps-brokers' => [
                'title' => "Best Stock Trading Apps for {$year}",
                'description' => 'Discover the best mobile trading apps for fast, intuitive, and on-the-go investing.',
                'popular' => true,
                'filters' => ['stocks', 'cfd', 'mobile-apps', 'general'],
            ],
            'copytrading-brokers' => [
                'title' => "Best Copy Trading Brokers in {$year}",
                'description' => 'Compare brokers with copy trading features, transparent stats, and flexible risk controls.',
                'popular' => true,
                'filters' => ['forex', 'copy-trading', 'social-trading'],
            ],
            'social-trading-brokers' => [
                'title' => "Best Social Trading Brokers in {$year}",
                'description' => 'Explore brokers with social trading communities, signal sharing, and collaborative tools.',
                'filters' => ['forex', 'social-trading', 'copy-trading'],
            ],
            'free-withdrawal-brokers' => [
                'title' => "Best Free Withdrawal Brokers in {$year}",
                'description' => 'Compare brokers with low non-trading costs, free withdrawals, and transparent fee schedules.',
                'filters' => ['forex', 'general', 'free-withdrawal'],
            ],
            'micro-accounts-brokers' => [
                'title' => "Best Micro Account Brokers in {$year}",
                'description' => 'Find brokers with micro and cent accounts, low minimum deposits, and flexible sizing.',
                'filters' => ['forex', 'beginners', 'micro-account'],
            ],
        ];
    }
}
