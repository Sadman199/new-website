<?php

namespace App\Services;

class TradingCalculator
{
    /**
     * Approximate USD cross rates for account-currency conversion.
     * These are reference defaults; the converter/API can override.
     */
    public static function defaultRates(): array
    {
        return [
            'USD' => 1.0,
            'EUR' => 1.08,
            'GBP' => 1.27,
            'JPY' => 0.0067,
            'AUD' => 0.66,
            'CAD' => 0.73,
            'CHF' => 1.12,
            'NZD' => 0.60,
        ];
    }

    public static function isJpyPair(string $pair): bool
    {
        $pair = strtoupper(str_replace(['-', '_', ' '], '/', $pair));
        return str_contains($pair, 'JPY');
    }

    public static function pipSize(string $pair): float
    {
        return self::isJpyPair($pair) ? 0.01 : 0.0001;
    }

    public static function contractSize(string $pair = 'EUR/USD'): float
    {
        // Standard forex lot
        return 100000;
    }

    /**
     * Pip value in account currency for 1 standard lot, then scaled by lots.
     */
    public static function pipValue(string $pair, float $lots, string $accountCurrency = 'USD', ?float $price = null, ?array $rates = null): float
    {
        $rates = $rates ?: self::defaultRates();
        $pair = strtoupper(str_replace(['-', '_', ' '], '/', $pair));
        $parts = explode('/', $pair);
        $base = $parts[0] ?? 'EUR';
        $quote = $parts[1] ?? 'USD';
        $pip = self::pipSize($pair);
        $contract = self::contractSize($pair);
        $price = $price ?: self::approxPrice($pair, $rates);

        // Pip value in quote currency for 1 lot
        $pipValueQuote = $pip * $contract;

        // Convert quote → USD → account
        $quoteToUsd = self::toUsd($quote, 1, $rates, $price, $base, $quote);
        $pipValueUsd = $pipValueQuote * $quoteToUsd;

        // For XXX/USD pairs, quote is USD so quoteToUsd≈1
        // For USD/JPY, pip value in JPY then to USD via price
        if ($quote === 'USD') {
            $pipValueUsd = $pipValueQuote;
        } elseif ($base === 'USD') {
            $pipValueUsd = $price > 0 ? ($pipValueQuote / $price) : 0;
        } else {
            // Cross: convert quote to USD using rates table
            $pipValueUsd = $pipValueQuote * ($rates[$quote] ?? 1);
        }

        $accountRate = $rates[strtoupper($accountCurrency)] ?? 1.0;
        $pipValueAccount = $accountRate > 0 ? ($pipValueUsd / $accountRate) : $pipValueUsd;

        return round($pipValueAccount * $lots, 4);
    }

    public static function approxPrice(string $pair, ?array $rates = null): float
    {
        $rates = $rates ?: self::defaultRates();
        $pair = strtoupper(str_replace(['-', '_', ' '], '/', $pair));
        $parts = explode('/', $pair);
        $base = $parts[0] ?? 'EUR';
        $quote = $parts[1] ?? 'USD';
        $baseUsd = $rates[$base] ?? 1;
        $quoteUsd = $rates[$quote] ?? 1;
        if ($quoteUsd <= 0) {
            return 1.0;
        }
        return round($baseUsd / $quoteUsd, 5);
    }

    public static function toUsd(string $currency, float $amount, array $rates, ?float $pairPrice = null, ?string $base = null, ?string $quote = null): float
    {
        $currency = strtoupper($currency);
        if ($currency === 'USD') {
            return $amount;
        }
        return $amount * ($rates[$currency] ?? 1);
    }

    public static function calculate(string $tool, array $input): array
    {
        $rates = self::defaultRates();
        if (! empty($input['rates']) && is_array($input['rates'])) {
            $rates = array_merge($rates, $input['rates']);
        }

        return match ($tool) {
            'pip' => self::calcPip($input, $rates),
            'position' => self::calcPosition($input, $rates),
            'profit' => self::calcProfit($input, $rates),
            'margin' => self::calcMargin($input, $rates),
            'risk' => self::calcRisk($input, $rates),
            'pivot' => self::calcPivot($input),
            'fibonacci' => self::calcFibonacci($input),
            'converter' => self::calcConverter($input, $rates),
            default => ['error' => 'Unknown tool'],
        };
    }

