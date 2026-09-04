<?php

namespace App\Http\Requests\Admin;

use App\Support\RichText;
use App\Support\TradingToolCategories;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TradingToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'seo_title' => $this->plain($this->input('seo_title')),
            'meta_description' => $this->plain($this->input('meta_description')),
            'canonical_url' => $this->plain($this->input('canonical_url')),
            'og_title' => $this->plain($this->input('og_title')),
            'og_description' => $this->plain($this->input('og_description')),
            'introduction' => $this->plain($this->input('introduction')),
            'how_to_use' => $this->plain($this->input('how_to_use')),
            'formula' => $this->plain($this->input('formula')),
            'example' => $this->plain($this->input('example')),
            'additional_explanation' => $this->plain($this->input('additional_explanation')),
            'faqs' => $this->normalizedFaqs(),
            'related_tool_ids' => $this->idList($this->input('related_tool_ids', [])),
            'related_broker_ids' => $this->idList($this->input('related_broker_ids', [])),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['boolean'],
            'category' => ['required', 'string', Rule::in(TradingToolCategories::keys())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'string', 'max:500'],
            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'introduction' => ['nullable', 'string'],
            'how_to_use' => ['nullable', 'string'],
            'formula' => ['nullable', 'string'],
            'example' => ['nullable', 'string'],
            'additional_explanation' => ['nullable', 'string'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:255'],
            'faqs.*.answer' => ['nullable', 'string'],
            'related_tool_ids' => ['nullable', 'array'],
            'related_tool_ids.*' => ['integer', 'exists:trading_tools,id'],
            'related_broker_ids' => ['nullable', 'array'],
            'related_broker_ids.*' => [
                'integer',
                Rule::exists('brokers', 'id')->where(fn ($query) => $query->where('is_scam', false)),
            ],
        ];
    }

    /** @return array<int, array{question: string, answer: string}> */
    protected function normalizedFaqs(): array
    {
        $faqs = [];

        foreach ((array) $this->input('faqs', []) as $faq) {
            $question = $this->plain($faq['question'] ?? null) ?? '';
            $answer = $this->plain($faq['answer'] ?? null) ?? '';

            if ($question === '' && $answer === '') {
                continue;
            }

            $faqs[] = compact('question', 'answer');
        }

        return $faqs;
    }

    /** @return int[] */
    protected function idList(mixed $value): array
    {
        return array_values(array_unique(array_filter(
            array_map('intval', (array) $value),
            fn (int $id) => $id > 0
        )));
    }

    protected function plain(mixed $value): ?string
    {
        $text = RichText::toPlainText(is_scalar($value) ? (string) $value : null);

        return $text !== null && $text !== '' ? $text : null;
    }
}
