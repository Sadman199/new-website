<div class="form-group"><label>Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required value="{{ old('name', $attribute->name) }}"></div>
<div class="form-group"><label>Slug</label><input type="text" name="slug" class="form-control" value="{{ old('slug', $attribute->slug) }}"></div>
<div class="form-group"><label>Group</label><input type="text" name="group" class="form-control" value="{{ old('group', $attribute->group) }}" placeholder="e.g. Platform, Funding Type"></div>
<div class="row"><div class="col-md-4 form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $attribute->sort_order ?? 0) }}"></div>
<div class="col-md-4 form-group pt-4"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $attribute->is_active ?? true))><label class="custom-control-label" for="is_active">Active</label></div></div></div>
<button type="submit" class="btn btn-primary">Save Attribute</button>
