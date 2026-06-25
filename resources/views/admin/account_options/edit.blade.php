@extends('admin.layout.app')
@section('button')
<a href="" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body">
    <h3>Edit Account Option for Broker: {{ $broker->name }}</h3>
    <form action="{{ route('admin_account_options_update', [$broker->id, $accountOption->id ?? 0]) }}" method="POST">
    @csrf
    @method('PUT') 
        <!-- To specify it's an update request -->
        <div class="card">
            <div class="card-body">
              <div class="row">
                    <div class="col-md-3">
                        <!-- Account Type -->
                        <div class="form-group">
                            <label for="account_type">Account Type</label>
                            <input type="text" class="form-control @error('account_type') is-invalid @enderror"
                                id="account_type" name="account_type"
                                value="{{ old('account_type', $accountOption->account_type) }}" required
                                placeholder="e.g., Standard, Premium, VIP">
                            @error('account_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="accountTypeHelp" class="form-text text-muted">
                                Specify the type of account (e.g., Standard, Premium, VIP).
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!-- Account Currency -->
                        <div class="form-group">
                            <label for="account_currency">Account Currency</label>
                            <input type="text" class="form-control @error('account_currency') is-invalid @enderror"
                                id="account_currency" name="account_currency"
                                value="{{ old('account_currency', $accountOption->account_currency) }}" required
                                placeholder="e.g., USD, EUR, GBP">
                            @error('account_currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="accountCurrencyHelp" class="form-text text-muted">
                                Enter the currency for the account (e.g., USD, EUR, GBP).
                            </small>
                        </div>

                    </div>


                    <div class="col-md-3">
                        <!-- Minimum Deposit -->
                        <div class="form-group">
                            <label for="min_deposit">Minimum Deposit</label>
                            <input type="number" step="0.01" class="form-control @error('min_deposit') is-invalid @enderror"
                                id="min_deposit" name="min_deposit"
                                value="{{ old('min_deposit', $accountOption->min_deposit) }}" required
                                placeholder="e.g., 100.00">
                            @error('min_deposit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="minDepositHelp" class="form-text text-muted">
                                Enter the minimum deposit amount (e.g., 100.00).
                            </small>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <!-- Maximum Leverage -->
                        <div class="form-group">
                          <label for="max_leverage">Maximum Leverage</label>
                          <input type="text" 
                                 class="form-control @error('max_leverage') is-invalid @enderror"
                                 id="max_leverage" 
                                 name="max_leverage"
                                 value="{{ old('max_leverage', $accountOption->max_leverage) }}"
                                 placeholder="e.g., 1:1200">
                          @error('max_leverage')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                          <small id="maxLeverageHelp" class="form-text text-muted">
                            Enter the maximum leverage ratio, e.g., 1:1200.
                          </small>
                        </div>


                    </div>

                    <div class="col-md-3">
                        <!-- Spread Type -->
                        <div class="form-group">
                            <label for="spread_type">Spread Type</label>
                            <input type="text" class="form-control @error('spread_type') is-invalid @enderror" id="spread_type"
                                name="spread_type" value="{{ old('spread_type', $accountOption->spread_type) }}" required
                                placeholder="e.g., Fixed, Variable">
                            @error('spread_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="spreadTypeHelp" class="form-text text-muted">
                                Enter the spread type (e.g., Fixed, Variable).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Spread Value -->
                        <div class="form-group">
                            <label for="spread_value">Spread Value</label>
                            <input type="number" step="0.01" class="form-control @error('spread_value') is-invalid @enderror"
                                id="spread_value" name="spread_value"
                                value="{{ old('spread_value', $accountOption->spread_value) }}"
                                placeholder="e.g., 1.50">
                            @error('spread_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="spreadValueHelp" class="form-text text-muted">
                                Enter the spread value (e.g., 1.50).
                            </small>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <!-- Is Demo Available -->
                        <div class="form-group">
                            <label for="is_demo_available">Is Demo Available?</label>
                            <select class="form-control @error('is_demo_available') is-invalid @enderror" id="is_demo_available"
                                name="is_demo_available" required>
                                <option value="" disabled {{ old('is_demo_available', $accountOption->is_demo_available) == '' ? 'selected' : '' }}>
                                    Select an option
                                </option>
                                <option value="1"
                                    {{ old('is_demo_available', $accountOption->is_demo_available) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('is_demo_available', $accountOption->is_demo_available) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('is_demo_available')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="isDemoAvailableHelp" class="form-text text-muted">
                                Select whether a demo account is available for this account type.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Is Swap Free -->
                        <div class="form-group">
                            <label for="swap_free">Is Swap Free?</label>
                            <select class="form-control @error('swap_free') is-invalid @enderror" id="swap_free"
                                name="swap_free" required>
                                <option value="" disabled {{ old('swap_free', $accountOption->swap_free) == '' ? 'selected' : '' }}>
                                    Select an option
                                </option>
                                <option value="1" {{ old('swap_free', $accountOption->swap_free) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0" {{ old('swap_free', $accountOption->swap_free) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('swap_free')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="swapFreeHelp" class="form-text text-muted">
                                Select whether the account type is swap-free (i.e., no interest charged on overnight positions).
                            </small>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <!-- Minimum Trade Size -->
                        <div class="form-group">
                            <label for="min_trade_size">Minimum Trade Size</label>
                            <input 
                                type="text"
                                class="form-control @error('min_trade_size') is-invalid @enderror"
                                id="min_trade_size" 
                                name="min_trade_size"
                                value="{{ old('min_trade_size', $accountOption->min_trade_size) }}"
                                placeholder="e.g., 0.001"
                            >
                            @error('min_trade_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="minTradeSizeHelp" class="form-text text-muted">
                                Enter the minimum trade size (e.g., 0.001 lots).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                      <!-- Maximum Trade Size -->
                        <div class="form-group">
                            <label for="max_trade_size">Maximum Trade Size</label>
                            <input 
                                type="text" 
                                class="form-control @error('max_trade_size') is-invalid @enderror"
                                id="max_trade_size" 
                                name="max_trade_size"
                                value="{{ old('max_trade_size', $accountOption->max_trade_size) }}"
                                placeholder="e.g., 100.00"
                            >
                            @error('max_trade_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="maxTradeSizeHelp" class="form-text text-muted">
                                Enter the maximum trade size allowed (e.g., 100.00 lots).
                            </small>
                        </div>


                    </div>

                    <div class="col-md-3">
                        <!-- Margin Call Level -->
                        <div class="form-group">
                            <label for="margin_call_level">Margin Call Level</label>
                            <input type="number" step="0.01" class="form-control @error('margin_call_level') is-invalid @enderror"
                                id="margin_call_level" name="margin_call_level"
                                value="{{ old('margin_call_level', $accountOption->margin_call_level) }}"
                                placeholder="e.g., 50.00">
                            @error('margin_call_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="marginCallLevelHelp" class="form-text text-muted">
                                Enter the margin call level as a percentage (e.g., 50.00).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Stop Out Level -->
                        <div class="form-group">
                            <label for="stop_out_level">Stop Out Level</label>
                            <input type="number" step="0.01" class="form-control @error('stop_out_level') is-invalid @enderror"
                                id="stop_out_level" name="stop_out_level"
                                value="{{ old('stop_out_level', $accountOption->stop_out_level) }}" required
                                placeholder="e.g., 30.00">
                            @error('stop_out_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="stopOutLevelHelp" class="form-text text-muted">
                                Enter the stop-out level as a percentage (e.g., 30.00).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Maximum Open Positions -->
                        <div class="form-group">
                            <label for="max_open_positions">Maximum Open Positions</label>
                            <input type="number" class="form-control @error('max_open_positions') is-invalid @enderror"
                                id="max_open_positions" name="max_open_positions"
                                value="{{ old('max_open_positions', $accountOption->max_open_positions) }}" 
                                placeholder="e.g., 10">
                            @error('max_open_positions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="maxOpenPositionsHelp" class="form-text text-muted">
                                Enter the maximum number of positions a trader can have open simultaneously (e.g., 10).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Commission -->
                        <div class="form-group">
                            <label for="commission">Commission</label>
                            <input type="text" class="form-control @error('commission') is-invalid @enderror"
                                id="commission" name="commission" value="{{ old('commission', $accountOption->commission) }}"
                                placeholder="e.g., 5.00">
                            @error('commission')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="commissionHelp" class="form-text text-muted">
                                Enter the commission per trade (e.g., 5.00). If none, leave empty.
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Interest Rate Field -->
                        <div class="form-group">
                            <label for="interest_rate">Interest Rate</label>
                            <input type="text"  class="form-control @error('interest_rate') is-invalid @enderror"
                                id="interest_rate" name="interest_rate" value="{{ old('interest_rate', $accountOption->interest_rate) }}"
                                placeholder="e.g., 3.50">
                            @error('interest_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="interestRateHelp" class="form-text text-muted">
                                Enter the interest rate for overnight financing (e.g., 3.50). If none, leave empty.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Access to Pro Features -->
                        <div class="form-group">
                            <label for="access_to_pro_features">Access to Pro Features</label>
                            <select class="form-control @error('access_to_pro_features') is-invalid @enderror"
                                id="access_to_pro_features" name="access_to_pro_features">
                                <option value="" disabled>Select an option</option>
                                <option value="1"
                                    {{ old('access_to_pro_features', $accountOption->access_to_pro_features) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('access_to_pro_features', $accountOption->access_to_pro_features) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('access_to_pro_features')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="proFeaturesHelp" class="form-text text-muted">
                                Select whether this account type has access to professional features like advanced charts, tools, and analysis.
                            </small>
                        </div>

                    </div>

                    <div class="col-md-6">
                            <!-- Exclusive Offers Field -->
                        <div class="form-group">
                            <label for="exclusive_offers">Exclusive Offers</label>
                            <textarea class="form-control @error('exclusive_offers') is-invalid @enderror snote" id="exclusive_offers"
                                name="exclusive_offers">{{ old('exclusive_offers', $accountOption->exclusive_offers) }}</textarea>
                            @error('exclusive_offers')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Trading Instruments Field -->
                        <div class="form-group">
                            <label for="trading_instruments">Trading Instruments</label>
                            <textarea class="form-control snote @error('trading_instruments') is-invalid @enderror"
                                id="trading_instruments"
                                name="trading_instruments"
                                placeholder="e.g., Forex, Stocks, Commodities, Indices, Cryptocurrencies">{{ old('trading_instruments', $accountOption->trading_instruments) }}</textarea>
                            @error('trading_instruments')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                      <!-- Risk Management Tools Field -->
                        <div class="form-group">
                            <label for="risk_management_tools">Risk Management Tools (comma separated)</label>
                            <textarea class="form-control snote @error('risk_management_tools') is-invalid @enderror"
                                id="risk_management_tools"
                                name="risk_management_tools">{{ old('risk_management_tools', $accountOption->risk_management_tools) }}</textarea>
                            @error('risk_management_tools')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Special Conditions Field -->
                        <div class="form-group">
                            <label for="special_conditions">Special Conditions</label>
                            <textarea class="form-control snote @error('special_conditions') is-invalid @enderror"
                                id="special_conditions"
                                name="special_conditions">{{ old('special_conditions', $accountOption->special_conditions) }}</textarea>
                            @error('special_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Account Management Field -->
                        <div class="form-group">
                            <label for="account_management">Account Management</label>
                            <select class="form-control @error('account_management') is-invalid @enderror"
                                id="account_management" name="account_management">
                                <option value="" disabled>Select an option</option>
                                <option value="1"
                                    {{ old('account_management', $accountOption->account_management) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('account_management', $accountOption->account_management) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('account_management')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="accountManagementHelp" class="form-text text-muted">
                                Select whether this account type comes with dedicated account management services, such as a personal account manager.
                            </small>
                        </div>
                    </div>

                    <div class="col-md-3">
                         <!-- Bonus Eligibility Field -->
                        <div class="form-group">
                            <label for="bonus_eligibility">Bonus Eligibility</label>
                            <select class="form-control @error('bonus_eligibility') is-invalid @enderror" id="bonus_eligibility"
                                name="bonus_eligibility">
                                <option value="" disabled>Select an option</option>
                                <option value="1"
                                    {{ old('bonus_eligibility', $accountOption->bonus_eligibility) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('bonus_eligibility', $accountOption->bonus_eligibility) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('bonus_eligibility')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Personalized Education Field -->
                        <div class="form-group">
                            <label for="personalized_education">Personalized Education</label>
                            <select class="form-control @error('personalized_education') is-invalid @enderror"
                                id="personalized_education" name="personalized_education">
                                <option value="" disabled>Select an option</option>
                                <option value="1"
                                    {{ old('personalized_education', $accountOption->personalized_education) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('personalized_education', $accountOption->personalized_education) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('personalized_education')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Exclusive Webinars Field -->
                        <div class="form-group">
                            <label for="exclusive_webinars">Exclusive Webinars</label>
                            <select class="form-control @error('exclusive_webinars') is-invalid @enderror"
                                id="exclusive_webinars" name="exclusive_webinars">
                                <option value="" disabled>Select an option</option>
                                <option value="1"
                                    {{ old('exclusive_webinars', $accountOption->exclusive_webinars) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0"
                                    {{ old('exclusive_webinars', $accountOption->exclusive_webinars) == '0' ? 'selected' : '' }}>
                                    No</option>
                            </select>
                            @error('exclusive_webinars')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <!-- Maximum Daily Trade Volume Field -->
                        <div class="form-group">
                            <label for="maximum_daily_trade_volume">Maximum Daily Trade Volume</label>
                            <input type="number" step="0.01"
                                class="form-control @error('maximum_daily_trade_volume') is-invalid @enderror"
                                id="maximum_daily_trade_volume" name="maximum_daily_trade_volume"
                                value="{{ old('maximum_daily_trade_volume', $accountOption->maximum_daily_trade_volume) }}">
                            @error('maximum_daily_trade_volume')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="maxDailyTradeVolumeHelp" class="form-text text-muted">
                                Specify the maximum volume of trades allowed per day for this account type (e.g., 1000 units).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <!-- Trading Hours Field -->
                        <div class="form-group">
                            <label for="trading_hours">Trading Hours</label>
                            <input type="text" class="form-control @error('trading_hours') is-invalid @enderror"
                                id="trading_hours" name="trading_hours"
                                value="{{ old('trading_hours', $accountOption->trading_hours) }}">
                            @error('trading_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="tradingHoursHelp" class="form-text text-muted">
                                Specify the trading hours for this account type (e.g., 24/5, 24/7, or specific time range).
                            </small>
                        </div>

                    </div>

                    <div class="col-md-3">
                            <div class="form-group">
                            <label for="is_regulated">Is Regulated?</label>
                            <select class="form-control @error('is_regulated') is-invalid @enderror" id="is_regulated" name="is_regulated">
                                <option value="" disabled>Select an option</option>
                                <option value="1" {{ old('is_regulated', $accountOption->is_regulated) == '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_regulated', $accountOption->is_regulated) == '0' ? 'selected' : '' }}>No</option>
                            </select>
                            @error('is_regulated')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Account Option</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </form>
</div>
@endsection