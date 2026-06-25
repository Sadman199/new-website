<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class AccountOption extends Model
{
    use HasFactory, Cachable;

    // Table associated with the model
    protected $table = 'account_options';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'broker_id',
        'account_type',
        'account_currency',
        'min_deposit',
        'max_leverage',
        'spread_type',
        'spread_value',
        'is_demo_available',
        'swap_free',
        'min_trade_size',
        'max_trade_size',
        'margin_call_level',
        'stop_out_level',
        'max_open_positions',
        'commission',
        'interest_rate',
        'access_to_pro_features',
        'exclusive_offers',
        'account_management',
        'trading_instruments',
        'risk_management_tools',
        'bonus_eligibility',
        'personalized_education',
        'exclusive_webinars',
        'maximum_daily_trade_volume',
        'trading_hours',
        'special_conditions',
        'is_regulated',
    ];

    // Cast JSON fields to array or proper types
    protected $casts = [
        'trading_instruments' => 'array',
        'risk_management_tools' => 'array',
        // 'exclusive_offers' and 'special_conditions' - 
        // keep as string if not JSON, else cast to 'array' if JSON
    ];

    // Relationship: the broker that owns this account option
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    // Accessor: format the spread_value to 2 decimal places
    public function getSpreadValueAttribute($value)
    {
        return number_format($value, 2);
    }
}
