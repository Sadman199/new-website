<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\AccountOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminBrokerController extends Controller
{
    // Display a listing of brokers
    public function show()
    {
        $brokers = Broker::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.brokers.show', compact('brokers'));
    }

    // Show the form for creating a new broker
    public function create()
    {
        $broker = new Broker();
        return view('admin.brokers.create', compact('broker'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'url' => 'nullable|string|max:2000',
            'logo' => 'sometimes|nullable|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'banner_image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'banner_image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'visit_site' => 'nullable|string|max:2000',
            'open_live'  => 'nullable|string|max:2000',
            'open_demo'  => 'nullable|string|max:2000',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'languages' => 'nullable|string',
            'pricing' => 'nullable|string',
            'deposit_methods' => 'nullable|string',
            'withdrawal_method' => 'nullable|string',
            'country' => 'required|string',
            'regulation' => 'nullable|array',
            'regulated_jurisdictions' => 'nullable|string',
            'regulatory_licenses' => 'nullable|string',
            'minimum_deposit' => 'nullable|numeric',
            'spreads' => 'nullable|string',
            'leverage' => 'nullable|string',
            'platforms' => 'nullable|array',
            'payment_methods' => 'nullable|string',
            'customer_support' => 'nullable|string',
            'educational_resources' => 'nullable|string',
            'research_tools' => 'nullable|string',
            'mobile_trading' => 'nullable|string',
            'social_trading' => 'nullable|string',
            'account_types' => 'nullable|array',
            'insurance' => 'nullable|string',
            'segregation_of_funds' => 'nullable|boolean',
            'web_trader' => 'nullable|string',
            'charting_tools' => 'nullable|string',
            'account_managers' => 'nullable|boolean',
            'news_and_analysis' => 'nullable|string',
            'featured_broker' => 'nullable|boolean',
            'economic_calendar' => 'nullable|boolean',
            'vps_hosting' => 'nullable|boolean',
            'associated_countries' => 'nullable|array',
            'top_broker' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'capitalization' => 'nullable|string',
            'slug' => 'nullable|string|max:255',
            'top_feature' => 'nullable|string',
        ]);
    
        // Create new broker
        $broker = new Broker();
        
        // Fill basic fields
        $broker->fill($request->only([
            'name', 'url', 'short_description', 'visit_site', 'open_live', 'open_demo',
            'pros', 'cons', 'languages', 'pricing', 'deposit_methods', 'withdrawal_method',
            'country', 'regulated_jurisdictions', 'regulatory_licenses',
            'minimum_deposit', 'spreads', 'leverage',
            'payment_methods', 'customer_support', 'educational_resources', 'research_tools',
            'mobile_trading', 'social_trading', 'capitalization', 'insurance', 'segregation_of_funds',
            'web_trader', 'charting_tools', 'account_managers', 'news_and_analysis',
            'economic_calendar', 'vps_hosting', 'slug', 'top_feature', 'featured_broker',
            'top_broker', 'meta_title', 'meta_keyword', 'meta_description', 'title', 'rating'
        ]));
    
        // Handle JSON array fields
        $jsonFields = ['account_types', 'associated_countries', 'regulation', 'platforms'];
        
        foreach ($jsonFields as $field) {
            if ($request->has($field) && !empty($request->$field)) {
                $broker->$field = json_encode($request->input($field));
            } else {
                $broker->$field = json_encode([]);
            }
        }
    
        // Generate slug if not provided
        if (empty($broker->slug)) {
            $broker->slug = Str::slug($request->name);
        }
    
        // Create uploads directories if they don't exist
        $uploadsPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $logosPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/logos/';
        
        if (!file_exists($uploadsPath)) {
            mkdir($uploadsPath, 0777, true);
        }
        if (!file_exists($logosPath)) {
            mkdir($logosPath, 0777, true);
        }
    
        // Upload logo → /uploads/logos/
        if ($request->hasFile('logo')) {
            $now = time();
            $ext = $request->file('logo')->extension();
            $filename = 'logo_' . $now . '.' . $ext;
            $request->file('logo')->move($logosPath, $filename);
            $broker->logo = 'uploads/logos/' . $filename;
        }
    
        // Upload banner 1 → /uploads/
        if ($request->hasFile('banner_image_1')) {
            $now = time();
            $ext = $request->file('banner_image_1')->extension();
            $filename = 'banner1_' . $now . '.' . $ext;
            $request->file('banner_image_1')->move($uploadsPath, $filename);
            $broker->banner_image_1 = 'uploads/' . $filename;
        }
    
        // Upload banner 2 → /uploads/
        if ($request->hasFile('banner_image_2')) {
            $now = time() + 1;
            $ext = $request->file('banner_image_2')->extension();
            $filename = 'banner2_' . $now . '.' . $ext;
            $request->file('banner_image_2')->move($uploadsPath, $filename);
            $broker->banner_image_2 = 'uploads/' . $filename;
        }
    
        // Save broker
        $broker->save();
    
        // Redirect to account options creation
        return redirect()->route('admin_account_options_create', $broker->id)
            ->with('success', 'Broker created successfully. Now add account options.');
    }
    
    // Show the form for editing an existing broker
    public function edit($id)
    {
        $broker = Broker::findOrFail($id);

        $accountOption = AccountOption::where('broker_id', $broker->id)->first();

        return view('admin.brokers.edit', compact('broker', 'accountOption'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'url' => 'nullable|string|max:2000',
            'logo' => 'sometimes|nullable|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'banner_image_1' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'banner_image_2' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:2048',
            'visit_site' => 'nullable|string|max:2000',
            'open_live'  => 'nullable|string|max:2000',
            'open_demo'  => 'nullable|string|max:2000',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'languages' => 'nullable|string',
            'pricing' => 'nullable|string',
            'deposit_methods' => 'nullable|string',
            'withdrawal_method' => 'nullable|string',
            'country' => 'required|string',
            'regulation' => 'nullable|array',
            'regulated_jurisdictions' => 'nullable|string',
            'regulatory_licenses' => 'nullable|string',
            'minimum_deposit' => 'nullable|numeric',
            'spreads' => 'nullable|string',
            'leverage' => 'nullable|string',
            'platforms' => 'nullable|array',
            'payment_methods' => 'nullable|string',
            'customer_support' => 'nullable|string',
            'educational_resources' => 'nullable|string',
            'research_tools' => 'nullable|string',
            'mobile_trading' => 'nullable|string',
            'social_trading' => 'nullable|string',
            'account_types' => 'nullable|array',
            'insurance' => 'nullable|string',
            'segregation_of_funds' => 'nullable|boolean',
            'web_trader' => 'nullable|string',
            'charting_tools' => 'nullable|string',
            'account_managers' => 'nullable|boolean',
            'news_and_analysis' => 'nullable|string',
            'economic_calendar' => 'nullable|boolean',
            'vps_hosting' => 'nullable|boolean',
            'associated_countries' => 'nullable|array',
            'slug' => 'nullable|string|max:255',
            'top_feature' => 'nullable|string',
            'featured_broker' => 'nullable|boolean',
            'top_broker' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'capitalization' => 'nullable|string',
        ]);
    
        $broker = Broker::findOrFail($id);
    
        // Create uploads directories if they don't exist
        $uploadsPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $logosPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/logos/';
        
        if (!file_exists($uploadsPath)) {
            mkdir($uploadsPath, 0777, true);
        }
        if (!file_exists($logosPath)) {
            mkdir($logosPath, 0777, true);
        }
    
        // Handle logo upload and deletion of old logo
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($broker->logo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->logo)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->logo);
            }
            
            $now = time();
            $ext = $request->file('logo')->extension();
            $filename = 'logo_' . $now . '.' . $ext;
            $request->file('logo')->move($logosPath, $filename);
            $broker->logo = 'uploads/logos/' . $filename;
        }
    
        // Handle banner_image_1 upload and deletion of old banner
        if ($request->hasFile('banner_image_1')) {
            // Delete old banner if exists
            if ($broker->banner_image_1 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_1)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_1);
            }
            
            $now = time();
            $ext = $request->file('banner_image_1')->extension();
            $filename = 'banner1_' . $now . '.' . $ext;
            $request->file('banner_image_1')->move($uploadsPath, $filename);
            $broker->banner_image_1 = 'uploads/' . $filename;
        }
    
        // Handle banner_image_2 upload and deletion of old banner
        if ($request->hasFile('banner_image_2')) {
            // Delete old banner if exists
            if ($broker->banner_image_2 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_2)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_2);
            }
            
            $now = time() + 1;
            $ext = $request->file('banner_image_2')->extension();
            $filename = 'banner2_' . $now . '.' . $ext;
            $request->file('banner_image_2')->move($uploadsPath, $filename);
            $broker->banner_image_2 = 'uploads/' . $filename;
        }
    
        // Handle JSON array fields
        $jsonFields = ['account_types', 'associated_countries', 'regulation', 'platforms'];
        
        foreach ($jsonFields as $field) {
            if ($request->has($field) && !empty($request->$field)) {
                $broker->$field = json_encode($request->input($field));
            } elseif ($request->has($field)) {
                $broker->$field = json_encode([]);
            }
        }
    
        // Fill all other fields (excluding the ones we handled separately)
        $broker->fill($request->except(array_merge($jsonFields, ['logo', 'banner_image_1', 'banner_image_2', '_token', '_method'])));
    
        // Generate slug if not provided
        if (empty($request->slug)) {
            $broker->slug = Str::slug($request->name);
        }
    
        $broker->save();
    
        // Handle account option redirection
        $accountOption = AccountOption::where('broker_id', $broker->id)->first();
        
        if (!$accountOption) {
            return redirect()->route('admin_account_options_create', $broker->id)
                ->with('success', 'Broker updated successfully. Please create an account option.');
        }
    
        return redirect()->route('admin_account_options_edit', [$broker->id, $accountOption->id])
            ->with('success', 'Broker updated successfully. You can now edit the account option.');
    }
    
    public function delete($id)
    {
        try {
            $broker = Broker::findOrFail($id);
            
            // Delete associated image files
            if ($broker->logo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->logo)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->logo);
            }
            if ($broker->banner_image_1 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_1)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_1);
            }
            if ($broker->banner_image_2 && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_2)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $broker->banner_image_2);
            }
            
            // Delete associated account options
            AccountOption::where('broker_id', $broker->id)->delete();
            
            // Delete the broker
            $broker->delete();
            
            return redirect()->route('admin_broker_show')->with('success', 'Broker deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting broker: ' . $e->getMessage());
        }
    }
}