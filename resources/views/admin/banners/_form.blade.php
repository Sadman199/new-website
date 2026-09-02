@php
    $isEdit = $banner->exists;
    $format = old('creative_format', $banner->creative_format ?: 'image');
    $targetingValue = old('targeting', $banner->targeting);
    $needsPage = $targetingValue === 'specific_page';
    $brokerMultiple = $targetingValue === 'multiple_brokers';
    $selectedBrokers = collect(old('broker_ids', $banner->relationLoaded('brokers') ? $banner->brokers->pluck('id')->all() : []))->map(fn ($id) => (string) $id);
    $selectedBrokerId = (string) old('broker_id', $selectedBrokers->first() ?? '');
    $currentPagePath = old('page_path', $banner->page_path);
    $placementGroups = $placementGroups ?? [];
    if ($placementGroups === []) {
        $placementGroups = \App\Support\BannerCatalog::placementGroups(old('placement', $banner->placement));
    }
    $pagePaths = $pagePaths ?? [];
    $starterHtml = '<div style="padding:20px 24px;border-radius:16px;background:linear-gradient(135deg,#1c1e24,#2d241c);color:#fff;font-family:Nunito Sans,sans-serif;">
  <p style="margin:0 0 6px;font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#ffd9b0;">Offer</p>
  <h3 style="margin:0 0 8px;font-size:22px;line-height:1.2;">Your headline</h3>
  <p style="margin:0 0 14px;color:rgba(255,255,255,.78);">Short supporting copy for this campaign.</p>
  <a href="https://" style="display:inline-block;padding:8px 14px;border-radius:999px;background:#e8822a;color:#fff;font-weight:700;text-decoration:none;">Learn more</a>
</div>';
@endphp

