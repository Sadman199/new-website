<?php

namespace App\Support;

class FindMyBrokerFilters
{
    /**
     * FastBull-style labels for the homepage hero dropdowns.
     * Values map to the same query params used by find-my-broker.
     */
    public static function homepageHeroCatalogs(): array
    {
        $base = self::catalogs();

        return [
            'regulation' => $base['regulation'],
            'spread' => [
                '' => 'Any trading cost',
                'low' => 'Low Spread without commission',
                'variable' => 'Mid-to-high Spread without commission',
                'raw' => 'Low Spread with commission',
            ],
            'leverage' => [
                '' => 'Any leverage',
                '25' => '0–25',
                '100' => '26–100',
                '400' => '101–400',
                '1000' => '401–1000',
                '2000' => '1000+',
            ],
        ];
    }

    public static function catalogs(): array
    {
        return [
            'min_deposit' => [
                '' => 'Any amount',
                '0' => '$0',
                '10' => 'Up to $10',
                '50' => 'Up to $50',
                '100' => 'Up to $100',
                '200' => 'Up to $200',
                '500' => 'Up to $500',
            ],
            'account_type' => [
                'standard' => 'Standard',
                'ecn' => 'ECN',
                'cent' => 'Cent',
                'islamic' => 'Islamic',
                'raw' => 'Raw',
                'vip' => 'VIP',
                'micro' => 'Micro',
            ],
            'regulation' => [
                'asic' => 'ASIC',
                'fca' => 'FCA',
                'cysec' => 'CySEC',
                'fsca' => 'FSCA',
                'nfa' => 'NFA/CFTC',
                'fsa' => 'FSA',
                'bafin' => 'BaFin',
                'mas' => 'MAS',
                'finma' => 'FINMA',
                'cima' => 'CIMA',
                'fsc' => 'FSC',
            ],
            'platform' => [
                'mt4' => 'MetaTrader 4',
                'mt5' => 'MetaTrader 5',
                'ctrader' => 'cTrader',
                'tradingview' => 'TradingView',
                'webtrader' => 'WebTrader',
            ],
            'leverage' => [
                '' => 'Any leverage',
                '100' => '1:100+',
                '200' => '1:200+',
                '500' => '1:500+',
                '1000' => '1:1000+',
            ],
            'spread' => [
                '' => 'Any spread',
                'raw' => 'Raw / ECN',
                'fixed' => 'Fixed',
                'variable' => 'Variable',
                'low' => 'Low spreads',
            ],
            'commission' => [
                '' => 'Any commission',
                'zero' => 'Zero commission',
                'low' => 'Low commission',
            ],
            'markets' => [
                'forex' => 'Forex',
                'gold' => 'Gold',
                'crypto' => 'Crypto',
                'stocks' => 'Stocks',
                'indices' => 'Indices',
            ],
            'payment' => [
                'visa' => 'Visa',
                'mastercard' => 'Mastercard',
                'skrill' => 'Skrill',
                'neteller' => 'Neteller',
                'crypto' => 'Crypto',
                'bank' => 'Bank Wire',
            ],
            'features' => [
                'copy_trading' => 'Copy Trading',
                'ea_support' => 'EA Support',
                'vps' => 'VPS',
            ],
            'deposit_bonus' => [
                '' => 'Any',
                '1' => 'Has deposit bonus',
            ],
            'country' => collect(BrokerTaxonomy::countries())->except('global')->all(),
            'rating' => [
                '' => 'Any rating',
                '3' => '3+ stars',
                '3.5' => '3.5+ stars',
                '4' => '4+ stars',
                '4.5' => '4.5+ stars',
            ],
            'sort' => [
                'highest_rated' => 'Highest Rated',
                'lowest_deposit' => 'Lowest Deposit',
                'lowest_spread' => 'Lowest Spread',
                'most_popular' => 'Most Popular',
                'newest' => 'Newest',
            ],
        ];
    }

