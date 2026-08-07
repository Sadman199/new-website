<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropFirm extends Model
{
    protected $fillable = [
        'prop_firm_category_id',
        'name',
        'slug',
        'logo',
        'cover_image',
        'description',
        'website',
        'affiliate_link',
        'founded_year',
        'headquarters',
        'max_funding',
        'profit_split',
        'min_fee',
        'max_fee',
        'scaling_available',
        'trust_score',
        'editor_rating',
        'user_rating',
        'overall_rating',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'is_featured',
        'is_verified',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'min_fee' => 'decimal:2',
        'max_fee' => 'decimal:2',
        'scaling_available' => 'boolean',
        'trust_score' => 'decimal:1',
        'editor_rating' => 'decimal:1',
        'user_rating' => 'decimal:1',
        'overall_rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PropFirmCategory::class, 'prop_firm_category_id');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(PropFirmProgram::class)->orderBy('sort_order');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(PropFirmAttribute::class, 'prop_firm_attribute_prop_firm');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(PropFirmReview::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(PropFirmFaq::class)->orderBy('sort_order');
    }
}
