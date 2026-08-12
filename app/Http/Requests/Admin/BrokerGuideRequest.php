<?php

namespace App\Http\Requests\Admin;

use App\Models\BrokerGuide;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrokerGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in([
                BrokerGuide::STATUS_DRAFT,
                BrokerGuide::STATUS_PUBLISHED,
                BrokerGuide::STATUS_HIDDEN,
            ])],
        ];
    }
}
