<?php

namespace App\Http\Controllers\Front;
use App\Helper\Helpers;
use App\Models\Language;
use App\Models\Page; 
use App\Models\Broker;
use App\Models\HomeAdvertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrokerComparisonController extends Controller
{
    public function compare($broker1_slug, $broker2_slug)
    {
        Helpers::read_json(); 

        // Handle language settings
        $current_short_name = session()->get('session_short_name') ?? 
                            Language::where('is_default', 'Yes')->first()->short_name;
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        
        // Get page data and ads
        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        // Get brokers with proper slug handling
        $broker1 = Broker::where('slug', $broker1_slug)->firstOrFail();
        $broker2 = Broker::where('slug', $broker2_slug)->firstOrFail();

        return view('front.comparison.broker_comparison_result', compact(
            'page_data', 
            'broker1', 
            'broker2', 
            'home_ad_data'
        ));
    }

    public function getComparison(Request $request)
    {
        $broker1_slug = $request->broker1_id;
        $broker2_slug = $request->broker2_id;

        // Validate the broker slugs before redirecting
        $broker1 = Broker::where('slug', $broker1_slug)->firstOrFail();
        $broker2 = Broker::where('slug', $broker2_slug)->firstOrFail();

        return redirect()->route('brokers.compare', [
            'broker1_slug' => $broker1_slug,
            'broker2_slug' => $broker2_slug
        ]);
    }

    public function showBrokerComparison()
    {
        Helpers::read_json(); 

        $current_short_name = session()->get('session_short_name') ?? 
                            Language::where('is_default', 'Yes')->first()->short_name;
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        $page_data = Page::where('language_id', $current_language_id)->first();
        $featured_brokers = Broker::where('featured_broker', 1)->latest()->take(6)->get();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
        $brokers = Broker::with('accountOptions')->get();
        $mostComparedBrokers = Broker::where('featured_broker', 1)->latest()->take(5)->get();

        return view('front.comparison.broker_comparison', compact(
            'brokers', 
            'page_data', 
            'featured_brokers', 
            'home_ad_data', 
            'mostComparedBrokers'
        ));
    }
}