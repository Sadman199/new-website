<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class ForexBonus extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'title',
        'slug',
        'publish_date',
        'author_name',
        'promo_type',
        'description',
        'feature_image',
        'link',
        'participate',
        'how_to_participate',
        'details',
        'general_terms',
        'prize',
        'eligibility_criteria',
        'expiry_date',
        'min_deposit',
        'bonus_type_details',
        'terms_conditions_url',
        'affiliate_link',
        'bonus_category',
        'promotion_status',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}
