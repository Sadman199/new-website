<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class HomeAdvertisement extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'above_search_ad',
        'above_search_ad_url',
        'above_search_ad_status',
        'above_footer_ad',
        'above_footer_ad_url',
        'above_footer_ad_status',
    ];
}
