<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrokerGuideTopic extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'default_summary',
        'icon',
        'context_profile',
        'requires_swap_free',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requires_swap_free' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function guides(): HasMany
    {
        return $this->hasMany(BrokerGuide::class, 'broker_guide_topic_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** @return array<string, string> */
    public static function contextProfileOptions(): array
    {
        return config('broker-guides.context_profiles', []);
    }
}
