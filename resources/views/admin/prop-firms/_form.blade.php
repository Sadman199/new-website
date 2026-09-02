@php
    $isEdit = $propFirm->exists;
    $selectedAttributes = old('attribute_ids', $isEdit ? $propFirm->attributes->pluck('id')->all() : []);
    $programs = old('programs', $isEdit ? $propFirm->programs->toArray() : []);
    $faqs = old('faqs', $isEdit ? $propFirm->faqs->toArray() : []);
    if (empty($programs)) { $programs = [[]]; }
    if (empty($faqs)) { $faqs = [[]]; }
@endphp

<div class="ab-form">
    <section class="ab-section" id="identity">
        <div class="ab-section__head">
            <h2>1. Identity</h2>
            <p>Name, slug, category, website, logos, and the public description.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name', $propFirm->name) }}">
                    @error('name')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $propFirm->slug) }}" placeholder="auto from name" @if($isEdit) data-autogen="off" @endif>
                    @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="prop_firm_category_id">Category</label>
                    <select name="prop_firm_category_id" id="prop_firm_category_id" class="form-control">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('prop_firm_category_id', $propFirm->prop_firm_category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" class="form-control" value="{{ old('website', $propFirm->website) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="affiliate_link">Affiliate Link</label>
                    <input type="url" name="affiliate_link" id="affiliate_link" class="form-control" value="{{ old('affiliate_link', $propFirm->affiliate_link) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="founded_year">Founded Year</label>
                    <input type="number" name="founded_year" id="founded_year" class="form-control" min="1900" max="{{ date('Y')+1 }}" value="{{ old('founded_year', $propFirm->founded_year) }}">
                </div>
                <div class="col-md-8 form-group">
                    <label for="headquarters">Headquarters</label>
                    <input type="text" name="headquarters" id="headquarters" class="form-control" value="{{ old('headquarters', $propFirm->headquarters) }}">
                </div>
                <div class="col-12 form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control snote" rows="6">{{ old('description', $propFirm->description) }}</textarea>
                </div>
                <div class="col-md-4">
                    @include('admin.partials._image_upload_preview', ['inputId' => 'logo', 'previewId' => 'logo_preview', 'label' => 'Logo', 'currentUrl' => $propFirm->mediaUrl()])
                </div>
                <div class="col-md-4">
                    @include('admin.partials._image_upload_preview', ['inputId' => 'cover_image', 'previewId' => 'cover_preview', 'label' => 'Cover Image', 'currentUrl' => $propFirm->mediaUrl($propFirm->cover_image)])
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="funding">
        <div class="ab-section__head">
            <h2>2. Funding</h2>
            <p>Headline funding, fees, and whether scaling is available.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-4 form-group"><label for="max_funding">Maximum Funding</label><input type="text" name="max_funding" id="max_funding" class="form-control" value="{{ old('max_funding', $propFirm->max_funding) }}"></div>
                <div class="col-md-4 form-group"><label for="profit_split">Profit Split</label><input type="text" name="profit_split" id="profit_split" class="form-control" value="{{ old('profit_split', $propFirm->profit_split) }}"></div>
                <div class="col-md-4 form-group"><label for="min_fee">Minimum Fee</label><input type="number" step="0.01" name="min_fee" id="min_fee" class="form-control" value="{{ old('min_fee', $propFirm->min_fee) }}"></div>
                <div class="col-md-4 form-group"><label for="max_fee">Maximum Fee</label><input type="number" step="0.01" name="max_fee" id="max_fee" class="form-control" value="{{ old('max_fee', $propFirm->max_fee) }}"></div>
                <div class="col-md-4 form-group d-flex align-items-end">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="scaling_available" value="0">
                        <input type="checkbox" class="custom-control-input" id="scaling_available" name="scaling_available" value="1" @checked(old('scaling_available', $propFirm->scaling_available))>
                        <label class="custom-control-label" for="scaling_available">Scaling Available</label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="ratings">
        <div class="ab-section__head">
            <h2>3. Ratings</h2>
            <p>Trust and review scores shown on the public listing.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-3 form-group"><label for="trust_score">Trust Score</label><input type="number" step="0.1" min="0" max="10" name="trust_score" id="trust_score" class="form-control" value="{{ old('trust_score', $propFirm->trust_score) }}"></div>
                <div class="col-md-3 form-group"><label for="editor_rating">Editor Rating</label><input type="number" step="0.1" min="0" max="10" name="editor_rating" id="editor_rating" class="form-control" value="{{ old('editor_rating', $propFirm->editor_rating) }}"></div>
                <div class="col-md-3 form-group"><label for="user_rating">User Rating</label><input type="number" step="0.1" min="0" max="10" name="user_rating" id="user_rating" class="form-control" value="{{ old('user_rating', $propFirm->user_rating) }}"></div>
                <div class="col-md-3 form-group mb-0"><label for="overall_rating">Overall Rating</label><input type="number" step="0.1" min="0" max="10" name="overall_rating" id="overall_rating" class="form-control" value="{{ old('overall_rating', $propFirm->overall_rating) }}"></div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="programs">
        <div class="ab-section__head">
            <h2>4. Programs</h2>
            <p>Add unlimited funding programs for this prop firm.</p>
        </div>
        <div class="ab-section__body">
            <div class="ab-repeater" id="programs-repeater">
                @foreach($programs as $i => $program)
                    @include('admin.prop-firms._program_row', ['index' => $i, 'program' => $program])
                @endforeach
            </div>
            <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm mt-3" id="add-program"><i class="fas fa-plus"></i> Add program</button>
        </div>
    </section>

    <section class="ab-section" id="attributes">
        <div class="ab-section__head">
            <h2>5. Attributes</h2>
            <p>Tags used on the public directory filters.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group mb-0">
                <label for="attribute_ids">Assign attributes</label>
                <select name="attribute_ids[]" id="attribute_ids" class="form-control select2" multiple data-placeholder="Search and select attributes…">
                    @foreach($attributes as $attr)
                        <option value="{{ $attr->id }}" @selected(in_array($attr->id, $selectedAttributes))>
                            @if($attr->group){{ $attr->group }} — @endif{{ $attr->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Manage attributes from the <a href="{{ route('admin_prop_firm_attributes_show') }}">Attributes</a> section.</small>
            </div>
        </div>
    </section>

    <section class="ab-section" id="faqs">
        <div class="ab-section__head">
            <h2>6. FAQs</h2>
            <p>Questions shown on the public firm page.</p>
        </div>
        <div class="ab-section__body">
            <div class="ab-repeater" id="faqs-repeater">
                @foreach($faqs as $i => $faq)
                    @include('admin.prop-firms._faq_row', ['index' => $i, 'faq' => $faq])
                @endforeach
            </div>
            <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm mt-3" id="add-faq"><i class="fas fa-plus"></i> Add FAQ</button>
        </div>
    </section>

    <section class="ab-section" id="seo">
        <div class="ab-section__head">
            <h2>7. SEO</h2>
            <p>Search and social metadata for this listing.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group"><label for="meta_title">Meta Title</label><input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $propFirm->meta_title) }}"></div>
            <div class="form-group"><label for="meta_description">Meta Description</label><textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description', $propFirm->meta_description) }}</textarea></div>
            <div class="form-group"><label for="meta_keywords">Meta Keywords</label><textarea name="meta_keywords" id="meta_keywords" class="form-control" rows="2">{{ old('meta_keywords', $propFirm->meta_keywords) }}</textarea></div>
            @include('admin.partials._image_upload_preview', ['inputId' => 'og_image', 'previewId' => 'og_preview', 'label' => 'Open Graph Image', 'currentUrl' => $propFirm->mediaUrl($propFirm->og_image)])
        </div>
    </section>

    <section class="ab-section" id="publish">
        <div class="ab-section__head">
            <h2>8. Publish</h2>
            <p>Visibility, badges, and listing order.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="sort_order">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-control" value="{{ old('sort_order', $propFirm->sort_order ?? 0) }}">
                </div>
                <div class="col-md-8 form-group d-flex align-items-end flex-wrap" style="gap:1.25rem;">
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $propFirm->is_featured))>
                        <label class="custom-control-label" for="is_featured">Featured</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_verified" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_verified" name="is_verified" value="1" @checked(old('is_verified', $propFirm->is_verified))>
                        <label class="custom-control-label" for="is_verified">Verified</label>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" @checked(old('is_active', $propFirm->exists ? $propFirm->is_active : true))>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="ab-save">
    <div class="ab-header__actions">
        <button type="submit" class="ab-btn ab-btn--primary">
            <i class="fas fa-save" aria-hidden="true"></i>
            {{ $isEdit ? 'Save changes' : 'Create prop firm' }}
        </button>
    </div>
    <a href="{{ route('admin_prop_firms_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
</div>

<template id="program-row-template">
    @include('admin.prop-firms._program_row', ['index' => '__INDEX__', 'program' => []])
</template>
<template id="faq-row-template">
    @include('admin.prop-firms._faq_row', ['index' => '__INDEX__', 'faq' => []])
</template>
