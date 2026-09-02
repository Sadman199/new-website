<div class="ab-repeater__item program-row" data-index="{{ $index }}">
    <div class="ab-repeater__head">
        <strong>Program @if(is_numeric($index))#{{ (int) $index + 1 }}@endif</strong>
        <button type="button" class="ab-btn ab-btn--danger ab-btn--sm remove-program"><i class="fas fa-times"></i></button>
    </div>
    @if(!empty($program['id']))
        <input type="hidden" name="programs[{{ $index }}][id]" value="{{ $program['id'] }}">
    @endif
    <div class="row">
        <div class="col-md-4 form-group"><label>Program Name</label><input type="text" name="programs[{{ $index }}][name]" class="form-control" value="{{ $program['name'] ?? '' }}"></div>
        <div class="col-md-4 form-group"><label>Account Size</label><input type="text" name="programs[{{ $index }}][account_size]" class="form-control" value="{{ $program['account_size'] ?? '' }}"></div>
        <div class="col-md-4 form-group"><label>Entry Fee</label><input type="number" step="0.01" name="programs[{{ $index }}][entry_fee]" class="form-control" value="{{ $program['entry_fee'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Profit Target</label><input type="text" name="programs[{{ $index }}][profit_target]" class="form-control" value="{{ $program['profit_target'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Daily Drawdown</label><input type="text" name="programs[{{ $index }}][daily_drawdown]" class="form-control" value="{{ $program['daily_drawdown'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Max Drawdown</label><input type="text" name="programs[{{ $index }}][max_drawdown]" class="form-control" value="{{ $program['max_drawdown'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Profit Split</label><input type="text" name="programs[{{ $index }}][profit_split]" class="form-control" value="{{ $program['profit_split'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Min Trading Days</label><input type="number" name="programs[{{ $index }}][min_trading_days]" class="form-control" value="{{ $program['min_trading_days'] ?? '' }}"></div>
        <div class="col-md-3 form-group"><label>Sort Order</label><input type="number" name="programs[{{ $index }}][sort_order]" class="form-control" value="{{ $program['sort_order'] ?? $index }}"></div>
        <div class="col-md-6 form-group d-flex align-items-end">
            <div class="custom-control custom-checkbox mb-3">
                <input type="hidden" name="programs[{{ $index }}][is_active]" value="0">
                <input type="checkbox" class="custom-control-input" id="prog_{{ $index }}_is_active" name="programs[{{ $index }}][is_active]" value="1" @checked(($program['is_active'] ?? true))>
                <label class="custom-control-label" for="prog_{{ $index }}_is_active">Active</label>
            </div>
        </div>
        <div class="col-12 form-group">
            @foreach(['news_trading' => 'News Trading', 'weekend_holding' => 'Weekend Holding', 'ea_allowed' => 'EA Allowed', 'copy_trading' => 'Copy Trading', 'hedging' => 'Hedging', 'refund_available' => 'Refund Available'] as $field => $label)
            <div class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="custom-control-input" id="prog_{{ $index }}_{{ $field }}" name="programs[{{ $index }}][{{ $field }}]" value="1" @checked(!empty($program[$field]))>
                <label class="custom-control-label" for="prog_{{ $index }}_{{ $field }}">{{ $label }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>
