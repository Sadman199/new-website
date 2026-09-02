<?php

namespace App\Http\Requests\Admin;

use App\Models\ForexBonus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForexBonusRequest extends FormRequest
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
            $slug = Str::slug($slug) ?: $slug;
        }

        $this->merge([
            'title' => $title,
            'slug' => $slug,
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('id');
        $isCreate = $this->routeIs('admin_forex_bonus_store');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('forex_bonuses', 'slug')->ignore($id),
            ],
            'broker_id' => ['nullable', 'exists:brokers,id'],
            'publish_date' => ['required', 'date'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'promo_type' => ['required', Rule::in(array_keys(ForexBonus::promoTypes()))],
            'description' => ['required', 'string'],
            'feature_image' => array_merge(
                $isCreate ? ['required'] : ['nullable'],
                ['image', 'mimes:jpg,jpeg,png,webp,avif,gif', 'max:5120']
            ),
            'link' => ['required', 'url'],
            'affiliate_link' => ['nullable', 'url'],
            'participate' => ['required', 'string'],
            'how_to_participate' => ['required', 'string'],
            'details' => ['required', 'string'],
            'general_terms' => ['required', 'string'],
            'prize' => ['required'],
            'eligibility_criteria' => ['nullable', 'string'],
            'expiry_date' => ['nullable', 'date'],
            'min_deposit' => ['nullable', 'numeric', 'min:0'],
            'bonus_amount' => ['nullable', 'numeric', 'min:0'],
            'bonus_percentage' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'wagering_requirement' => ['nullable', 'string', 'max:255'],
            'max_credit' => ['nullable', 'numeric', 'min:0'],
            'eligible_clients' => ['nullable', Rule::in(['new', 'existing', 'both'])],
            'volume_requirement' => ['nullable', 'string', 'max:255'],
            'bonus_type_details' => ['nullable', 'string'],
            'terms_conditions_url' => ['nullable', 'url'],
            'bonus_category' => ['nullable', 'string', 'max:255'],
            'promotion_status' => ['nullable', Rule::in(['ongoing', 'limited-time', 'expired'])],
            'is_featured' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'written_assignee' => ['nullable', 'string'],
            'edited_assignee' => ['nullable', 'string'],
            'fact_checked_assignee' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a bonus title.',
            'slug.required' => 'Please enter a URL slug.',
            'slug.unique' => 'Another bonus already uses this URL.',
            'publish_date.required' => 'Please choose a publish date.',
            'promo_type.required' => 'Please choose a bonus type.',
            'description.required' => 'Please add a short description.',
            'feature_image.required' => 'Please upload a feature image.',
            'feature_image.image' => 'The feature image must be an image file.',
            'link.required' => 'Please enter the offer link.',
            'link.url' => 'The offer link must be a valid URL.',
            'affiliate_link.url' => 'The affiliate link must be a valid URL.',
            'participate.required' => 'Please enter country restrictions (or write “None”).',
            'how_to_participate.required' => 'Please explain how to take part.',
            'details.required' => 'Please add the bonus details.',
            'general_terms.required' => 'Please add the general terms.',
            'prize.required' => 'Please enter the prize or offer headline.',
        ];
    }
}
