<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeAdvertisement;
use App\Models\Setting;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\Video;
use App\Models\Category;
use App\Models\Language;
use App\Models\ForexBonus;
use App\Models\AccountOption;
use App\Models\Broker;
use App\Helper\Helpers;
use App\Models\Page; 
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        // Detect current language
        $current_short_name = session()->get('session_short_name') ??
            Language::where('is_default', 'Yes')->value('short_name');

        $language = Language::where('short_name', $current_short_name)->first();
        $current_language_id = $language->id ?? Language::where('is_default', 'Yes')->value('id');

        // ✅ Cache queries for 1 hour (3600 seconds)
        $forex_bonus_data = Cache::remember('forex_bonus_data', 3600, function () {
            return ForexBonus::where('promo_type', 'Forex Deposit Bonus')->latest()->take(6)->get();
        });

        $noDepositBonuses = Cache::remember('noDepositBonuses', 3600, function () {
            return ForexBonus::where('promo_type', 'Forex No Deposit Bonus')->latest()->take(6)->get();
        });

        $demoContest = Cache::remember('demoContest', 3600, function () {
            return ForexBonus::where('promo_type', 'Forex Demo Contest')->latest()->take(6)->get();
        });

        $liveContest = Cache::remember('liveContest', 3600, function () {
            return ForexBonus::where('promo_type', 'Forex Live Contest')->latest()->take(6)->get();
        });

        $forexCashbackRebate = Cache::remember('forexCashbackRebate', 3600, function () {
            return ForexBonus::where('promo_type', 'Forex Cashback Rebate')->latest()->take(6)->get();
        });

        $cryptoBonusPromotion = Cache::remember('cryptoBonusPromotion', 3600, function () {
            return ForexBonus::where('promo_type', 'Crypto Bonus Promotion')->latest()->take(6)->get();
        });

        // ✅ Limit broker queries
        $featured_brokers = Cache::remember('featured_brokers', 3600, function () {
            return Broker::where('featured_broker', 1)->latest()->take(8)->get();
        });

        $all_brokers = Broker::whereNotNull('rating')
        ->orderByDesc('rating')
        ->take(10)
        ->get();


        $top_brokers = Cache::remember('top_brokers_' . rand(), 3600, function () {
            return Broker::where('featured_broker', 1)
                ->inRandomOrder()
                ->take(6)
                ->get();
        });

        $best_leverage_brokers = Cache::remember('best_leverage_brokers', 3600, function () {
            return Broker::orderBy('leverage', 'desc')->take(8)->get();
        });

           $regulatedBrokers = Cache::remember('regulatedBrokers', 3600, function () {
            return Broker::with('accountOptions')
                ->whereHas('accountOptions', function ($query) {
                    $query->where('is_regulated', 1);
                })
                ->orderBy('updated_at', 'desc') // sort by last updated
                ->take(12)
                ->get();
        });

        $non_regulatedBrokers = Cache::remember('non_regulatedBrokers', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->where('is_regulated', false);
            })->take(6)->get();
        });

        $bestForBeginners = Cache::remember('bestForBeginners', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->where('min_deposit', '<=', 10)
                      ->where('is_demo_available', 1);
            })->take(6)->get();
        });

        $bestBonuses = Cache::remember('bestBonuses', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->whereNotNull('exclusive_offers')
                      ->orWhere('bonus_eligibility', 1);
            })->take(6)->get();
        });

        // ✅ Optimize spread ranking (SQL instead of PHP sort)
        $spreadRankings = Cache::remember('spreadRankings', 3600, function () {
            return Broker::select('brokers.*')
                ->join('account_options', 'brokers.id', '=', 'account_options.broker_id')
                ->orderBy('account_options.spread_value', 'asc')
                ->take(10)
                ->get();
        });

        // Top brokers this month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $topBrokersThisMonth = Cache::remember('topBrokersThisMonth', 1800, function () use ($startOfMonth, $endOfMonth) {
            $top = Broker::whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->orderBy('rating', 'desc')
                ->take(5)
                ->get();

            return $top->isNotEmpty() ? $top : Broker::orderBy('rating', 'desc')->take(6)->get();
        });
        
        $topRatedBrokers = Broker::orderBy('rating', 'desc')->take(6)->get();


        $topRatedRegulatedBrokers = Cache::remember('topRatedRegulatedBrokers', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->where('is_regulated', 1);
            })->orderBy('rating', 'desc')->take(5)->get();
        });

        $demoAvailableBrokers = Cache::remember('demoAvailableBrokers', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->where('is_demo_available', 1);
            })->take(6)->get();
        });

        $lowDepositBrokers = Cache::remember('lowDepositBrokers', 3600, function () {
            return Broker::whereHas('accountOptions', function ($query) {
                $query->where('min_deposit', '<=', 50);
            })->take(6)->get();
        });

        // ✅ News (cache + limit)
        $recentNewsData = Cache::remember("recentNews_{$current_language_id}", 600, function () use ($current_language_id) {
            return Post::with('rSubCategory')
                ->where('language_id', $current_language_id)
                ->latest()
                ->take(6)
                ->get();
        });

        $popularNewsData = Cache::remember("popularNews_{$current_language_id}", 600, function () use ($current_language_id) {
            return Post::with('rSubCategory')
                ->where('language_id', $current_language_id)
                ->orderBy('visitors', 'desc')
                ->take(6)
                ->get();
        });

        $hasNewsSection = $recentNewsData->isNotEmpty() || $popularNewsData->isNotEmpty();
        
        
   


        // ✅ Smaller queries (no cache needed)
        $video_data = Video::where('language_id', $current_language_id)->take(10)->get();
        $home_ad_data = HomeAdvertisement::find(1);
        $setting_data = Setting::find(1);

        $post_data = Post::with('rSubCategory')
            ->where('language_id', $current_language_id)
            ->latest()
            ->paginate(10); // ✅ Use pagination instead of loading all

        $sub_category_data = SubCategory::with('rPost')
            ->where('show_on_home', 'Show')
            ->where('language_id', $current_language_id)
            ->orderBy('sub_category_order', 'asc')
            ->take(10)
            ->get();

        $category_data = Category::where('language_id', $current_language_id)
            ->orderBy('category_order', 'asc')
            ->take(10)
            ->get();

        $forex_tips = Cache::remember('forex_tips', 600, function () {
            return Post::with('rSubCategory')->whereHas('rSubCategory', function ($query) {
                $query->where('sub_category_name', 'Forex Tips');
            })->latest()->take(5)->get();
        });

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

        return view('front.homepage.home', compact(
            'home_ad_data',
            'setting_data',
            'post_data',
            'sub_category_data',
            'video_data',
            'category_data',
            'forex_bonus_data',
            'noDepositBonuses',
            'featured_brokers',
            'top_brokers',
            'best_leverage_brokers',
            'accountTypes',
            'regulatedBrokers',
            'demoContest',
            'liveContest',
            'forexCashbackRebate',
            'cryptoBonusPromotion',
            'non_regulatedBrokers',
            'forex_tips',
            'bestForBeginners',
            'bestBonuses',
            'spreadRankings',
            'topBrokersThisMonth',
            'all_brokers',
            'recentNewsData',
            'popularNewsData',
            'hasNewsSection',
            'topRatedRegulatedBrokers',
            'demoAvailableBrokers',
            'lowDepositBrokers',
            'topRatedBrokers'
        ));
    }
}
