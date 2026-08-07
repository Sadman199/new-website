<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropFirmModuleSetting;
use Illuminate\Http\Request;

class AdminPropFirmSettingController extends Controller
{
    public function edit()
    {
        $settings = PropFirmModuleSetting::instance();

        return view('admin.prop-firms.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'default_sort_order' => ['required', 'in:sort_order,name,trust_score,overall_rating,created_at'],
            'enable_reviews' => ['nullable', 'boolean'],
            'enable_faqs' => ['nullable', 'boolean'],
            'enable_programs' => ['nullable', 'boolean'],
        ]);

        $settings = PropFirmModuleSetting::instance();
        $settings->setMany([
            'default_sort_order' => $validated['default_sort_order'],
            'enable_reviews' => $request->boolean('enable_reviews'),
            'enable_faqs' => $request->boolean('enable_faqs'),
            'enable_programs' => $request->boolean('enable_programs'),
        ]);

        return redirect()->route('admin_prop_firm_settings_edit')->with('success', 'Settings saved successfully.');
    }
}
