<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropFirmReview extends Model
{
    protected $fillable = [
        'prop_firm_id',
        'rating',
        'title',
        'content',
        'author',
        'status',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function propFirm(): BelongsTo
    {
        return $this->belongsTo(PropFirm::class);
    }
}
