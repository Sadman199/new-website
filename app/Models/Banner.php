<?php

namespace App\Models;

use App\Support\BannerCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'creative_format',
        'desktop_image',
        'mobile_image',
        'html_content',
        'banner_type',
        'targeting',
        'placement',
        'page_path',
        'button_text',
        'button_url',
        'start_date',
        'end_date',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    public function brokers(): BelongsToMany
    {
        return $this->belongsToMany(Broker::class, 'banner_broker')->withTimestamps();
    }

    public function scopeCurrentlyDisplayable(Builder $query, ?Carbon $on = null): Builder
    {
        $day = ($on ?? Carbon::today())->toDateString();

        return $query
            ->where('is_active', true)
            ->whereDate('start_date', '<=', $day)
            ->whereDate('end_date', '>=', $day);
    }

    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement);
    }

    public function scopeRanked(Builder $query): Builder
    {
        return $query->orderByDesc('priority')->orderByDesc('id');
    }

    public function isWithinSchedule(?Carbon $on = null): bool
    {
        $day = ($on ?? Carbon::today())->startOfDay();

        return $this->start_date && $this->end_date
            && $day->gte($this->start_date->copy()->startOfDay())
            && $day->lte($this->end_date->copy()->startOfDay());
    }

    public function isCurrentlyLive(?Carbon $on = null): bool
    {
        return $this->is_active && $this->isWithinSchedule($on);
    }

    public function scheduleStatus(?Carbon $on = null): string
    {
        $day = ($on ?? Carbon::today())->startOfDay();

        if (! $this->is_active) {
            return 'inactive';
        }

        if ($this->start_date && $day->lt($this->start_date->copy()->startOfDay())) {
            return 'scheduled';
        }

        if ($this->end_date && $day->gt($this->end_date->copy()->startOfDay())) {
            return 'expired';
        }

        return 'active';
    }

    public function scheduleStatusLabel(?Carbon $on = null): string
    {
        return match ($this->scheduleStatus($on)) {
            'inactive' => 'Inactive',
            'scheduled' => 'Scheduled',
            'expired' => 'Expired',
            default => 'Active',
        };
    }

    public function placementLabel(): string
    {
        return BannerCatalog::placementLabel($this->placement);
    }

    public function typeLabel(): string
    {
        return BannerCatalog::typeLabel($this->banner_type);
    }

    public function targetingLabel(): string
    {
        return BannerCatalog::targetingLabel($this->targeting);
    }

    public function formatLabel(): string
    {
        return BannerCatalog::formatLabel($this->creative_format);
    }

    public function isHtmlCreative(): bool
    {
        return BannerCatalog::isHtml($this->creative_format);
    }

    public function desktopImageUrl(): ?string
    {
        return $this->publicImageUrl($this->desktop_image);
    }

    public function mobileImageUrl(): ?string
    {
        return $this->publicImageUrl($this->mobile_image);
    }

    public function previewImageUrl(): ?string
    {
        return $this->desktopImageUrl() ?: $this->mobileImageUrl();
    }

    private function publicImageUrl(?string $path): ?string
    {
        if ($path === null || trim($path) === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }
}
