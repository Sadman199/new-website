<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Faq extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'faq_title',
        'faq_detail',
        'language_id',
        'broker_id',
    ];

    public function rLanguage()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}
