@php
    $selectedAttributes = old('attribute_ids', $propFirm->exists ? $propFirm->attributes->pluck('id')->all() : []);
    $programs = old('programs', $propFirm->exists ? $propFirm->programs->toArray() : []);
    $faqs = old('faqs', $propFirm->exists ? $propFirm->faqs->toArray() : []);
    if (empty($programs)) { $programs = [[]]; }
    if (empty($faqs)) { $faqs = [[]]; }
@endphp

<ul class="nav nav-tabs mb-4" id="propFirmTabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-basic">Basic</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-funding">Funding</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-ratings">Ratings</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-programs">Programs</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-attributes">Attributes</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-faqs">FAQs</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-seo">SEO</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-status">Status</a></li>
</ul>

<div class="tab-content">
    {{-- Basic --}}
    <div class="tab-pane fade show active" id="tab-basic">
        <div class="row">
            <div class="col-md-4 form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $propFirm->name) }}">
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $propFirm->slug) }}" placeholder="auto from name">
                @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4 form-group">
                <label>Category</label>
                <select name="prop_firm_category_id" class="form-control">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('prop_firm_category_id', $propFirm->prop_firm_category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label>Website</label>
                <input type="url" name="website" class="form-control" value="{{ old('website', $propFirm->website) }}">
            </div>
            <div class="col-md-6 form-group">
                <label>Affiliate Link</label>
                <input type="url" name="affiliate_link" class="form-control" value="{{ old('affiliate_link', $propFirm->affiliate_link) }}">
            </div>
            <div class="col-md-4 form-group">
                <label>Founded Year</label>
                <input type="number" name="founded_year" class="form-control" min="1900" max="{{ date('Y')+1 }}" value="{{ old('founded_year', $propFirm->founded_year) }}">
            </div>
            <div class="col-md-8 form-group">
                <label>Headquarters</label>
                <input type="text" name="headquarters" class="form-control" value="{{ old('headquarters', $propFirm->headquarters) }}">
            </div>
            <div class="col-12 form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $propFirm->description) }}</textarea>
            </div>
            <div class="col-md-4">
                @include('admin.partials._image_upload_preview', ['inputId' => 'logo', 'previewId' => 'logo_preview', 'label' => 'Logo', 'currentUrl' => $propFirm->logo ? asset($propFirm->logo) : null])
            </div>
            <div class="col-md-4">
                @include('admin.partials._image_upload_preview', ['inputId' => 'cover_image', 'previewId' => 'cover_preview', 'label' => 'Cover Image', 'currentUrl' => $propFirm->cover_image ? asset($propFirm->cover_image) : null])
            </div>
        </div>
    </div>

    {{-- Funding --}}
    <div class="tab-pane fade" id="tab-funding">
        <div class="row">
            <div class="col-md-4 form-group"><label>Maximum Funding</label><input type="text" name="max_funding" class="form-control" value="{{ old('max_funding', $propFirm->max_funding) }}"></div>
            <div class="col-md-4 form-group"><label>Profit Split</label><input type="text" name="profit_split" class="form-control" value="{{ old('profit_split', $propFirm->profit_split) }}"></div>
            <div class="col-md-4 form-group"><label>Minimum Fee</label><input type="number" step="0.01" name="min_fee" class="form-control" value="{{ old('min_fee', $propFirm->min_fee) }}"></div>
            <div class="col-md-4 form-group"><label>Maximum Fee</label><input type="number" step="0.01" name="max_fee" class="form-control" value="{{ old('max_fee', $propFirm->max_fee) }}"></div>
            <div class="col-md-4 form-group pt-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="scaling_available" name="scaling_available" value="1" @checked(old('scaling_available', $propFirm->scaling_available))>
                    <label class="custom-control-label" for="scaling_available">Scaling Available</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Ratings --}}
    <div class="tab-pane fade" id="tab-ratings">
        <div class="row">
            <div class="col-md-3 form-group"><label>Trust Score</label><input type="number" step="0.1" min="0" max="10" name="trust_score" class="form-control" value="{{ old('trust_score', $propFirm->trust_score) }}"></div>
            <div class="col-md-3 form-group"><label>Editor Rating</label><input type="number" step="0.1" min="0" max="10" name="editor_rating" class="form-control" value="{{ old('editor_rating', $propFirm->editor_rating) }}"></div>
            <div class="col-md-3 form-group"><label>User Rating</label><input type="number" step="0.1" min="0" max="10" name="user_rating" class="form-control" value="{{ old('user_rating', $propFirm->user_rating) }}"></div>
            <div class="col-md-3 form-group"><label>Overall Rating</label><input type="number" step="0.1" min="0" max="10" name="overall_rating" class="form-control" value="{{ old('overall_rating', $propFirm->overall_rating) }}"></div>
        </div>
    </div>

    {{-- Programs --}}
    <div class="tab-pane fade" id="tab-programs">
        <p class="text-muted small">Add unlimited funding programs for this prop firm.</p>
        <div id="programs-repeater">
            @foreach($programs as $i => $program)
                @include('admin.prop-firms._program_row', ['index' => $i, 'program' => $program])
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-program"><i class="fas fa-plus"></i> Add Program</button>
    </div>

    {{-- Attributes --}}
    <div class="tab-pane fade" id="tab-attributes">
        <div class="form-group">
            <label>Assign Attributes</label>
            <select name="attribute_ids[]" class="form-control select2" multiple data-placeholder="Search and select attributes…">
                @foreach($attributes as $attr)
                    <option value="{{ $attr->id }}" @selected(in_array($attr->id, $selectedAttributes))>
                        @if($attr->group){{ $attr->group }} — @endif{{ $attr->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Manage attributes from <a href="{{ route('admin_prop_firm_attributes_show') }}">Attributes</a> section.</small>
        </div>
    </div>

    {{-- FAQs --}}
    <div class="tab-pane fade" id="tab-faqs">
        <div id="faqs-repeater">
            @foreach($faqs as $i => $faq)
                @include('admin.prop-firms._faq_row', ['index' => $i, 'faq' => $faq])
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-faq"><i class="fas fa-plus"></i> Add FAQ</button>
    </div>

    {{-- SEO --}}
    <div class="tab-pane fade" id="tab-seo">
        <div class="form-group"><label>Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $propFirm->meta_title) }}"></div>
        <div class="form-group"><label>Meta Description</label><textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $propFirm->meta_description) }}</textarea></div>
        <div class="form-group"><label>Meta Keywords</label><textarea name="meta_keywords" class="form-control" rows="2">{{ old('meta_keywords', $propFirm->meta_keywords) }}</textarea></div>
        @include('admin.partials._image_upload_preview', ['inputId' => 'og_image', 'previewId' => 'og_preview', 'label' => 'Open Graph Image', 'currentUrl' => $propFirm->og_image ? asset($propFirm->og_image) : null])
    </div>

    {{-- Status --}}
    <div class="tab-pane fade" id="tab-status">
        <div class="row">
            <div class="col-md-4 form-group"><label>Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $propFirm->sort_order ?? 0) }}"></div>
            <div class="col-md-8 form-group pt-4">
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $propFirm->is_featured))>
                    <label class="custom-control-label" for="is_featured">Featured</label>
                </div>
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="is_verified" name="is_verified" value="1" @checked(old('is_verified', $propFirm->is_verified))>
                    <label class="custom-control-label" for="is_verified">Verified</label>
                </div>
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $propFirm->exists ? $propFirm->is_active : true))>
                    <label class="custom-control-label" for="is_active">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Prop Firm</button>
</div>

<template id="program-row-template">
    @include('admin.prop-firms._program_row', ['index' => '__INDEX__', 'program' => []])
</template>
<template id="faq-row-template">
    @include('admin.prop-firms._faq_row', ['index' => '__INDEX__', 'faq' => []])
</template>
