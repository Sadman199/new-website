<?php

namespace App\Support;

class TradingToolCategories
{
    public const TRADING_CALCULATORS = 'trading_calculators';
    public const TECHNICAL_ANALYSIS = 'technical_analysis';
    public const MARKET_TOOLS = 'market_tools';

    /** @return array<string, array{label: string, intro: string, icon: string}> */
    public static function all(): array
    {
        return [
            self::TRADING_CALCULATORS => [
                'label' => 'Trading Calculators',
                'intro' => 'Work out pip value, position size, profit, margin, risk, and estimated trading costs before you place an order.',
                'icon' => 'fas fa-calculator',
            ],
            self::TECHNICAL_ANALYSIS => [
                'label' => 'Technical Analysis Tools',
                'intro' => 'Map support, resistance, and retracement levels from the previous session’s high, low, and close.',
                'icon' => 'fas fa-chart-line',
            ],
            self::MARKET_TOOLS => [
                'label' => 'Market Tools',
                'intro' => 'Convert currencies and follow live FX rates, the forex heatmap, and the economic calendar.',
                'icon' => 'fas fa-globe',
            ],
        ];
    }

    /** @return string[] */
    public static function keys(): array
    {
        return array_keys(self::all());
    }

    public static function isValid(?string $key): bool
    {
        return $key !== null && in_array($key, self::keys(), true);
    }

    public static function label(string $key): string
    {
        return self::all()[$key]['label'] ?? 'Trading Tools';
    }

    public static function intro(string $key): string
    {
        return self::all()[$key]['intro'] ?? '';
    }

    public static function icon(string $key): string
    {
        return self::all()[$key]['icon'] ?? 'fas fa-calculator';
    }
}
