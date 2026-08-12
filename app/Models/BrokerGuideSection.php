<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerGuideSection extends Model
{
    protected $fillable = [
        'broker_guide_id',
        'section_type',
        'section_data',
        'sort_order',
    ];

    protected $casts = [
        'section_data' => 'array',
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(BrokerGuide::class, 'broker_guide_id');
    }

    public function data(string $key, mixed $default = null): mixed
    {
        return data_get($this->section_data, $key, $default);
    }
}
