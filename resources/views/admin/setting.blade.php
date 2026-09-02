@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Settings')

@section('main_content')
<div class="ab-page ab-page--hub ab-page--settings">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Website</p>
                <h1 class="ab-header__title">Settings</h1>
                <p class="ab-header__sub">Logo, brand colors, identity, and front-end features for the public site.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="ab-btn ab-btn--ghost">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                    View site
                </a>
            </div>
        </header>

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_setting_update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <nav class="ab-section-nav" aria-label="Settings sections">
                <a href="#settings-home" data-ab-nav class="is-active">Home Page</a>
                <a href="#settings-logo" data-ab-nav>Logo & Favicon</a>
                <a href="#settings-topbar" data-ab-nav>Top Bar</a>
                <a href="#settings-theme" data-ab-nav>Brand Colors</a>
                <a href="#settings-identity" data-ab-nav>Site Identity</a>
                <a href="#settings-features" data-ab-nav>Features</a>
                <a href="#settings-analytics" data-ab-nav>Google Analytics</a>
                <a href="#settings-disqus" data-ab-nav>Disqus Comment</a>
                <a href="#settings-google" data-ab-nav>Google Sign-In</a>
            </nav>

            <div class="ab-form-stack">
                <section class="ab-section" id="settings-home">
                    <div class="ab-section__head">
                        <h2>Home page</h2>
                        <p>Video block on the public homepage.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="row">
                            <div class="col-md-6 form-group mb-md-0">
                                <label for="video_total">Video item total</label>
                                <input type="number" name="video_total" id="video_total" class="form-control" value="{{ old('video_total', $setting_data->video_total ?? '6') }}" min="0" max="100">
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label for="video_status">Video item status</label>
                                <select name="video_status" id="video_status" class="form-control">
                                    <option value="Show" @if(($setting_data->video_status ?? '') == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if(($setting_data->video_status ?? '') == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-logo">
                    <div class="ab-section__head">
                        <h2>Logo & favicon</h2>
                        <p>Upload a new image to replace the current asset. Preview updates instantly before you save.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="ab-settings-split">
                            <div class="ab-upload">
                                <span class="ab-upload__label">Site logo</span>
                                <div class="ab-upload__preview" id="logoPreviewWrap">
                                    <img
                                        id="logoPreview"
                                        src="{{ \App\Support\SiteTheme::logoUrl() }}"
                                        alt="Current logo"
                                        data-original="{{ \App\Support\SiteTheme::logoUrl() }}"
                                    >
                                    <span class="ab-upload__empty d-none" id="logoPreviewEmpty">No logo selected</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input bc-image-input" id="logo" name="logo" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/svg+xml,.svg">
                                    <label class="custom-file-label" for="logo" data-default="Choose logo…">Choose logo…</label>
                                </div>
                                <small class="ab-note">PNG, JPG, WEBP, GIF, or SVG. Max 4MB.</small>
                                <label class="ab-check">
                                    <input type="checkbox" class="bc-remove-upload" id="remove_logo" name="remove_logo" value="1" data-target="logo">
                                    Reset to default logo
                                </label>
                            </div>
                            <div class="ab-upload">
                                <span class="ab-upload__label">Favicon</span>
                                <div class="ab-upload__preview ab-upload__preview--icon" id="faviconPreviewWrap">
                                    <img
                                        id="faviconPreview"
                                        src="{{ \App\Support\SiteTheme::faviconUrl() }}"
                                        alt="Current favicon"
                                        data-original="{{ \App\Support\SiteTheme::faviconUrl() }}"
                                    >
                                    <span class="ab-upload__empty d-none" id="faviconPreviewEmpty">No favicon selected</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input bc-image-input" id="favicon" name="favicon" accept="image/png,image/jpeg,image/jpg,image/gif,image/webp,image/x-icon,.ico">
                                    <label class="custom-file-label" for="favicon" data-default="Choose favicon…">Choose favicon…</label>
                                </div>
                                <small class="ab-note">PNG, JPG, WEBP, GIF, or ICO. Max 2MB. Ideal size 32×32 or 64×64.</small>
                                <label class="ab-check">
                                    <input type="checkbox" class="bc-remove-upload" id="remove_favicon" name="remove_favicon" value="1" data-target="favicon">
                                    Reset to default favicon
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-topbar">
                    <div class="ab-section__head">
                        <h2>Top bar</h2>
                        <p>Date and contact email shown in the public header strip.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="top_bar_date_status">Date status</label>
                                <select name="top_bar_date_status" id="top_bar_date_status" class="form-control">
                                    <option value="Show" @if(($setting_data->top_bar_date_status ?? 'Show') == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if(($setting_data->top_bar_date_status ?? '') == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="top_bar_email_status">Email status</label>
                                <select name="top_bar_email_status" id="top_bar_email_status" class="form-control">
                                    <option value="Show" @if(($setting_data->top_bar_email_status ?? 'Show') == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if(($setting_data->top_bar_email_status ?? '') == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-0">
                                <label for="top_bar_email">Email address</label>
                                <input type="email" name="top_bar_email" id="top_bar_email" class="form-control" value="{{ old('top_bar_email', $setting_data->top_bar_email ?? 'info@brokerscourt.com') }}" placeholder="Enter email address">
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-theme">
                    <div class="ab-section__head">
                        <h2>Brand colors</h2>
                        <p>These three colors drive buttons, navigation accents, dark backgrounds, and text highlights across the public website.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="ab-theme-layout">
                            <div>
                                @include('admin.setting.partials.color_field', [
                                    'name' => 'theme_color_1',
                                    'label' => 'Primary accent color',
                                    'help' => 'Used for buttons, links, badges, and key CTAs.',
                                    'value' => $setting_data->theme_color_1,
                                    'default' => $theme_defaults['theme_color_1'],
                                    'id' => 'theme_color_1',
                                ])
                                @include('admin.setting.partials.color_field', [
                                    'name' => 'theme_color_2',
                                    'label' => 'Dark background color',
                                    'help' => 'Used for headers, hero backgrounds, and dark sections.',
                                    'value' => $setting_data->theme_color_2,
                                    'default' => $theme_defaults['theme_color_2'],
                                    'id' => 'theme_color_2',
                                ])
                                @include('admin.setting.partials.color_field', [
                                    'name' => 'theme_color_3',
                                    'label' => 'Light text / surface color',
                                    'help' => 'Used for readable text on dark backgrounds and subtle borders.',
                                    'value' => $setting_data->theme_color_3 ?? $theme_defaults['theme_color_3'],
                                    'default' => $theme_defaults['theme_color_3'],
                                    'id' => 'theme_color_3',
                                ])
                            </div>
                            <div>
                                <p class="ab-upload__label">Live preview</p>
                                <div id="themePreviewPanel" class="ab-theme-preview">
                                    <div id="themePreviewHeader" class="ab-theme-preview__bar" style="background:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_2, $theme_defaults['theme_color_2']) }};">
                                        <strong id="themePreviewTitle" style="color:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_3, $theme_defaults['theme_color_3']) }};">BrokersCourt</strong>
                                    </div>
                                    <div class="ab-theme-preview__body">
                                        <button type="button" id="themePreviewButton" class="ab-theme-preview__btn" style="background:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_1, $theme_defaults['theme_color_1']) }};">Primary button</button>
                                        <p class="ab-note" style="margin-top:0.75rem;">Preview updates as you change colors.</p>
                                    </div>
                                </div>
                                <button type="button" class="ab-btn ab-btn--ghost ab-btn--sm" id="resetThemeDefaults" style="margin-top:0.75rem;">
                                    Reset to BrokersCourt defaults
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-identity">
                    <div class="ab-section__head">
                        <h2>Site identity & SEO</h2>
                        <p>Name, contact details, and fallback search-engine text.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="site_name">Site name</label>
                                <input type="text" name="site_name" id="site_name" class="form-control" value="{{ old('site_name', $setting_data->site_name ?? '') }}" placeholder="BrokersCourt">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="site_tagline">Site tagline</label>
                                <input type="text" name="site_tagline" id="site_tagline" class="form-control" value="{{ old('site_tagline', $setting_data->site_tagline ?? '') }}" placeholder="Independent broker reviews and comparisons">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="contact_phone">Contact phone</label>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control" value="{{ old('contact_phone', $setting_data->contact_phone ?? '') }}" placeholder="+44 7577 309951">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="footer_copyright">Footer copyright override</label>
                                <input type="text" name="footer_copyright" id="footer_copyright" class="form-control" value="{{ old('footer_copyright', $setting_data->footer_copyright ?? '') }}" placeholder="Leave blank to use the default © year + site name">
                            </div>
                            <div class="col-md-12 form-group mb-0">
                                <label for="default_meta_description">Default meta description</label>
                                <textarea name="default_meta_description" id="default_meta_description" class="form-control" rows="4" placeholder="Used on pages without a custom SEO description.">{{ old('default_meta_description', $setting_data->default_meta_description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-features">
                    <div class="ab-section__head">
                        <h2>Front-end features</h2>
                        <p>Spotlight, drawers, and the public maintenance page.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="show_broker_spotlight">Broker spotlight dock</label>
                                <select name="show_broker_spotlight" id="show_broker_spotlight" class="form-control">
                                    <option value="Show" @if(($setting_data->show_broker_spotlight ?? 'Show') === 'Show') selected @endif>Show on all pages</option>
                                    <option value="Hide" @if(($setting_data->show_broker_spotlight ?? 'Show') === 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="show_quick_access_drawer">Quick access drawer</label>
                                <select name="show_quick_access_drawer" id="show_quick_access_drawer" class="form-control">
                                    <option value="Show" @if(($setting_data->show_quick_access_drawer ?? 'Show') === 'Show') selected @endif>Show on all pages</option>
                                    <option value="Hide" @if(($setting_data->show_quick_access_drawer ?? 'Show') === 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="maintenance_mode">Maintenance mode</label>
                                <select name="maintenance_mode" id="maintenance_mode" class="form-control">
                                    <option value="Hide" @if(($setting_data->maintenance_mode ?? 'Hide') === 'Hide') selected @endif>Off — site is live</option>
                                    <option value="Show" @if(($setting_data->maintenance_mode ?? 'Hide') === 'Show') selected @endif>On — show maintenance page</option>
                                </select>
                                <small class="ab-note">Admin and author panels remain accessible.</small>
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label for="maintenance_message">Maintenance message</label>
                                <textarea name="maintenance_message" id="maintenance_message" class="form-control" rows="4" placeholder="We are performing scheduled maintenance. Please check back soon.">{{ old('maintenance_message', $setting_data->maintenance_message ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-analytics">
                    <div class="ab-section__head">
                        <h2>Google Analytics</h2>
                        <p>Tracking ID used on the public site.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="row">
                            <div class="col-md-6 form-group mb-md-0">
                                <label for="analytic_id">Analytics ID</label>
                                <input type="text" name="analytic_id" id="analytic_id" class="form-control" value="{{ old('analytic_id', $setting_data->analytic_id ?? '') }}" placeholder="e.g., UA-XXXXX-Y">
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label for="analytic_status">Status</label>
                                <select name="analytic_status" id="analytic_status" class="form-control">
                                    <option value="Show" @if(($setting_data->analytic_status ?? 'Hide') == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if(($setting_data->analytic_status ?? 'Hide') == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-disqus">
                    <div class="ab-section__head">
                        <h2>Disqus comments</h2>
                        <p>Embed code for article comments.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="form-group mb-0">
                            <label for="disqus_code">Disqus code</label>
                            <textarea name="disqus_code" id="disqus_code" class="form-control" rows="8" data-admin-editor="off" placeholder="Enter Disqus code">{{ old('disqus_code', $setting_data->disqus_code ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="ab-section" id="settings-google">
                    <div class="ab-section__head">
                        <h2>Google Sign-In</h2>
                        <p>OAuth client for login and register. Only the Client ID is required for the sign-in button.</p>
                    </div>
                    <div class="ab-section__body">
                        <p class="ab-note" style="margin-bottom:1rem;">
                            Create an OAuth 2.0 Web Client in Google Cloud Console. Add authorized JavaScript origins
                            for your site (e.g. <code>http://127.0.0.1:8000</code> and <code>https://www.brokerscourt.com</code>).
                        </p>
                        <div class="row">
                            <div class="col-md-6 form-group mb-md-0">
                                <label for="google_client_id">Google Client ID</label>
                                <input type="text" name="google_client_id" id="google_client_id" class="form-control" value="{{ old('google_client_id', $setting_data->google_client_id ?? '') }}" placeholder="xxxx.apps.googleusercontent.com">
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label for="google_client_secret">Google Client Secret <span class="text-muted">(optional)</span></label>
                                <input type="password" name="google_client_secret" id="google_client_secret" class="form-control" value="{{ old('google_client_secret', $setting_data->google_client_secret ?? '') }}" placeholder="Only needed for redirect OAuth flow">
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Update Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var defaults = @json($theme_defaults);

        function normalizeHex(value, fallback) {
            value = (value || '').trim();
            if (!value) return fallback;
            if (value.charAt(0) !== '#') value = '#' + value;
            return /^#[0-9A-Fa-f]{6}$/.test(value) ? value.toUpperCase() : fallback;
        }

        function syncColorField(input) {
            var picker = document.getElementById(input.id + '_picker');
            var preview = document.querySelector('[data-preview-for="' + input.id + '"]');
            var value = normalizeHex(input.value, defaults[input.name] || '#007AAD');

            input.value = value;
            if (picker) picker.value = value;
            if (preview) {
                preview.style.background = value;
                preview.style.color = (input.id === 'theme_color_3') ? '#0C1D32' : '#FFFFFF';
            }
            updateThemePreview();
        }

        function updateThemePreview() {
            var primary = normalizeHex(document.getElementById('theme_color_1') && document.getElementById('theme_color_1').value, defaults.theme_color_1);
            var dark = normalizeHex(document.getElementById('theme_color_2') && document.getElementById('theme_color_2').value, defaults.theme_color_2);
            var light = normalizeHex(document.getElementById('theme_color_3') && document.getElementById('theme_color_3').value, defaults.theme_color_3);

            var header = document.getElementById('themePreviewHeader');
            var title = document.getElementById('themePreviewTitle');
            var button = document.getElementById('themePreviewButton');

            if (header) header.style.background = dark;
            if (title) title.style.color = light;
            if (button) button.style.background = primary;
        }

        document.querySelectorAll('.bc-color-input').forEach(function (input) {
            var picker = document.getElementById(input.id + '_picker');
            input.addEventListener('input', function () { syncColorField(input); });
            input.addEventListener('change', function () { syncColorField(input); });
            if (picker) {
                picker.addEventListener('input', function () {
                    input.value = picker.value.toUpperCase();
                    syncColorField(input);
                });
            }
            syncColorField(input);
        });

        var resetBtn = document.getElementById('resetThemeDefaults');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                ['theme_color_1', 'theme_color_2', 'theme_color_3'].forEach(function (name) {
                    var input = document.getElementById(name);
                    if (!input) return;
                    input.value = defaults[name];
                    syncColorField(input);
                });
            });
        }

        function bindImagePreview(inputId, previewId, emptyId) {
            var input = document.getElementById(inputId);
            var preview = document.getElementById(previewId);
            var empty = document.getElementById(emptyId);
            if (!input || !preview) return;

            var label = input.nextElementSibling;
            var objectUrl = null;

            input.addEventListener('change', function () {
                var remove = document.getElementById('remove_' + inputId);
                if (remove) remove.checked = false;

                var file = input.files && input.files[0];
                if (label) {
                    label.textContent = file ? file.name : (label.getAttribute('data-default') || 'Choose file…');
                }

                if (objectUrl) {
                    URL.revokeObjectURL(objectUrl);
                    objectUrl = null;
                }

                if (!file) {
                    preview.src = preview.getAttribute('data-original') || '';
                    preview.classList.remove('d-none');
                    if (empty) empty.classList.add('d-none');
                    return;
                }

                if (!file.type || file.type.indexOf('image/') !== 0) {
                    preview.classList.add('d-none');
                    if (empty) {
                        empty.textContent = 'Selected file is not an image';
                        empty.classList.remove('d-none');
                    }
                    return;
                }

                objectUrl = URL.createObjectURL(file);
                preview.src = objectUrl;
                preview.classList.remove('d-none');
                if (empty) empty.classList.add('d-none');
            });
        }

        bindImagePreview('logo', 'logoPreview', 'logoPreviewEmpty');
        bindImagePreview('favicon', 'faviconPreview', 'faviconPreviewEmpty');

        document.querySelectorAll('.bc-remove-upload').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var target = checkbox.getAttribute('data-target');
                var input = document.getElementById(target);
                var preview = document.getElementById(target + 'Preview');
                var empty = document.getElementById(target + 'PreviewEmpty');
                var label = input ? input.nextElementSibling : null;

                if (checkbox.checked) {
                    if (input) {
                        input.value = '';
                        if (label) label.textContent = label.getAttribute('data-default') || 'Choose file…';
                    }
                    if (preview) {
                        preview.classList.add('d-none');
                        preview.removeAttribute('src');
                    }
                    if (empty) {
                        empty.textContent = 'Will reset to default on save';
                        empty.classList.remove('d-none');
                    }
                } else if (preview) {
                    preview.src = preview.getAttribute('data-original') || '';
                    preview.classList.remove('d-none');
                    if (empty) empty.classList.add('d-none');
                }
            });
        });
    })();
</script>
@endpush
