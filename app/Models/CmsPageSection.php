<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_type',
        'section_data',
        'sort_order',
    ];

    protected $casts = [
        'section_data' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function data(string $key, mixed $default = null): mixed
    {
        return data_get($this->section_data, $key, $default);
    }
}