    /**
     * Search terms used for LIKE matching per filter slug.
     */
    public static function searchTerms(string $group, string $slug): array
    {
        $map = [
            'account_type' => [
                'standard' => ['standard'],
                'ecn' => ['ecn'],
                'cent' => ['cent'],
                'islamic' => ['islamic', 'swap free', 'swap-free'],
                'raw' => ['raw'],
                'vip' => ['vip'],
                'micro' => ['micro'],
            ],
            'regulation' => [
                'asic' => ['ASIC'],
                'fca' => ['FCA'],
                'cysec' => ['CySEC', 'Cyprus'],
                'fsca' => ['FSCA'],
                'nfa' => ['NFA', 'CFTC'],
                'fsa' => ['FSA'],
                'bafin' => ['BaFin'],
                'mas' => ['MAS'],
                'finma' => ['FINMA'],
                'cima' => ['CIMA'],
                'fsc' => ['FSC'],
            ],
            'platform' => [
                'mt4' => ['MT4', 'MetaTrader 4', 'MetaTrader4'],
                'mt5' => ['MT5', 'MetaTrader 5', 'MetaTrader5'],
                'ctrader' => ['cTrader', 'ctrader'],
                'tradingview' => ['TradingView', 'Trading View'],
                'webtrader' => ['WebTrader', 'Web Trader'],
            ],
            'spread' => [
                'raw' => ['raw', 'ecn'],
                'fixed' => ['fixed'],
                'variable' => ['variable', 'floating'],
                'low' => ['low'],
            ],
            'markets' => [
                'forex' => ['forex', 'fx', 'currency'],
                'gold' => ['gold', 'xau'],
                'crypto' => ['crypto', 'bitcoin', 'btc'],
                'stocks' => ['stock', 'share', 'equity'],
                'indices' => ['indice', 'index', 'indices'],
            ],
            'payment' => [
                'visa' => ['visa'],
                'mastercard' => ['mastercard', 'master card'],
                'skrill' => ['skrill'],
                'neteller' => ['neteller'],
                'crypto' => ['crypto', 'bitcoin', 'usdt', 'tether'],
                'bank' => ['bank', 'wire', 'transfer'],
            ],
            'features' => [
                'copy_trading' => ['copy-trading-brokers', 'copy trading', 'copytrading'],
                'ea_support' => ['ea', 'expert advisor', 'robot'],
                'vps' => ['vps'],
            ],
        ];

        return $map[$group][$slug] ?? [$slug];
    }

    public static function parseList(?string $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    public static function buildCanonicalQuery(array $params): string
    {
        $allowed = [
            'q', 'min_deposit', 'account_type', 'regulation', 'platform',
            'leverage', 'spread', 'commission', 'markets', 'payment',
            'features', 'deposit_bonus', 'country', 'rating', 'sort', 'page',
        ];

        $clean = [];
        foreach ($allowed as $key) {
            if (!array_key_exists($key, $params)) {
                continue;
            }
            $val = $params[$key];
            if ($val === null || $val === '' || $val === []) {
                continue;
            }
            if (is_array($val)) {
                $val = implode(',', array_values(array_filter($val)));
            }
            if ($val === '' || ($key === 'sort' && $val === 'highest_rated') || ($key === 'page' && (int) $val <= 1)) {
                continue;
            }
            $clean[$key] = $val;
        }

        ksort($clean);

        return http_build_query($clean);
    }

    public static function seoTitle(array $activeLabels): string
    {
        $parts = array_slice($activeLabels, 0, 3);
        if (empty($parts)) {
            return 'Find My Broker — Compare Forex Brokers | BrokersCourt';
        }

        return 'Best ' . implode(' ', $parts) . ' Brokers | BrokersCourt';
    }

    public static function seoDescription(array $activeLabels, int $count): string
    {
        if (empty($activeLabels)) {
            return "Compare {$count}+ forex brokers with advanced filters. Find the best platform by deposit, regulation, leverage, spreads, and more.";
        }

        $label = implode(', ', $activeLabels);

        return "Compare {$count} brokers matching {$label}. Filter by deposit, regulation, platform, leverage, and more on BrokersCourt.";
    }
}
