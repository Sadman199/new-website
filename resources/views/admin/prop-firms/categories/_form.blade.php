@php $isEdit = $category->exists; @endphp
<div class="ab-form">
    <section class="ab-section" id="content">
        <div class="ab-section__head">
            <h2>Category</h2>
            <p>Name, URL slug, and whether it appears on the public index.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name', $category->name) }}">
                @error('name')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $category->slug) }}" placeholder="auto from name" @if($isEdit) data-autogen="off" @endif>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control snote" data-admin-editor="compact" rows="4">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                </div>
                <div class="col-md-4 form-group d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="ab-save">
    <button type="submit" class="ab-btn ab-btn--primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Save category' : 'Create category' }}</button>
    <a href="{{ route('admin_prop_firm_categories_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>
