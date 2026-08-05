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
use App\Services\EditorialAssignmentService;
use Illuminate\Support\Str;



class BonusController extends Controller
{

    public function showBonusByType($type, Request $request)
{
    Helpers::read_json();

    $current_short_name = session()->get('session_short_name', optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en');
    $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

    $page_data = Page::where('language_id', $current_language_id)->first();

    $promo_types = [
        'deposit-bonuses' => 'Forex Deposit Bonus',
        'no-deposit-bonuses' => 'Forex No Deposit Bonus',
        'live-contests' => 'Forex Live Contest',
        'demo-contests' => 'Forex Demo Contest',
        'cashback-rebates' => 'Forex Cashback Rebate',
        'crypto-bonuses' => 'Crypto Bonus Promotion',
    ];

    if (!array_key_exists($type, $promo_types)) {
        abort(404);
    }

    $promo_type = $promo_types[$type];
    $page_title = 'BrokersCourt | ' . $promo_type . ' for Every Trader';
    $page_url = url()->current();

    $sort = $request->get('sort', 'default');
    $query = ForexBonus::where('promo_type', $promo_type);

    // Sorting based on min_deposit
    if ($sort === 'high-to-low') {
        $query->orderByDesc('min_deposit');
    } elseif ($sort === 'low-to-high') {
        $query->orderBy('min_deposit');
    } elseif ($sort === 'expiring-soon') {
        $query->orderBy('expiry_date', 'asc');
    } elseif ($sort === 'latest-expiry') {
        $query->orderBy('expiry_date', 'desc');
    } elseif ($sort === 'recently-published') {
        $query->orderBy('publish_date', 'desc');
    } elseif ($sort === 'title-asc') {
        $query->orderBy('title', 'asc');
    } elseif ($sort === 'title-desc') {
        $query->orderBy('title', 'desc');
    } else {
        $query->latest();
    }

    $forexBonuses = $query->with('broker')->paginate(6)->appends(['sort' => $sort]);

    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();

    foreach ($forexBonuses as $bonus) {
        $bonus->broker_name = $bonus->broker?->name ?? $this->brokerNameFromUrl($bonus->link);
    }

    return view('front.bonuses.bonus_type', compact(
        'page_data', 'forexBonuses', 'promo_type', 'recommended_brokers',
        'type', 'page_title', 'page_url'
    ));
}

    
    

public function bonusDetail($slug)
    {
        // Optional helper functionality
        Helpers::read_json(); 

        // Determine the current language
        $current_short_name = session()->get('session_short_name', optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en');

        // Get the current language ID
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        // Fetch the page data for the current language
        $page_data = Page::where('language_id', $current_language_id)->first();

        // Fetch the bonus by slug
        $bonus = ForexBonus::with([
            'broker',
            'writtenByAuthor',
            'editedByAuthor',
            'factCheckedByAuthor',
            'writtenByAdmin',
            'editedByAdmin',
            'factCheckedByAdmin',
        ])->where('slug', $slug)->firstOrFail();

        $editorialCredits = EditorialAssignmentService::creditsFor($bonus);

        // Fetch the promo type dynamically
        $promo_type = $bonus->promo_type;
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        // Define route mapping for promo types
        $routes = [
            'Forex No Deposit Bonus' => 'forex_no_deposit_bonus',
            'Forex Deposit Bonus' => 'forex_deposit_bonus',
            'Forex Cashback Rebate' => 'forex_cashback_rebate',
            'Crypto Bonus Promotion' => 'crypto_bonus_promotion',
            'Forex Live Contest' => 'forex_live_contest',
            'Forex Demo Contest' => 'forex_demo_contest',
        ];
        $promo_route = isset($routes[$promo_type]) ? route($routes[$promo_type]) : route('home');


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
        return view('front.bonuses.bonus_detail', compact(
            'page_data', 'bonus', 'promo_type', 'promo_route', 'recent_deposit_bonuses',
            'recent_no_deposit_bonuses', 'featured_brokers', 'home_ad_data', 'editorialCredits'
        ));
    }

    protected function brokerNameFromUrl(?string $url): string
    {
        if (! $url) {
            return 'Unknown Broker';
        }

        $parsedUrl = parse_url($url);
        $brokerName = $parsedUrl['host'] ?? 'Unknown Broker';

        return ucfirst(preg_replace('/\.[a-z]{2,6}$/', '', str_replace('www.', '', $brokerName)));
    }

    
}