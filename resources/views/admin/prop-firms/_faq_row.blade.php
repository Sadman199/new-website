<div class="ab-repeater__item faq-row">
    <div class="ab-repeater__head">
        <strong>FAQ</strong>
        <button type="button" class="ab-btn ab-btn--danger ab-btn--sm remove-faq"><i class="fas fa-times"></i></button>
    </div>
    @if(!empty($faq['id']))
        <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq['id'] }}">
    @endif
    <div class="form-group"><label>Question</label><input type="text" name="faqs[{{ $index }}][question]" class="form-control" value="{{ $faq['question'] ?? '' }}"></div>
    <div class="form-group"><label>Answer</label><textarea name="faqs[{{ $index }}][answer]" class="form-control snote" data-admin-editor="compact" rows="4">{{ $faq['answer'] ?? '' }}</textarea></div>
    <div class="row">
        <div class="col-md-3 form-group"><label>Sort Order</label><input type="number" name="faqs[{{ $index }}][sort_order]" class="form-control" value="{{ $faq['sort_order'] ?? $index }}"></div>
        <div class="col-md-3 form-group d-flex align-items-end">
            <div class="custom-control custom-checkbox mb-3">
                <input type="hidden" name="faqs[{{ $index }}][is_active]" value="0">
                <input type="checkbox" class="custom-control-input" id="faq_active_{{ $index }}" name="faqs[{{ $index }}][is_active]" value="1" @checked(($faq['is_active'] ?? true))>
                <label class="custom-control-label" for="faq_active_{{ $index }}">Active</label>
            </div>
        </div>
    </div>
</div>
