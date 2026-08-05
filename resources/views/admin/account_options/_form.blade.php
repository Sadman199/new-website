@php
    $isEdit = isset($accountOption) && $accountOption->exists;
    $option = $accountOption ?? new \App\Models\AccountOption();
    $selectedFeatures = old('features', is_array($option->features) ? $option->features : []);
@endphp

<div class="alert alert-light border mb-4">
    <i class="fas fa-info-circle text-primary mr-1"></i>
    Broker-level data (regulation, demo account, markets, education, account managers) is managed on the
    <a href="{{ route('admin_broker_edit', $broker->id) }}">Broker profile</a> page — not duplicated here.
</div>

<div id="account-option-accordion">
    {{-- SECTION 1: Profile & Costs --}}
    <div class="card">
        <div class="card-header" id="headingAccountProfile">
            <h5 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseAccountProfile" aria-expanded="true">
                    <i class="fas fa-wallet mr-1"></i> 1. Account Profile &amp; Costs
                </button>
            </h5>
        </div>
        <div id="collapseAccountProfile" class="collapse show" data-parent="#account-option-accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="account_type">Account Type <span class="text-danger">*</span></label>
                        <input type="text" name="account_type" id="account_type" class="form-control" required
                               list="account-type-presets" value="{{ old('account_type', $option->account_type) }}"
                               placeholder="e.g. Standard, ECN, Islamic">
                        <datalist id="account-type-presets">
                            @foreach($formOptions['accountTypes'] as $label)
                                <option value="{{ $label }}">
                            @endforeach
                        </datalist>
                        @error('account_type')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control"
                               value="{{ old('slug', $option->slug) }}" placeholder="auto from account type">
                        @error('slug')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="account_currency">Account Currency <span class="text-danger">*</span></label>
                        <input type="text" name="account_currency" id="account_currency" class="form-control" required
                               value="{{ old('account_currency', $option->account_currency ?? 'USD') }}" placeholder="USD, EUR, GBP">
                        @error('account_currency')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-control" min="0" max="255"
                               value="{{ old('sort_order', $option->sort_order ?? 0) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="is_active">Status</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" @selected(old('is_active', $option->is_active ?? true))>Active</option>
                            <option value="0" @selected(! old('is_active', $option->is_active ?? true))>Hidden</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="min_deposit">Minimum Deposit ($)</label>
                        <input type="number" step="0.01" name="min_deposit" id="min_deposit" class="form-control"
                               value="{{ old('min_deposit', $option->min_deposit) }}">
                        @error('min_deposit')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="max_leverage_numeric">Max Leverage (numeric)</label>
                        <input type="number" name="max_leverage_numeric" id="max_leverage_numeric" class="form-control" min="1" max="10000"
                               value="{{ old('max_leverage_numeric', $option->max_leverage_numeric) }}" placeholder="500 for 1:500">
                        @error('max_leverage_numeric')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="leverage_label">Leverage Label</label>
                        <input type="text" name="leverage_label" id="leverage_label" class="form-control"
                               value="{{ old('leverage_label', $option->leverage_label) }}" placeholder="1:500">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="spread_type">Spread Type</label>
                        <select name="spread_type" id="spread_type" class="form-control">
                            <option value="">— Select —</option>
                            @foreach($formOptions['spreadTypes'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('spread_type', $option->spread_type) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="spread_from_pips">Spread From (pips)</label>
                        <input type="number" step="0.01" name="spread_from_pips" id="spread_from_pips" class="form-control"
                               value="{{ old('spread_from_pips', $option->spread_from_pips ?? $option->spread_value) }}">
                        @error('spread_from_pips')<small class="text-danger d-block">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="commission_label">Commission Label</label>
                        <input type="text" name="commission_label" id="commission_label" class="form-control"
                               value="{{ old('commission_label', $option->commission_label ?? $option->commission) }}" placeholder="$3.50/lot or None">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="commission_per_lot">Commission per Lot ($)</label>
                        <input type="number" step="0.01" name="commission_per_lot" id="commission_per_lot" class="form-control"
                               value="{{ old('commission_per_lot', $option->commission_per_lot) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="execution_model">Execution Model</label>
                        <select name="execution_model" id="execution_model" class="form-control">
                            <option value="">— Select —</option>
                            @foreach($formOptions['executionModels'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('execution_model', $option->execution_model) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="description">Short Description</label>
                        <textarea name="description" id="description" class="form-control" rows="2"
                                  placeholder="Brief summary for this account type">{{ old('description', $option->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: Limits, Features & Content --}}
    <div class="card">
        <div class="card-header" id="headingAccountLimits">
            <h5 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseAccountLimits">
                    <i class="fas fa-sliders-h mr-1"></i> 2. Limits, Features &amp; Content
                </button>
            </h5>
        </div>
        <div id="collapseAccountLimits" class="collapse" data-parent="#account-option-accordion">
            <div class="card-body">
                <h6 class="text-primary font-weight-bold mb-3">Trading Limits</h6>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="min_trade_size">Min Trade Size (lots)</label>
                        <input type="number" step="0.0001" name="min_trade_size" id="min_trade_size" class="form-control"
                               value="{{ old('min_trade_size', $option->min_trade_size) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="max_trade_size">Max Trade Size (lots)</label>
                        <input type="number" step="0.0001" name="max_trade_size" id="max_trade_size" class="form-control"
                               value="{{ old('max_trade_size', $option->max_trade_size) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="margin_call_level">Margin Call (%)</label>
                        <input type="number" step="0.01" name="margin_call_level" id="margin_call_level" class="form-control"
                               value="{{ old('margin_call_level', $option->margin_call_level) }}">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="stop_out_level">Stop Out (%)</label>
                        <input type="number" step="0.01" name="stop_out_level" id="stop_out_level" class="form-control"
                               value="{{ old('stop_out_level', $option->stop_out_level) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="max_open_positions">Max Open Positions</label>
                        <input type="number" name="max_open_positions" id="max_open_positions" class="form-control"
                               value="{{ old('max_open_positions', $option->max_open_positions) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="maximum_daily_trade_volume">Max Daily Volume</label>
                        <input type="number" step="0.01" name="maximum_daily_trade_volume" id="maximum_daily_trade_volume" class="form-control"
                               value="{{ old('maximum_daily_trade_volume', $option->maximum_daily_trade_volume) }}">
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3 mt-2">Account Features</h6>
                <div class="row">
                    @foreach([
                        'swap_free' => 'Swap-free / Islamic',
                        'ea_allowed' => 'EA / Algo trading',
                        'hedging_allowed' => 'Hedging allowed',
                        'vps_eligible' => 'VPS eligible',
                        'bonus_eligibility' => 'Bonus eligible',
                        'access_to_pro_features' => 'Pro / VIP features',
                    ] as $field => $label)
                        <div class="col-md-4 form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="{{ $field }}" name="{{ $field }}" value="1"
                                       @checked(old($field, $option->{$field} ?? false))>
                                <label class="custom-control-label" for="{{ $field }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label>Extra Feature Tags</label>
                    <div class="row">
                        @foreach($formOptions['featureTags'] as $value => $label)
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="feature_{{ $value }}"
                                           name="features[]" value="{{ $value }}"
                                           @checked(in_array($value, $selectedFeatures, true))>
                                    <label class="custom-control-label" for="feature_{{ $value }}">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <h6 class="text-primary font-weight-bold mb-3 mt-2">Comparison Content</h6>
                <div class="form-group">
                    <label for="exclusive_offers">Comparison Table (HTML)</label>
                    <textarea name="exclusive_offers" id="exclusive_offers" class="form-control snote" rows="6"
                              placeholder="HTML table comparing account tiers — as on BrokerChooser account pages">{{ old('exclusive_offers', $option->exclusive_offers) }}</textarea>
                    <small class="text-muted">Shown on the broker review account-types section.</small>
                </div>
                <div class="form-group">
                    <label for="special_conditions">Special Conditions</label>
                    <textarea name="special_conditions" id="special_conditions" class="form-control" rows="3"
                              placeholder="Account-specific notes, restrictions, or promotions">{{ old('special_conditions', $option->special_conditions) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-4 mb-0">
    <button type="submit" class="btn btn-primary btn-lg">
        <i class="fas fa-save"></i> {{ $isEdit ? 'Update Account Option' : 'Create Account Option' }}
    </button>
    <a href="{{ route('admin_account_options_index', $broker->id) }}" class="btn btn-light ml-2">Cancel</a>
</div>
