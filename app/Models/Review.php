<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Review extends Model
{
    use HasFactory, Cachable;

    public const LENGTH_OF_USE_OPTIONS = [
        'not_used' => 'Not used yet',
        '0_6_months' => '0–6 months',
        '7_12_months' => '7–12 months',
        '1_3_years' => '1–3 years',
        'over_3_years' => 'Over 3 years',
    ];

    public const SCORE_FILTER_OPTIONS = [
        'all' => 'All Scores',
        'outstanding' => 'Outstanding: 9+',
        'good' => 'Good: 7–9',
        'average' => 'Average: 5–7',
        'poor' => 'Poor: 0–5',
    ];

    protected $fillable = [
        'broker_id',
        'parent_id',
        'user_id',
        'name',
        'email',
        'description',
        'rating',
        'rating_cost',
        'rating_platforms',
        'rating_customer_support',
        'length_of_use',
        'account_type',
        'country',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'rating_cost' => 'integer',
        'rating_platforms' => 'integer',
        'rating_customer_support' => 'integer',
        'status' => 'integer',
        'parent_id' => 'integer',
        'user_id' => 'integer',
        'broker_id' => 'integer',
    ];

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function approvedReplies(): HasMany
    {
        return $this->replies()->where('status', 1)->latest();
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
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
            && $this->isPending()
            && $this->isRoot();
    }

    public function lengthOfUseLabel(): ?string
    {
        if (! $this->length_of_use) {
            return null;
        }

        return self::LENGTH_OF_USE_OPTIONS[$this->length_of_use] ?? $this->length_of_use;
    }

    public function score10(): float
    {
        return (float) $this->rating * 2;
    }

    public static function overallFromDimensions(int $cost, int $platforms, int $support): int
    {
        return (int) round(($cost + $platforms + $support) / 3);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function scopeApprovedRoots(Builder $query): Builder
    {
        return $query->roots()->approved();
    }
}
