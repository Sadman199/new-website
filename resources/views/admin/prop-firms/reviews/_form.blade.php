<div class="form-group"><label>Prop Firm <span class="text-danger">*</span></label>
<select name="prop_firm_id" class="form-control" required>
    <option value="">Select firm</option>
    @foreach($propFirms as $firm)
        <option value="{{ $firm->id }}" @selected(old('prop_firm_id', $review->prop_firm_id) == $firm->id)>{{ $firm->name }}</option>
    @endforeach
</select></div>
<div class="row">
    <div class="col-md-3 form-group"><label>Rating <span class="text-danger">*</span></label><input type="number" step="0.1" min="0" max="5" name="rating" class="form-control" required value="{{ old('rating', $review->rating) }}"></div>
    <div class="col-md-3 form-group"><label>Status <span class="text-danger">*</span></label>
    <select name="status" class="form-control" required>
        @foreach(['pending','approved','rejected'] as $status)
        <option value="{{ $status }}" @selected(old('status', $review->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select></div>
    <div class="col-md-6 form-group"><label>Author</label><input type="text" name="author" class="form-control" value="{{ old('author', $review->author) }}"></div>
</div>
<div class="form-group"><label>Title <span class="text-danger">*</span></label><input type="text" name="title" class="form-control" required value="{{ old('title', $review->title) }}"></div>
<div class="form-group"><label>Content <span class="text-danger">*</span></label><textarea name="content" class="form-control" rows="6" required>{{ old('content', $review->content) }}</textarea></div>
<button type="submit" class="btn btn-primary">Save Review</button>
