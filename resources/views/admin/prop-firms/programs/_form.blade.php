<div class="form-group"><label>Prop Firm <span class="text-danger">*</span></label>
<select name="prop_firm_id" class="form-control" required>
    <option value="">Select firm</option>
    @foreach($propFirms as $firm)
        <option value="{{ $firm->id }}" @selected(old('prop_firm_id', $program->prop_firm_id) == $firm->id)>{{ $firm->name }}</option>
    @endforeach
</select>@error('prop_firm_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
<div class="row">
    <div class="col-md-6 form-group"><label>Program Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required value="{{ old('name', $program->name) }}"></div>
    <div class="col-md-3 form-group"><label>Account Size</label><input type="text" name="account_size" class="form-control" value="{{ old('account_size', $program->account_size) }}"></div>
    <div class="col-md-3 form-group"><label>Entry Fee</label><input type="number" step="0.01" name="entry_fee" class="form-control" value="{{ old('entry_fee', $program->entry_fee) }}"></div>
    <div class="col-md-3 form-group"><label>Profit Target</label><input type="text" name="profit_target" class="form-control" value="{{ old('profit_target', $program->profit_target) }}"></div>
    <div class="col-md-3 form-group"><label>Daily Drawdown</label><input type="text" name="daily_drawdown" class="form-control" value="{{ old('daily_drawdown', $program->daily_drawdown) }}"></div>
    <div class="col-md-3 form-group"><label>Max Drawdown</label><input type="text" name="max_drawdown" class="form-control" value="{{ old('max_drawdown', $program->max_drawdown) }}"></div>
    <div class="col-md-3 form-group"><label>Profit Split</label><input type="text" name="profit_split" class="form-control" value="{{ old('profit_split', $program->profit_split) }}"></div>
    <div class="col-md-3 form-group"><label>Min Trading Days</label><input type="number" name="min_trading_days" class="form-control" value="{{ old('min_trading_days', $program->min_trading_days) }}"></div>
    <div class="col-md-3 form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $program->sort_order ?? 0) }}"></div>
</div>
<div class="form-group pt-2">
    @foreach(['news_trading' => 'News Trading', 'weekend_holding' => 'Weekend Holding', 'ea_allowed' => 'EA Allowed', 'copy_trading' => 'Copy Trading', 'hedging' => 'Hedging', 'refund_available' => 'Refund Available'] as $field => $label)
    <div class="custom-control custom-checkbox custom-control-inline">
        <input type="checkbox" class="custom-control-input" id="prog_{{ $field }}" name="{{ $field }}" value="1" @checked(old($field, $program->{$field} ?? false))>
        <label class="custom-control-label" for="prog_{{ $field }}">{{ $label }}</label>
    </div>
    @endforeach
</div>
<div class="form-group"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $program->is_active ?? true))><label class="custom-control-label" for="is_active">Active</label></div></div>
<button type="submit" class="btn btn-primary">Save Program</button>
