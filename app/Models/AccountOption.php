<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountOption extends Model
{
    use HasFactory;

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

    // The broker that owns the account option
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    // Cast JSON fields to array (e.g., features, instruments, etc.)
    protected $casts = [
        'features' => 'array',
        'trading_instruments' => 'array',
        'risk_management_tools' => 'array',
        'exclusive_offers' => 'string',  // assuming this is a string or JSON field
        'special_conditions' => 'string',  // assuming this is a string
    ];

    // If necessary, you can also add custom methods or accessors/mutators here

    // Example of a simple accessor to format the `spread_value`:
    public function getSpreadValueAttribute($value)
    {
        return number_format($value, 2);  // Format spread value to two decimal places
    }
}
