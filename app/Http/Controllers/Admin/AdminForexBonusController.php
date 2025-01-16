<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ForexBonus;
use Illuminate\Http\Request;

class AdminForexBonusController extends Controller
{
    // Show a list of Forex Bonus posts
    public function show()
{
    // Get forex bonuses with pagination
    $forexBonuses = ForexBonus::paginate(10); // Adjust the number of records per page as needed

    return view('admin.forex_bonuses.show', compact('forexBonuses'));
}


    // Show the form to create a new Forex Bonus post
    public function create()
    {
        return view('admin.forex_bonuses.create');
    }

    public function store(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:forex_bonuses,slug', // Ensure slug is unique
        'publish_date' => 'required|date',
        'author_name' => 'required|string|max:255',
        'promo_type' => 'required|in:Forex Deposit Bonus,Forex No Deposit Bonus,Forex Live Contest,Forex Demo Contest,Forex Cashback Rebate,Crypto Bonus Promotion',
        'description' => 'required|string',
        'eligibility_criteria' => 'nullable|string', // Matches schema addition
        'expiry_date' => 'nullable|date',
        'min_deposit' => 'nullable|numeric|min:0', // Ensures the value is a non-negative number
        'bonus_type_details' => 'nullable|string',
        'feature_image' => 'required|image|mimes:jpg,png,jpeg,gif,webp|max:2048', // Added max size for better control
        'link' => 'required|url',
        'terms_conditions_url' => 'nullable|url',
        'affiliate_link' => 'nullable|url',
        'bonus_category' => 'nullable|string',
        'promotion_status' => 'nullable|in:ongoing,limited-time,expired',
        'participate' => 'required|string',
        'how_to_participate' => 'required|string',
        'details' => 'required|string',
        'general_terms' => 'required|string',
        'prize' => 'required|string|max:255', // Limited to prevent overly long prize descriptions
    ]);

    // Handle the image upload
    if ($request->hasFile('feature_image')) {
        // Get current timestamp and file extension
        $now = time();
        $ext = $request->file('feature_image')->extension();
        
        // Create a unique file name
        $final_name = 'feature_image_' . $now . '.' . $ext;
        
        // Move the file to the desired directory
        $request->file('feature_image')->move(public_path('uploads/forex_bonuses/'), $final_name);
        
        // Save the path in the forex bonus instance
        $forexBonus->feature_image = 'uploads/forex_bonuses/' . $final_name;
    }
    
    
    // Store the Forex Bonus in the database
    ForexBonus::create([
        'title' => $request->input('title'),
        'slug' => $request->input('slug'),  // Store the slug
        'publish_date' => $request->input('publish_date'),
        'author_name' => $request->input('author_name'),
        'promo_type' => $request->input('promo_type'),
        'description' => $request->input('description'),
        'feature_image' => 'forex_bonuses/' . $final_name,  // Use the relative path
        'link' => $request->input('link'),
        'participate' => $request->input('participate'),
        'how_to_participate' => $request->input('how_to_participate'),
        'details' => $request->input('details'),
        'general_terms' => $request->input('general_terms'),
        'prize' => $request->input('prize'),
        'eligibility_criteria' => $request->input('eligibility_criteria'),
        'expiry_date' => $request->input('expiry_date'),
        'min_deposit' => $request->input('min_deposit'),
        'bonus_type_details' => $request->input('bonus_type_details'),
        'terms_conditions_url' => $request->input('terms_conditions_url'),
        'affiliate_link' => $request->input('affiliate_link'),
        'bonus_category' => $request->input('bonus_category'),
        'promotion_status' => $request->input('promotion_status', 'ongoing'),  // Default to 'ongoing'
    ]);

    // Redirect to the index page with a success message
    return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus created successfully!');
}


    // Show the form to edit an existing Forex Bonus post
    public function edit($id)
    {
        // Find the Forex Bonus by ID
        $forexBonus = ForexBonus::findOrFail($id);

        // Return the edit view with the Forex Bonus data
        return view('admin.forex_bonuses.edit', compact('forexBonus'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'publish_date' => 'required|date',
            'author_name' => 'required|string|max:255',
            'promo_type' => 'required|in:Forex Deposit Bonus,Forex No Deposit Bonus,Forex Live Contest,Forex Demo Contest,Forex Cashback Rebate,Crypto Bonus Promotion',
            'description' => 'required|string',
            'feature_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048', // Optional image validation
            'link' => 'required|url',
            'participate' => 'required|string',
            'how_to_participate' => 'required|string',
            'details' => 'required|string',
            'general_terms' => 'required|string',
            'prize' => 'required|string|max:255', // Added max length for the prize
            'slug' => 'nullable|string|max:255|unique:forex_bonuses,slug,' . $id, // Handle uniqueness for updates
            'eligibility_criteria' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'min_deposit' => 'nullable|numeric|min:0', // Ensures no negative values
            'bonus_type_details' => 'nullable|string',
            'terms_conditions_url' => 'nullable|url',
            'affiliate_link' => 'nullable|url',
            'bonus_category' => 'nullable|string|max:255', // Added max length for category
            'promotion_status' => 'required|in:ongoing,limited-time,expired', // Ensures promotion status is valid
        ]);

    
        // Find the Forex Bonus by ID
        $forexBonus = ForexBonus::findOrFail($id);
    
        if ($request->hasFile('feature_image')) {
            // Delete the old image
            if ($forexBonus->feature_image && file_exists(public_path($forexBonus->feature_image))) {
                unlink(public_path($forexBonus->feature_image));
            }
        
            // Handle new image upload
            $now = time();
            $ext = $request->file('feature_image')->extension();
            $final_name = 'feature_image_' . $now . '.' . $ext;
            $request->file('feature_image')->move(public_path('uploads/forex_bonuses/'), $final_name);
            $forexBonus->feature_image = 'uploads/forex_bonuses/' . $final_name;
            $forexBonus->save();
        }
        
    
        // Update other fields
        $forexBonus->title = $request->input('title');
        $forexBonus->publish_date = $request->input('publish_date');
        $forexBonus->author_name = $request->input('author_name');
        $forexBonus->promo_type = $request->input('promo_type');
        $forexBonus->description = $request->input('description');
        $forexBonus->link = $request->input('link');
        $forexBonus->participate = $request->input('participate');
        $forexBonus->how_to_participate = $request->input('how_to_participate');
        $forexBonus->details = $request->input('details');
        $forexBonus->general_terms = $request->input('general_terms');
        $forexBonus->prize = $request->input('prize');
        $forexBonus->slug = $request->input('slug');  // Update the slug
        $forexBonus->eligibility_criteria = $request->input('eligibility_criteria');
        $forexBonus->expiry_date = $request->input('expiry_date');
        $forexBonus->min_deposit = $request->input('min_deposit');
        $forexBonus->bonus_type_details = $request->input('bonus_type_details');
        $forexBonus->terms_conditions_url = $request->input('terms_conditions_url');
        $forexBonus->affiliate_link = $request->input('affiliate_link');
        $forexBonus->bonus_category = $request->input('bonus_category');
        $forexBonus->promotion_status = $request->input('promotion_status');
    
        // Save all changes to the database
        $forexBonus->save();
    
        // Redirect to the index page with a success message
        return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus updated successfully!');
    }
    
    

    // Delete a Forex Bonus post
    public function delete($id)
    {
        // Find the Forex Bonus by ID and delete it
        $forexBonus = ForexBonus::findOrFail($id);
        $forexBonus->delete();

        // Redirect to the index page with a success message
        return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus deleted successfully!');
    }
}