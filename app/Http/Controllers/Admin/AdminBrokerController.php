<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\AccountOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AdminBrokerController extends Controller
{
    // Display a listing of brokers
    public function show()
    {
        $brokers = Broker::all();
        return view('admin.brokers.show', compact('brokers'));
    }

    // Show the form for creating a new broker
    public function create()
    {
        $broker = new Broker(); // You can create a new broker or fetch an existing one if needed
        return view('admin.brokers.create', compact('broker'));
    }

    public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'url' => 'required|url',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        'visit_site' => 'nullable|url',
        'open_live' => 'nullable|url',
        'open_demo' => 'nullable|url',
        'pros' => 'nullable|string',
        'cons' => 'nullable|string',
        'languages' => 'nullable|string',
        'pricing' => 'nullable|string',
        'deposit_methods' => 'nullable|string',
        'withdrawal_method' => 'nullable|string',
        'country' => 'required|string',
        'regulation' => 'nullable|string',
        'regulated_jurisdictions' => 'nullable|string',
        'regulatory_licenses' => 'nullable|string',
        'minimum_deposit' => 'nullable|numeric',
        'spreads' => 'nullable|string',
        'leverage' => 'nullable|string',
        'platforms' => 'nullable|string',
        'payment_methods' => 'nullable|string',
        'customer_support' => 'nullable|string',
        'educational_resources' => 'nullable|string',
        'research_tools' => 'nullable|string',
        'mobile_trading' => 'nullable|string',
        'social_trading' => 'nullable|string',
        'account_types' => 'nullable|array', // Ensure it’s an array
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
        'title' => 'nullable|string|max:255',  // New title field validation
        'rating' => 'nullable|numeric|min:0|max:5', // Rating validation (0 to 5)
    ]);


// Create a new Broker instance with validated data
$broker = new Broker($request->only([
    'name', 'url', 'short_description', 'visit_site', 'open_live',
    'open_demo', 'pros', 'cons', 'languages', 'pricing', 'deposit_methods',
    'withdrawal_method', 'country', 'regulation', 'regulated_jurisdictions',
    'regulatory_licenses', 'minimum_deposit', 'spreads', 'leverage', 'platforms',
    'payment_methods', 'customer_support', 'educational_resources', 'research_tools',
    'mobile_trading', 'social_trading', 'capitalization', 'insurance',
    'segregation_of_funds', 'web_trader', 'charting_tools', 'account_managers',
    'news_and_analysis', 'economic_calendar', 'vps_hosting', 'associated_countries',
    'slug', 'top_feature', 'featured_broker', 'top_broker', 
    'meta_title', 'meta_keyword', 'meta_description',
    'title', 'rating'
]));

// Handle the `account_types` field
if ($request->has('account_types')) {
    // Convert the array of selected account types to JSON and store it
    $broker->account_types = json_encode($request->input('account_types'));
}

if ($request->hasFile('logo')) {
    // Get current time and file extension
    $now = time();
    $ext = $request->file('logo')->extension();
    // Create a unique file name
    $final_name = 'logo_'.$now.'.'.$ext;
    // Move the file to the desired location
    $request->file('logo')->move(public_path('uploads/logos/'), $final_name);
    // Save the path in the broker instance
    $broker->logo = 'uploads/logos/' . $final_name;
}
    // Save the broker instance
    $broker->save();

// Redirect to the broker list page with a success message
return redirect()->route('admin_account_options_create', $broker->id)
                 ->with('success', 'Broker created successfully. Now add account options.');
}


    // Show the form for editing an existing broker
    public function edit($id)
{
    // Find the broker
    $broker = Broker::findOrFail($id);

    // Find the associated account option for this broker (if exists)
    $accountOption = AccountOption::where('broker_id', $broker->id)->first();

    // Return the view and pass the broker and accountOption (if it exists)
    return view('admin.brokers.edit', compact('broker', 'accountOption'));
}


   public function update(Request $request, $id)
   {
       // Validate the incoming request, including 'logo' and other fields
       $request->validate([
           'name' => 'required|string|max:255',
           'url' => 'required|url',
           'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate logo image
           'visit_site' => 'nullable|url',
           'open_live' => 'nullable|url',
           'open_demo' => 'nullable|url',
           'pros' => 'nullable|string',
           'cons' => 'nullable|string',
           'languages' => 'nullable|string',
           'pricing' => 'nullable|string',
           'deposit_methods' => 'nullable|string',
           'withdrawal_method' => 'nullable|string',
           'country' => 'required|string',
           'regulation' => 'nullable|string',
           'regulated_jurisdictions' => 'nullable|string',
           'regulatory_licenses' => 'nullable|string',
           'minimum_deposit' => 'nullable|numeric',
           'spreads' => 'nullable|string',
           'leverage' => 'nullable|string',
           'platforms' => 'nullable|string',
           'payment_methods' => 'nullable|string',
           'customer_support' => 'nullable|string',
           'educational_resources' => 'nullable|string',
           'research_tools' => 'nullable|string',
           'mobile_trading' => 'nullable|string',
           'social_trading' => 'nullable|string',
           'account_types' => 'nullable|array', // Ensure it’s an array
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
           'title' => 'nullable|string|max:255', // New title field validation
           'rating' => 'nullable|numeric|min:0|max:5', // Rating validation (0 to 5)
       ]);
   
       // Find the broker by ID
       $broker = Broker::findOrFail($id);
   
       // Handle the logo update
       if ($request->hasFile('logo')) {
        // If there is an existing logo, delete it from storage
        if ($broker->logo && file_exists(public_path($broker->logo))) {
            unlink(public_path($broker->logo));
        }
        // Get the current timestamp and file extension
        $now = time();
        $ext = $request->file('logo')->extension();
        
        // Generate a new unique filename
        $final_name = 'logo_'.$now.'.'.$ext;

        // Move the new file to the uploads/logos folder
        $request->file('logo')->move(public_path('uploads/logos/'), $final_name);

        // Update the broker's logo path in the database
        $broker->logo = 'uploads/logos/' . $final_name;
    }

   
       // Handle account types as JSON
       if ($request->has('account_types')) {
           $broker->account_types = json_encode($request->input('account_types'));
       }
   
       // Update all other fields (except 'logo' and 'account_types')
       $broker->update($request->except(['logo', 'account_types']));
   
       // Check if an AccountOption exists for this broker
       $accountOption = AccountOption::where('broker_id', $broker->id)->first();
       if (!$accountOption) {
           // Redirect to create an account option if it doesn't exist
           return redirect()->route('admin_account_options_create', $broker->id)
               ->with('success', 'Broker updated successfully. Please create an account option.');
       }
   
       // Redirect to edit the existing account option
       return redirect()->route('admin_account_options_edit', [$broker->id, $accountOption->id])
           ->with('success', 'Broker updated successfully. You can now edit the account option.');
   }
   
   

    // Delete an existing broker from the database
    public function delete($id)
    {
        $broker = Broker::findOrFail($id);
        $broker->delete();
        return redirect()->route('admin_broker_show')->with('success', 'Broker deleted successfully.');
    }
}