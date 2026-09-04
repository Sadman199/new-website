<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrokerAlternativePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'alternative_broker_ids' => array_values(array_filter(
                array_map('intval', (array) $this->input('alternative_broker_ids', []))
            )),
            'seo_title' => $this->plain($this->input('seo_title')),
            'meta_description' => $this->plain($this->input('meta_description')),
            'intro' => $this->plain($this->input('intro')),
            'why_consider' => $this->plain($this->input('why_consider')),
            'faqs' => $this->normalizedFaqs(),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'broker_id' => [
                'required',
                'exists:brokers,id',
                Rule::unique('broker_alternative_pages', 'broker_id')->ignore($id),
            ],
            'is_published' => ['boolean'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'intro' => ['nullable', 'string'],
            'why_consider' => ['nullable', 'string'],
            'alternative_broker_ids' => ['nullable', 'array'],
            'alternative_broker_ids.*' => [
                'integer',
                Rule::exists('brokers', 'id')->where(fn ($query) => $query->where('is_scam', false)),
                'different:broker_id',
            ],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:255'],
            'faqs.*.answer' => ['nullable', 'string'],
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

    protected function plain(mixed $value): ?string
    {
        $text = \App\Support\RichText::toPlainText(is_scalar($value) ? (string) $value : null);

        return $text !== null && $text !== '' ? $text : null;
    }
}
