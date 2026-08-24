<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class TopAdvertisement extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'top_ad',
        'top_ad_url',
        'top_ad_status',
    ];
}