    private static function calcPip(array $in, array $rates): array
    {
        $pair = $in['pair'] ?? 'EUR/USD';
        $lots = (float) ($in['lots'] ?? 1);
        $currency = $in['account_currency'] ?? 'USD';
        $price = isset($in['price']) ? (float) $in['price'] : self::approxPrice($pair, $rates);
        $pipSize = self::pipSize($pair);
        $pv = self::pipValue($pair, $lots, $currency, $price, $rates);
        $notional = $lots * self::contractSize($pair) * $price;

        return [
            'pair' => strtoupper($pair),
            'lots' => $lots,
            'price' => round($price, 5),
            'pip_size' => $pipSize,
            'pip_value' => $pv,
            'position_value' => round($notional, 2),
            'account_currency' => strtoupper($currency),
        ];
    }

    private static function calcPosition(array $in, array $rates): array
    {
        $balance = (float) ($in['balance'] ?? 10000);
        $riskPct = (float) ($in['risk_percent'] ?? 1);
        $slPips = (float) ($in['sl_pips'] ?? 20);
        $pair = $in['pair'] ?? 'EUR/USD';
        $currency = $in['account_currency'] ?? 'USD';
        $price = isset($in['price']) ? (float) $in['price'] : self::approxPrice($pair, $rates);

        $riskAmount = $balance * ($riskPct / 100);
        $pvOneLot = self::pipValue($pair, 1, $currency, $price, $rates);
        $lots = ($slPips > 0 && $pvOneLot > 0) ? ($riskAmount / ($slPips * $pvOneLot)) : 0;

        return [
            'risk_amount' => round($riskAmount, 2),
            'position_size_lots' => round($lots, 4),
            'pip_value_per_lot' => round($pvOneLot, 4),
            'sl_pips' => $slPips,
            'account_currency' => strtoupper($currency),
        ];
    }

    private static function calcProfit(array $in, array $rates): array
    {
        $pair = $in['pair'] ?? 'EUR/USD';
        $direction = strtolower($in['direction'] ?? 'buy');
        $entry = (float) ($in['entry'] ?? 0);
        $exit = (float) ($in['exit'] ?? 0);
        $lots = (float) ($in['lots'] ?? 0.1);
        $currency = $in['account_currency'] ?? 'USD';
        $pipSize = self::pipSize($pair);

        $raw = $direction === 'sell' ? ($entry - $exit) : ($exit - $entry);
        $pips = $pipSize > 0 ? ($raw / $pipSize) : 0;
        $pipVal = self::pipValue($pair, $lots, $currency, $entry ?: null, $rates);
        $pl = $pips * $pipVal;

        return [
            'pips' => round($pips, 1),
            'pip_value' => round($pipVal, 4),
            'profit_loss' => round($pl, 2),
            'direction' => $direction,
            'account_currency' => strtoupper($currency),
            'is_profit' => $pl >= 0,
        ];
    }

    private static function calcMargin(array $in, array $rates): array
    {
        $pair = $in['pair'] ?? 'EUR/USD';
        $lots = (float) ($in['lots'] ?? 1);
        $leverage = max(1, (float) ($in['leverage'] ?? 100));
        $currency = $in['account_currency'] ?? 'USD';
        $price = isset($in['price']) ? (float) $in['price'] : self::approxPrice($pair, $rates);
        $notional = $lots * self::contractSize($pair) * $price;

        // Convert notional (in quote-ish / USD approx) to account currency
        $notionalUsd = $notional; // approximate when price is quote-based for XXX/USD
        $parts = explode('/', strtoupper(str_replace(['-', '_'], '/', $pair)));
        $quote = $parts[1] ?? 'USD';
        if ($quote !== 'USD') {
            $notionalUsd = $notional * ($rates[$quote] ?? 1);
        }
        $accountRate = $rates[strtoupper($currency)] ?? 1;
        $notionalAccount = $accountRate > 0 ? ($notionalUsd / $accountRate) : $notionalUsd;
        $margin = $notionalAccount / $leverage;

        return [
            'position_value' => round($notionalAccount, 2),
            'required_margin' => round($margin, 2),
            'leverage' => $leverage,
            'free_margin_note' => 'Ensure free margin covers this before opening the trade.',
            'account_currency' => strtoupper($currency),
        ];
    }

