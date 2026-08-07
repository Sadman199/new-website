@extends('admin.layout.app')

@section('heading', 'Settings')

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Website Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_setting_update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Home Page</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="logo-tab" data-toggle="tab" href="#logo" role="tab" aria-controls="logo" aria-selected="false">Logo & Favicon</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="topbar-tab" data-toggle="tab" href="#topbar" role="tab" aria-controls="topbar" aria-selected="false">Top Bar</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="theme-tab" data-toggle="tab" href="#theme" role="tab" aria-controls="theme" aria-selected="false">Brand Colors</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="identity-tab" data-toggle="tab" href="#identity" role="tab" aria-controls="identity" aria-selected="false">Site Identity</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="features-tab" data-toggle="tab" href="#features" role="tab" aria-controls="features" aria-selected="false">Features</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="analytic-tab" data-toggle="tab" href="#analytic" role="tab" aria-controls="analytic" aria-selected="false">Google Analytics</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="disqus-tab" data-toggle="tab" href="#disqus" role="tab" aria-controls="disqus" aria-selected="false">Disqus Comment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="google-oauth-tab" data-toggle="tab" href="#google-oauth" role="tab" aria-controls="google-oauth" aria-selected="false">Google Sign-In</a>
                                </li>
                            </ul>
                            <!-- Tabs Content -->
                            <div class="tab-content" id="settingsTabsContent">
                                <!-- Home Page Tab -->
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Home Page Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Video Item Total <span class="text-danger">*</span></label>
                                                <input type="number" name="video_total" class="form-control" value="{{ old('video_total', $setting_data->video_total ?? '') }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Video Item Status</label>
                                                <select name="video_status" class="form-control custom-select">
                                                    <option value="Show" @if(($setting_data->video_status ?? '') == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if(($setting_data->video_status ?? '') == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Logo & Favicon Tab -->
                                <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Logo & Favicon Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Logo</label>
                                                <div class="mb-3">
                                                    <img src="{{ asset('uploads/'.$setting_data->logo) }}" alt="Logo" class="img-fluid rounded" style="max-height: 80px;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Change Logo</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="logo" name="logo">
                                                    <label class="custom-file-label" for="logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Existing Favicon</label>
                                                <div class="mb-3">
                                                    <img src="{{ asset('uploads/'.$setting_data->favicon) }}" alt="Favicon" class="img-fluid rounded" style="max-height: 30px;">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Change Favicon</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="favicon" name="favicon">
                                                    <label class="custom-file-label" for="favicon">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Top Bar Tab -->
                                <div class="tab-pane fade" id="topbar" role="tabpanel" aria-labelledby="topbar-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Top Bar Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Date Status</label>
                                                <select name="top_bar_date_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->top_bar_date_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->top_bar_date_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Email Status</label>
                                                <select name="top_bar_email_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->top_bar_email_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->top_bar_email_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="top_bar_email" class="form-control" value="{{ old('top_bar_email', $setting_data->top_bar_email) }}" placeholder="Enter email address" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Brand Colors Tab -->
                                <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">
                                    <h6 class="mb-2 font-weight-bold text-muted">Global brand colors</h6>
                                    <p class="text-muted small mb-4">
                                        These three colors drive buttons, navigation accents, dark backgrounds, and text highlights across the public website.
                                    </p>

                                    <div class="row mb-4">
                                        <div class="col-lg-8">
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
                                        <div class="col-lg-4">
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="font-weight-bold mb-3">Live preview</h6>
                                                    <div id="themePreviewPanel" class="rounded overflow-hidden border">
                                                        <div id="themePreviewHeader" style="background:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_2, $theme_defaults['theme_color_2']) }};padding:1rem;">
                                                            <strong id="themePreviewTitle" style="color:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_3, $theme_defaults['theme_color_3']) }};">BrokersCourt</strong>
                                                        </div>
                                                        <div style="padding:1rem;background:#fff;">
                                                            <button type="button" id="themePreviewButton" class="btn btn-sm" style="background:{{ \App\Support\SiteTheme::normalizeHex($setting_data->theme_color_1, $theme_defaults['theme_color_1']) }};color:#fff;border:none;">Primary button</button>
                                                            <p class="small text-muted mt-3 mb-0">Preview updates as you change colors.</p>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-3" id="resetThemeDefaults">
                                                        Reset to BrokersCourt defaults
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Site Identity Tab -->
                                <div class="tab-pane fade" id="identity" role="tabpanel" aria-labelledby="identity-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Site identity & SEO</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Site name</label>
                                                <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $setting_data->site_name ?? '') }}" placeholder="BrokersCourt">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Site tagline</label>
                                                <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $setting_data->site_tagline ?? '') }}" placeholder="Independent broker reviews and comparisons">
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Contact phone</label>
                                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $setting_data->contact_phone ?? '') }}" placeholder="+44 7577 309951">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Default meta description</label>
                                                <textarea name="default_meta_description" class="form-control" rows="4" placeholder="Used on pages without a custom SEO description.">{{ old('default_meta_description', $setting_data->default_meta_description ?? '') }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Footer copyright override</label>
                                                <input type="text" name="footer_copyright" class="form-control" value="{{ old('footer_copyright', $setting_data->footer_copyright ?? '') }}" placeholder="Leave blank to use the default © year + site name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Features Tab -->
                                <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Front-end features</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Broker spotlight dock</label>
                                                <select name="show_broker_spotlight" class="form-control custom-select">
                                                    <option value="Show" @if(($setting_data->show_broker_spotlight ?? 'Show') === 'Show') selected @endif>Show on all pages</option>
                                                    <option value="Hide" @if(($setting_data->show_broker_spotlight ?? 'Show') === 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Quick access drawer</label>
                                                <select name="show_quick_access_drawer" class="form-control custom-select">
                                                    <option value="Show" @if(($setting_data->show_quick_access_drawer ?? 'Show') === 'Show') selected @endif>Show on all pages</option>
                                                    <option value="Hide" @if(($setting_data->show_quick_access_drawer ?? 'Show') === 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Maintenance mode</label>
                                                <select name="maintenance_mode" class="form-control custom-select">
                                                    <option value="Hide" @if(($setting_data->maintenance_mode ?? 'Hide') === 'Hide') selected @endif>Off — site is live</option>
                                                    <option value="Show" @if(($setting_data->maintenance_mode ?? 'Hide') === 'Show') selected @endif>On — show maintenance page</option>
                                                </select>
                                                <small class="text-muted">Admin and author panels remain accessible.</small>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Maintenance message</label>
                                                <textarea name="maintenance_message" class="form-control" rows="4" placeholder="We are performing scheduled maintenance. Please check back soon.">{{ old('maintenance_message', $setting_data->maintenance_message ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Google Analytics Tab -->
                                <div class="tab-pane fade" id="analytic" role="tabpanel" aria-labelledby="analytic-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Google Analytics Settings</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Analytics ID</label>
                                                <input type="text" name="analytic_id" class="form-control" value="{{ old('analytic_id', $setting_data->analytic_id) }}" placeholder="e.g., UA-XXXXX-Y">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Status</label>
                                                <select name="analytic_status" class="form-control custom-select">
                                                    <option value="Show" @if($setting_data->analytic_status == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($setting_data->analytic_status == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Disqus Comment Tab -->
                                <div class="tab-pane fade" id="disqus" role="tabpanel" aria-labelledby="disqus-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Disqus Comment Settings</h6>
                                    <div class="form-group">
                                        <label class="font-weight-bold">Disqus Code</label>
                                        <textarea name="disqus_code" class="form-control" rows="8" placeholder="Enter Disqus code">{{ old('disqus_code', $setting_data->disqus_code) }}</textarea>
                                    </div>
                                </div>
                                <!-- Google Sign-In Tab -->
                                <div class="tab-pane fade" id="google-oauth" role="tabpanel" aria-labelledby="google-oauth-tab">
                                    <h6 class="mb-3 font-weight-bold text-muted">Google Sign-In (Login / Register)</h6>
                                    <p class="text-muted small mb-3">
                                        Create an OAuth 2.0 Web Client in Google Cloud Console. Add authorized JavaScript origins
                                        for your site (e.g. <code>http://127.0.0.1:8000</code> and <code>https://www.brokerscourt.com</code>).
                                        Only the Client ID is required for the sign-in button.
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Google Client ID</label>
                                                <input type="text" name="google_client_id" class="form-control" value="{{ old('google_client_id', $setting_data->google_client_id ?? '') }}" placeholder="xxxx.apps.googleusercontent.com">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Google Client Secret <span class="text-muted">(optional)</span></label>
                                                <input type="password" name="google_client_secret" class="form-control" value="{{ old('google_client_secret', $setting_data->google_client_secret ?? '') }}" placeholder="Only needed for redirect OAuth flow">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    .bc-color-picker {
        width: 52px;
        height: 42px;
        padding: 0;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
    }
</style>
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
            var primary = normalizeHex(document.getElementById('theme_color_1')?.value, defaults.theme_color_1);
            var dark = normalizeHex(document.getElementById('theme_color_2')?.value, defaults.theme_color_2);
            var light = normalizeHex(document.getElementById('theme_color_3')?.value, defaults.theme_color_3);

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

        document.getElementById('resetThemeDefaults')?.addEventListener('click', function () {
            ['theme_color_1', 'theme_color_2', 'theme_color_3'].forEach(function (name) {
                var input = document.getElementById(name);
                if (!input) return;
                input.value = defaults[name];
                syncColorField(input);
            });
        });
    })();
</script>
@endpush