@extends('admin.layout.app') @section('heading', 'Add Broker') @section('button') <a href="{{ route('admin_category_show') }}" class="btn btn-primary">
  <i class="fas fa-eye"></i> View </a> @endsection @section('main_content') <div class="section-body">
  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin_account_options_store', $broker->id) }}" method="POST"> @csrf <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="account_type">Account Type</label>
              <input type="text" class="form-control @error('account_type') is-invalid @enderror" id="account_type" name="account_type" value="{{ old('account_type') }}" required placeholder="e.g., Standard, Premium, VIP"> @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="accountTypeHelp" class="form-text text-muted"> Specify the type of account (e.g., Standard, Premium, VIP). </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="account_currency">Account Currency</label>
              <input type="text" class="form-control @error('account_currency') is-invalid @enderror" id="account_currency" name="account_currency" value="{{ old('account_currency') }}" required> @error('account_currency') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="accountCurrencyHelp" class="form-text text-muted"> Specify the currency for the account (e.g., USD, EUR, GBP). </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="min_deposit">Minimum Deposit</label>
              <input type="number" class="form-control @error('min_deposit') is-invalid @enderror" id="min_deposit" name="min_deposit" value="{{ old('min_deposit') }}" required> @error('min_deposit') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="minDepositHelp" class="form-text text-muted"> Enter the minimum deposit amount required to open an account (e.g., 100.00 USD). </small>
            </div>
          </div>
          <div class="col-md-3">
         <div class="form-group">
              <label for="max_leverage">Maximum Leverage</label>
              <input type="text" 
                     class="form-control @error('max_leverage') is-invalid @enderror" 
                     id="max_leverage" 
                     name="max_leverage" 
                     value="{{ old('max_leverage') }}">
              @error('max_leverage') 
                <div class="invalid-feedback">{{ $message }}</div> 
              @enderror
              <small id="maxLeverageHelp" class="form-text text-muted"> 
                Enter the maximum leverage ratio allowed for this account (e.g., 1:500). 
              </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="spread_type">Spread Type</label>
              <input type="text" class="form-control @error('spread_type') is-invalid @enderror" id="spread_type" name="spread_type" value="{{ old('spread_type') }}" required> @error('spread_type') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="spreadTypeHelp" class="form-text text-muted"> Enter the type of spread used for this account (e.g., Fixed, Variable). </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="spread_value">Spread Value</label>
              <input type="number" class="form-control @error('spread_value') is-invalid @enderror" id="spread_value" name="spread_value" value="{{ old('spread_value') }}"> @error('spread_value') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="spreadValueHelp" class="form-text text-muted"> Enter the spread value (e.g., 1.5) for this account type. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="is_demo_available">Is Demo Available?</label>
              <select class="form-control @error('is_demo_available') is-invalid @enderror" id="is_demo_available" name="is_demo_available">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('is_demo_available') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_demo_available') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('is_demo_available') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="demoAvailableHelp" class="form-text text-muted"> Choose whether a demo account is available for this account type. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="swap_free">Is Swap Free?</label>
              <select class="form-control @error('swap_free') is-invalid @enderror" id="swap_free" name="swap_free">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('swap_free') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('swap_free') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('swap_free') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="swapFreeHelp" class="form-text text-muted"> Choose whether this account type is swap-free (no interest on overnight positions). </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="min_trade_size">Minimum Trade Size</label>
              <input 
                type="text" 
                step="0.001" 
                min="0.001"
                class="form-control @error('min_trade_size') is-invalid @enderror" 
                id="min_trade_size" 
                name="min_trade_size" 
                value="{{ old('min_trade_size') }}"
              >
              @error('min_trade_size')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small id="minTradeSizeHelp" class="form-text text-muted">Enter the minimum trade size for this account type (e.g., 0.001 lots).</small>
            </div>

          </div>
          <div class="col-md-3">
           <div class="form-group">
              <label for="max_trade_size">Maximum Trade Size</label>
              <input 
                type="text" 
                step="0.001" 
                min="0.001"
                class="form-control @error('max_trade_size') is-invalid @enderror" 
                id="max_trade_size" 
                name="max_trade_size" 
                value="{{ old('max_trade_size') }}"
              >
              @error('max_trade_size')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small id="maxTradeSizeHelp" class="form-text text-muted">Enter the maximum trade size for this account type (e.g., 1000 lots).</small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="margin_call_level">Margin Call Level</label>
              <input type="number" class="form-control @error('margin_call_level') is-invalid @enderror" id="margin_call_level" name="margin_call_level" value="{{ old('margin_call_level') }}"> @error('margin_call_level') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="marginCallLevelHelp" class="form-text text-muted"> Specify the margin call level as a percentage (e.g., 50 for 50%). This is the level at which a margin call will be triggered. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="stop_out_level">Stop Out Level</label>
              <input type="number" class="form-control @error('stop_out_level') is-invalid @enderror" id="stop_out_level" name="stop_out_level" value="{{ old('stop_out_level') }}" required> @error('stop_out_level') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="stopOutLevelHelp" class="form-text text-muted"> Specify the stop-out level as a percentage (e.g., 20 for 20%). This is the level at which positions will automatically be closed to prevent further losses. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="max_open_positions">Maximum Open Positions</label>
              <input type="number" class="form-control @error('max_open_positions') is-invalid @enderror" id="max_open_positions" name="max_open_positions" value="{{ old('max_open_positions') }}" required> @error('max_open_positions') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="maxOpenPositionsHelp" class="form-text text-muted"> Enter the maximum number of open positions allowed for this account type. This helps manage risk and system limits. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="commission">Commission</label>
              <input type="text" class="form-control @error('commission') is-invalid @enderror" id="commission" name="commission" value="{{ old('commission') }}"> @error('commission') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="commissionHelp" class="form-text text-muted"> Specify the commission charged per trade for this account type, if applicable. Use decimal values (e.g., 5.00 or 0.50). </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="interest_rate">Interest Rate</label>
              <input type="text" class="form-control @error('interest_rate') is-invalid @enderror" id="interest_rate" name="interest_rate" value="{{ old('interest_rate') }}"> @error('interest_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="interestRateHelp" class="form-text text-muted"> Enter the interest rate (e.g., 1.25) applied for overnight financing, if applicable. Leave blank if not relevant. </small>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="access_to_pro_features">Access to Pro Features</label>
              <select class="form-control @error('access_to_pro_features') is-invalid @enderror" id="access_to_pro_features" name="access_to_pro_features">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('access_to_pro_features') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('access_to_pro_features') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('access_to_pro_features') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="accessToProFeaturesHelp" class="form-text text-muted"> Select "Yes" if the account type allows access to advanced or professional features, otherwise select "No." </small>
            </div>
          </div>
          <div class="col-md-6">
            <!-- Exclusive Offers Field -->
            <div class="form-group">
              <label for="exclusive_offers">Exclusive Offers</label>
              <textarea class="form-control snote @error('exclusive_offers') is-invalid @enderror" id="exclusive_offers" name="exclusive_offers">{{ old('exclusive_offers') }}</textarea> @error('exclusive_offers') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="col-md-6">
            <!-- Trading Instruments Field -->
            <div class="form-group">
              <label for="trading_instruments">Trading Instruments</label>
              <textarea class="form-control snote @error('trading_instruments') is-invalid @enderror" id="trading_instruments" name="trading_instruments">{{ old('trading_instruments') }}</textarea> @error('trading_instruments') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="col-md-6">
            <!-- Risk Management Tools Field -->
            <div class="form-group">
              <label for="risk_management_tools">Risk Management Tools</label>
              <textarea class="form-control snote @error('risk_management_tools') is-invalid @enderror" id="risk_management_tools" name="risk_management_tools">{{ old('risk_management_tools') }}</textarea> @error('risk_management_tools') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="col-md-6">
            <!-- Special Conditions Field -->
            <div class="form-group">
              <label for="special_conditions">Special Conditions</label>
              <textarea class="form-control snote @error('special_conditions') is-invalid @enderror" id="special_conditions" name="special_conditions">{{ old('special_conditions') }}</textarea> @error('special_conditions') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
          <div class="col-md-3">
            <!-- Account Management Field -->
            <div class="form-group">
              <label for="account_management">Account Management</label>
              <select class="form-control @error('account_management') is-invalid @enderror" id="account_management" name="account_management">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('account_management') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('account_management') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('account_management') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="accountManagementHelp" class="form-text text-muted"> Choose "Yes" if account management services are available for this account type, otherwise select "No." </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Bonus Eligibility Field -->
            <div class="form-group">
              <label for="bonus_eligibility">Bonus Eligibility</label>
              <select class="form-control @error('bonus_eligibility') is-invalid @enderror" id="bonus_eligibility" name="bonus_eligibility">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('bonus_eligibility') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('bonus_eligibility') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('bonus_eligibility') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="bonusEligibilityHelp" class="form-text text-muted"> Select "Yes" if the account type qualifies for bonuses, otherwise choose "No." </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Personalized Education Field -->
            <div class="form-group">
              <label for="personalized_education">Personalized Education</label>
              <select class="form-control @error('personalized_education') is-invalid @enderror" id="personalized_education" name="personalized_education">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('personalized_education') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('personalized_education') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('personalized_education') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="personalizedEducationHelp" class="form-text text-muted"> Select "Yes" if this account type offers tailored educational resources, otherwise choose "No." </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Exclusive Webinars Field -->
            <div class="form-group">
              <label for="exclusive_webinars">Exclusive Webinars</label>
              <select class="form-control @error('exclusive_webinars') is-invalid @enderror" id="exclusive_webinars" name="exclusive_webinars">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('exclusive_webinars') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('exclusive_webinars') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('exclusive_webinars') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="exclusiveWebinarsHelp" class="form-text text-muted"> Select "Yes" if this account type includes access to exclusive webinars hosted by experts, otherwise choose "No." </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Maximum Daily Trade Volume Field -->
            <div class="form-group">
              <label for="maximum_daily_trade_volume">Maximum Daily Trade Volume</label>
              <input type="number" class="form-control @error('maximum_daily_trade_volume') is-invalid @enderror" id="maximum_daily_trade_volume" name="maximum_daily_trade_volume" value="{{ old('maximum_daily_trade_volume') }}"> @error('maximum_daily_trade_volume') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="maxDailyTradeVolumeHelp" class="form-text text-muted"> Enter the maximum trade volume allowed per day for this account type. The volume is in lots or contract units, depending on the broker's system. </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Trading Hours Field -->
            <div class="form-group">
              <label for="trading_hours">Trading Hours</label>
              <input type="text" class="form-control @error('trading_hours') is-invalid @enderror" id="trading_hours" name="trading_hours" value="{{ old('trading_hours') }}"> @error('trading_hours') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="tradingHoursHelp" class="form-text text-muted"> Enter the trading hours for this account type. Format: 'HH:MM - HH:MM' (e.g., '08:00 - 17:00'). </small>
            </div>
          </div>
          <div class="col-md-3">
            <!-- Is Regulated Field -->
            <div class="form-group">
              <label for="is_regulated">Is Regulated?</label>
              <select class="form-control @error('is_regulated') is-invalid @enderror" id="is_regulated" name="is_regulated">
                <option value="" disabled selected>Select an option</option>
                <option value="1" {{ old('is_regulated') == '1' ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ old('is_regulated') == '0' ? 'selected' : '' }}>No</option>
              </select> @error('is_regulated') <div class="invalid-feedback">{{ $message }}</div> @enderror <small id="isRegulatedHelp" class="form-text text-muted"> Select whether the broker is regulated by a recognized financial authority. </small>
            </div>
          </div>
          <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Create Account Option</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div> @endsection