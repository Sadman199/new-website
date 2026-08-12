<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Review extends Model
{
    use HasFactory, Cachable;

    protected $fillable = [
        'broker_id', 'user_id', 'name', 'email', 'description', 'rating', 'country', 'status',
    ];

    // Define relationship with Broker
    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }

    // Define relationship with the submitting User (nullable for legacy guest reviews)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return (int) $this->status === 0;
    }

    public function isApproved(): bool
    {
        return (int) $this->status === 1;
    }

    public function isDeclined(): bool
    {
        return (int) $this->status === -1;
    }

    public function canBeEditedBy(?User $user): bool
    {
        return $user
            && (int) $this->user_id === (int) $user->id
            && $this->isPending();
    }
}
