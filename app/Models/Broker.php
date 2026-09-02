<?php

namespace App\Models;

use App\Support\JsonList;
use App\Support\RichText;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'trust_score', 'regulatory_tier', 'banner_image_1', 'banner_image_2', 'logo',
        'is_scam', 'scam_reason', 'scam_reported_date',
        'written_by_author_id', 'edited_by_author_id', 'fact_checked_by_author_id',
        'written_by_admin_id', 'edited_by_admin_id', 'fact_checked_by_admin_id',
    ];

    protected $casts = [
        'minimum_deposit' => 'decimal:2',
        'rating' => 'decimal:2',
        'capitalization' => 'decimal:2',
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

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_broker')->withTimestamps();
    }

    public function banners()
    {
        return $this->belongsToMany(Banner::class, 'banner_broker')->withTimestamps();
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

    /** Strip legacy Summernote / entity markup from short text fields site-wide. */
    protected function title(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function shortDescription(): Attribute
    {
        return $this->richTextAttribute();
    }

    protected function topFeature(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function commission(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function pricing(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function depositMethods(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function withdrawalMethod(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function withdrawalFee(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function paymentMethods(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function languages(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function spreads(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function leverage(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function customerSupport(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function country(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function mobileTrading(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function webTrader(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function chartingTools(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function newsAndAnalysis(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function researchTools(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function educationalResources(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function socialTrading(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function insurance(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function regulatedJurisdictions(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function regulatoryLicenses(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function metaTitle(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function metaKeyword(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function metaDescription(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function scamReason(): Attribute
    {
        return $this->plainTextAttribute();
    }

    protected function description(): Attribute
    {
        return $this->richTextAttribute();
    }

    protected function verdict(): Attribute
    {
        return $this->richTextAttribute();
    }

    protected function pros(): Attribute
    {
        return $this->richTextAttribute();
    }

    protected function cons(): Attribute
    {
        return $this->richTextAttribute();
    }

    private function plainTextAttribute(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => RichText::toPlainText(
                $value === null || $value === '' ? null : (string) $value
            ),
        );
    }

    private function richTextAttribute(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => RichText::forDisplay(
                $value === null || $value === '' ? null : (string) $value
            ),
        );
    }

    /** @return array<int, string> */
    public function marketList(): array
    {
        return JsonList::normalize($this->markets);
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

    public function mediaUrl(?string $path = null): ?string
    {
        $path = $path ?? $this->logo;

        if (! filled($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    public function ogShareImageUrl(): string
    {
        return app(\App\Services\BrokerOgImageService::class)->publicUrl($this);
    }

    public function usesGeneratedOgImage(): bool
    {
        $service = app(\App\Services\BrokerOgImageService::class);

        return $service->canGenerate() || is_file(public_path('uploads/og/'.\Illuminate\Support\Str::slug($this->listingSlug()).'-og.png'));
    }

    /** @return array<int, string> */
    public function regulationList(): array
    {
        $items = JsonList::normalize($this->regulation);

        return $items !== [] ? $items : (
            is_string($this->regulation) && $this->regulation !== ''
                ? [RichText::toPlainText($this->regulation) ?? strip_tags($this->regulation)]
                : []
        );
    }

    /** @return array<int, string> */
    public function platformList(): array
    {
        $items = JsonList::normalize($this->platforms);

        return $items !== [] ? $items : (
            is_string($this->platforms) && $this->platforms !== ''
                ? [RichText::toPlainText($this->platforms) ?? strip_tags($this->platforms)]
                : []
        );
    }

    /** @return array<int, string> */
    public function brokerCategoryList(): array
    {
        $categories = JsonList::normalize($this->broker_categories);

        if ($categories !== []) {
            return $categories;
        }

        [$legacyCategories] = \App\Support\BrokerTaxonomy::splitLegacyAccountTypes($this->account_types);

        return $legacyCategories;
    }

    /** @return array<int, string> */
    public function regionList(): array
    {
        return JsonList::normalize($this->regions);
    }

    /** @return array<int, string> */
    public function accountTypeLabelList(): array
    {
        [, $labels] = \App\Support\BrokerTaxonomy::splitLegacyAccountTypes($this->account_types);

        if ($labels !== []) {
            return $labels;
        }

        return $this->accountOptions->pluck('account_type')->filter()->unique()->values()->all();
    }
}
