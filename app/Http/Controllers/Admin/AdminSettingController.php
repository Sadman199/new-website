<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\GlobalViewDataService;
use App\Support\SiteTheme;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting_data = Setting::query()->find(1) ?? new Setting(SiteTheme::defaultAttributes());
        $theme_defaults = SiteTheme::defaults();

        return view('admin.setting', compact('setting_data', 'theme_defaults'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg|max:4096',
            'favicon' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,ico|max:2048',
            'remove_logo' => 'nullable|boolean',
            'remove_favicon' => 'nullable|boolean',
            'video_total' => 'nullable|integer|min:0|max:100',
            'video_status' => 'nullable|in:Show,Hide',
            'top_bar_date_status' => 'nullable|in:Show,Hide',
            'top_bar_email' => 'nullable|email|max:255',
            'top_bar_email_status' => 'nullable|in:Show,Hide',
            'theme_color_1' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'theme_color_2' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'theme_color_3' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'site_name' => 'nullable|string|max:120',
            'site_tagline' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:40',
            'footer_copyright' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:320',
            'maintenance_mode' => 'nullable|in:Show,Hide',
            'maintenance_message' => 'nullable|string|max:500',
            'show_broker_spotlight' => 'nullable|in:Show,Hide',
            'show_quick_access_drawer' => 'nullable|in:Show,Hide',
            'analytic_id' => 'nullable|string|max:255',
            'analytic_status' => 'nullable|in:Show,Hide',
            'disqus_code' => 'nullable|string',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
        ]);

        $setting = Setting::query()->firstOrNew(['id' => 1]);
        $defaults = SiteTheme::defaultAttributes();

        if (! $setting->exists) {
            $setting->fill($defaults);
        }

        $setting->video_total = (string) ($validated['video_total']
            ?? $setting->video_total
            ?? $defaults['video_total']);
        $setting->video_status = $this->showHide(
            $validated['video_status'] ?? null,
            $setting->video_status ?? $defaults['video_status']
        );

        if ($request->boolean('remove_logo')) {
            $this->deleteUpload($setting->logo);
            $setting->logo = 'logo.png';
        } elseif ($request->hasFile('logo')) {
            $setting->logo = $this->storeUpload($request->file('logo'), 'logo', $setting->logo);
        } else {
            $setting->logo = $this->nonEmpty($setting->logo, $defaults['logo']);
        }

        if ($request->boolean('remove_favicon')) {
            $this->deleteUpload($setting->favicon);
            $setting->favicon = 'favicon.png';
        } elseif ($request->hasFile('favicon')) {
            $setting->favicon = $this->storeUpload($request->file('favicon'), 'favicon', $setting->favicon);
        } else {
            $setting->favicon = $this->nonEmpty($setting->favicon, $defaults['favicon']);
        }

        $setting->top_bar_date_status = $this->showHide(
            $validated['top_bar_date_status'] ?? null,
            $setting->top_bar_date_status ?? $defaults['top_bar_date_status']
        );
        $setting->top_bar_email = $this->nonEmpty(
            $validated['top_bar_email'] ?? null,
            $setting->top_bar_email ?? $defaults['top_bar_email']
        );
        $setting->top_bar_email_status = $this->showHide(
            $validated['top_bar_email_status'] ?? null,
            $setting->top_bar_email_status ?? $defaults['top_bar_email_status']
        );

        $setting->theme_color_1 = SiteTheme::normalizeHex(
            $validated['theme_color_1'] ?? null,
            $setting->theme_color_1 ?? SiteTheme::DEFAULT_PRIMARY
        );
        $setting->theme_color_2 = SiteTheme::normalizeHex(
            $validated['theme_color_2'] ?? null,
            $setting->theme_color_2 ?? SiteTheme::DEFAULT_DARK
        );
        $setting->theme_color_3 = SiteTheme::normalizeHex(
            $validated['theme_color_3'] ?? null,
            $setting->theme_color_3 ?? SiteTheme::DEFAULT_LIGHT
        );

        $setting->site_name = $this->nullableString($validated['site_name'] ?? null);
        $setting->site_tagline = $this->nullableString($validated['site_tagline'] ?? null);
        $setting->contact_phone = $this->nullableString($validated['contact_phone'] ?? null);
        $setting->footer_copyright = $this->nullableString($validated['footer_copyright'] ?? null);
        $setting->default_meta_description = $this->nullableString($validated['default_meta_description'] ?? null);

        $setting->maintenance_mode = $this->showHide(
            $validated['maintenance_mode'] ?? null,
            $setting->maintenance_mode ?? $defaults['maintenance_mode'],
            'Hide'
        );
        $setting->maintenance_message = $this->nullableString($validated['maintenance_message'] ?? null);
        $setting->show_broker_spotlight = $this->showHide(
            $validated['show_broker_spotlight'] ?? null,
            $setting->show_broker_spotlight ?? $defaults['show_broker_spotlight']
        );
        $setting->show_quick_access_drawer = $this->showHide(
            $validated['show_quick_access_drawer'] ?? null,
            $setting->show_quick_access_drawer ?? $defaults['show_quick_access_drawer']
        );

        // NOT NULL text columns — never persist null.
        $setting->analytic_id = (string) ($validated['analytic_id'] ?? $setting->analytic_id ?? '');
        $setting->analytic_status = $this->showHide(
            $validated['analytic_status'] ?? null,
            $setting->analytic_status ?? $defaults['analytic_status'],
            'Hide'
        );
        $setting->disqus_code = (string) ($validated['disqus_code'] ?? $setting->disqus_code ?? '');
        $setting->google_client_id = $this->nullableString($validated['google_client_id'] ?? null);

        if (array_key_exists('google_client_secret', $validated) && filled($validated['google_client_secret'])) {
            $setting->google_client_secret = $validated['google_client_secret'];
        }

        try {
            $setting->save();
        } catch (\Throwable $e) {
            report($e);

            throw ValidationException::withMessages([
                'settings' => 'Settings could not be saved. Please check required fields and try again.',
            ]);
        }

        SiteTheme::forgetCache();
        GlobalViewDataService::flush();

        return redirect()
            ->route('admin_setting')
            ->with('success', 'Website settings updated successfully.');
    }

    private function storeUpload(UploadedFile $file, string $basename, ?string $previous): string
    {
        $uploads = public_path('uploads');
        File::ensureDirectoryExists($uploads);

        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'png');
        $finalName = $basename . '.' . $ext;

        if (! empty($previous) && $previous !== $finalName) {
            $this->deleteUpload($previous);
        }

        $target = $uploads . DIRECTORY_SEPARATOR . $finalName;
        if (is_file($target)) {
            @unlink($target);
        }

        $file->move($uploads, $finalName);

        return $finalName;
    }

    private function deleteUpload(?string $filename): void
    {
        $filename = trim((string) $filename);
        if ($filename === '' || str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            return;
        }

        if (in_array($filename, ['logo.png', 'favicon.png'], true)) {
            return;
        }

        $path = public_path('uploads/' . $filename);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function showHide(?string $value, ?string $fallback, string $default = 'Show'): string
    {
        $value = trim((string) $value);
        if ($value === 'Show' || $value === 'Hide') {
            return $value;
        }

        $fallback = trim((string) $fallback);

        return ($fallback === 'Show' || $fallback === 'Hide') ? $fallback : $default;
    }

    private function nonEmpty(?string $value, string $fallback): string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : $fallback;
    }

    private function nullableString(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }
}
