<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropFirmFaq extends Model
{
    protected $fillable = [
        'prop_firm_id',
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function propFirm(): BelongsTo
    {
        return $this->belongsTo(PropFirm::class);
    }
}
