<?php

namespace App\Http\Requests\Admin;

use App\Models\BrokerGuideTopic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrokerGuideTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $topicId = $this->route('id');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:80',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('broker_guide_topics', 'slug')->ignore($topicId),
            ],
            'default_summary' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:80'],
            'context_profile' => ['nullable', 'string', 'max:40'],
            'requires_swap_free' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
