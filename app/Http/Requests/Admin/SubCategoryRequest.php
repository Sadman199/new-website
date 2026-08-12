<?php

namespace App\Http\Requests\Admin;

use App\Services\GlobalViewDataService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sub_category_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'show_on_menu' => ['nullable', Rule::in(['Show', 'Hide'])],
            'show_on_home' => ['nullable', Rule::in(['Show', 'Hide'])],
            'sub_category_order' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'language_id' => ['required', 'integer', Rule::exists('languages', 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->filled('language_id')) {
            $this->merge([
                'language_id' => app(GlobalViewDataService::class)->currentLanguageId(),
            ]);
        }
    }
}
