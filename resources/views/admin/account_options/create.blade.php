@extends('admin.layout.app')

@section('heading', 'Add Broker')

@section('button')
<a href="{{ route('admin_category_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin_account_options_store', $broker->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="account_type">Account Type</label>
                    <input type="text" class="form-control @error('account_type') is-invalid @enderror"
                        id="account_type" name="account_type" value="{{ old('account_type') }}" required>
                    @error('account_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="account_currency">Account Currency</label>
                    <input type="text" class="form-control @error('account_currency') is-invalid @enderror"
                        id="account_currency" name="account_currency" value="{{ old('account_currency') }}" required>
                    @error('account_currency')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="min_deposit">Minimum Deposit</label>
                    <input type="number" class="form-control @error('min_deposit') is-invalid @enderror"
                        id="min_deposit" name="min_deposit" value="{{ old('min_deposit') }}" required>
                    @error('min_deposit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_leverage">Maximum Leverage</label>
                    <input type="number" class="form-control @error('max_leverage') is-invalid @enderror"
                        id="max_leverage" name="max_leverage" value="{{ old('max_leverage') }}" required>
                    @error('max_leverage')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="spread_type">Spread Type</label>
                    <input type="text" class="form-control @error('spread_type') is-invalid @enderror" id="spread_type"
                        name="spread_type" value="{{ old('spread_type') }}" required>
                    @error('spread_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="spread_value">Spread Value</label>
                    <input type="number" class="form-control @error('spread_value') is-invalid @enderror"
                        id="spread_value" name="spread_value" value="{{ old('spread_value') }}" >
                    @error('spread_value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_demo_available">Is Demo Available?</label>
                    <select class="form-control @error('is_demo_available') is-invalid @enderror" id="is_demo_available"
                        name="is_demo_available">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('is_demo_available') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('is_demo_available') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('is_demo_available')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="swap_free">Is Swap Free?</label>
                    <select class="form-control @error('swap_free') is-invalid @enderror" id="swap_free"
                        name="swap_free">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('swap_free') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('swap_free') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('swap_free')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="min_trade_size">Minimum Trade Size</label>
                    <input type="number" class="form-control @error('min_trade_size') is-invalid @enderror"
                        id="min_trade_size" name="min_trade_size" value="{{ old('min_trade_size') }}" required>
                    @error('min_trade_size')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_trade_size">Maximum Trade Size</label>
                    <input type="number" class="form-control @error('max_trade_size') is-invalid @enderror"
                        id="max_trade_size" name="max_trade_size" value="{{ old('max_trade_size') }}" required>
                    @error('max_trade_size')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="margin_call_level">Margin Call Level</label>
                    <input type="number" class="form-control @error('margin_call_level') is-invalid @enderror"
                        id="margin_call_level" name="margin_call_level" value="{{ old('margin_call_level') }}">
                    @error('margin_call_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stop_out_level">Stop Out Level</label>
                    <input type="number" class="form-control @error('stop_out_level') is-invalid @enderror"
                        id="stop_out_level" name="stop_out_level" value="{{ old('stop_out_level') }}" required>
                    @error('stop_out_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="max_open_positions">Maximum Open Positions</label>
                    <input type="number" class="form-control @error('max_open_positions') is-invalid @enderror"
                        id="max_open_positions" name="max_open_positions" value="{{ old('max_open_positions') }}"
                        required>
                    @error('max_open_positions')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Commission Field -->
                <div class="form-group">
                    <label for="commission">Commission</label>
                    <input type="number" step="0.01" class="form-control @error('commission') is-invalid @enderror"
                        id="commission" name="commission" value="{{ old('commission') }}">
                    @error('commission')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Interest Rate Field -->
                <div class="form-group">
                    <label for="interest_rate">Interest Rate</label>
                    <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror"
                        id="interest_rate" name="interest_rate" value="{{ old('interest_rate') }}">
                    @error('interest_rate')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Access to Pro Features -->
                <div class="form-group">
                    <label for="access_to_pro_features">Access to Pro Features</label>
                    <select class="form-control @error('access_to_pro_features') is-invalid @enderror"
                        id="access_to_pro_features" name="access_to_pro_features">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('access_to_pro_features') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('access_to_pro_features') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('access_to_pro_features')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exclusive Offers Field -->
                <div class="form-group">
                    <label for="exclusive_offers">Exclusive Offers</label>
                    <textarea class="form-control @error('exclusive_offers') is-invalid @enderror" id="exclusive_offers"
                        name="exclusive_offers">{{ old('exclusive_offers') }}</textarea>
                    @error('exclusive_offers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Account Management Field -->
                <div class="form-group">
                    <label for="account_management">Account Management</label>
                    <select class="form-control @error('account_management') is-invalid @enderror"
                        id="account_management" name="account_management">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('account_management') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('account_management') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('account_management')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               
                <!-- Trading Instruments Field -->
                <div class="form-group">
                    <label for="trading_instruments">Trading Instruments (comma separated)</label>
                    <textarea class="form-control @error('trading_instruments') is-invalid @enderror" 
                            id="trading_instruments" name="trading_instruments">{{ old('trading_instruments') }}</textarea>
                    @error('trading_instruments')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Risk Management Tools Field -->
                <div class="form-group">
                    <label for="risk_management_tools">Risk Management Tools (comma separated)</label>
                    <textarea class="form-control @error('risk_management_tools') is-invalid @enderror" 
                            id="risk_management_tools" name="risk_management_tools">{{ old('risk_management_tools') }}</textarea>
                    @error('risk_management_tools')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Bonus Eligibility Field -->
                <div class="form-group">
                    <label for="bonus_eligibility">Bonus Eligibility</label>
                    <select class="form-control @error('bonus_eligibility') is-invalid @enderror" id="bonus_eligibility"
                        name="bonus_eligibility">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('bonus_eligibility') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('bonus_eligibility') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('bonus_eligibility')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Personalized Education Field -->
                <div class="form-group">
                    <label for="personalized_education">Personalized Education</label>
                    <select class="form-control @error('personalized_education') is-invalid @enderror"
                        id="personalized_education" name="personalized_education">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('personalized_education') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('personalized_education') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('personalized_education')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Exclusive Webinars Field -->
                <div class="form-group">
                    <label for="exclusive_webinars">Exclusive Webinars</label>
                    <select class="form-control @error('exclusive_webinars') is-invalid @enderror"
                        id="exclusive_webinars" name="exclusive_webinars">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('exclusive_webinars') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('exclusive_webinars') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('exclusive_webinars')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Maximum Daily Trade Volume Field -->
                <div class="form-group">
                    <label for="maximum_daily_trade_volume">Maximum Daily Trade Volume</label>
                    <input type="number" step="0.01"
                        class="form-control @error('maximum_daily_trade_volume') is-invalid @enderror"
                        id="maximum_daily_trade_volume" name="maximum_daily_trade_volume"
                        value="{{ old('maximum_daily_trade_volume') }}">
                    @error('maximum_daily_trade_volume')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Trading Hours Field -->
                <div class="form-group">
                    <label for="trading_hours">Trading Hours</label>
                    <input type="text" class="form-control @error('trading_hours') is-invalid @enderror"
                        id="trading_hours" name="trading_hours" value="{{ old('trading_hours') }}">
                    @error('trading_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Special Conditions Field -->
                <div class="form-group">
                    <label for="special_conditions">Special Conditions</label>
                    <textarea class="form-control @error('special_conditions') is-invalid @enderror"
                        id="special_conditions" name="special_conditions">{{ old('special_conditions') }}</textarea>
                    @error('special_conditions')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Is Regulated Field -->
                <div class="form-group">
                    <label for="is_regulated">Is Regulated?</label>
                    <select class="form-control @error('is_regulated') is-invalid @enderror" id="is_regulated"
                        name="is_regulated">
                        <option value="" disabled selected>Select an option</option>
                        <option value="1" {{ old('is_regulated') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('is_regulated') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    @error('is_regulated')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Account Option</button>
            </form>
        </div>
    </div>
</div>
@endsection