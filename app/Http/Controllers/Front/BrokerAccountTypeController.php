<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Broker;
use App\Helper\Helpers;

class BrokerAccountTypeController extends Controller
{

public function showByAccountType($typeSlug)
{
    Helpers::read_json();

    // URL slug parameter
    $type = $typeSlug; // Pass to view for title etc.

    // Get current language and its ID
    $current_short_name = session()->get('session_short_name', optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en');
    $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

    // Page & home ad data
    $page_data = Page::where('language_id', $current_language_id)->first();
    $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        if ($type == 'trusted') {
            $paginatedBrokers = Broker::where('featured_broker', 1)->paginate(10);
            $brokers = $paginatedBrokers;
        
        } elseif (isset($slugToJsonValue[$type])) {
            $values = $slugToJsonValue[$type];
        
            $query = Broker::query();
        
            foreach ($values as $v) {
                $query->orWhereJsonContains('account_types', $v);
            }
        
            $paginatedBrokers = $query->paginate(10);
            $brokers = $paginatedBrokers;
        
        } else {
            // Normal account type
            $paginatedBrokers = Broker::whereJsonContains('account_types', $type)->paginate(10);
            $brokers = $paginatedBrokers;
        }



    // Featured brokers
    $featured_brokers = Broker::where('featured_broker', 1)
        ->latest()
        ->take(6)
        ->get();

    // Top rated brokers
    $top_brokers = ($type == 'trusted') 
        ? Broker::where('featured_broker', 1)->orderBy('rating', 'desc')->take(5)->get()
        : Broker::whereJsonContains('account_types', $type)
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

    // Recommended brokers
    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();

    // Optional account types list
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

    // Metrics calculation
    $totalBrokers = $brokers->count();

    $regulatedBrokers = $brokers->filter(fn ($broker) => $broker->isRegulated());

    $regulatedPercentage = $totalBrokers > 0
        ? round(($regulatedBrokers->count() / $totalBrokers) * 100)
        : 0;

    $allSpreadValues = $brokers->flatMap(function ($broker) {
        return $broker->accountOptions->pluck('spread_value');
    })->filter();

    $avgSpread = $allSpreadValues->count() > 0 ? round($allSpreadValues->avg(), 2) : null;

    $maxLeverage = $brokers->flatMap(function ($broker) {
        return $broker->accountOptions->pluck('max_leverage');
    })->filter()->map(function ($value) {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    })->max();

    $swapFreeCount = $brokers->flatMap(function ($broker) {
        return $broker->accountOptions->where('swap_free', 1);
    })->count();

    $metrics = [
        ['value' => $paginatedBrokers->count(), 'label' => 'Matching Brokers'],
        ['value' => $featured_brokers->count(), 'label' => 'Featured Brokers'],
        ['value' => $top_brokers->count(), 'label' => 'Top Rated Brokers'],
    ];

    // Return view with $type variable for title
    return view('front.brokers.brokers_by_account_type', compact(
        'paginatedBrokers',
        'brokers',
        'type', // ✅ view er title/headings er jonno
        'page_data',
        'featured_brokers',
        'accountTypes',
        'home_ad_data',
        'top_brokers',
        'metrics',
        'recommended_brokers',
        'avgSpread',
        'maxLeverage',
        'regulatedPercentage',
        'swapFreeCount'
    ));
}




}
