<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrokerRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        if ($this->filled('account_types_combined') && ! $this->has('account_types')) {
            $types = array_values(array_filter(array_map('trim', explode(',', $this->input('account_types_combined')))));
            $this->merge(['account_types' => $types]);
        }

        if ($this->filled('associated_countries_combined') && ! $this->has('associated_countries')) {
            $countries = array_values(array_filter(array_map('trim', explode(',', $this->input('associated_countries_combined')))));
            $this->merge(['associated_countries' => $countries]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brokerId = $this->route('broker')?->id ?? $this->route('id');

        return [
            // Section 1 — Profile & SEO
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('brokers', 'slug')->ignore($brokerId),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:2000'],
            'short_description' => ['nullable', 'string'],
            'country' => ['required', 'string', 'max:500'],
            'year_founded' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'languages' => ['nullable', 'string', 'max:1000'],
            'visit_site' => ['nullable', 'string', 'max:2000'],
            'open_live' => ['nullable', 'string', 'max:2000'],
            'open_demo' => ['nullable', 'string', 'max:2000'],
            'demo_link' => ['nullable', 'string', 'max:2000'],
            'demo_duration' => ['nullable', 'string', 'max:50'],
            'demo_account_available' => ['nullable', 'boolean'],
            'top_feature' => ['nullable', 'string'],
            'featured_broker' => ['nullable', 'boolean'],
            'top_broker' => ['nullable', 'integer', 'min:0', 'max:100'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:2048'],
            'banner_image_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:2048'],
            'banner_image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg,avif', 'max:2048'],

            // Section 2 — Trading & Payments
            'minimum_deposit' => ['nullable', 'numeric', 'min:0'],
            'spreads' => ['nullable', 'string', 'max:255'],
            'leverage' => ['nullable', 'string', 'max:255'],
            'pricing' => ['nullable', 'string'],
            'commission' => ['nullable', 'string', 'max:100'],
            'fee_level' => ['nullable', Rule::in(['low', 'medium', 'high'])],
            'withdrawal_fee' => ['nullable', 'string', 'max:100'],
            'platforms' => ['nullable', 'array'],
            'platforms.*' => ['string', 'max:100'],
            'account_types' => ['nullable', 'array'],
            'account_types.*' => ['string', 'max:100'],
            'broker_categories' => ['nullable', 'array'],
            'broker_categories.*' => ['string', Rule::in(\App\Support\BrokerTaxonomy::categorySlugs())],
            'regions' => ['nullable', 'array'],
            'regions.*' => ['string', Rule::in(\App\Support\BrokerTaxonomy::regionSlugs())],
            'markets' => ['nullable', 'array'],
            'markets.*' => ['string', 'max:50'],
            'instrument_count' => ['nullable', 'integer', 'min:0'],
            'deposit_methods' => ['nullable', 'string'],
            'withdrawal_method' => ['nullable', 'string'],
            'payment_methods' => ['nullable', 'string'],
            'mobile_trading' => ['nullable', 'string'],
            'social_trading' => ['nullable', 'string'],
            'web_trader' => ['nullable', 'string'],
            'charting_tools' => ['nullable', 'string'],
            'customer_support' => ['nullable', 'string'],
            'educational_resources' => ['nullable', 'string'],
            'research_tools' => ['nullable', 'string'],
            'news_and_analysis' => ['nullable', 'string'],
            'vps_hosting' => ['nullable', 'boolean'],
            'economic_calendar' => ['nullable', 'boolean'],
            'account_managers' => ['nullable', 'boolean'],

            // Section 3 — Safety, Scores & Review
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'trust_score' => ['nullable', 'integer', 'min:1', 'max:99'],
            'regulatory_tier' => ['nullable', 'integer', 'min:1', 'max:5'],
            'category_scores' => ['nullable', 'array'],
            'category_scores.*' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'regulation' => ['nullable', 'array'],
            'regulation.*' => ['string', 'max:100'],
            'regulated_jurisdictions' => ['nullable', 'string'],
            'regulatory_licenses' => ['nullable', 'string'],
            'associated_countries' => ['nullable', 'array'],
            'associated_countries.*' => ['string', 'max:100'],
            'capitalization' => ['nullable', 'string'],
            'insurance' => ['nullable', 'string'],
            'investor_protection' => ['nullable', 'boolean'],
            'segregation_of_funds' => ['nullable', 'boolean'],
            'negative_balance_protection' => ['nullable', 'boolean'],
            'pros' => ['nullable', 'string'],
            'cons' => ['nullable', 'string'],
            'verdict' => ['nullable', 'string'],
            'is_scam' => ['nullable', 'boolean'],
            'scam_reason' => ['nullable', 'string'],
            'scam_reported_date' => ['nullable', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'broker name',
            'year_founded' => 'year founded',
            'fee_level' => 'fee level',
            'trust_score' => 'trust score',
            'regulatory_tier' => 'regulatory tier',
            'instrument_count' => 'instrument count',
            'demo_account_available' => 'demo account available',
            'investor_protection' => 'investor protection',
            'negative_balance_protection' => 'negative balance protection',
        ];
    }
}
