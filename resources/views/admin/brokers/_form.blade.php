@php
    $isEdit = $broker->exists;
    $selectedRegulations = old('regulation', $broker->regulationList());
    $selectedPlatforms = old('platforms', $broker->platformList());
    $selectedAccountTypes = old('account_types', $broker->accountTypeLabelList());
    $selectedBrokerCategories = old('broker_categories', $broker->brokerCategoryList());
    $selectedRegions = old('regions', $broker->regionList());
    $selectedMarkets = old('markets', is_array($broker->markets) ? $broker->markets : []);
    $selectedCountries = old('associated_countries', is_array($broker->associated_countries) ? $broker->associated_countries : []);
    $categoryScores = old('category_scores', is_array($broker->category_scores) ? $broker->category_scores : []);
@endphp

<div id="broker-accordion" class="tw-space-y-5">
    {{-- 1. Identity --}}
    <div class="card tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="card-header tw-bg-slate-50 tw-border-b tw-border-slate-200/70 tw-px-6 tw-py-4" id="headingIdentity">
            <h5 class="mb-0">
                <button class="btn btn-link tw-w-full tw-text-left tw-flex tw-items-center tw-gap-3 tw-font-extrabold tw-text-slate-900 hover:tw-underline" type="button" data-toggle="collapse" data-target="#collapseIdentity" aria-expanded="true">
                    1. Identity
                </button>
            </h5>
        </div>
        <div id="collapseIdentity" class="collapse show" data-parent="#broker-accordion">
            <div class="card-body tw-px-6 tw-py-5">
                <p class="tw-mt-0 tw-mb-4 tw-text-xs tw-text-slate-600">
                    Who the broker is — name, slug, HQ, logos, affiliate links, and where they appear in listings.
                </p>

                <h6 class="text-primary font-weight-bold mb-3">Basics</h6>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Broker Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $broker->name) }}">
                        @error('name')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $broker->slug) }}" placeholder="auto from name if empty">
                        @error('slug')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="title">Review Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $broker->title) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="url">Website URL</label>
                        <input type="url" name="url" id="url" class="form-control" value="{{ old('url', $broker->url) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="country">Headquarters / Country <span class="text-danger">*</span></label>
                        <input type="text" name="country" id="country" class="form-control" required value="{{ old('country', $broker->country) }}">
                        @error('country')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="year_founded">Year Founded</label>
                        <input type="number" name="year_founded" id="year_founded" class="form-control" min="1900" max="{{ date('Y') + 1 }}" value="{{ old('year_founded', $broker->year_founded) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="languages">Languages</label>
                        <input type="text" name="languages" id="languages" class="form-control" value="{{ old('languages', $broker->languages) }}">
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3 mt-2">Affiliate Links</h6>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="visit_site">Visit Site URL</label>
                        <input type="url" name="visit_site" id="visit_site" class="form-control" value="{{ old('visit_site', $broker->visit_site) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="open_live">Open Live Account</label>
                        <input type="url" name="open_live" id="open_live" class="form-control" value="{{ old('open_live', $broker->open_live) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="open_demo">Open Demo URL</label>
                        <input type="url" name="open_demo" id="open_demo" class="form-control" value="{{ old('open_demo', $broker->open_demo) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="demo_link">Demo Link (alternate)</label>
                        <input type="url" name="demo_link" id="demo_link" class="form-control" value="{{ old('demo_link', $broker->demo_link) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="demo_duration">Demo Duration</label>
                        <input type="text" name="demo_duration" id="demo_duration" class="form-control" value="{{ old('demo_duration', $broker->demo_duration) }}" placeholder="Unlimited, 30 days…">
                    </div>
                    <div class="col-md-4 form-group d-flex align-items-end">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="hidden" name="demo_account_available" value="0">
                            <input type="checkbox" class="custom-control-input" name="demo_account_available" id="demo_account_available" value="1" @checked(old('demo_account_available', $broker->demo_account_available))>
                            <label class="custom-control-label" for="demo_account_available">Demo account available</label>
                        </div>
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3 mt-2">Media</h6>
                <div class="row">
                    <div class="col-md-4">
                        @include('admin.partials._image_upload_preview', [
                            'inputId' => 'logo',
                            'previewId' => 'logo_preview',
                            'label' => 'Broker Logo',
                            'currentUrl' => $broker->logo ? asset($broker->logo) : null,
                        ])
                    </div>
                    <div class="col-md-4">
                        @include('admin.partials._image_upload_preview', [
                            'inputId' => 'banner_image_1',
                            'previewId' => 'banner_image_1_preview',
                            'label' => 'Banner Image 1',
                            'currentUrl' => $broker->banner_image_1 ? asset($broker->banner_image_1) : null,
                        ])
                    </div>
                    <div class="col-md-4">
                        @include('admin.partials._image_upload_preview', [
                            'inputId' => 'banner_image_2',
                            'previewId' => 'banner_image_2_preview',
                            'label' => 'Banner Image 2',
                            'currentUrl' => $broker->banner_image_2 ? asset($broker->banner_image_2) : null,
                        ])
                    </div>
                </div>

                <div class="form-group">
                    <label for="short_description">Short Description</label>
                    <textarea name="short_description" id="short_description" class="form-control snote" rows="4">{{ old('short_description', $broker->short_description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="top_feature">Top Feature Highlight</label>
                    <textarea name="top_feature" id="top_feature" class="form-control" rows="2">{{ old('top_feature', $broker->top_feature) }}</textarea>
                </div>

                <h6 class="text-primary font-weight-bold mb-2 mt-3">Broker Categories</h6>
                <p class="text-muted small mb-3">Used for <strong>Best Brokers → By Category</strong> listings.</p>
                <div class="form-group mb-4">
                    <div class="row">
                        @foreach($formOptions['brokerCategories'] as $value => $label)
                            <div class="col-md-6 col-lg-4">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="broker_categories[]" id="broker_category_{{ $value }}" value="{{ $value }}" @checked(in_array($value, $selectedBrokerCategories, true))>
                                    <label class="custom-control-label" for="broker_category_{{ $value }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('broker_categories')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    @error('broker_categories.*')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                <h6 class="text-primary font-weight-bold mb-2">Regions</h6>
                <div class="form-group mb-4">
                    <div class="row">
                        @foreach($formOptions['regions'] as $value => $label)
                            <div class="col-md-6 col-lg-4">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="regions[]" id="region_{{ $value }}" value="{{ $value }}" @checked(in_array($value, $selectedRegions, true))>
                                    <label class="custom-control-label" for="region_{{ $value }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('regions')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    @error('regions.*')<small class="text-danger d-block">{{ $message }}</small>@enderror
                </div>

                <h6 class="text-primary font-weight-bold mb-2">Country Listings</h6>
                <p class="text-muted small mb-3">Optional — country-specific best-broker pages.</p>
                <div class="form-group mb-0">
                    <div class="row">
                        @foreach($formOptions['countryListings'] as $value => $label)
                            <div class="col-md-6 col-lg-4">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="associated_countries[]" id="country_{{ $value }}" value="{{ $value }}" @checked(in_array($value, $selectedCountries, true))>
                                    <label class="custom-control-label" for="country_{{ $value }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Regulation --}}
    <div class="card tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="card-header tw-bg-slate-50 tw-border-b tw-border-slate-200/70 tw-px-6 tw-py-4" id="headingRegulation">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed tw-w-full tw-text-left tw-flex tw-items-center tw-gap-3 tw-font-extrabold tw-text-slate-900 hover:tw-underline" type="button" data-toggle="collapse" data-target="#collapseRegulation">
                    2. Regulation
                </button>
            </h5>
        </div>
        <div id="collapseRegulation" class="collapse" data-parent="#broker-accordion">
            <div class="card-body tw-px-6 tw-py-5">
                <p class="tw-mt-0 tw-mb-4 tw-text-xs tw-text-slate-600">
                    Licenses, fund protection, trust signals, and scam flags.
                </p>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="trust_score">Trust Score (1–99)</label>
                        <input type="number" min="1" max="99" name="trust_score" id="trust_score" class="form-control" value="{{ old('trust_score', $broker->trust_score) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="regulatory_tier">Regulatory Tier (1–5)</label>
                        <input type="number" min="1" max="5" name="regulatory_tier" id="regulatory_tier" class="form-control" value="{{ old('regulatory_tier', $broker->regulatory_tier) }}">
                        <small class="text-muted">1 = top-tier, 5 = offshore</small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Regulators</label>
                    <div class="row">
                        @foreach($formOptions['regulations'] as $value => $label)
                            <div class="col-md-4 col-lg-3">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="regulation[]" id="reg_{{ \Illuminate\Support\Str::slug($value) }}" value="{{ $value }}" @checked(in_array($value, $selectedRegulations, true))>
                                    <label class="custom-control-label" for="reg_{{ \Illuminate\Support\Str::slug($value) }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="regulated_jurisdictions">Regulated Jurisdictions</label>
                    <textarea name="regulated_jurisdictions" id="regulated_jurisdictions" class="form-control" rows="2">{{ old('regulated_jurisdictions', $broker->regulated_jurisdictions) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="regulatory_licenses">Regulatory Licenses</label>
                    <textarea name="regulatory_licenses" id="regulatory_licenses" class="form-control" rows="2">{{ old('regulatory_licenses', $broker->regulatory_licenses) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="capitalization">Capitalization</label>
                        <textarea name="capitalization" id="capitalization" class="form-control" rows="2">{{ old('capitalization', $broker->capitalization) }}</textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="insurance">Insurance / Compensation</label>
                        <textarea name="insurance" id="insurance" class="form-control" rows="2">{{ old('insurance', $broker->insurance) }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    @foreach([
                        'investor_protection' => 'Investor protection',
                        'segregation_of_funds' => 'Segregated funds',
                        'negative_balance_protection' => 'Negative balance protection',
                    ] as $field => $label)
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="{{ $field }}" value="0">
                                <input type="checkbox" class="custom-control-input" name="{{ $field }}" id="{{ $field }}" value="1" @checked(old($field, $broker->{$field}))>
                                <label class="custom-control-label" for="{{ $field }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h6 class="text-primary font-weight-bold mb-3 mt-2">Scam Flag</h6>
                <div class="custom-control custom-checkbox mb-3">
                    <input type="hidden" name="is_scam" value="0">
                    <input type="checkbox" class="custom-control-input" name="is_scam" id="is_scam" value="1" @checked(old('is_scam', $broker->is_scam))>
                    <label class="custom-control-label text-danger" for="is_scam">Mark as scam / high-risk</label>
                </div>
                <div class="form-group">
                    <label for="scam_reason">Scam Reason</label>
                    <textarea name="scam_reason" id="scam_reason" class="form-control" rows="2">{{ old('scam_reason', $broker->scam_reason) }}</textarea>
                </div>
                <div class="form-group mb-0">
                    <label for="scam_reported_date">Scam Reported Date</label>
                    <input type="date" name="scam_reported_date" id="scam_reported_date" class="form-control"
                           value="{{ old('scam_reported_date', optional($broker->scam_reported_date)->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Trading --}}
    <div class="card tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="card-header tw-bg-slate-50 tw-border-b tw-border-slate-200/70 tw-px-6 tw-py-4" id="headingTrading">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed tw-w-full tw-text-left tw-flex tw-items-center tw-gap-3 tw-font-extrabold tw-text-slate-900 hover:tw-underline" type="button" data-toggle="collapse" data-target="#collapseTrading">
                    3. Trading
                </button>
            </h5>
        </div>
        <div id="collapseTrading" class="collapse" data-parent="#broker-accordion">
            <div class="card-body tw-px-6 tw-py-5">
                <p class="tw-mt-0 tw-mb-4 tw-text-xs tw-text-slate-600">
                    Costs, platforms, markets, payments, and trading tools.
                </p>

                <h6 class="text-primary font-weight-bold mb-3">Trading Conditions</h6>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="minimum_deposit">Min. Deposit ($)</label>
                        <input type="number" step="0.01" min="0" name="minimum_deposit" id="minimum_deposit" class="form-control" value="{{ old('minimum_deposit', $broker->minimum_deposit) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="spreads">Spreads</label>
                        <input type="text" name="spreads" id="spreads" class="form-control" value="{{ old('spreads', $broker->spreads) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="leverage">Leverage</label>
                        <input type="text" name="leverage" id="leverage" class="form-control" value="{{ old('leverage', $broker->leverage) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="commission">Commission</label>
                        <input type="text" name="commission" id="commission" class="form-control" value="{{ old('commission', $broker->commission) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="fee_level">Fee Level</label>
                        <select name="fee_level" id="fee_level" class="form-control">
                            <option value="">— Select —</option>
                            @foreach($formOptions['feeLevels'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('fee_level', $broker->fee_level) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="withdrawal_fee">Withdrawal Fee</label>
                        <input type="text" name="withdrawal_fee" id="withdrawal_fee" class="form-control" value="{{ old('withdrawal_fee', $broker->withdrawal_fee) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="instrument_count">Instrument Count</label>
                        <input type="number" min="0" name="instrument_count" id="instrument_count" class="form-control" value="{{ old('instrument_count', $broker->instrument_count) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pricing">Pricing &amp; Fees (detail)</label>
                    <textarea name="pricing" id="pricing" class="form-control" rows="3">{{ old('pricing', $broker->pricing) }}</textarea>
                </div>

                <h6 class="text-primary font-weight-bold mb-3">Platforms &amp; Markets</h6>
                <div class="form-group">
                    <label>Trading Platforms</label>
                    <div class="row">
                        @foreach($formOptions['platforms'] as $value => $label)
                            <div class="col-md-4 col-lg-3">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="platforms[]" id="platform_{{ \Illuminate\Support\Str::slug($value) }}" value="{{ $value }}" @checked(in_array($value, $selectedPlatforms, true))>
                                    <label class="custom-control-label" for="platform_{{ \Illuminate\Support\Str::slug($value) }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label>Markets / Asset Classes</label>
                    <div class="row">
                        @foreach($formOptions['markets'] as $value => $label)
                            <div class="col-md-4 col-lg-3">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" name="markets[]" id="market_{{ $value }}" value="{{ $value }}" @checked(in_array($value, $selectedMarkets, true))>
                                    <label class="custom-control-label" for="market_{{ $value }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label for="account_types_combined">Account Type Labels</label>
                    <input type="text" name="account_types_combined" id="account_types_combined" class="form-control"
                           value="{{ old('account_types_combined', implode(', ', $selectedAccountTypes)) }}"
                           placeholder="Standard, Raw ECN, Pro…">
                    <small class="text-muted">Optional labels shown on the review page (separate from listing categories).</small>
                </div>

                <h6 class="text-primary font-weight-bold mb-3">Payment Methods</h6>
                <div class="form-group">
                    <label for="deposit_methods">Deposit Methods</label>
                    <textarea name="deposit_methods" id="deposit_methods" class="form-control" rows="2">{{ old('deposit_methods', $broker->deposit_methods) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="withdrawal_method">Withdrawal Methods</label>
                    <textarea name="withdrawal_method" id="withdrawal_method" class="form-control" rows="2">{{ old('withdrawal_method', $broker->withdrawal_method) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="payment_methods">Payment Summary</label>
                    <textarea name="payment_methods" id="payment_methods" class="form-control" rows="2">{{ old('payment_methods', $broker->payment_methods) }}</textarea>
                </div>

                <h6 class="text-primary font-weight-bold mb-3">Tools &amp; Support</h6>
                @foreach([
                    'mobile_trading' => 'Mobile Trading',
                    'web_trader' => 'Web Trader',
                    'charting_tools' => 'Charting Tools',
                    'social_trading' => 'Social / Copy Trading',
                    'customer_support' => 'Customer Support',
                    'educational_resources' => 'Educational Resources',
                    'research_tools' => 'Research Tools',
                    'news_and_analysis' => 'News & Analysis',
                ] as $field => $label)
                    <div class="form-group">
                        <label for="{{ $field }}">{{ $label }}</label>
                        <textarea name="{{ $field }}" id="{{ $field }}" class="form-control" rows="2">{{ old($field, $broker->{$field}) }}</textarea>
                    </div>
                @endforeach
                <div class="row">
                    @foreach(['vps_hosting' => 'VPS Hosting', 'economic_calendar' => 'Economic Calendar', 'account_managers' => 'Account Managers'] as $field => $label)
                        <div class="col-md-4">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="hidden" name="{{ $field }}" value="0">
                                <input type="checkbox" class="custom-control-input" name="{{ $field }}" id="{{ $field }}" value="1" @checked(old($field, $broker->{$field}))>
                                <label class="custom-control-label" for="{{ $field }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 4. SEO --}}
    <div class="card tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="card-header tw-bg-slate-50 tw-border-b tw-border-slate-200/70 tw-px-6 tw-py-4" id="headingSeo">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed tw-w-full tw-text-left tw-flex tw-items-center tw-gap-3 tw-font-extrabold tw-text-slate-900 hover:tw-underline" type="button" data-toggle="collapse" data-target="#collapseSeo">
                    4. SEO
                </button>
            </h5>
        </div>
        <div id="collapseSeo" class="collapse" data-parent="#broker-accordion">
            <div class="card-body tw-px-6 tw-py-5">
                <p class="tw-mt-0 tw-mb-4 tw-text-xs tw-text-slate-600">
                    Meta fields for search previews on broker review pages.
                </p>
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $broker->meta_title) }}">
                </div>
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" class="form-control" rows="2">{{ old('meta_description', $broker->meta_description) }}</textarea>
                </div>
                <div class="form-group mb-0">
                    <label for="meta_keyword">Meta Keywords</label>
                    <textarea name="meta_keyword" id="meta_keyword" class="form-control" rows="2">{{ old('meta_keyword', $broker->meta_keyword) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. Publish --}}
    <div class="card tw-bg-white tw-rounded-2xl tw-border tw-border-slate-200/70 tw-overflow-hidden">
        <div class="card-header tw-bg-slate-50 tw-border-b tw-border-slate-200/70 tw-px-6 tw-py-4" id="headingPublish">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed tw-w-full tw-text-left tw-flex tw-items-center tw-gap-3 tw-font-extrabold tw-text-slate-900 hover:tw-underline" type="button" data-toggle="collapse" data-target="#collapsePublish">
                    5. Publish
                </button>
            </h5>
        </div>
        <div id="collapsePublish" class="collapse" data-parent="#broker-accordion">
            <div class="card-body tw-px-6 tw-py-5">
                <p class="tw-mt-0 tw-mb-4 tw-text-xs tw-text-slate-600">
                    Visibility, scores, editorial review copy, and credit assignments.
                </p>

                <h6 class="text-primary font-weight-bold mb-3">Visibility</h6>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="top_broker">Top Broker Rank</label>
                        <input type="number" name="top_broker" id="top_broker" class="form-control" min="0" max="100" value="{{ old('top_broker', $broker->top_broker) }}">
                    </div>
                    <div class="col-md-4 form-group d-flex align-items-end">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="hidden" name="featured_broker" value="0">
                            <input type="checkbox" class="custom-control-input" name="featured_broker" id="featured_broker" value="1" @checked(old('featured_broker', $broker->featured_broker))>
                            <label class="custom-control-label" for="featured_broker">Featured broker</label>
                        </div>
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3">Scores</h6>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="rating">Overall Rating (0–5)</label>
                        <input type="number" step="0.01" min="0" max="5" name="rating" id="rating" class="form-control" value="{{ old('rating', $broker->rating) }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Category Scores (0–10)</label>
                    <div class="row">
                        @foreach($formOptions['categoryScores'] as $key => $label)
                            <div class="col-md-4 form-group">
                                <label for="category_scores_{{ $key }}" class="small">{{ $label }}</label>
                                <input type="number" step="0.1" min="0" max="10" class="form-control form-control-sm"
                                       name="category_scores[{{ $key }}]" id="category_scores_{{ $key }}"
                                       value="{{ old('category_scores.' . $key, $categoryScores[$key] ?? '') }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3">Review Content</h6>
                <div class="form-group">
                    <label for="verdict">Verdict</label>
                    <textarea name="verdict" id="verdict" class="form-control" rows="3">{{ old('verdict', $broker->verdict) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="pros">Pros</label>
                        <textarea name="pros" id="pros" class="form-control snote" rows="6">{{ old('pros', $broker->pros) }}</textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="cons">Cons</label>
                        <textarea name="cons" id="cons" class="form-control snote" rows="6">{{ old('cons', $broker->cons) }}</textarea>
                    </div>
                </div>

                @include('admin.partials._editorial_fields', ['model' => $broker, 'editorialOptions' => $formOptions['editorialOptions'] ?? null])
            </div>
        </div>
    </div>
</div>

<div class="tw-pt-2 tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center sm:tw-justify-between tw-gap-3">
    <div class="tw-flex tw-items-center tw-gap-3">
        <button type="submit"
                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-brand tw-text-white tw-px-5 tw-py-2.5 tw-text-sm tw-font-bold hover:tw-bg-brand/90">
            <i class="fas fa-save"></i>
            {{ $isEdit ? 'Update Broker' : 'Create Broker' }}
        </button>

        @if($isEdit)
            <a href="{{ route('admin_account_options_index', $broker->id) }}"
               class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-text-slate-800 tw-px-4 tw-py-2.5 tw-text-sm tw-font-semibold hover:tw-bg-slate-50">
                <i class="fas fa-layer-group tw-text-slate-400"></i>
                Account Options
            </a>
        @endif
    </div>

    <a href="{{ route('admin_broker_show') }}"
       class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-bg-slate-50 tw-border tw-border-slate-200 tw-text-slate-700 tw-px-4 tw-py-2.5 tw-text-sm tw-font-semibold hover:tw-bg-slate-100">
        Cancel
    </a>
</div>
