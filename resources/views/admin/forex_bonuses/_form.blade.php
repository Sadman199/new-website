@php
    $isEdit = $bonus->exists;
    $liveUrl = $isEdit ? $bonus->detailUrl() : null;
@endphp

<nav class="ab-section-nav" aria-label="Bonus sections">
    <a href="#basics" data-ab-nav class="is-active">Basics</a>
    <a href="#offer" data-ab-nav>Offer</a>
    <a href="#links" data-ab-nav>Links</a>
    <a href="#content" data-ab-nav>Content</a>
    <a href="#editorial" data-ab-nav>Editorial</a>
    <a href="#seo" data-ab-nav>SEO</a>
    <a href="#publish" data-ab-nav>Publish</a>
</nav>

<div class="ab-layout ab-layout--bonus">
    <div class="ab-form-main">
        <section class="ab-section" id="basics">
            <div class="ab-section__head">
                <h2>Basic information</h2>
                <p>Title, broker, type, and the image visitors see on listing cards.</p>
            </div>
            <div class="ab-section__body">
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="title">Bonus title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" required
                               value="{{ old('title', $bonus->title) }}" placeholder="e.g. 50% Welcome Deposit Bonus">
                        @error('title')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="slug">URL slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" required
                               value="{{ old('slug', $bonus->slug) }}" placeholder="auto from title"
                               @if($isEdit) data-autogen="off" @endif>
                        @error('slug')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="broker_id">Broker</label>
                        <select name="broker_id" id="broker_id" class="form-control">
                            <option value="">— No broker —</option>
                            @foreach($brokers as $broker)
                                <option value="{{ $broker->id }}" @selected((string) old('broker_id', $bonus->broker_id) === (string) $broker->id)>{{ $broker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="promo_type">Bonus type <span class="text-danger">*</span></label>
                        <select name="promo_type" id="promo_type" class="form-control" required>
                            @foreach($promoTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('promo_type', $bonus->promo_type) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="publish_date">Publish date <span class="text-danger">*</span></label>
                        <input type="date" name="publish_date" id="publish_date" class="form-control @error('publish_date') is-invalid @enderror" required
                               value="{{ old('publish_date', optional($bonus->publish_date)->format('Y-m-d') ?: date('Y-m-d')) }}">
                        @error('publish_date')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="author_name">Display author</label>
                        <input type="text" name="author_name" id="author_name" class="form-control"
                               value="{{ old('author_name', $bonus->author_name) }}" placeholder="Optional — filled from Written credit">
                    </div>
                    <div class="col-md-6 form-group mb-0">
                        @include('admin.partials._image_upload_preview', [
                            'inputId' => 'feature_image',
                            'previewId' => 'image_preview',
                            'label' => 'Feature image',
                            'required' => ! $isEdit,
                            'currentUrl' => $bonus->imageUrl(),
                            'hint' => 'JPG, PNG, WEBP, AVIF — max 5MB. Leave empty on edit to keep the current image.',
                        ])
                        @error('feature_image')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="ab-section" id="offer">
            <div class="ab-section__head">
                <h2>Offer details</h2>
                <p>Numbers shown on the card: deposit, bonus size, wagering, and who can claim it.</p>
            </div>
            <div class="ab-section__body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="min_deposit">Minimum deposit ($)</label>
                        <input type="number" step="0.01" min="0" name="min_deposit" id="min_deposit" class="form-control"
                               value="{{ old('min_deposit', $bonus->min_deposit) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="bonus_amount">Bonus amount ($)</label>
                        <input type="number" step="0.01" min="0" name="bonus_amount" id="bonus_amount" class="form-control"
                               value="{{ old('bonus_amount', $bonus->bonus_amount) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="bonus_percentage">Bonus percentage (%)</label>
                        <input type="number" step="0.01" min="0" name="bonus_percentage" id="bonus_percentage" class="form-control"
                               value="{{ old('bonus_percentage', $bonus->bonus_percentage) }}">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="max_credit">Maximum credit ($)</label>
                        <input type="number" step="0.01" min="0" name="max_credit" id="max_credit" class="form-control"
                               value="{{ old('max_credit', $bonus->max_credit) }}" placeholder="e.g. 500">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="wagering_requirement">Wagering requirement</label>
                        <input type="text" name="wagering_requirement" id="wagering_requirement" class="form-control"
                               value="{{ old('wagering_requirement', $bonus->wagering_requirement) }}" placeholder="e.g. 30x bonus">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="volume_requirement">Volume requirement</label>
                        <input type="text" name="volume_requirement" id="volume_requirement" class="form-control"
                               value="{{ old('volume_requirement', $bonus->volume_requirement) }}" placeholder="e.g. 10 standard lots">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="eligible_clients">Who can claim it</label>
                        <select name="eligible_clients" id="eligible_clients" class="form-control">
                            <option value="">Not specified</option>
                            <option value="new" @selected(old('eligible_clients', $bonus->eligible_clients) === 'new')>New clients</option>
                            <option value="existing" @selected(old('eligible_clients', $bonus->eligible_clients) === 'existing')>Existing clients</option>
                            <option value="both" @selected(old('eligible_clients', $bonus->eligible_clients) === 'both')>New and existing</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="bonus_category">Category label</label>
                        <input type="text" name="bonus_category" id="bonus_category" class="form-control"
                               value="{{ old('bonus_category', $bonus->bonus_category) }}" placeholder="Optional extra label">
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="expiry_date">Expiry date</label>
                        <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                               value="{{ old('expiry_date', optional($bonus->expiry_date)->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </section>

        <section class="ab-section" id="links">
            <div class="ab-section__head">
                <h2>Links</h2>
                <p>Where the claim button goes, plus optional affiliate and terms URLs.</p>
            </div>
            <div class="ab-section__body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="link">Offer link <span class="text-danger">*</span></label>
                        <input type="url" name="link" id="link" class="form-control @error('link') is-invalid @enderror" required
                               value="{{ old('link', $bonus->link) }}" placeholder="https://">
                        @error('link')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="affiliate_link">Affiliate link</label>
                        <input type="url" name="affiliate_link" id="affiliate_link" class="form-control @error('affiliate_link') is-invalid @enderror"
                               value="{{ old('affiliate_link', $bonus->affiliate_link) }}" placeholder="https://">
                        @error('affiliate_link')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-12 form-group mb-0">
                        <label for="terms_conditions_url">Terms and conditions URL</label>
                        <input type="url" name="terms_conditions_url" id="terms_conditions_url" class="form-control @error('terms_conditions_url') is-invalid @enderror"
                               value="{{ old('terms_conditions_url', $bonus->terms_conditions_url) }}" placeholder="https://">
                        @error('terms_conditions_url')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="ab-section" id="content">
            <div class="ab-section__head">
                <h2>Page content</h2>
                <p>These blocks appear on the public bonus page. Required fields are marked.</p>
            </div>
            <div class="ab-section__body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="prize">Prize / offer headline <span class="text-danger">*</span></label>
                        <textarea name="prize" id="prize" class="form-control snote @error('prize') is-invalid @enderror" rows="4" required>{{ old('prize', $bonus->prize) }}</textarea>
                        @error('prize')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control snote @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $bonus->description) }}</textarea>
                        @error('description')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="how_to_participate">How to take part <span class="text-danger">*</span></label>
                        <textarea name="how_to_participate" id="how_to_participate" class="form-control snote @error('how_to_participate') is-invalid @enderror" rows="4" required>{{ old('how_to_participate', $bonus->how_to_participate) }}</textarea>
                        @error('how_to_participate')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="participate">Country restrictions <span class="text-danger">*</span></label>
                        <textarea name="participate" id="participate" class="form-control snote @error('participate') is-invalid @enderror" rows="4" required>{{ old('participate', $bonus->participate) }}</textarea>
                        @error('participate')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="details">Details <span class="text-danger">*</span></label>
                        <textarea name="details" id="details" class="form-control snote @error('details') is-invalid @enderror" rows="4" required>{{ old('details', $bonus->details) }}</textarea>
                        @error('details')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="general_terms">General terms <span class="text-danger">*</span></label>
                        <textarea name="general_terms" id="general_terms" class="form-control snote @error('general_terms') is-invalid @enderror" rows="4" required>{{ old('general_terms', $bonus->general_terms) }}</textarea>
                        @error('general_terms')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="eligibility_criteria">Eligibility</label>
                        <textarea name="eligibility_criteria" id="eligibility_criteria" class="form-control snote" rows="4">{{ old('eligibility_criteria', $bonus->eligibility_criteria) }}</textarea>
                    </div>
                    <div class="col-md-6 form-group mb-0">
                        <label for="bonus_type_details">Type notes</label>
                        <textarea name="bonus_type_details" id="bonus_type_details" class="form-control snote" rows="4">{{ old('bonus_type_details', $bonus->bonus_type_details) }}</textarea>
                    </div>
                </div>
            </div>
        </section>

        <section class="ab-section" id="editorial">
            <div class="ab-section__head">
                <h2>Editorial credits</h2>
                <p>Who wrote, edited, and fact-checked this bonus page.</p>
            </div>
            <div class="ab-section__body">
                @include('admin.partials._editorial_fields', ['model' => $isEdit ? $bonus : null, 'editorialOptions' => $editorialOptions])
            </div>
        </section>

        <section class="ab-section" id="seo">
            <div class="ab-section__head">
                <h2>Search listing</h2>
                <p>Leave blank to use the bonus title. These appear in Google results.</p>
            </div>
            <div class="ab-section__body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="meta_title">Search title</label>
                        <input type="text" name="meta_title" id="meta_title" class="form-control" maxlength="255"
                               value="{{ old('meta_title', $bonus->meta_title) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="meta_keywords">Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control"
                               value="{{ old('meta_keywords', $bonus->meta_keywords) }}" placeholder="forex, deposit bonus">
                    </div>
                    <div class="col-md-12 form-group mb-0">
                        <label for="meta_description">Search description</label>
                        <textarea name="meta_description" id="meta_description" class="form-control" rows="3" maxlength="500">{{ old('meta_description', $bonus->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <aside class="ab-side" id="publish">
        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Publish</h2>
                <p>Status and homepage placement.</p>
            </div>
            <div class="ab-section__body">
                <div class="form-group">
                    <label for="promotion_status">Status</label>
                    <select name="promotion_status" id="promotion_status" class="form-control">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(old('promotion_status', $bonus->promotion_status ?: 'ongoing') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="ab-check">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $bonus->is_featured))>
                    Featured on homepage
                </label>
                @if($isEdit)
                    <p class="ab-note mt-3 mb-0">Last saved {{ $bonus->updated_at?->format('M j, Y H:i') }}</p>
                @endif
            </div>
        </section>
        <div class="ab-save ab-save--side">
            <button type="submit" class="ab-btn ab-btn--primary">
                <i class="fas fa-save" aria-hidden="true"></i>
                {{ $isEdit ? 'Save changes' : 'Create Bonus' }}
            </button>
            @if($liveUrl)
                <a href="{{ $liveUrl }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
            @endif
            <a href="{{ route('admin_forex_bonus_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
        </div>
    </aside>
</div>