    private static function calcRisk(array $in, array $rates): array
    {
        $balance = (float) ($in['balance'] ?? 10000);
        $riskPct = (float) ($in['risk_percent'] ?? 1);
        $rewardRatio = (float) ($in['reward_ratio'] ?? 2);
        $riskAmount = $balance * ($riskPct / 100);
        $rewardAmount = $riskAmount * $rewardRatio;

        return [
            'balance' => round($balance, 2),
            'risk_percent' => $riskPct,
            'risk_amount' => round($riskAmount, 2),
            'reward_ratio' => $rewardRatio,
            'reward_amount' => round($rewardAmount, 2),
            'break_even_winrate' => $rewardRatio > 0 ? round(100 / (1 + $rewardRatio), 1) : 0,
        ];
    }

    private static function calcPivot(array $in): array
    {
        $high = (float) ($in['high'] ?? 0);
        $low = (float) ($in['low'] ?? 0);
        $close = (float) ($in['close'] ?? 0);
        $method = strtolower($in['method'] ?? 'classic');
        $pp = ($high + $low + $close) / 3;
        $range = $high - $low;

        if ($method === 'fibonacci') {
            return [
                'method' => 'fibonacci',
                'pivot' => round($pp, 5),
                'r1' => round($pp + 0.382 * $range, 5),
                'r2' => round($pp + 0.618 * $range, 5),
                'r3' => round($pp + 1.000 * $range, 5),
                's1' => round($pp - 0.382 * $range, 5),
                's2' => round($pp - 0.618 * $range, 5),
                's3' => round($pp - 1.000 * $range, 5),
            ];
        }

        return [
            'method' => 'classic',
            'pivot' => round($pp, 5),
            'r1' => round((2 * $pp) - $low, 5),
            'r2' => round($pp + $range, 5),
            'r3' => round($high + 2 * ($pp - $low), 5),
            's1' => round((2 * $pp) - $high, 5),
            's2' => round($pp - $range, 5),
            's3' => round($low - 2 * ($high - $pp), 5),
        ];
    }

    private static function calcFibonacci(array $in): array
    {
        $high = (float) ($in['high'] ?? 0);
        $low = (float) ($in['low'] ?? 0);
        $trend = strtolower($in['trend'] ?? 'up'); // up = swing low→high
        $diff = $high - $low;
        $levels = [0, 0.236, 0.382, 0.5, 0.618, 0.786, 1, 1.272, 1.618];
        $out = [];
        foreach ($levels as $ratio) {
            if ($trend === 'down') {
                $price = $high - ($diff * $ratio);
            } else {
                $price = $low + ($diff * $ratio);
            }
            $out[] = [
                'level' => $ratio,
                'label' => (string) ($ratio * 100) . '%',
                'price' => round($price, 5),
            ];
        }
        return ['trend' => $trend, 'levels' => $out];
    }

    private static function calcConverter(array $in, array $rates): array
    {
        $amount = (float) ($in['amount'] ?? 1);
        $from = strtoupper($in['from'] ?? 'USD');
        $to = strtoupper($in['to'] ?? 'EUR');
        $fromRate = $rates[$from] ?? 1;
        $toRate = $rates[$to] ?? 1;
        $usd = $amount * $fromRate;
        $converted = $toRate > 0 ? ($usd / $toRate) : 0;
        $rate = $amount > 0 ? ($converted / $amount) : 0;

        return [
            'amount' => $amount,
            'from' => $from,
            'to' => $to,
            'rate' => round($rate, 6),
            'converted' => round($converted, 4),
            'note' => 'Reference mid-market style rates for planning only — not live quotes.',
        ];
    }
}
