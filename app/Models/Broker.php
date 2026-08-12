<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Broker extends Model
{
    use HasFactory, Cachable;

    protected $table = 'brokers';

    protected $fillable = [
        'name', 'url', 'short_description', 'description', 'visit_site', 'open_live',
        'open_demo', 'demo_link', 'demo_duration', 'demo_account_available',
        'pros', 'cons', 'verdict', 'languages', 'pricing', 'commission',
        'fee_level', 'deposit_methods', 'withdrawal_method', 'withdrawal_fee',
        'country', 'year_founded', 'regulation', 'regulated_jurisdictions',
        'regulatory_licenses', 'minimum_deposit', 'spreads', 'leverage', 'platforms',
        'payment_methods', 'customer_support', 'educational_resources', 'research_tools',
        'mobile_trading', 'social_trading', 'account_types', 'markets', 'instrument_count',
        'category_scores', 'capitalization', 'insurance', 'investor_protection',
        'segregation_of_funds', 'negative_balance_protection', 'web_trader', 'charting_tools',
        'account_managers', 'news_and_analysis', 'economic_calendar', 'vps_hosting',
        'associated_countries', 'broker_categories', 'regions', 'slug', 'top_feature', 'featured_broker', 'top_broker',
        'meta_title', 'meta_keyword', 'meta_description', 'title', 'rating',
        'trust_score', 'regulatory_tier', 'banner_image_1', 'banner_image_2',
        'is_scam', 'scam_reason', 'scam_reported_date',
        'written_by_author_id', 'edited_by_author_id', 'fact_checked_by_author_id',
        'written_by_admin_id', 'edited_by_admin_id', 'fact_checked_by_admin_id',
    ];

    protected $casts = [
        'minimum_deposit' => 'decimal:2',
        'rating' => 'decimal:2',
        'year_founded' => 'integer',
        'instrument_count' => 'integer',
        'trust_score' => 'integer',
        'regulatory_tier' => 'integer',
        'top_broker' => 'integer',
        'demo_account_available' => 'boolean',
        'investor_protection' => 'boolean',
        'segregation_of_funds' => 'boolean',
        'negative_balance_protection' => 'boolean',
        'account_managers' => 'boolean',
        'economic_calendar' => 'boolean',
        'vps_hosting' => 'boolean',
        'featured_broker' => 'boolean',
        'is_scam' => 'boolean',
        'scam_reported_date' => 'date',
        'regulation' => 'array',
        'platforms' => 'array',
        'account_types' => 'array',
        'broker_categories' => 'array',
        'regions' => 'array',
        'associated_countries' => 'array',
        'markets' => 'array',
        'category_scores' => 'array',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }

    public function reports()
    {
        return $this->hasMany(BrokerReport::class);
    }

    public function accountOptions()
    {
        $relation = $this->hasMany(AccountOption::class);

        if (\Illuminate\Support\Facades\Schema::hasColumn('account_options', 'sort_order')) {
            return $relation->orderBy('sort_order')->orderBy('id');
        }

        return $relation->orderBy('id');
    }

    public function guides()
    {
        return $this->hasMany(BrokerGuide::class);
    }

    public function forexBonuses()
    {
        return $this->hasMany(ForexBonus::class);
    }

    public function writtenByAuthor()
    {
        return $this->belongsTo(Author::class, 'written_by_author_id');
    }

    public function editedByAuthor()
    {
        return $this->belongsTo(Author::class, 'edited_by_author_id');
    }

    public function factCheckedByAuthor()
    {
        return $this->belongsTo(Author::class, 'fact_checked_by_author_id');
    }

    public function writtenByAdmin()
    {
        return $this->belongsTo(Admin::class, 'written_by_admin_id');
    }

    public function editedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'edited_by_admin_id');
    }

    public function factCheckedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'fact_checked_by_admin_id');
    }

    public function isRegulated(): bool
    {
        return count($this->regulationList()) > 0 || (bool) $this->investor_protection;
    }

    /** @return array<int, string> */
    public function marketList(): array
    {
        if (is_array($this->markets)) {
            return $this->markets;
        }

        return [];
    }

    public function getScamSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->name);
    }

    public function listingSlug(): string
    {
        if (filled($this->slug)) {
            return \Illuminate\Support\Str::slug($this->slug);
        }

        return \Illuminate\Support\Str::slug($this->name);
    }

    /** @return array<int, string> */
    public function regulationList(): array
    {
        if (is_array($this->regulation)) {
            return $this->regulation;
        }

        if (is_string($this->regulation) && $this->regulation !== '') {
            $decoded = json_decode($this->regulation, true);

            return is_array($decoded) ? $decoded : [strip_tags($this->regulation)];
        }

        return [];
    }

    /** @return array<int, string> */
    public function platformList(): array
    {
        if (is_array($this->platforms)) {
            return $this->platforms;
        }

        if (is_string($this->platforms) && $this->platforms !== '') {
            $decoded = json_decode($this->platforms, true);

            return is_array($decoded) ? $decoded : [strip_tags($this->platforms)];
        }

        return [];
    }

    /** @return array<int, string> */
    public function brokerCategoryList(): array
    {
        $categories = is_array($this->broker_categories) ? $this->broker_categories : [];

        if ($categories !== []) {
            return array_values($categories);
        }

        [$legacyCategories] = \App\Support\BrokerTaxonomy::splitLegacyAccountTypes(
            is_array($this->account_types) ? $this->account_types : null
        );

        return $legacyCategories;
    }

    /** @return array<int, string> */
    public function regionList(): array
    {
        if (is_array($this->regions)) {
            return array_values($this->regions);
        }

        return [];
    }

    /** @return array<int, string> */
    public function accountTypeLabelList(): array
    {
        [, $labels] = \App\Support\BrokerTaxonomy::splitLegacyAccountTypes(
            is_array($this->account_types) ? $this->account_types : null
        );

        return $labels;
    }
}
