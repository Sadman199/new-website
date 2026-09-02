<?php

namespace App\Http\Requests\Admin;

use App\Support\CmsSectionRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CmsPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $slug = trim((string) $this->input('slug', ''));
        $title = trim((string) $this->input('title', ''));

        if ($slug === '' && $title !== '') {
            $slug = Str::slug($title);
        } elseif ($slug !== '') {
            $slug = Str::slug($slug);
        }

        $this->merge([
            'title' => $title,
            'slug' => $slug,
            'meta_title' => $this->filled('meta_title') ? trim((string) $this->input('meta_title')) : null,
            'meta_description' => $this->filled('meta_description') ? trim((string) $this->input('meta_description')) : null,
        ]);
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
            'title.required' => 'Please enter a page title.',
            'slug.required' => 'Please enter a page URL.',
            'slug.regex' => 'The page URL may only contain lowercase letters, numbers, and hyphens.',
            'slug.not_in' => 'That URL is already used by the site. Please choose a different one.',
            'slug.unique' => 'Another page already uses this URL.',
            'template.required' => 'Please choose a page layout.',
            'template.in' => 'Please choose a valid page layout.',
            'status.required' => 'Please choose whether this page is a draft or published.',
            'status.in' => 'Please choose Draft or Published.',
            'meta_title.max' => 'The search title must be 255 characters or less.',
            'meta_description.max' => 'The search description must be 500 characters or less.',
        ];
    }
}
