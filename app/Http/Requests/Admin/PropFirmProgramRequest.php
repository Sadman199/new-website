<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PropFirmProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prop_firm_id' => ['required', 'exists:prop_firms,id'],
            'name' => ['required', 'string', 'max:255'],
            'account_size' => ['nullable', 'string', 'max:255'],
            'entry_fee' => ['nullable', 'numeric', 'min:0'],
            'profit_target' => ['nullable', 'string', 'max:255'],
            'daily_drawdown' => ['nullable', 'string', 'max:255'],
            'max_drawdown' => ['nullable', 'string', 'max:255'],
            'profit_split' => ['nullable', 'string', 'max:255'],
            'min_trading_days' => ['nullable', 'integer', 'min:0'],
            'news_trading' => ['nullable', 'boolean'],
            'weekend_holding' => ['nullable', 'boolean'],
            'ea_allowed' => ['nullable', 'boolean'],
            'copy_trading' => ['nullable', 'boolean'],
            'hedging' => ['nullable', 'boolean'],
            'refund_available' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
