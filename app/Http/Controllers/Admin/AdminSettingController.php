<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\SiteTheme;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting_data = Setting::where('id', 1)->first() ?? new Setting();
        $theme_defaults = SiteTheme::defaults();

        return view('admin.setting', compact('setting_data', 'theme_defaults'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,svg',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,ico',
            'top_bar_email' => 'required|email',
            'theme_color_1' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'theme_color_2' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'theme_color_3' => ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'],
            'site_name' => 'nullable|string|max:120',
            'site_tagline' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:40',
            'footer_copyright' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:320',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        $setting = Setting::updateOrCreate(['id' => 1]);

        $setting->video_total = $request->video_total;
        $setting->video_status = $request->video_status;

        if ($request->hasFile('logo')) {
            $old_logo_path = public_path('uploads/' . $setting->logo);
            if (! empty($setting->logo) && file_exists($old_logo_path)) {
                unlink($old_logo_path);
            }

            $ext = $request->file('logo')->extension();
            $final_name = 'logo.' . $ext;
            $request->file('logo')->move(public_path('uploads/'), $final_name);
            $setting->logo = $final_name;
        }

        if ($request->hasFile('favicon')) {
            $old_favicon_path = public_path('uploads/' . $setting->favicon);
            if (! empty($setting->favicon) && file_exists($old_favicon_path)) {
                unlink($old_favicon_path);
            }

            $ext = $request->file('favicon')->extension();
            $final_name = 'favicon.' . $ext;
            $request->file('favicon')->move(public_path('uploads/'), $final_name);
            $setting->favicon = $final_name;
        }

        $setting->top_bar_date_status = $request->top_bar_date_status;
        $setting->top_bar_email = $request->top_bar_email;
        $setting->top_bar_email_status = $request->top_bar_email_status;

        $setting->theme_color_1 = SiteTheme::normalizeHex($request->theme_color_1, SiteTheme::DEFAULT_PRIMARY);
        $setting->theme_color_2 = SiteTheme::normalizeHex($request->theme_color_2, SiteTheme::DEFAULT_DARK);
        $setting->theme_color_3 = SiteTheme::normalizeHex($request->theme_color_3, SiteTheme::DEFAULT_LIGHT);

        $setting->site_name = $request->site_name;
        $setting->site_tagline = $request->site_tagline;
        $setting->contact_phone = $request->contact_phone;
        $setting->footer_copyright = $request->footer_copyright;
        $setting->default_meta_description = $request->default_meta_description;

        $setting->maintenance_mode = $request->maintenance_mode === 'Show' ? 'Show' : 'Hide';
        $setting->maintenance_message = $request->maintenance_message;
        $setting->show_broker_spotlight = $request->show_broker_spotlight === 'Hide' ? 'Hide' : 'Show';
        $setting->show_quick_access_drawer = $request->show_quick_access_drawer === 'Hide' ? 'Hide' : 'Show';

        $setting->analytic_id = $request->analytic_id;
        $setting->analytic_status = $request->analytic_status;
        $setting->disqus_code = $request->disqus_code;
        $setting->google_client_id = $request->google_client_id;
        $setting->google_client_secret = $request->google_client_secret;

        $setting->save();

        return redirect()->route('admin_setting')->with('success', 'Website settings updated successfully.');
    }
}
