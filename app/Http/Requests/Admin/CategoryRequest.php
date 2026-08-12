<?php

namespace App\Http\Requests\Admin;

use App\Services\GlobalViewDataService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'show_on_menu' => ['nullable', Rule::in(['Show', 'Hide'])],
            'category_order' => ['required', 'integer', 'min:0'],
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
