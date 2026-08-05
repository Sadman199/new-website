<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingController extends Controller
{
    public function index()
    {
        $setting_data = Setting::where('id',1)->first();
        return view('admin.setting', compact('setting_data'));
    }

 public function update(Request $request)
    {
        $request->validate([
            'news_ticker_total' => 'required',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,gif'
        ]);

        $setting = Setting::where('id', 1)->first();

        $setting->news_ticker_total = $request->news_ticker_total;
        $setting->news_ticker_status = $request->news_ticker_status;
        $setting->video_total = $request->video_total;
        $setting->video_status = $request->video_status;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $old_logo_path = public_path('uploads/' . $setting->logo);
            if (!empty($setting->logo) && file_exists($old_logo_path)) {
                unlink($old_logo_path);
            }

            $ext = $request->file('logo')->extension();
            $final_name = 'logo.' . $ext;
            $request->file('logo')->move(public_path('uploads/'), $final_name);
            $setting->logo = $final_name;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $old_favicon_path = public_path('uploads/' . $setting->favicon);
            if (!empty($setting->favicon) && file_exists($old_favicon_path)) {
                unlink($old_favicon_path);
            }

            $ext = $request->file('favicon')->extension();
            $final_name = 'favicon.' . $ext;
            $request->file('favicon')->move(public_path('uploads/'), $final_name);
            $setting->favicon = $final_name;
        }

        // Other settings
        $setting->top_bar_date_status = $request->top_bar_date_status;
        $setting->top_bar_email = $request->top_bar_email;
        $setting->top_bar_email_status = $request->top_bar_email_status;
        $setting->theme_color_1 = $request->theme_color_1;
        $setting->theme_color_2 = $request->theme_color_2;
        $setting->analytic_id = $request->analytic_id;
        $setting->analytic_status = $request->analytic_status;
        $setting->disqus_code = $request->disqus_code;

        $setting->save(); // Save all updates

        return redirect()->route('admin_setting')->with('success', 'Data is updated successfully.');
    }
}
