<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\ForexBonus;
use App\Models\Broker;
use App\Helper\Helpers;
use App\Models\HomeAdvertisement;
use App\Models\Page;
use Illuminate\Support\Str;



class BonusController extends Controller
{
    public function showBonusByType($type)
{
    Helpers::read_json(); // Optional helper functionality

    // Determine the current language
    $current_short_name = session()->get('session_short_name', Language::where('is_default', 'Yes')->first()->short_name);

    // Get the current language ID
    $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

    // Fetch the page data for the current language
    $page_data = Page::where('language_id', $current_language_id)->first();
    $allforexBonuses = ForexBonus::all();

    // Map the type parameter to the corresponding promo type
    $promo_types = [
        'deposit-bonuses' => 'Forex Deposit Bonus',
        'no-deposit-bonuses' => 'Forex No Deposit Bonus',
        'live-contests' => 'Forex Live Contest',
        'demo-contests' => 'Forex Demo Contest',
        'cashback-rebates' => 'Forex Cashback Rebate',
        'crypto-bonuses' => 'Crypto Bonus Promotion',
    ];

    // Validate the type
    if (!array_key_exists($type, $promo_types)) {
        abort(404); // Throw a 404 error if the type is invalid
    }

    $promo_type = $promo_types[$type];

    // Set the dynamic title and URL
    $page_title = 'BrokersCourt | ' . $promo_type . ' for Every Trader';
    $page_url = url()->current();  // Get the current URL

    // Fetch the bonuses based on the promo type
    $forexBonuses = ForexBonus::where('promo_type', $promo_type)->paginate(6);
    $featured_brokers = Broker::where('featured_broker', 1)->latest()->take(5)->get();
    $home_ad_data = HomeAdvertisement::where('id', 1)->first();
    $demoContest = ForexBonus::where('promo_type', 'Forex Demo Contest')->latest()->take(6)->get();
    $liveContest = ForexBonus::where('promo_type', 'Forex Live Contest')->latest()->take(6)->get();
    $forexCashbackRebate = ForexBonus::where('promo_type', 'Forex Cashback Rebate')->latest()->take(6)->get();
    $cryptoBonusPromotion = ForexBonus::where('promo_type', 'Crypto Bonus Promotion')->latest()->take(6)->get();

    // Add broker name extraction (without TLD like .com, .net, etc.)
    foreach ($forexBonuses as $bonus) {
        $url = $bonus->link;
        $parsedUrl = parse_url($url);
        $brokerName = $parsedUrl['host'] ?? 'Unknown Broker';

        // Remove 'www.' and the domain suffix (.com, .net, etc.)
        $brokerName = preg_replace('/\.[a-z]{2,6}$/', '', str_replace('www.', '', $brokerName));

        // Store the broker name in the bonus object
        $bonus->broker_name = ucfirst($brokerName); // Capitalize the first letter
    }

    // Return the shared view for all bonus types
    return view('front.bonuses.bonus_type', compact('page_data', 'forexBonuses', 'promo_type', 'featured_brokers', 'home_ad_data', 'type', 'demoContest', 'liveContest', 'forexCashbackRebate', 'cryptoBonusPromotion', 'page_title', 'page_url'));
}

    
    

public function bonusDetail($slug)
    {
        // Optional helper functionality
        Helpers::read_json(); 

        // Determine the current language
        $current_short_name = session()->get('session_short_name', Language::where('is_default', 'Yes')->first()->short_name);

        // Get the current language ID
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        // Fetch the page data for the current language
        $page_data = Page::where('language_id', $current_language_id)->first();

        // Fetch the bonus by slug
        $bonus = ForexBonus::where('slug', $slug)->firstOrFail();

        // Fetch the promo type dynamically
        $promo_type = $bonus->promo_type;
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        // Define route mapping for promo types
        $routes = [
            'Forex No Deposit Bonus' => 'forex_no_deposit_bonus',
            'Forex Deposit Bonus' => 'forex_deposit_bonus',
            'Welcome Bonus' => 'welcome_bonus',
            'Forex Cashback Rebate' => 'forex_cashback_rebate',
            'Crypto Bonus Promotion' => 'crypto_bonus_promotion',
            'Forex Live Contest' => 'forex_live_contest',
        ];
        $promo_route = route($routes[$promo_type]);


            // Fetch the latest 5 Forex Deposit Bonuses
        $recent_deposit_bonuses = ForexBonus::where('promo_type', 'Forex Deposit Bonus')
        ->orderBy('publish_date', 'desc')
        ->take(5)
        ->get();

        // Fetch the latest 5 Forex No Deposit Bonuses
        $recent_no_deposit_bonuses = ForexBonus::where('promo_type', 'Forex No Deposit Bonus')
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get();

        $featured_brokers = Broker::where('featured_broker', 1)->take(5)->get();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
        
       

        // Return the view and pass the bonus and page data
        return view('front.bonuses.bonus_detail', compact('page_data', 'bonus', 'promo_type', 'promo_route','recent_deposit_bonuses','recent_no_deposit_bonuses','featured_brokers','home_ad_data'));
    }

    
}