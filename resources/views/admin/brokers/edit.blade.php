@extends('admin.layout.app')
@section('heading', 'Edit Broker')
@section('button')
@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin_broker_update', $broker->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Broker Name Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Broker Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ old('name', $broker->name) }}">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Broker URL Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="url">Broker URL</label>
                            <input type="url" name="url" id="url" class="form-control" required
                                value="{{ old('url', $broker->url) }}">
                            @error('url')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Slug -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                value="{{ old('slug', $broker->slug ?? '') }}">
                            @error('slug')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Title Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                value="{{ old('title', $broker->title ?? '') }}">
                            @error('title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Rating Field -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <input type="number" name="rating" id="rating" class="form-control" step="0.01" min="0" max="5"
                                value="{{ old('rating', $broker->rating ?? '') }}">
                            @error('rating')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Logo Upload (Optional) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="logo">Logo (Optional)</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                            @if($broker->logo)
                            <p>Current Logo:</p>
                            <img src="{{ asset('storage/' . $broker->logo) }}" alt="Current Broker Logo" width="100">
                            @endif
                            @error('logo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Short Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea name="short_description" id="short_description" class="form-control snote"
                                    rows="3">{{ old('short_description', $broker->short_description) }}</textarea>
                                @error('short_description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Visit Site -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="visit_site">Visit Site (Optional)</label>
                            <input type="url" name="visit_site" id="visit_site" class="form-control"
                                value="{{ old('visit_site', $broker->visit_site) }}">
                            @error('visit_site')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Open Live Account -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="open_live">Open Live Account (Optional)</label>
                            <input type="url" name="open_live" id="open_live" class="form-control"
                                value="{{ old('open_live', $broker->open_live) }}">
                            @error('open_live')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Open Demo Account -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="open_demo">Open Demo Account (Optional)</label>
                            <input type="url" name="open_demo" id="open_demo" class="form-control"
                                value="{{ old('open_demo', $broker->open_demo) }}">
                            @error('open_demo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Pros -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pros">Pros</label>
                            <textarea name="pros" id="pros" class="form-control snote" rows="3">{{ old('pros', $broker->pros) }}</textarea>
                            @error('pros')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Cons -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cons">Cons</label>
                            <textarea name="cons" id="cons" class="form-control snote" rows="3">{{ old('cons', $broker->cons) }}</textarea>
                            @error('cons')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Languages -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="languages">Languages</label>
                            <textarea name="languages" id="languages" class="form-control snote">{{ old('languages', $broker->languages) }}</textarea>
                            @error('languages')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Pricing Detail -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pricing">Pricing</label>
                            <textarea name="pricing" id="pricing" class="form-control snote">{{ old('pricing', $broker->pricing) }}</textarea>
                            @error('pricing')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Deposit Methods -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="deposit_methods">Deposit Methods</label>
                            <textarea name="deposit_methods" id="deposit_methods" class="form-control snote">{{ old('deposit_methods', $broker->deposit_methods) }}</textarea>
                            @error('deposit_methods')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Withdrawal Method -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="withdrawal_method">Withdrawal Method</label>
                            <textarea name="withdrawal_method" id="withdrawal_method" class="form-control snote">{{ old('withdrawal_method', $broker->withdrawal_method) }}</textarea>
                            @error('withdrawal_method')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Regulation -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regulation">Regulation</label>
                            <textarea name="regulation" id="regulation" class="form-control snote" rows="3">{{ old('regulation', $broker->regulation) }}</textarea>
                            @error('regulation')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Regulated Jurisdictions -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regulated_jurisdictions">Regulated Jurisdictions</label>
                            <textarea name="regulated_jurisdictions" id="regulated_jurisdictions" class="form-control snote" rows="3">{{ old('regulated_jurisdictions', $broker->regulated_jurisdictions) }}</textarea>
                            @error('regulated_jurisdictions')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Regulatory Licenses -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="regulatory_licenses">Regulatory Licenses</label>
                            <textarea name="regulatory_licenses" id="regulatory_licenses" class="form-control snote" rows="3">{{ old('regulatory_licenses', $broker->regulatory_licenses) }}</textarea>
                            @error('regulatory_licenses')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Platforms -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="platforms">Platforms</label>
                            <textarea name="platforms" id="platforms" class="form-control snote">{{ old('platforms', $broker->platforms) }}</textarea>
                            @error('platforms')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Payment Methods -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_methods">Payment Methods</label>
                            <textarea name="payment_methods" id="payment_methods" class="form-control snote">{{ old('payment_methods', $broker->payment_methods) }}</textarea>
                            @error('payment_methods')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Customer Support -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_support">Customer Support</label>
                            <textarea name="customer_support" id="customer_support" class="form-control snote">{{ old('customer_support', $broker->customer_support) }}</textarea>
                            @error('customer_support')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Educational Resources -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="educational_resources">Educational Resources</label>
                            <textarea name="educational_resources" id="educational_resources" class="form-control snote">{{ old('educational_resources', $broker->educational_resources) }}</textarea>
                            @error('educational_resources')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Research Tools -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="research_tools">Research Tools</label>
                            <textarea name="research_tools" id="research_tools" class="form-control snote">{{ old('research_tools', $broker->research_tools) }}</textarea>
                            @error('research_tools')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Capitalization -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="capitalization">Capitalization</label>
                            <textarea name="capitalization" id="capitalization" class="form-control snote">{{ old('capitalization', $broker->capitalization) }}</textarea>
                            @error('capitalization')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Insurance -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="insurance">Insurance</label>
                            <textarea name="insurance" id="insurance" class="form-control snote">{{ old('insurance', $broker->insurance) }}</textarea>
                            @error('insurance')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Country -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control" required
                                value="{{ old('country', $broker->country) }}">
                            @error('country')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Minimum Deposit -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="minimum_deposit">Minimum Deposit</label>
                            <input type="number" name="minimum_deposit" id="minimum_deposit" class="form-control"
                                value="{{ old('minimum_deposit', $broker->minimum_deposit) }}">
                            @error('minimum_deposit')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Spreads -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="spreads">Spreads</label>
                            <input type="text" name="spreads" id="spreads" class="form-control"
                                value="{{ old('spreads', $broker->spreads) }}">
                            @error('spreads')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Leverage -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="leverage">Leverage</label>
                            <input type="text" name="leverage" id="leverage" class="form-control"
                                value="{{ old('leverage', $broker->leverage ?? '') }}">
                            @error('leverage')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Mobile Trading -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile_trading">Mobile Trading</label>
                            <input type="text" name="mobile_trading" id="mobile_trading" class="form-control"
                                value="{{ old('mobile_trading', $broker->mobile_trading) }}">
                            @error('mobile_trading')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Social Trading -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="social_trading">Social Trading</label>
                            <input type="text" name="social_trading" id="social_trading" class="form-control"
                                value="{{ old('social_trading', $broker->social_trading) }}">
                            @error('social_trading')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Account Types -->
                    <div class="col-md-4">
                    <h4>Select Account Types:</h4>
                        @php
                            // Decode the JSON field to an array for pre-checking
                            $selectedAccountTypes = json_decode($broker->account_types, true) ?? [];
                        @endphp

                        @foreach ([
                            'Standard Accounts',
                            'Islamic Account',
                            'ECN Accounts',
                            'Classic Account',
                            'Copy Trading Accounts',
                            'VIP Accounts',
                            'Raw Account',
                            'Micro Accounts'
                        ] as $type)
                            <label>
                                <input type="checkbox" name="account_types[]" value="{{ $type }}"
                                    {{ in_array($type, $selectedAccountTypes) ? 'checked' : '' }}>
                                {{ $type }}
                            </label>
                            <br>
                        @endforeach
                    </div>
                    <!-- Segregation of Funds (Boolean) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="segregation_of_funds">Segregation of Funds</label>
                            <select name="segregation_of_funds" id="segregation_of_funds" class="form-control">
                                <option value="1"
                                    {{ old('segregation_of_funds', $broker->segregation_of_funds) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('segregation_of_funds', $broker->segregation_of_funds) == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('segregation_of_funds')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Web Trader -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="web_trader">Web Trader</label>
                            <input type="text" name="web_trader" id="web_trader" class="form-control"
                                value="{{ old('web_trader', $broker->web_trader) }}">
                            @error('web_trader')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Account Managers (Boolean) -->
                     <div class="col-md-4">
                        <div class="form-group">
                            <label for="account_managers">Account Managers</label>
                            <select name="account_managers" id="account_managers" class="form-control">
                                <option value="1"
                                    {{ old('account_managers', $broker->account_managers) == '1' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="0"
                                    {{ old('account_managers', $broker->account_managers) == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('account_managers')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                     </div>
                    <!-- Economic Calendar (Boolean) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="economic_calendar">Economic Calendar</label>
                            <select name="economic_calendar" id="economic_calendar" class="form-control">
                                <option value="1"
                                    {{ old('economic_calendar', $broker->economic_calendar) == '1' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="0"
                                    {{ old('economic_calendar', $broker->economic_calendar) == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('economic_calendar')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>   
                    <!-- Charting Tools -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="charting_tools">Charting Tools</label>
                            <textarea name="charting_tools" id="charting_tools"
                                class="form-control snote">{{ old('charting_tools', $broker->charting_tools) }}</textarea>
                            @error('charting_tools')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                     <!-- News and Analysis -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="news_and_analysis">News and Analysis</label>
                            <textarea name="news_and_analysis" id="news_and_analysis"
                                class="form-control snote">{{ old('news_and_analysis', $broker->news_and_analysis) }}</textarea>
                            @error('news_and_analysis')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Associated Countries -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="associated_countries">Associated Countries</label>

                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Asia"
                                        {{ in_array('Asia', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    Asian Broker
                                </label>
                            </div>

                            <!-- Checkbox for USA -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="USA"
                                        {{ in_array('USA', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    USA
                                </label>
                            </div>

                            <!-- Checkbox for Canada -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Canada"
                                        {{ in_array('Canada', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    Canada
                                </label>
                            </div>

                            <!-- Checkbox for UK -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="UK"
                                        {{ in_array('UK', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    UK
                                </label>
                            </div>

                            <!-- Checkbox for Australia -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Australia"
                                        {{ in_array('Australia', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    Australia
                                </label>
                            </div>

                            <!-- Checkbox for South Africa -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="South Africa"
                                        {{ in_array('South Africa', old('associated_countries', $broker->associated_countries ?? [])) ? 'checked' : '' }}>
                                    South Africa
                                </label>
                            </div>

                            <!-- Add more countries in similar fashion -->

                            @error('associated_countries')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- VPS Hosting (Boolean) -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="vps_hosting">VPS Hosting</label>
                            <select name="vps_hosting" id="vps_hosting" class="form-control">
                                <option value="1" {{ old('vps_hosting', $broker->vps_hosting) == '1' ? 'selected' : '' }}>Yes
                                </option>
                                <option value="0" {{ old('vps_hosting', $broker->vps_hosting) == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('vps_hosting')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Featured Broker -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="featured_broker">Featured Broker</label>
                            <div class="form-check">
                                <!-- Hidden input to ensure a value of 0 is sent if the checkbox is not checked -->
                                <input type="hidden" name="featured_broker" value="0">
                                <!-- Checkbox input for 'featured_broker' -->
                                <input type="checkbox" name="featured_broker" id="featured_broker" class="form-check-input"
                                    value="1" {{ old('featured_broker', $broker->featured_broker) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured_broker">Check if this broker is featured</label>
                            </div>
                            <!-- Display error message if validation fails -->
                            @error('featured_broker')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Top Broker Number -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="top_broker">Top Broker Number</label>
                            <input type="number" name="top_broker" id="top_broker" class="form-control"
                                value="{{ old('top_broker', $broker->top_broker ?? '') }}">
                            @error('top_broker')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Meta Title -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                value="{{ old('meta_title', $broker->meta_title ?? '') }}">
                            @error('meta_title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Meta Keyword -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_keyword">Meta Keyword</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                value="{{ old('meta_keyword', $broker->meta_keyword ?? '') }}">
                            @error('meta_keyword')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Top Feature -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="top_feature">Top Feature</label>
                            <textarea name="top_feature" id="top_feature"
                                class="form-control snote">{{ old('top_feature', $broker->top_feature ?? '') }}</textarea>
                            @error('top_feature')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Meta Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description"
                                class="form-control snote">{{ old('meta_description', $broker->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Broker</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection