<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropFirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $propFirmId = $this->route('id') ?? $this->route('propFirm')?->id;

        return [
            'prop_firm_category_id' => ['nullable', 'exists:prop_firm_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('prop_firms', 'slug')->ignore($propFirmId),
            ],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:2000'],
            'affiliate_link' => ['nullable', 'url', 'max:2000'],
            'founded_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'headquarters' => ['nullable', 'string', 'max:255'],
            'max_funding' => ['nullable', 'string', 'max:255'],
            'profit_split' => ['nullable', 'string', 'max:255'],
            'min_fee' => ['nullable', 'numeric', 'min:0'],
            'max_fee' => ['nullable', 'numeric', 'min:0'],
            'scaling_available' => ['nullable', 'boolean'],
            'trust_score' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'editor_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'user_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'overall_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:4096'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'is_verified' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'attribute_ids' => ['nullable', 'array'],
            'attribute_ids.*' => ['integer', 'exists:prop_firm_attributes,id'],
            'programs' => ['nullable', 'array'],
            'programs.*.name' => ['nullable', 'string', 'max:255'],
            'programs.*.entry_fee' => ['nullable', 'numeric', 'min:0'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:500'],
            'faqs.*.answer' => ['nullable', 'string'],
        ];
    }
}
