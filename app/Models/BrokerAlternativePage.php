<?php

namespace App\Models;

use App\Support\RichText;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BrokerAlternativePage extends Model
{
    protected $fillable = [
        'broker_id',
        'is_published',
        'seo_title',
        'meta_description',
        'intro',
        'why_consider',
        'faqs',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'faqs' => 'array',
    ];

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BrokerAlternativeItem::class, 'page_id')->orderBy('sort_order')->orderBy('id');
    }

    public function alternativeBrokers(): BelongsToMany
    {
        return $this->belongsToMany(
            Broker::class,
            'broker_alternative_items',
            'page_id',
            'alternative_broker_id'
        )->withPivot('sort_order')->withTimestamps()->orderByPivot('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** @return array<int, array{question: string, answer: string}> */
    public function normalizedFaqs(): array
    {
        $faqs = [];

        foreach ($this->faqs ?? [] as $faq) {
            $question = trim((string) (RichText::toPlainText($faq['question'] ?? null) ?? ''));
            $answer = trim((string) (RichText::toPlainText($faq['answer'] ?? null) ?? ''));

            if ($question === '' || $answer === '') {
                continue;
            }

            $faqs[] = compact('question', 'answer');
        }

        return $faqs;
    }
}
