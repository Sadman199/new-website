<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    // The table associated with the model (optional if the table name is 'brokers')
    protected $table = 'brokers';

    // The attributes that are mass assignable
    protected $fillable = [
        'name', 'url', 'short_description', 'visit_site', 'open_live',
        'open_demo', 'pros', 'cons', 'languages', 'pricing', 'deposit_methods',
        'withdrawal_method', 'country', 'regulation', 'regulated_jurisdictions',
        'regulatory_licenses', 'minimum_deposit', 'spreads', 'leverage', 'platforms',
        'payment_methods', 'customer_support', 'educational_resources', 'research_tools',
        'mobile_trading', 'social_trading', 'account_types', 'capitalization', 'insurance',
        'segregation_of_funds', 'web_trader', 'charting_tools', 'account_managers',
        'news_and_analysis', 'economic_calendar', 'vps_hosting', 'associated_countries',
        'slug', 'top_feature', 'featured_broker', 'top_broker', 
        'meta_title', 'meta_keyword', 'meta_description', 'title', 'rating' // Added 'title' and 'rating'
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'minimum_deposit' => 'decimal:2',
        'segregation_of_funds' => 'boolean',
        'account_managers' => 'boolean',
        'economic_calendar' => 'boolean',
        'vps_hosting' => 'boolean',
        'associated_countries' => 'array', // Casts JSON to an array
        'rating' => 'decimal:2', // Added casting for 'rating'
         'account_types' => 'array',
    ];

 
    public function reviews()
{
    return $this->hasMany(Review::class);
}

public function faqs()
{
    return $this->hasMany(Faq::class);
}

public function accountOptions()
{
    return $this->hasMany(AccountOption::class);
}
public function getAccountTypesAttribute($value)
{
    return json_decode($value, true) ?? [];
}



}