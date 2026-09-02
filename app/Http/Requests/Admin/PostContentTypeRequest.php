<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostContentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => ['required', 'string', 'max:80'],
            'slug' => [
                'nullable',
                'string',
                'max:80',
                Rule::unique('post_content_types', 'slug')->ignore($id),
            ],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
