<?php

namespace App\Http\Requests\Admin;

use App\Services\GlobalViewDataService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LiveChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading' => ['required', 'string', 'max:255'],
            'video_id' => ['required', 'string', 'max:255'],
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
