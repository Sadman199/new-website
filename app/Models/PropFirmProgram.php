<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropFirmProgram extends Model
{
    protected $fillable = [
        'prop_firm_id',
        'name',
        'account_size',
        'entry_fee',
        'profit_target',
        'daily_drawdown',
        'max_drawdown',
        'profit_split',
        'min_trading_days',
        'news_trading',
        'weekend_holding',
        'ea_allowed',
        'copy_trading',
        'hedging',
        'refund_available',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'entry_fee' => 'decimal:2',
        'min_trading_days' => 'integer',
        'news_trading' => 'boolean',
        'weekend_holding' => 'boolean',
        'ea_allowed' => 'boolean',
        'copy_trading' => 'boolean',
        'hedging' => 'boolean',
        'refund_available' => 'boolean',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function propFirm(): BelongsTo
    {
        return $this->belongsTo(PropFirm::class);
    }
}
