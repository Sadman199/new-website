<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [
        'title',
        'type',
        'image',
        'html_code',
        'video_url',
        'link',
        'description',
        'position',
        'is_active',
        'priority',
        'start_date',
        'end_date',
        'trigger_type',
        'trigger_value',
        'repeatable',
        'category',
        'pages',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'repeatable' => 'boolean',
        'priority' => 'integer',
        'trigger_value' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'pages' => 'array',
    ];

    public function scopeActive($query)
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });
    }

    public function scopePopups($query)
    {
        return $query->where('type', 'popup')->orderByDesc('priority')->orderByDesc('id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('uploads/' . ltrim($this->image, '/'));
    }

    /**
     * Payload consumed by the front-end popup engine.
     */
    public function toPopupPayload(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image_url,
            'html_code' => $this->html_code,
            'video_url' => $this->video_url,
            'link' => $this->link,
            'description' => $this->description,
            'trigger_type' => $this->trigger_type ?: 'scroll',
            'trigger_value' => (int) ($this->trigger_value ?? 50),
            'repeatable' => (bool) $this->repeatable,
            'category' => $this->category,
            'pages' => $this->pages ?: [],
            'priority' => (int) $this->priority,
        ];
    }
}
