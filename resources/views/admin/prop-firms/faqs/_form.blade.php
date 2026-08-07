<div class="form-group"><label>Prop Firm <span class="text-danger">*</span></label>
<select name="prop_firm_id" class="form-control" required>
    <option value="">Select firm</option>
    @foreach($propFirms as $firm)
        <option value="{{ $firm->id }}" @selected(old('prop_firm_id', $faq->prop_firm_id) == $firm->id)>{{ $firm->name }}</option>
    @endforeach
</select></div>
<div class="form-group"><label>Question <span class="text-danger">*</span></label><input type="text" name="question" class="form-control" required value="{{ old('question', $faq->question) }}"></div>
<div class="form-group"><label>Answer <span class="text-danger">*</span></label><textarea name="answer" class="form-control" rows="5" required>{{ old('answer', $faq->answer) }}</textarea></div>
<div class="row">
    <div class="col-md-4 form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"></div>
    <div class="col-md-4 form-group pt-4"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true))><label class="custom-control-label" for="is_active">Active</label></div></div>
</div>
<button type="submit" class="btn btn-primary">Save FAQ</button>
