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
        $compare_brokers = Broker::where('id', '!=', $broker->id)->get();
        $faqs = $broker->faqs;
        $account_options = $broker->accountOptions; // Fetch all account options associated with the broker

        // Return the view and pass all necessary data
        return view('front.broker_detail', compact(
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
    



    // New method for broker list page
    public function list()
    {
        Helpers::read_json(); 

        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        $page_data = Page::where('language_id', $current_language_id)->first();

        $brokers = Broker::all(); 
        return view('front.broker_list', compact('page_data', 'brokers'));
    }



}