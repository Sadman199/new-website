<?php

namespace App\Http\Requests\Admin;

use App\Models\Banner;
use App\Support\BannerCatalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'title' => trim((string) $this->input('title', '')),
            'button_text' => trim((string) $this->input('button_text', '')),
            'button_url' => trim((string) $this->input('button_url', '')),
            'page_path' => trim((string) $this->input('page_path', '')),
            'creative_format' => $this->input('creative_format', 'image'),
            'is_active' => $this->boolean('is_active'),
            'broker_id' => $this->input('broker_id') ?: null,
            'broker_ids' => $this->normalizedBrokerIds(),
        ]);
    }

    /** @return list<int> */
    private function normalizedBrokerIds(): array
    {
        $targeting = (string) $this->input('targeting');
        $fromSingle = (int) $this->input('broker_id', 0);
        $raw = $this->input('broker_ids', []);
        if (! is_array($raw)) {
            $raw = $raw === null || $raw === '' ? [] : [$raw];
        }
        $fromList = array_values(array_unique(array_filter(
            array_map('intval', $raw),
            fn (int $id) => $id > 0
        )));

        if ($targeting === 'specific_broker') {
            if ($fromSingle > 0) {
                return [$fromSingle];
            }

            return array_slice($fromList, 0, 1);
        }

        if ($targeting === 'multiple_brokers') {
            if ($fromList !== []) {
                return $fromList;
            }

            return $fromSingle > 0 ? [$fromSingle] : [];
        }

        return [];
    }

    public function rules(): array
    {
        $isCreate = $this->routeIs('admin_banners_store');
        $isHtml = BannerCatalog::isHtml((string) $this->input('creative_format'));
        $imageRules = ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'];

        $needsDesktopImage = false;
        if (! $isHtml) {
            if ($isCreate) {
                $needsDesktopImage = true;
            } else {
                $existing = Banner::query()->find($this->route('id'));
                $needsDesktopImage = ! $existing || trim((string) $existing->desktop_image) === '';
            }
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'creative_format' => ['required', Rule::in(array_keys(BannerCatalog::formats()))],
            'desktop_image' => $isHtml
                ? ['nullable']
                : array_merge($needsDesktopImage ? ['required'] : ['nullable'], $imageRules),
            'mobile_image' => array_merge(['nullable'], $imageRules),
            'html_content' => [
                Rule::requiredIf(BannerCatalog::isHtml($this->input('creative_format'))),
                'nullable',
                'string',
            ],
            'banner_type' => ['required', Rule::in(array_keys(BannerCatalog::types()))],
            'targeting' => ['required', Rule::in(array_keys(BannerCatalog::targeting()))],
            'placement' => [
                'required',
                'string',
                'max:60',
                Rule::in(array_keys(BannerCatalog::placements(
                    $this->routeIs('admin_banners_update')
                        ? optional(Banner::query()->find($this->route('id')))->placement
                        : null
                ))),
            ],
            'page_path' => [
                Rule::requiredIf($this->input('targeting') === 'specific_page'),
                'nullable',
                'string',
                'max:255',
            ],
            'broker_id' => $this->input('targeting') === 'specific_broker'
                ? ['required', 'integer', 'exists:brokers,id']
                : ['nullable', 'integer', 'exists:brokers,id'],
            'broker_ids' => $this->input('targeting') === 'multiple_brokers'
                ? ['required', 'array', 'min:1']
                : ['nullable', 'array'],
            'broker_ids.*' => ['integer', 'exists:brokers,id'],
            'button_text' => ['nullable', 'string', 'max:120'],
            'button_url' => [
                Rule::requiredIf(trim((string) $this->input('button_text', '')) !== ''),
                'nullable',
                'url',
                'max:500',
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'priority' => ['required', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $targeting = (string) $this->input('targeting');
            $ids = (array) $this->input('broker_ids', []);

            if ($targeting === 'specific_broker' && count($ids) > 1) {
                $validator->errors()->add('broker_ids', 'Choose only one broker for specific-broker targeting.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a banner title.',
            'desktop_image.required' => 'Please upload a desktop banner image.',
            'html_content.required' => 'Please enter HTML for this banner template.',
            'desktop_image.image' => 'The desktop file must be an image.',
            'mobile_image.image' => 'The mobile file must be an image.',
            'banner_type.required' => 'Please choose a banner type.',
            'targeting.required' => 'Please choose a targeting option.',
            'placement.required' => 'Please choose a placement.',
            'page_path.required' => 'Enter the page path when targeting a specific page.',
            'broker_id.required' => 'Select a broker from the “Select broker” list.',
            'broker_id.exists' => 'That broker was not found.',
            'broker_ids.required' => 'Select at least one broker in More brokers.',
            'broker_ids.min' => 'Select at least one broker in More brokers.',
            'button_url.required' => 'Enter a button URL when button text is set.',
            'button_url.url' => 'The button URL must be a valid URL.',
            'start_date.required' => 'Please choose a start date.',
            'end_date.required' => 'Please choose an end date.',
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
            'priority.required' => 'Please enter a priority.',
        ];
    }
}
