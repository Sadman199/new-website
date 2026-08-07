<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PropFirmReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prop_firm_id' => ['required', 'exists:prop_firms,id'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:pending,approved,rejected'],
        ];
    }
}
