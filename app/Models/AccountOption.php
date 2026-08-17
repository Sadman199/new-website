<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class AccountOption extends Model
{
    use HasFactory, Cachable;

    protected $table = 'account_options';

    protected $fillable = [
        'broker_id',
        'account_type',
        'slug',
        'sort_order',
        'is_active',
        'account_currency',
        'min_deposit',
        'max_leverage',
        'max_leverage_numeric',
        'leverage_label',
        'spread_type',
        'spread_value',
        'spread_from_pips',
        'commission',
        'commission_per_lot',
        'commission_label',
        'execution_model',
        'swap_free',
        'ea_allowed',
        'hedging_allowed',
        'vps_eligible',
        'min_trade_size',
        'max_trade_size',
        'margin_call_level',
        'stop_out_level',
        'max_open_positions',
        'maximum_daily_trade_volume',
        'bonus_eligibility',
        'access_to_pro_features',
        'description',
        'exclusive_offers',
        'special_conditions',
        'features',
    ];

    protected $casts = [
        'min_deposit' => 'decimal:2',
        'spread_value' => 'decimal:2',
        'spread_from_pips' => 'decimal:2',
        'commission_per_lot' => 'decimal:2',
        'min_trade_size' => 'decimal:4',
        'max_trade_size' => 'decimal:4',
        'margin_call_level' => 'decimal:2',
        'stop_out_level' => 'decimal:2',
        'maximum_daily_trade_volume' => 'decimal:2',
        'max_leverage_numeric' => 'integer',
        'max_open_positions' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'swap_free' => 'boolean',
        'ea_allowed' => 'boolean',
        'hedging_allowed' => 'boolean',
        'vps_eligible' => 'boolean',
        'bonus_eligibility' => 'boolean',
        'access_to_pro_features' => 'boolean',
        'features' => 'array',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        if (! Schema::hasColumn($this->getTable(), 'is_active')) {
            return $query;
        }

        return $query->where(function (Builder $inner) {
            $inner->where('is_active', true)->orWhereNull('is_active');
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        if (Schema::hasColumn($this->getTable(), 'sort_order')) {
            return $query->orderBy('sort_order')->orderBy('id');
        }

        return $query->orderBy('id');
    }

    public function getLeverageLabelAttribute(): ?string
    {
        if (! empty($this->leverage_label)) {
            return (string) $this->leverage_label;
        }

        if ($this->max_leverage_numeric) {
            return '1:' . $this->max_leverage_numeric;
        }

        if ($this->max_leverage !== null && $this->max_leverage !== '') {
            $raw = (string) $this->max_leverage;

            return is_numeric($raw) && (float) $raw > 0 ? '1:' . (int) $raw : $raw;
        }

        return null;
    }

    public function getCommissionDisplayAttribute(): ?string
    {
        if (! empty($this->commission_label)) {
            return (string) $this->commission_label;
        }

        if ($this->commission_per_lot !== null && $this->commission_per_lot !== '') {
            return '$' . number_format((float) $this->commission_per_lot, 2) . '/lot';
        }

        if ($this->commission !== null && $this->commission !== '') {
            return (string) $this->commission;
        }

        return null;
    }

    public function getSpreadLabelAttribute(): ?string
    {
        $pips = $this->spread_from_pips ?? $this->spread_value;

        if ($pips === null || $pips === '') {
            return null;
        }

        $type = $this->spread_type ? ucfirst($this->spread_type) : 'From';

        return $type . ' ' . $pips . ' pips';
    }
}