<div class="ab-banner-form__stack">
    <section class="ab-section" id="basics">
        <div class="ab-section__head">
            <h2>Basics</h2>
            <p>Name the banner and choose how the creative is built.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-lg-8 form-group">
                    <label for="title">Banner title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" required
                           value="{{ old('title', $banner->title) }}" placeholder="e.g. Spring trading promo">
                    @error('title')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-lg-4 form-group">
                    <label for="banner_type">Banner type <span class="text-danger">*</span></label>
                    <select name="banner_type" id="banner_type" class="form-control @error('banner_type') is-invalid @enderror" required>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" @selected(old('banner_type', $banner->banner_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('banner_type')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
            </div>

            <fieldset class="ab-banner-format">
                <legend>Creative format <span class="text-danger">*</span></legend>
                <div class="ab-banner-format__grid">
                    @foreach($formats as $value => $label)
                        <label class="ab-banner-choice {{ $format === $value ? 'is-selected' : '' }}">
                            <input type="radio" name="creative_format" value="{{ $value }}" @checked($format === $value)>
                            <span>
                                <strong>{{ $label }}</strong>
                                <small>
                                    @if($value === 'html')
                                        Write or paste HTML. No image required.
                                    @else
                                        Upload desktop artwork. Mobile is optional.
                                    @endif
                                </small>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('creative_format')<small class="ab-error">{{ $message }}</small>@enderror
            </fieldset>
        </div>
    </section>

    <section class="ab-section" id="banner-placement">
        <div class="ab-section__head">
            <h2>Placement</h2>
            <p>Choose where this banner appears on the website.</p>
        </div>
        <div class="ab-section__body">
            <div class="form-group mb-0 ab-banner-field--full">
                <label for="banner_placement">Placement <span class="text-danger">*</span></label>
                <select name="placement" id="banner_placement" class="form-control ab-banner-placement-select @error('placement') is-invalid @enderror" required>
                    <option value="">Select a page or section</option>
                    @foreach($placementGroups as $group => $items)
                        <optgroup label="{{ $group }}">
                            @foreach($items as $value => $label)
                                <option value="{{ $value }}" @selected(old('placement', $banner->placement) === $value)>{{ $label }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <p class="ab-note mb-0 mt-1">Open the dropdown to pick homepage, a site page, a CMS page, or a blog section.</p>
                @error('placement')<small class="ab-error">{{ $message }}</small>@enderror
            </div>
        </div>
    </section>

    <section class="ab-section" id="creative" data-banner-images @if($format === 'html') hidden @endif>
        <div class="ab-section__head">
            <h2>Images</h2>
            <p>Desktop is required for image banners. Mobile is optional.</p>
        </div>
        <div class="ab-section__body">
            <div class="ab-banner-uploads">
                <div class="ab-banner-upload">
                    @include('admin.partials._image_upload_preview', [
                        'inputId' => 'desktop_image',
                        'previewId' => 'desktop_image_preview',
                        'label' => 'Desktop image',
                        'required' => false,
                        'currentUrl' => $banner->desktopImageUrl(),
                        'hint' => 'JPG, PNG, or WEBP. Max 5MB. Recommended wide landscape.',
                    ])
                    @error('desktop_image')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="ab-banner-upload">
                    @include('admin.partials._image_upload_preview', [
                        'inputId' => 'mobile_image',
                        'previewId' => 'mobile_image_preview',
                        'label' => 'Mobile image',
                        'required' => false,
                        'currentUrl' => $banner->mobileImageUrl(),
                        'hint' => 'Optional. Desktop is used if this is empty.',
                    ])
                    @error('mobile_image')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="html" data-banner-html @if($format !== 'html') hidden @endif>
        <div class="ab-section__head">
            <h2>HTML template</h2>
            <p>Write or paste HTML. Preview updates as you type. Scripts are blocked in the preview.</p>
        </div>
        <div class="ab-section__body">
            <div class="ab-html-toolbar">
                <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" data-banner-html-starter>Insert starter layout</button>
                <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" data-banner-html-refresh>Refresh preview</button>
            </div>
            <div class="ab-html-grid">
                <div class="ab-html-editor">
                    <label for="html_content">HTML code <span class="text-danger">*</span></label>
                    <textarea name="html_content" id="html_content" class="form-control ab-html-code @error('html_content') is-invalid @enderror" rows="16" data-admin-editor="off" spellcheck="false" placeholder="<div>Your banner HTML</div>">{{ old('html_content', $banner->html_content) }}</textarea>
                    @error('html_content')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="ab-html-preview-wrap">
                    <p class="ab-html-preview-label">Preview</p>
                    <iframe class="ab-html-preview" data-banner-html-preview title="HTML banner preview" sandbox="allow-same-origin"></iframe>
                </div>
            </div>
            <textarea hidden data-banner-html-starter-source>{{ $starterHtml }}</textarea>
        </div>
    </section>

    <section class="ab-section" id="targeting">
        <div class="ab-section__head">
            <h2>Who should see it</h2>
            <p>Everyone, one broker, several brokers, or only one extra URL.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="targeting">Audience <span class="text-danger">*</span></label>
                    <select name="targeting" id="targeting" class="form-control @error('targeting') is-invalid @enderror" required>
                        @foreach($targetingOptions as $value => $label)
                            <option value="{{ $value }}" @selected($targetingValue === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('targeting')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-12 form-group ab-banner-broker-box" data-banner-brokers>
                    <label for="broker_id">Select broker <span class="text-danger">*</span></label>
                    <select name="broker_id" id="broker_id" class="form-control select2 @error('broker_id') is-invalid @enderror @error('broker_ids') is-invalid @enderror" data-placeholder="Type a broker name">
                        <option value="">Select a broker</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected($selectedBrokerId === (string) $broker->id)>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                    <p class="ab-note mb-0 mt-1">This box is where you pick the broker. Type the name, then click it. Required for Specific Broker.</p>
                    @error('broker_id')<small class="ab-error">{{ $message }}</small>@enderror
                    @error('broker_ids')<small class="ab-error">{{ $message }}</small>@enderror
                    @if($brokers->isEmpty())
                        <p class="ab-note mb-0 mt-1">No brokers found. Add a broker first, then come back to target it.</p>
                    @endif
                </div>
                <div class="col-md-12 form-group" data-banner-multi-brokers @unless($brokerMultiple) hidden @endunless>
                    <label for="broker_ids">More brokers</label>
                    <select name="broker_ids[]" id="broker_ids" class="form-control select2" multiple data-placeholder="Search more brokers">
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}" @selected($selectedBrokers->contains((string) $broker->id))>{{ $broker->name }}</option>
                        @endforeach
                    </select>
                    <p class="ab-note mb-0 mt-1">Used when audience is Multiple Brokers.</p>
                    @error('broker_ids.*')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-12 form-group mb-0" data-banner-page @unless($needsPage) hidden @endunless>
                    <label for="page_path">Only this URL</label>
                    <select name="page_path" id="page_path" class="form-control select2 @error('page_path') is-invalid @enderror" data-placeholder="Search a site page or type a path">
                        <option value=""></option>
                        @foreach($pagePaths as $path => $label)
                            <option value="{{ $path }}" @selected($currentPagePath === $path)>{{ $label }} — {{ $path }}</option>
                        @endforeach
                        @if($currentPagePath && ! array_key_exists($currentPagePath, $pagePaths))
                            <option value="{{ $currentPagePath }}" selected>{{ $currentPagePath }}</option>
                        @endif
                    </select>
                    @error('page_path')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="cta">
        <div class="ab-section__head">
            <h2>Call to action</h2>
            <p>Optional for image banners. HTML templates can include their own links.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-5 form-group">
                    <label for="button_text">Button text</label>
                    <input type="text" name="button_text" id="button_text" class="form-control @error('button_text') is-invalid @enderror"
                           value="{{ old('button_text', $banner->button_text) }}" placeholder="Visit broker">
                    @error('button_text')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-7 form-group mb-0">
                    <label for="button_url">Button URL</label>
                    <input type="url" name="button_url" id="button_url" class="form-control @error('button_url') is-invalid @enderror"
                           value="{{ old('button_url', $banner->button_url) }}" placeholder="https://">
                    @error('button_url')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
            </div>
        </div>
    </section>

    <section class="ab-section" id="schedule">
        <div class="ab-section__head">
            <h2>Schedule &amp; status</h2>
            <p>The banner only appears between these dates while it is turned on. Higher priority shows first.</p>
        </div>
        <div class="ab-section__body">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="start_date">Start date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" required
                           value="{{ old('start_date', optional($banner->start_date)->format('Y-m-d') ?: date('Y-m-d')) }}">
                    @error('start_date')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="end_date">End date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" required
                           value="{{ old('end_date', optional($banner->end_date)->format('Y-m-d') ?: date('Y-m-d', strtotime('+1 month'))) }}">
                    @error('end_date')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="priority">Priority <span class="text-danger">*</span></label>
                    <input type="number" name="priority" id="priority" class="form-control @error('priority') is-invalid @enderror" min="0" max="9999" required
                           value="{{ old('priority', $banner->priority ?? 0) }}">
                    @error('priority')<small class="ab-error">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-12 form-group mb-0">
                    <label class="ab-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $banner->exists ? $banner->is_active : true))>
                        <span>Active — eligible to show during the date range</span>
                    </label>
                    @if($isEdit)
                        <p class="ab-note mb-0 mt-2">Last saved {{ $banner->updated_at?->format('M j, Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="ab-banner-form__actions">
        <button type="submit" class="ab-btn ab-btn--primary" data-banner-submit>
            <i class="fas fa-save" aria-hidden="true"></i>
            {{ $isEdit ? 'Save changes' : 'Create Banner' }}
        </button>
        <a href="{{ route('admin_banners_index') }}" class="ab-btn ab-btn--ghost">Cancel</a>
    </div>
</div>
