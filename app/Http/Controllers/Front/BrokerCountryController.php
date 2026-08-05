<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Broker;
use Illuminate\Support\Facades\Cookie;


class BrokerCountryController extends Controller
{
public function showBrokersByCountry($country)
{
    Helpers::read_json(); 

    $country = urldecode($country);

    // Language setup
    $current_short_name = Cookie::get('session_short_name') ?? optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
    $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

    // Page & ads
    $page_data = Page::where('language_id', $current_language_id)->first();
    $home_ad_data = HomeAdvertisement::find(1);

    // Load all brokers once (avoid repeated DB calls)
    $allBrokers = Broker::with('accountOptions')
        ->whereNotNull('rating')
        ->orderByDesc('rating')
        ->orderByDesc('id')
        ->get();

    // Filter by country in PHP
    $filteredBrokers = $allBrokers->filter(function ($broker) use ($country) {
        $rawCountries = $broker->associated_countries;
    
        // If already array, use it directly; if JSON string, decode it
        $countries = is_array($rawCountries) ? $rawCountries : (json_decode($rawCountries, true) ?: []);
    
        return in_array($country, $countries);
    })->values();


    // Manual pagination
    $page = request()->get('page', 1);
    $perPage = 10;
    $paginatedBrokers = new \Illuminate\Pagination\LengthAwarePaginator(
        $filteredBrokers->forPage($page, $perPage),
        $filteredBrokers->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Featured brokers (latest)
    $featured_brokers = Broker::where('featured_broker', 1)
        ->latest()
        ->take(6)
        ->get();

    // Featured brokers from this country
    $f_broker_country = Broker::get()->filter(function ($broker) use ($country) {
        $raw = $broker->associated_countries;
    
        // Decode only if it's a string, otherwise assume it's already an array
        $countries = is_array($raw) ? $raw : (json_decode($raw, true) ?: []);
    
        return in_array($country, $countries);
    })->take(5);


    // Top rated brokers by country
    $top_brokers = $filteredBrokers->take(5);

    // Metrics and statistics
    $totalBrokers = $filteredBrokers->count();

   

    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();
    $topRatedBrokers = Broker::orderBy('rating', 'desc')->take(6)->get();


    $metrics = [
        ['value' => $paginatedBrokers->total(), 'label' => 'Matching Brokers'],
        ['value' => $featured_brokers->count(), 'label' => 'Featured Brokers'],
        ['value' => $top_brokers->count(), 'label' => 'Top Rated Brokers'],
    ];

    $accountTypes = [
        'Standard Accounts',
        'Islamic Account',
        'ECN Accounts',
        'Classic Account',
        'Copy Trading Accounts',
        'VIP Accounts',
        'Raw Account',
        'Micro Accounts'
    ];

    return view('front.brokers.brokers_by_country', compact(
        'paginatedBrokers',
        'country',
        'page_data',
        'f_broker_country',
        'top_brokers',
        'home_ad_data',
        'featured_brokers',
        'metrics',
        'recommended_brokers',
        'accountTypes',
        'topRatedBrokers'
    ));
}


}
