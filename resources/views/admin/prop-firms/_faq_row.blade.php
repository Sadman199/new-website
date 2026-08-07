<div class="card mb-3 faq-row">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>FAQ</strong>
            <button type="button" class="btn btn-sm btn-outline-danger remove-faq"><i class="fas fa-times"></i></button>
        </div>
        @if(!empty($faq['id']))
            <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq['id'] }}">
        @endif
        <div class="form-group"><label>Question</label><input type="text" name="faqs[{{ $index }}][question]" class="form-control" value="{{ $faq['question'] ?? '' }}"></div>
        <div class="form-group"><label>Answer</label><textarea name="faqs[{{ $index }}][answer]" class="form-control" rows="3">{{ $faq['answer'] ?? '' }}</textarea></div>
        <div class="row">
            <div class="col-md-3 form-group"><label>Sort Order</label><input type="number" name="faqs[{{ $index }}][sort_order]" class="form-control" value="{{ $faq['sort_order'] ?? $index }}"></div>
            <div class="col-md-3 form-group pt-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="faq_active_{{ $index }}" name="faqs[{{ $index }}][is_active]" value="1" @checked(($faq['is_active'] ?? true))>
                    <label class="custom-control-label" for="faq_active_{{ $index }}">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
