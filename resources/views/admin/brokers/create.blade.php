@extends('admin.layout.app')

@section('heading', 'Add Broker')
@section('button')
<!-- In create.blade.php -->



@endsection

@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin_broker_store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <!-- Broker Name -->
                        <div class="form-group">
                            <label for="name">Broker Name</label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Broker URL -->
                        <div class="form-group">
                            <label for="url">Broker URL</label>
                            <input type="url" name="url" id="url" class="form-control" required value="{{ old('url') }}">
                            @error('url')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div> 
                    <div class="col-md-4">
                         <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                value="{{ old('slug', $broker->slug ?? '') }}">
                            @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Title Field -->
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required value="{{ old('title') }}">
                            @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Rating Field -->
                        <div class="form-group">
                            <label for="rating">Rating</label>
                            <input type="number" name="rating" id="rating" class="form-control" step="0.01" min="0" max="5"
                                value="{{ old('rating') }}">
                            @error('rating')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Logo -->
                        <div class="form-group">
                            <label for="logo">Logo (Optional)</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                            @error('logo')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <!-- Preview Image Section -->
                        <div class="form-group" id="logo-preview-container" style="display: none;">
                            <label>Preview</label>
                            <img id="logo-preview" src="" alt="Logo Preview" style="max-width: 200px; max-height: 200px;">
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('#logo').change(function() {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        $('#logo-preview').attr('src', e.target.result);
                                        $('#logo-preview-container').show();
                                    };
                                    if (this.files[0]) {
                                        reader.readAsDataURL(this.files[0]);
                                    } else {
                                        $('#logo-preview-container').hide();
                                    }
                                });
                            });
                        </script>

                    </div>
                    <div class="col-md-12">
                        <!-- Short Description -->
                        <div class="form-group">
                            <label for="short_description">Short Description</label>
                            <textarea name="short_description" id="short_description" class="form-control snote"
                                rows="3">{{ old('short_description') }}</textarea>
                            @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                          <!-- Visit Site (Optional) -->
                        <div class="form-group">
                            <label for="visit_site">Visit Site (Optional)</label>
                            <input type="url" name="visit_site" id="visit_site" class="form-control"
                                value="{{ old('visit_site') }}">
                            @error('visit_site')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Open Live Account (Optional) -->
                        <div class="form-group">
                            <label for="open_live">Open Live Account (Optional)</label>
                            <input type="url" name="open_live" id="open_live" class="form-control"
                                value="{{ old('open_live') }}">
                            @error('open_live')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Open Demo Account (Optional) -->
                        <div class="form-group">
                            <label for="open_demo">Open Demo Account (Optional)</label>
                            <input type="url" name="open_demo" id="open_demo" class="form-control"
                                value="{{ old('open_demo') }}">
                            @error('open_demo')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Pros -->
                        <div class="form-group">
                            <label for="pros">Pros</label>
                            <textarea name="pros" id="pros" class="form-control snote" rows="3">{{ old('pros') }}</textarea>
                            @error('pros')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Cons -->
                        <div class="form-group">
                            <label for="cons">Cons</label>
                            <textarea name="cons" id="cons" class="form-control snote" rows="3">{{ old('cons') }}</textarea>
                            @error('cons')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                    <!-- Languages -->
                        <div class="form-group">
                            <label for="languages">Languages</label>
                            <textarea name="languages" id="languages"
                                class="form-control snote">{{ old('languages') }}</textarea>
                            @error('languages')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Pricing -->
                        <div class="form-group">
                            <label for="pricing">Pricing</label>
                            <textarea name="pricing" id="pricing" class="form-control snote">{{ old('pricing') }}</textarea>
                            @error('pricing')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                          <!-- Deposit Methods -->
                        <div class="form-group">
                            <label for="deposit_methods">Deposit Methods</label>
                            <textarea name="deposit_methods" id="deposit_methods"
                                class="form-control snote">{{ old('deposit_methods') }}</textarea>
                            @error('deposit_methods')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Withdrawal Method -->
                        <div class="form-group">
                            <label for="withdrawal_method">Withdrawal Method</label>
                            <textarea name="withdrawal_method" id="withdrawal_method"
                                class="form-control snote">{{ old('withdrawal_method') }}</textarea>
                            @error('withdrawal_method')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                          <!-- Regulation -->
                        <div class="form-group">
                            <label for="regulation">Regulation</label>
                            <textarea name="regulation" id="regulation" class="form-control snote"
                                rows="3">{{ old('regulation') }}</textarea>
                            @error('regulation')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Regulated Jurisdictions -->
                        <div class="form-group">
                            <label for="regulated_jurisdictions">Regulated Jurisdictions</label>
                            <textarea name="regulated_jurisdictions" id="regulated_jurisdictions" class="form-control snote"
                                rows="3">{{ old('regulated_jurisdictions') }}</textarea>
                            @error('regulated_jurisdictions')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                         <!-- Regulatory Licenses -->
                        <div class="form-group">
                            <label for="regulatory_licenses">Regulatory Licenses</label>
                            <textarea name="regulatory_licenses" id="regulatory_licenses" class="form-control snote"
                                rows="3">{{ old('regulatory_licenses') }}</textarea>
                            @error('regulatory_licenses')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                         <!-- Platforms -->
                        <div class="form-group">
                            <label for="platforms">Platforms</label>
                            <textarea name="platforms" id="platforms"
                                class="form-control snote">{{ old('platforms') }}</textarea>
                            @error('platforms')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                    <!-- Payment Methods -->
                        <div class="form-group">
                            <label for="payment_methods">Payment Methods</label>
                            <textarea name="payment_methods" id="payment_methods"
                                class="form-control snote">{{ old('payment_methods') }}</textarea>
                            @error('payment_methods')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Customer Support -->
                        <div class="form-group">
                            <label for="customer_support">Customer Support</label>
                            <textarea name="customer_support" id="customer_support"
                                class="form-control snote">{{ old('customer_support') }}</textarea>
                            @error('customer_support')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                          <!-- Educational Resources -->
                        <div class="form-group">
                            <label for="educational_resources">Educational Resources</label>
                            <textarea name="educational_resources" id="educational_resources"
                                class="form-control snote">{{ old('educational_resources') }}</textarea>
                            @error('educational_resources')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Research Tools -->
                        <div class="form-group">
                            <label for="research_tools">Research Tools</label>
                            <textarea name="research_tools" id="research_tools"
                                class="form-control snote">{{ old('research_tools') }}</textarea>
                            @error('research_tools')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Capitalization -->
                        <div class="form-group">
                            <label for="capitalization">Capitalization</label>
                            <textarea name="capitalization" id="capitalization"
                                class="form-control snote">{{ old('capitalization') }}</textarea>
                            @error('capitalization')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Insurance -->
                        <div class="form-group">
                            <label for="insurance">Insurance</label>
                            <textarea name="insurance" id="insurance"
                                class="form-control snote">{{ old('insurance') }}</textarea>
                            @error('insurance')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Country -->
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control" required
                                value="{{ old('country') }}">
                            @error('country')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!-- Minimum Deposit -->
                        <div class="form-group">
                            <label for="minimum_deposit">Minimum Deposit</label>
                            <input type="number" name="minimum_deposit" id="minimum_deposit" class="form-control"
                                value="{{ old('minimum_deposit') }}">
                            @error('minimum_deposit')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!-- Spreads -->
                        <div class="form-group">
                            <label for="spreads">Spreads</label>
                            <input type="text" name="spreads" id="spreads" class="form-control" value="{{ old('spreads') }}">
                            @error('spreads')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                          <!-- Leverage -->
                        <div class="form-group">
                            <label for="leverage">Leverage</label>
                            <input type="text" name="leverage" id="leverage" class="form-control"
                                value="{{ old('leverage', $broker->leverage ?? '') }}">
                            @error('leverage')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Mobile Trading -->
                        <div class="form-group">
                            <label for="mobile_trading">Mobile Trading</label>
                            <input type="text" name="mobile_trading" id="mobile_trading" class="form-control"
                                value="{{ old('mobile_trading') }}">
                            @error('mobile_trading')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Social Trading -->
                        <div class="form-group">
                            <label for="social_trading">Social Trading</label>
                            <input type="text" name="social_trading" id="social_trading" class="form-control"
                                value="{{ old('social_trading') }}">
                            @error('social_trading')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Account Types -->
                        <h4>Select Account Types:</h4>
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
                                    {{ in_array($type, old('account_types', [])) ? 'checked' : '' }}>
                                {{ $type }}
                            </label>
                            <br>
                        @endforeach
                    </div>

                    <div class="col-md-4">
                        <!-- Segregation of Funds (Boolean) -->
                        <div class="form-group">
                            <label for="segregation_of_funds">Segregation of Funds</label>
                            <select name="segregation_of_funds" id="segregation_of_funds" class="form-control">
                                <option value="1" {{ old('segregation_of_funds') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('segregation_of_funds') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('segregation_of_funds')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Web Trader -->
                        <div class="form-group">
                            <label for="web_trader">Web Trader</label>
                            <input type="text" name="web_trader" id="web_trader" class="form-control"
                                value="{{ old('web_trader') }}">
                            @error('web_trader')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                            <!-- Account Managers (Boolean) -->
                        <div class="form-group">
                            <label for="account_managers">Account Managers</label>
                            <select name="account_managers" id="account_managers" class="form-control">
                                <option value="1" {{ old('account_managers') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('account_managers') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('account_managers')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Economic Calendar (Boolean) -->
                        <div class="form-group">
                            <label for="economic_calendar">Economic Calendar</label>
                            <select name="economic_calendar" id="economic_calendar" class="form-control">
                                <option value="1" {{ old('economic_calendar') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('economic_calendar') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('economic_calendar')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Charting Tools -->
                        <div class="form-group">
                            <label for="charting_tools">Charting Tools</label>
                            <textarea name="charting_tools" id="charting_tools"
                                class="form-control snote">{{ old('charting_tools') }}</textarea>
                            @error('charting_tools')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- News and Analysis -->
                        <div class="form-group">
                            <label for="news_and_analysis">News and Analysis</label>
                            <textarea name="news_and_analysis" id="news_and_analysis"
                                class="form-control snote">{{ old('news_and_analysis') }}</textarea>
                            @error('news_and_analysis')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Associated Countries -->
                        <div class="form-group">
                            <label for="associated_countries">Associated Countries</label>

                            <!-- Checkbox for Asian -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Asia"
                                        {{ in_array('Asia', old('associated_countries', [])) ? 'checked' : '' }}> Asian Brokers
                                </label>
                            </div>

                            <!-- Checkbox for USA -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="USA"
                                        {{ in_array('USA', old('associated_countries', [])) ? 'checked' : '' }}> USA
                                </label>
                            </div>

                            <!-- Checkbox for Canada -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Canada"
                                        {{ in_array('Canada', old('associated_countries', [])) ? 'checked' : '' }}> Canada
                                </label>
                            </div>

                            <!-- Checkbox for UK -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="UK"
                                        {{ in_array('UK', old('associated_countries', [])) ? 'checked' : '' }}> UK
                                </label>
                            </div>

                            <!-- Checkbox for Australia -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Australia"
                                        {{ in_array('Australia', old('associated_countries', [])) ? 'checked' : '' }}> Australia
                                </label>
                            </div>

                            <!-- Checkbox for South Africa -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="South Africa"
                                        {{ in_array('South Africa', old('associated_countries', [])) ? 'checked' : '' }}> South Africa
                                </label>
                            </div>

                            <!-- Checkbox for Germany -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Germany"
                                        {{ in_array('Germany', old('associated_countries', [])) ? 'checked' : '' }}> Germany
                                </label>
                            </div>

                            <!-- Checkbox for France -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="France"
                                        {{ in_array('France', old('associated_countries', [])) ? 'checked' : '' }}> France
                                </label>
                            </div>

                            <!-- Checkbox for India -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="India"
                                        {{ in_array('India', old('associated_countries', [])) ? 'checked' : '' }}> India
                                </label>
                            </div>

                            <!-- Checkbox for China -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="China"
                                        {{ in_array('China', old('associated_countries', [])) ? 'checked' : '' }}> China
                                </label>
                            </div>

                            <!-- Checkbox for Japan -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Japan"
                                        {{ in_array('Japan', old('associated_countries', [])) ? 'checked' : '' }}> Japan
                                </label>
                            </div>

                            <!-- Checkbox for Brazil -->
                            <div>
                                <label>
                                    <input type="checkbox" name="associated_countries[]" value="Brazil"
                                        {{ in_array('Brazil', old('associated_countries', [])) ? 'checked' : '' }}> Brazil
                                </label>
                            </div>

                            @error('associated_countries')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>


                    <div class="col-md-3">
                        <!-- VPS Hosting (Boolean) -->
                        <div class="form-group">
                            <label for="vps_hosting">VPS Hosting</label>
                            <select name="vps_hosting" id="vps_hosting" class="form-control">
                                <option value="1" {{ old('vps_hosting') == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('vps_hosting') == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('vps_hosting')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                         <!-- Featured Broker -->
                        <div class="form-group">
                            <label for="featured_broker">Featured Broker</label>
                            <div class="form-check">
                                <input type="hidden" name="featured_broker" value="0">
                                <!-- Hidden input to ensure a value is always sent -->
                                <input type="checkbox" name="featured_broker" id="featured_broker" class="form-check-input"
                                    value="1" {{ old('featured_broker', $broker->featured_broker ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured_broker">Check if this broker is featured</label>
                            </div>
                            @error('featured_broker')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Top Broker Number -->
                        <div class="form-group">
                            <label for="top_broker">Top Broker Number</label>
                            <input type="number" name="top_broker" id="top_broker" class="form-control"
                                value="{{ old('top_broker', $broker->top_broker ?? '') }}">
                            @error('top_broker')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Meta Title -->
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control"
                                value="{{ old('meta_title', $broker->meta_title ?? '') }}">
                            @error('meta_title')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Meta Keyword -->
                        <div class="form-group">
                            <label for="meta_keyword">Meta Keyword</label>
                            <input type="text" name="meta_keyword" id="meta_keyword" class="form-control"
                                value="{{ old('meta_keyword', $broker->meta_keyword ?? '') }}">
                            @error('meta_keyword')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <!-- Top Feature -->
                        <div class="form-group">
                            <label for="top_feature">Top Feature</label>
                            <textarea name="top_feature" id="top_feature"
                                class="form-control snote">{{ old('top_feature', $broker->top_feature ?? '') }}</textarea>
                            @error('top_feature')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <!-- Meta Description -->
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description"
                                class="form-control snote">{{ old('meta_description', $broker->meta_description ?? '') }}</textarea>
                            @error('meta_description')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="col-md-4">
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Broker</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection