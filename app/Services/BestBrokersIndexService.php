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
                'popular' => in_array($slug, ['india', 'united-kingdom', 'united-states', 'australia', 'singapore', 'cyprus'], true),
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
            'url' => route('brokers.best', ['slug' => $slug]),
            'popular' => (bool) ($meta['popular'] ?? false),
            'filters' => $meta['filters'] ?? [],
            'updated_label' => 'Updated: ' . now()->format('F j, Y'),
            'broker_logos' => $topBrokers->map(fn (Broker $broker) => [
                'name' => $broker->name,
                'logo' => $broker->logo ? asset($broker->logo) : null,
            ])->all(),
            'broker_count' => BrokerListingFilter::brokersFor($slug, $brokers)->count(),
            'description' => \App\Support\RichText::toPlainText(\App\Models\BrokerTaxonomyTerm::descriptionFor($type, $slug)),
        ];
    }

    /** @return array<string, array{title: string, popular?: bool, filters: string[]}> */
    private function categoryDefinitions(int $year): array
    {
        return [
            'brokers-for-beginners' => [
                'title' => "Best Brokers for Beginners in {$year}",
                'popular' => true,
                'filters' => ['forex', 'general', 'beginners'],
            ],
            'low-spread-brokers' => [
                'title' => "Lowest Spread Forex Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'cfd', 'general', 'low-spreads'],
            ],
            'scalping-brokers' => [
                'title' => "Best Scalping Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'cfd', 'scalping', 'day-trading'],
            ],
            'mt4-brokers' => [
                'title' => "Best MetaTrader 4 Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'platform-mt4'],
            ],
            'mt5-brokers' => [
                'title' => "Best MetaTrader 5 Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'cfd', 'platform-mt5'],
            ],
            'trading-apps-brokers' => [
                'title' => "Best Stock Trading Apps for {$year}",
                'popular' => true,
                'filters' => ['stocks', 'cfd', 'mobile-apps', 'general'],
            ],
            'copytrading-brokers' => [
                'title' => "Best Copy Trading Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'copy-trading', 'social-trading'],
            ],
            'social-trading-brokers' => [
                'title' => "Best Social Trading Brokers in {$year}",
                'filters' => ['forex', 'social-trading', 'copy-trading'],
            ],
            'free-withdrawal-brokers' => [
                'title' => "Best Free Withdrawal Brokers in {$year}",
                'filters' => ['forex', 'general', 'free-withdrawal'],
            ],
            'micro-accounts-brokers' => [
                'title' => "Best Micro Account Brokers in {$year}",
                'filters' => ['forex', 'beginners', 'micro-account'],
            ],
            'high-leverage' => [
                'title' => "Best High Leverage Brokers in {$year}",
                'popular' => true,
                'filters' => ['forex', 'cfd', 'professionals'],
            ],
            'ea-brokers' => [
                'title' => "Best Forex Brokers with Expert Advisors (EAs) in {$year}",
                'filters' => ['forex', 'platform-mt4', 'platform-mt5', 'ea-trading'],
            ],
            'trading-signals-brokers' => [
                'title' => "Best Forex Brokers with Trading Signals in {$year}",
                'filters' => ['forex', 'signals', 'copy-trading'],
            ],
            'mam-brokers' => [
                'title' => "Best Forex Brokers with MAM Accounts in {$year}",
                'filters' => ['forex', 'managed-accounts', 'mam'],
            ],
            'pamm-brokers' => [
                'title' => "Best Forex Brokers with PAMM Accounts in {$year}",
                'filters' => ['forex', 'managed-accounts', 'pamm'],
            ],
        ];
    }

    public function heroLead(?array $preferredCountry = null): string
    {
        $slug = $preferredCountry['slug'] ?? 'global';
        $name = $preferredCountry['name'] ?? null;

        if ($slug !== 'global' && $name) {
            return "Compare regulated brokers serving clients in {$name} that offer competitive fees, robust platforms, and transparent trading conditions.";
        }

        return 'Compare regulated brokers that offer competitive fees, robust platforms, and transparent trading conditions.';
    }
}
