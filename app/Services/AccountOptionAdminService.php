<?php

namespace App\Services;

use App\Models\AccountOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountOptionAdminService
{
    /** @var string[] */
    protected array $booleanFields = [
        'is_active',
        'swap_free',
        'ea_allowed',
        'hedging_allowed',
        'vps_eligible',
        'bonus_eligibility',
        'access_to_pro_features',
    ];

    /** @var string[] */
    protected array $scalarFields = [
        'account_type', 'slug', 'sort_order', 'account_currency',
        'min_deposit', 'leverage_label',
        'spread_type', 'spread_value', 'spread_from_pips',
        'commission', 'commission_per_lot', 'commission_label', 'execution_model',
        'min_trade_size', 'max_trade_size',
        'margin_call_level', 'stop_out_level', 'max_open_positions',
        'maximum_daily_trade_volume', 'description',
        'exclusive_offers', 'special_conditions',
    ];

    public function save(AccountOption $option, Request $request, int $brokerId): AccountOption
    {
        $option->broker_id = $brokerId;
        $option->fill($request->only($this->scalarFields));

        foreach ($this->booleanFields as $field) {
            $option->{$field} = $request->boolean($field);
        }

        $option->features = $this->normalizeArrayInput($request->input('features', []));

        if (empty($option->slug)) {
            $option->slug = Str::slug($option->account_type);
        }

        if ($request->filled('max_leverage_numeric')) {
            $numeric = (int) $request->input('max_leverage_numeric');
            $option->max_leverage_numeric = $numeric;
            $option->max_leverage = $numeric;
            if (empty($option->leverage_label)) {
                $option->leverage_label = '1:' . $numeric;
            }
        } elseif ($request->filled('leverage_label')) {
            $label = (string) $request->input('leverage_label');
            $numeric = $this->parseLeverageNumeric($label);
            $option->leverage_label = $label;
            $option->max_leverage_numeric = $numeric;
            $option->max_leverage = $numeric;
        }

        if ($request->filled('spread_from_pips')) {
            $option->spread_from_pips = $request->input('spread_from_pips');
            $option->spread_value = $request->input('spread_from_pips');
        }

        if ($request->filled('commission_per_lot') && empty($option->commission_label)) {
            $option->commission_label = '$' . number_format((float) $request->input('commission_per_lot'), 2) . '/lot';
            $option->commission = (float) $request->input('commission_per_lot');
        } elseif ($request->filled('commission_label')) {
            $option->commission_label = $request->input('commission_label');
        }

        $option->save();

        return $option;
    }

    /** @return array<int, string> */
    public static function accountTypePresets(): array
    {
        return [
            'standard' => 'Standard',
            'ecn' => 'ECN',
            'raw' => 'Raw Spread',
            'pro' => 'Pro / VIP',
            'islamic' => 'Islamic / Swap-Free',
            'cent' => 'Cent',
            'micro' => 'Micro',
            'demo' => 'Demo',
        ];
    }

    /** @return array<int, string> */
    public static function spreadTypes(): array
    {
        return [
            'variable' => 'Variable',
            'fixed' => 'Fixed',
            'raw' => 'Raw',
            'ecn' => 'ECN',
        ];
    }

    /** @return array<int, string> */
    public static function executionModels(): array
    {
        return [
            'ecn' => 'ECN',
            'stp' => 'STP',
            'market_maker' => 'Market Maker',
            'hybrid' => 'Hybrid',
        ];
    }

    /** @return array<int, string> */
    public static function featureTags(): array
    {
        return [
            'scalping' => 'Scalping allowed',
            'news_trading' => 'News trading',
            'copy_trading' => 'Copy trading',
            'negative_balance_protection' => 'Negative balance protection',
        ];
    }

    protected function normalizeArrayInput(mixed $value): ?array
    {
        if (! is_array($value)) {
            return null;
        }

        $items = array_values(array_filter(array_map('trim', $value)));

        return $items ?: null;
    }

    protected function parseLeverageNumeric(string $raw): ?int
    {
        if ($raw === '') {
            return null;
        }

        if (preg_match('/1\s*:\s*(\d+)/i', $raw, $matches)) {
            $numeric = (int) $matches[1];

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        if (preg_match('/(\d+)\s*:\s*1/i', $raw, $matches)) {
            $numeric = (int) $matches[1];

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        if (is_numeric($raw)) {
            $numeric = (int) $raw;

            return ($numeric > 0 && $numeric <= 10000) ? $numeric : null;
        }

        return null;
    }
}
