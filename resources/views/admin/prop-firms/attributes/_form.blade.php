@php $isEdit = $attribute->exists; @endphp
<div class="ab-form">
    <section class="ab-section" id="content">
        <div class="ab-section__head"><h2>Attribute</h2><p>Name, slug, and optional group used on directory filters.</p></div>
        <div class="ab-section__body">
            <div class="form-group"><label for="name">Name <span class="text-danger">*</span></label><input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $attribute->name) }}"></div>
            <div class="form-group"><label for="slug">Slug</label><input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $attribute->slug) }}" placeholder="auto from name" @if($isEdit) data-autogen="off" @endif></div>
            <div class="form-group"><label for="group">Group</label><input type="text" name="group" id="group" class="form-control" value="{{ old('group', $attribute->group) }}" placeholder="e.g. Platform, Funding Type"></div>
            <div class="row">
                <div class="col-md-4 form-group"><label for="sort_order">Sort Order</label><input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $attribute->sort_order ?? 0) }}"></div>
                <div class="col-md-4 form-group d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $attribute->is_active ?? true))>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="ab-save">
    <button type="submit" class="ab-btn ab-btn--primary"><i class="fas fa-save" aria-hidden="true"></i> {{ $isEdit ? 'Save attribute' : 'Create attribute' }}</button>
    <a href="{{ route('admin_prop_firm_attributes_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>
