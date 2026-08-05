<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if ($this->filled('max_leverage_numeric') && ! $this->filled('leverage_label')) {
            $this->merge([
                'leverage_label' => '1:' . $this->input('max_leverage_numeric'),
            ]);
        }

        if ($this->filled('spread_from_pips') && ! $this->filled('spread_value')) {
            $this->merge(['spread_value' => $this->input('spread_from_pips')]);
        }
    }

    public function rules(): array
    {
        $optionId = $this->route('id');
        $brokerId = $this->route('broker_id');

        return [
            'account_type' => ['required', 'string', 'max:100'],
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('account_options', 'slug')
                    ->where(fn ($q) => $q->where('broker_id', $brokerId))
                    ->ignore($optionId),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'account_currency' => ['required', 'string', 'max:10'],
            'min_deposit' => ['nullable', 'numeric', 'min:0'],
            'max_leverage_numeric' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'leverage_label' => ['nullable', 'string', 'max:100'],
            'spread_type' => ['nullable', Rule::in(['fixed', 'variable', 'raw', 'ecn'])],
            'spread_value' => ['nullable', 'numeric', 'min:0'],
            'spread_from_pips' => ['nullable', 'numeric', 'min:0'],
            'commission' => ['nullable', 'numeric', 'min:0'],
            'commission_label' => ['nullable', 'string', 'max:150'],
            'commission_per_lot' => ['nullable', 'numeric', 'min:0'],
            'execution_model' => ['nullable', Rule::in(['ecn', 'stp', 'market_maker', 'hybrid'])],
            'swap_free' => ['nullable', 'boolean'],
            'ea_allowed' => ['nullable', 'boolean'],
            'hedging_allowed' => ['nullable', 'boolean'],
            'vps_eligible' => ['nullable', 'boolean'],
            'min_trade_size' => ['nullable', 'numeric', 'min:0'],
            'max_trade_size' => ['nullable', 'numeric', 'min:0'],
            'margin_call_level' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stop_out_level' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'max_open_positions' => ['nullable', 'integer', 'min:0'],
            'maximum_daily_trade_volume' => ['nullable', 'numeric', 'min:0'],
            'bonus_eligibility' => ['nullable', 'boolean'],
            'access_to_pro_features' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'exclusive_offers' => ['nullable', 'string'],
            'special_conditions' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:50'],
        ];
    }

    public function attributes(): array
    {
        return [
            'account_type' => 'account type',
            'account_currency' => 'account currency',
            'min_deposit' => 'minimum deposit',
            'max_leverage_numeric' => 'maximum leverage',
            'spread_from_pips' => 'spread from (pips)',
            'commission_per_lot' => 'commission per lot',
        ];
    }
}
