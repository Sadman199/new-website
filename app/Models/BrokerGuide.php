<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrokerGuide extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_HIDDEN = 'hidden';

    protected $fillable = [
        'broker_id',
        'broker_guide_topic_id',
        'title',
        'summary',
        'content',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(BrokerGuideTopic::class, 'broker_guide_topic_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(BrokerGuideSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function topicSlug(): string
    {
        return $this->topic?->slug ?? '';
    }

    public function hasRenderableBody(): bool
    {
        return filled(strip_tags($this->content ?? ''));
    }

    public function seoTitle(): string
    {
        if (filled($this->meta_title)) {
            return $this->meta_title;
        }

        $brokerName = $this->broker?->name ?? 'Broker';

        return $this->title . ' at ' . $brokerName . ' | BrokersCourt';
    }

    public function seoDescription(): ?string
    {
        if (filled($this->meta_description)) {
            return $this->meta_description;
        }

        return $this->summary;
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
