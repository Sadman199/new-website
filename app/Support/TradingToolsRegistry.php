<?php

namespace App\Support;

class TradingToolsRegistry
{
    /**
     * Internal tool key → public route slug and fallback copy.
     * Public URLs must stay stable; only add new keys, never rename existing slugs.
     *
     * @var array<string, array{slug: string, title: string, meta: string, about: string, category?: string, related?: string[], type?: string, icon?: string}>
     */
    public const TOOLS = [
        'pip' => [
            'slug' => 'pip-calculator',
            'title' => 'Pip Calculator',
            'meta' => 'Calculate pip value, pip size, and position notional for any forex pair and lot size.',
            'about' => 'Work out how much each pip is worth in your account currency before you enter a trade.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['position', 'profit', 'margin', 'cost'],
        ],
        'position' => [
            'slug' => 'position-size-calculator',
            'title' => 'Position Size Calculator',
            'meta' => 'Size your forex position from account balance, risk percentage, and stop-loss distance.',
            'about' => 'Convert your risk plan into an exact lot size based on stop-loss pips and pip value.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['risk', 'profit', 'margin'],
        ],
        'profit' => [
            'slug' => 'profit-calculator',
            'title' => 'Profit / Loss Calculator',
            'meta' => 'Estimate forex trade profit or loss in pips and account currency from entry and exit prices.',
            'about' => 'See projected P/L for long or short trades before you close the position.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['pip', 'cost', 'position'],
        ],
        'margin' => [
            'slug' => 'margin-calculator',
            'title' => 'Margin Calculator',
            'meta' => 'Calculate required margin for a forex position based on leverage, lots, and pair price.',
            'about' => 'Check whether your account has enough free margin before opening a position.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['position', 'risk', 'cost'],
        ],
        'risk' => [
            'slug' => 'risk-calculator',
            'title' => 'Risk Calculator',
            'meta' => 'Calculate risk amount, reward target, and break-even win rate from balance and R:R ratio.',
            'about' => 'Plan risk per trade and reward targets using a fixed percentage of your balance.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['position', 'profit', 'margin'],
        ],
        'cost' => [
            'slug' => 'trading-cost-calculator',
            'title' => 'Trading Cost Calculator',
            'meta' => 'Estimate forex trading costs from spread, commission, and swap using your figures or published broker data.',
            'about' => 'See spread, commission, and overnight swap as separate costs, then an estimated total when the figures are known.',
            'category' => TradingToolCategories::TRADING_CALCULATORS,
            'related' => ['pip', 'profit', 'position'],
            'icon' => 'fas fa-file-invoice-dollar',
        ],
        'pivot' => [
            'slug' => 'pivot-points-calculator',
            'title' => 'Pivot Points Calculator',
            'meta' => 'Generate classic or Fibonacci pivot support and resistance levels from prior session H/L/C.',
            'about' => 'Plot pivot, R1–R3, and S1–S3 levels for the next trading session.',
            'category' => TradingToolCategories::TECHNICAL_ANALYSIS,
            'related' => ['fibonacci'],
        ],
        'fibonacci' => [
            'slug' => 'fibonacci-calculator',
            'title' => 'Fibonacci Calculator',
            'meta' => 'Calculate Fibonacci retracement and extension levels between a swing high and low.',
            'about' => 'Find key retracement zones for pullbacks in uptrends and downtrends.',
            'category' => TradingToolCategories::TECHNICAL_ANALYSIS,
            'related' => ['pivot'],
        ],
        'converter' => [
            'slug' => 'currency-converter',
            'title' => 'Currency Converter',
            'meta' => 'Convert between major trading currencies using reference mid-market style rates.',
            'about' => 'Quickly convert account sizes, profits, or margin between USD, EUR, GBP, and more.',
            'category' => TradingToolCategories::MARKET_TOOLS,
            'related' => ['pip', 'cost'],
        ],
        'live-markets' => [
            'slug' => 'live-market-widgets',
            'title' => 'Live Market Widgets',
            'meta' => 'Real-time currency cross rates, forex heatmap, and economic calendar powered by TradingView.',
            'about' => 'Track live FX crosses, heatmap strength, and upcoming economic events in one place.',
            'type' => 'widget',
            'icon' => 'fas fa-chart-area',
            'category' => TradingToolCategories::MARKET_TOOLS,
            'related' => ['converter', 'pip', 'cost'],
        ],
    ];

    /** @return array<string, string> */
    public static function routeSlugMap(): array
    {
        return collect(self::TOOLS)->mapWithKeys(
            fn (array $meta, string $key) => [$key => $meta['slug']]
        )->all();
    }

    public static function routeSlug(string $toolKey): ?string
    {
        return self::TOOLS[$toolKey]['slug'] ?? null;
    }

    public static function toolKey(?string $routeSlug): ?string
    {
        if (! $routeSlug) {
            return null;
        }

        foreach (self::TOOLS as $key => $meta) {
            if ($meta['slug'] === $routeSlug) {
                return $key;
            }
        }

        return null;
    }

    /** @return array{title: string, meta: string, about: string, slug: string}|null */
    public static function meta(string $toolKey): ?array
    {
        return self::TOOLS[$toolKey] ?? null;
    }

    /** @return string[] */
    public static function allowedToolKeys(): array
    {
        return array_keys(array_filter(
            self::TOOLS,
            fn (array $meta) => ($meta['type'] ?? 'calculator') === 'calculator'
        ));
    }

    /** @return string[] */
    public static function allToolKeys(): array
    {
        return array_keys(self::TOOLS);
    }

    public static function isWidget(string $toolKey): bool
    {
        return (self::TOOLS[$toolKey]['type'] ?? 'calculator') === 'widget';
    }

    public static function defaultCategory(string $toolKey): string
    {
        return self::TOOLS[$toolKey]['category'] ?? TradingToolCategories::TRADING_CALCULATORS;
    }

    /** @return string[] */
    public static function defaultRelated(string $toolKey): array
    {
        return array_values(array_filter(
            self::TOOLS[$toolKey]['related'] ?? [],
            fn (string $key) => isset(self::TOOLS[$key])
        ));
    }

    public static function showsBrokerCosts(string $toolKey): bool
    {
        return in_array($toolKey, ['cost', 'pip', 'profit', 'margin', 'position'], true);
    }
}
