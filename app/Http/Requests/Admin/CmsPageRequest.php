<?php

namespace App\Http\Requests\Admin;

use App\Support\CmsSectionRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CmsPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::notIn(CmsSectionRegistry::reservedSlugs()),
                Rule::unique('cms_pages', 'slug')->ignore($pageId),
            ],
            'template' => ['required', Rule::in(array_keys(CmsSectionRegistry::TEMPLATES))],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'sections_payload' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'The slug may only contain lowercase letters, numbers, and hyphens.',
            'slug.not_in' => 'This slug is reserved by the application.',
        ];
    }
}
