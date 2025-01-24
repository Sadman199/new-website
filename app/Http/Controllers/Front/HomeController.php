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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

  public function index()
  {
      // Call the helper to read JSON (your existing logic)
      Helpers::read_json();
  
      // Get current language settings
      if (!session()->get('session_short_name')) {
          $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
      } else {
          $current_short_name = session()->get('session_short_name');
      }
  
      // Fetch the current language and check if it exists
      $language = Language::where('short_name', $current_short_name)->first();
  
      if ($language) {
          $current_language_id = $language->id;
      } else {
          // Handle the case when the language is not found (e.g., use default language ID)
          $current_language_id = Language::where('is_default', 'Yes')->first()->id;
      }
  
      // Fetch Forex Bonus and other data
      $forex_bonus_data = ForexBonus::where('promo_type', 'Forex Deposit Bonus')->latest()->take(4)->get();
      $noDepositBonuses = ForexBonus::where('promo_type', 'Forex No Deposit Bonus')->latest()->take(4)->get();
      $demoContest = ForexBonus::where('promo_type', 'Forex Demo Contest')->latest()->take(6)->get();
      $liveContest = ForexBonus::where('promo_type', 'Forex Live Contest')->latest()->take(6)->get();
      $forexCashbackRebate = ForexBonus::where('promo_type', 'Forex Cashback Rebate')->latest()->take(6)->get();
      $cryptoBonusPromotion = ForexBonus::where('promo_type', 'Crypto Bonus Promotion')->latest()->take(6)->get();
      $brokers = Broker::all();
      $featured_brokers = Broker::where('featured_broker', 1)->latest() ->take(8)->get();
      $top_brokers = Broker::orderBy('rating', 'desc')->take(6)->get();
      $best_leverage_brokers = Broker::orderBy('leverage', 'desc')->latest()->take(8)->get();
      $video_data = Video::where('language_id', $current_language_id)->get();
      $home_ad_data = HomeAdvertisement::where('id', 1)->first();
      $setting_data = Setting::where('id', 1)->first();
      $post_data = Post::with('rSubCategory')->orderBy('id', 'desc')->where('language_id', $current_language_id)->get();
      $sub_category_data = SubCategory::with('rPost')->orderBy('sub_category_order', 'asc')->where('show_on_home', 'Show')->where('language_id', $current_language_id)->get();
      $category_data = Category::orderBy('category_order', 'asc')->where('language_id', $current_language_id)->get();
      $regulatedBrokers = Broker::whereHas('accountOptions', function($query) {
          $query->where('is_regulated', 1);
      })->get();
      $forex_tips = Post::with('rSubCategory')->whereHas('rSubCategory', function ($query) {
        $query->where('sub_category_name', 'Forex Tips'); // Replace 'Forex Tips' with your actual subcategory name
    })->latest()->take(5)->get();

      $non_regulatedBrokers = Broker::whereHas('accountOptions', function($query) {
          $query->where('is_regulated', false); // Fetch non-regulated brokers
      })->take(6)->get();
  
      // Account types to display on the homepage (the 8 account types)
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
  
      // Return the view with all the data
      return view('front.home', compact(
          'home_ad_data', 
          'setting_data', 
          'post_data', 
          'sub_category_data', 
          'video_data', 
          'category_data', 
          'forex_bonus_data',
          'noDepositBonuses',
          'brokers', 
          'featured_brokers', 
          'top_brokers', 
          'best_leverage_brokers',
          'accountTypes', // Pass the account types here
          'regulatedBrokers',
          'demoContest',
          'liveContest',
          'forexCashbackRebate',
          'cryptoBonusPromotion',
          'non_regulatedBrokers',
          'forex_tips'
      ));
  }

    public function get_subcategory_by_category($id)
    {
        Helpers::read_json();
        
        $sub_category_data = SubCategory::where('category_id',$id)->get();
        $response = "<option value=''>".SELECT_SUBCATEGORY."</option>";
        foreach($sub_category_data as $item) {
            $response .= '<option value="'.$item->id.'">'.$item->sub_category_name.'</option>';
        }

        return response()->json(['sub_category_data'=>$response]);
    }
    public function search(Request $request)
    {
        Helpers::read_json();
        
        $post_data = Post::with('rSubCategory')->orderBy('id','desc');
        if($request->text_item!=''){
            $post_data = $post_data->where('post_title', 'like', '%'.$request->text_item.'%');
        }
        if($request->sub_category!='') {
            $post_data = $post_data->where('sub_category_id', $request->sub_category);
        }
        $post_data = $post_data->paginate(12);

        return view('front.search_result', compact('post_data'));
    }
    public function showBrokersByCountry($country)
    {
        Helpers::read_json(); 
        $country = strtolower(urldecode($country));
        $current_short_name = session()->get('session_short_name', Language::where('is_default', 'Yes')->first()->short_name);
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        $page_data = Page::where('language_id', $current_language_id)->first();
        $brokers = Broker::whereRaw('LOWER(JSON_EXTRACT(associated_countries, "$")) LIKE ?', ['%"' . $country . '"%'])->get();
        $featured_brokers = Broker::where('featured_broker', 1)->latest() ->take(6)->get();
        $regulatedBrokers = Broker::whereHas('accountOptions', function($query) {
            $query->where('is_regulated', 1);
        })->get();
        return view('front.brokers_by_country', compact('brokers', 'country','page_data', 'featured_brokers','regulatedBrokers'));
    }
    
    public function showByAccountType($type)
    {
        Helpers::read_json();
        $current_short_name = session()->get('session_short_name', Language::where('is_default', 'Yes')->first()->short_name);
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
    
        $type = str_replace('-', ' ', $type);
    
        $brokers = Broker::where('account_types', 'like', '%'.$type.'%')->get();
        $featured_brokers = Broker::where('featured_broker', 1)->latest()->take(6)->get();
    
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
    
        $type = strtolower(trim(str_replace('-', ' ', $type)));
        return view('front.brokers_by_account_type', compact('brokers', 'type', 'page_data', 'featured_brokers', 'accountTypes', 'home_ad_data'));
    }




    public function showComparisonDropdown()
    {
        // Fetch all brokers from the database and limit to 10
        $brokers = Broker::limit(10)->get();
    
        return view('front.home', compact('brokers'));
    }
    
    public function compare($broker1_slug, $broker2_slug)
    {
        Helpers::read_json(); // Optional helper functionality
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
        $broker1 = Broker::where('slug', $broker1_slug)->firstOrFail();
        $broker2 = Broker::where('slug', $broker2_slug)->firstOrFail();
    
        return view('front.broker_comparison_result', compact('page_data', 'broker1', 'broker2', 'home_ad_data'));
    }


    

    public function getComparison(Request $request)
    {
        return redirect()->route('brokers.compare', [
            'broker1_slug' => $request->broker1_id,
            'broker2_slug' => $request->broker2_id
        ]);
    }

    public function updateVisitCount(Request $request)
    {
        $broker = Broker::findOrFail($request->broker_id);
        $broker->increment('visit_count', $request->visit_count);
        return response()->json(['success' => true]);
    }


    public function showBrokerComparison()
{
    // Fetch language settings
    Helpers::read_json(); 
    if (!session()->get('session_short_name')) {
        $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
    } else {
        $current_short_name = session()->get('session_short_name');
    }
    $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

    // Fetch page data for the current language
    $page_data = Page::where('language_id', $current_language_id)->first();
    $featured_brokers = Broker::where('featured_broker', 1)->latest() ->take(6)->get();
    $home_ad_data = HomeAdvertisement::where('id', 1)->first();

    // Fetch brokers for comparison
    $brokers = Broker::with('accountOptions')->get(); // Adjust relationships as needed

    return view('front.broker_comparison', compact('brokers', 'page_data','featured_brokers','home_ad_data'));
}


}