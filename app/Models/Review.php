<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_id', 'name', 'email', 'description', 'rating', 'country', 'status',
    ];

    // Define relationship with Broker
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}
