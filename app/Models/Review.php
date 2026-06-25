<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Review extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'broker_id', 'name', 'email', 'description', 'rating', 'country', 'status',
    ];

    // Define relationship with Broker
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}
