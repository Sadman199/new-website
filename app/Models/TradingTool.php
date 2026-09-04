<?php

namespace App\Models;

use App\Support\RichText;
use App\Support\TradingToolCategories;
use App\Support\TradingToolsRegistry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradingTool extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'icon',
        'short_description',
        'description',
        'is_active',
        'sort_order',
        'category',
        'seo_title',
        'meta_description',
        'canonical_url',
        'og_title',
        'og_description',
        'introduction',
        'how_to_use',
        'formula',
        'example',
        'additional_explanation',
        'faqs',
        'related_tool_ids',
        'related_broker_ids',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'faqs' => 'array',
        'related_tool_ids' => 'array',
        'related_broker_ids' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function categoryKey(): string
    {
        $category = (string) ($this->category ?? '');

        return TradingToolCategories::isValid($category)
            ? $category
            : TradingToolsRegistry::defaultCategory((string) $this->slug);
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

    /** @return int[] */
    public function relatedToolIdList(): array
    {
        return $this->idList($this->related_tool_ids);
    }

    /** @return int[] */
    public function relatedBrokerIdList(): array
    {
        return $this->idList($this->related_broker_ids);
    }

    public function proseHtml(?string $field): string
    {
        $value = $this->{$field} ?? null;
        $html = RichText::forDisplay(is_string($value) ? $value : null);

        if ($html) {
            return $html;
        }

        $plain = trim((string) $value);

        return $plain !== '' ? nl2br(e($plain)) : '';
    }

    /** @param mixed $value */
    private function idList($value): array
    {
        $ids = [];

        foreach ((array) $value as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }
}
