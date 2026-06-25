<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForexBonus;
use App\Models\Broker; 
use App\Models\Review;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\AccountOption;
use App\Models\Language;
use App\Helper\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class BrokerController extends Controller
{
    public function detail($slug)
    {
        Helpers::read_json(); // Optional helper functionality
    
        // Determine the current language
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }
    
        // Get the current language ID
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
    
        // Fetch the broker details by slug
        $broker = Broker::where('slug', $slug)->firstOrFail();
    
        // Fetch the page data for the current language
        $page_data = Page::where('language_id', $current_language_id)->first();
    
        // Fetch the latest 5 brokers
        $brokers = Broker::latest()->take(5)->get();
    
        // Fetch approved reviews for the broker
        $approved_reviews = $broker->reviews()->where('status', 1)->get();
        foreach ($approved_reviews as $review) {
            $review->formatted_date = $review->created_at->format('F Y'); // Format to 'Month Year'
        }
    
        // Fetch the featured brokers
        $featured = Broker::where('featured_broker', 1)->get();
    
        // Fetch the home advertisement data
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
    
        // Fetch all brokers for comparison, excluding the current broker
        $compare_brokers = Broker::where('id', '!=', $broker->id)
        ->inRandomOrder()
        ->limit(15)
        ->get();        $faqs = $broker->faqs;
        $account_options = $broker->accountOptions; // Fetch all account options associated with the broker

        // Return the view and pass all necessary data
        return view('front.brokers.broker_detail', compact(
            'broker',
            'page_data',
            'approved_reviews',
            'brokers',
            'home_ad_data',
            'featured',
            'compare_brokers',
            'faqs',
            'account_options'
        ));
    }
    


public function liveSearch(Request $request)
{
    $query = $request->get('query');

    if (!$query) {
        return response()->json([]);
    }

    $normalizedQuery = str_replace([' ', '-'], '', $query);

    $brokers = Broker::where('name', 'LIKE', "%{$query}%")
        ->orWhere(
            DB::raw("REPLACE(REPLACE(name,' ',''),'-','')"),
            'LIKE',
            "%{$normalizedQuery}%"
        )
        ->limit(8)
        ->get()
        ->map(function ($broker) {
            return [
                'name' => $broker->name,
                'slug' => $broker->slug,
                'logo_url' => $broker->logo
                    ? asset($broker->logo)
                    : asset('images/default-broker.png'),
            ];
        });

    return response()->json($brokers);
}




public function byAward($award)
{
    // Filter brokers by award
    $brokers = Broker::all()->filter(function ($broker) use ($award) {
        $types = $broker->account_types;

        // Decode JSON string to array if necessary
        if (is_string($types)) {
            $types = json_decode($types, true);
        }

        if (!is_array($types)) return false;

        // Check if the award slug exists in the broker's account_types array
        return in_array($award, $types);
    });

    // Paginate filtered brokers
    $paginatedBrokers = new \Illuminate\Pagination\LengthAwarePaginator(
        $brokers->forPage(request()->get('page', 1), 10),
        $brokers->count(),
        10,
        request()->get('page', 1),
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Featured brokers filtered by award/account type
    $featured_brokers = $brokers->filter(function ($broker) {
        return $broker->featured_broker == 1;
    })->take(6);

    // Top rated brokers filtered by award/account type
    $top_brokers = $brokers->sortByDesc('rating')->take(5);

    // Recommended brokers (overall top rated, not award-specific)
    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();

    // Metrics
    $metrics = [
        ['value' => $paginatedBrokers->count(), 'label' => 'Matching Brokers'],
        ['value' => $featured_brokers->count(), 'label' => 'Featured Brokers'],
        ['value' => $top_brokers->count(), 'label' => 'Top Rated Brokers'],
    ];

         return view('front.brokers.listing', [
            'paginatedBrokers' => $paginatedBrokers,
            'featured_brokers' => $featured_brokers,
            'top_brokers' => $top_brokers,
            'recommended_brokers' => $recommended_brokers,
            'metrics' => $metrics,
            'awardName' => $award, // rename here to match view
        ]);

}




public function bestBrokers($slug)
{
    $brokers = Broker::all()->filter(function ($broker) use ($slug) {
        // Account types
        $types = $broker->account_types;
        if (is_string($types)) {
            $types = json_decode($types, true);
        }

        // Countries  
        $countries = $broker->associated_countries;
        if (is_string($countries)) {
            $countries = json_decode($countries, true);
        }

        return (
            (is_array($types) && in_array($slug, $types)) ||
            (is_array($countries) && in_array($slug, $countries))
        );
    });

    // Get recommended brokers (top 5 by rating)
    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();
    // Top brokers from current category
    $top_brokers = $brokers
        ->sortByDesc('rating')
        ->take(5);

    // Determine if slug is country or account type
    $type = $slug;
    $displayName = ucfirst($slug);
    
    return view('front.brokers.category', compact(
        'brokers', 
        'slug', 
        'type', 
        'displayName',
        'top_brokers',
        'recommended_brokers'  // Add this line
    ));
}

}