@php $isEdit = $review->exists; @endphp
<div class="ab-form">
    <section class="ab-section" id="content">
        <div class="ab-section__head"><h2>Review</h2><p>Rating, status, and the published copy.</p></div>
        <div class="ab-section__body">
            <div class="form-group">
                <label for="prop_firm_id">Prop Firm <span class="text-danger">*</span></label>
                <select name="prop_firm_id" id="prop_firm_id" class="form-control" required>
                    <option value="">Select firm</option>
                    @foreach($propFirms as $firm)
                        <option value="{{ $firm->id }}" @selected(old('prop_firm_id', $review->prop_firm_id) == $firm->id)>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col-md-3 form-group"><label>Rating <span class="text-danger">*</span></label><input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" required value="{{ old('rating', $review->rating) }}"></div>
                <div class="col-md-3 form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" required>
                        @foreach(['pending','approved','rejected'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $review->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group"><label>Author</label><input type="text" name="author" class="form-control" value="{{ old('author', $review->author) }}"></div>
            </div>
            <div class="form-group"><label for="title">Title <span class="text-danger">*</span></label><input type="text" name="title" id="title" class="form-control" required value="{{ old('title', $review->title) }}"></div>
            <div class="form-group mb-0"><label>Content <span class="text-danger">*</span></label><textarea name="content" class="form-control snote" rows="8" required>{{ old('content', $review->content) }}</textarea></div>
        </div>
    </section>
</div>
<div class="ab-save">
    <button type="submit" class="ab-btn ab-btn--primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Save review' : 'Create review' }}</button>
    <a href="{{ route('admin_prop_firm_reviews_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>
