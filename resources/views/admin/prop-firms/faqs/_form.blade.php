@php $isEdit = $faq->exists; @endphp
<div class="ab-form">
    <section class="ab-section" id="content">
        <div class="ab-section__head"><h2>FAQ</h2><p>Question and answer shown on the public firm page.</p></div>
        <div class="ab-section__body">
            <div class="form-group">
                <label for="prop_firm_id">Prop Firm <span class="text-danger">*</span></label>
                <select name="prop_firm_id" id="prop_firm_id" class="form-control" required>
                    <option value="">Select firm</option>
                    @foreach($propFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(old('prop_firm_id', $faq->prop_firm_id) == $firm->id)>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group"><label>Question <span class="text-danger">*</span></label><input type="text" name="question" class="form-control" required value="{{ old('question', $faq->question) }}"></div>
            <div class="form-group"><label>Answer <span class="text-danger">*</span></label><textarea name="answer" class="form-control snote" rows="6" required>{{ old('answer', $faq->answer) }}</textarea></div>
            <div class="row">
                <div class="col-md-4 form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"></div>
                <div class="col-md-4 form-group d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $faq->is_active ?? true))>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="ab-save">
    <button type="submit" class="ab-btn ab-btn--primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Save FAQ' : 'Create FAQ' }}</button>
    <a href="{{ route('admin_prop_firm_faqs_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>
