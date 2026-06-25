<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\TopAdvertisement;
use App\Models\SidebarAdvertisement;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Page;
use App\Models\LiveChannel;
use App\Models\Post;
use App\Models\OnlinePoll;
use App\Models\SocialItem;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Broker;
use App\Models\ForexBonus;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    Paginator::useBootstrap();

    // Fetching global data
    $top_ad_data = TopAdvertisement::where('id', 1)->first();
    $sidebar_top_ad = SidebarAdvertisement::where('sidebar_ad_location', 'Top')->get();
    $sidebar_bottom_ad = SidebarAdvertisement::where('sidebar_ad_location', 'Bottom')->get();
    $categories = Category::with('rSubCategory')->where('show_on_menu', 'Show')->orderBy('category_order', 'asc')->get();

    // Social, setting, language data
    $social_item_data = SocialItem::get();
    $setting_data = Setting::where('id', 1)->first();
    $language_data = Language::get();
    $default_lang_data = Language::where('is_default', 'Yes')->first();

    // Sharing global data with all views
    view()->share('global_top_ad_data', $top_ad_data);
    view()->share('global_sidebar_top_ad', $sidebar_top_ad);
    view()->share('global_sidebar_bottom_ad', $sidebar_bottom_ad);
    view()->share('global_categories', $categories);
    view()->share('global_social_item_data', $social_item_data);
    view()->share('global_setting_data', $setting_data);
    view()->share('global_language_data', $language_data);
    view()->share('global_short_name', $default_lang_data->short_name);

    // Share brokers data globally
    $brokers = Broker::all();
    view()->share('brokers', $brokers);

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

    View::share('accountTypes', $accountTypes);

   // Share Demo Contest
   $demoContest = ForexBonus::where('promo_type', 'Forex Demo Contest')->latest()->take(6)->get();
   View::share('demoContest', $demoContest);

   // Share Live Contest
   $liveContest = ForexBonus::where('promo_type', 'Forex Live Contest')->latest()->take(6)->get();
   View::share('liveContest', $liveContest);

   // Share Forex Cashback Rebate
   $forexCashbackRebate = ForexBonus::where('promo_type', 'Forex Cashback Rebate')->latest()->take(6)->get();
   View::share('forexCashbackRebate', $forexCashbackRebate);

   // Share Crypto Bonus Promotion
   $cryptoBonusPromotion = ForexBonus::where('promo_type', 'Crypto Bonus Promotion')->latest()->take(6)->get();
   View::share('cryptoBonusPromotion', $cryptoBonusPromotion);
   
     // ✅ Share Top Rated Brokers (Fixes your navbar error)
    $topRatedBrokers = Broker::orderBy('rating', 'DESC')->take(6)->get();
    View::share('topRatedBrokers', $topRatedBrokers);
}


}