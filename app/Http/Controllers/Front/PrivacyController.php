<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\ForexBonus;
use App\Models\Language;
use App\Helper\Helpers;

class PrivacyController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        if(!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default','Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }    
        $current_language_id = Language::where('short_name',$current_short_name)->first()->id;

        $demoContest = ForexBonus::where('promo_type', 'Forex Demo Contest')->latest()->take(6)->get();
        $liveContest = ForexBonus::where('promo_type', 'Forex Live Contest')->latest()->take(6)->get();
        $forexCashbackRebate = ForexBonus::where('promo_type', 'Forex Cashback Rebate')->latest()->take(6)->get();
        $cryptoBonusPromotion = ForexBonus::where('promo_type', 'Crypto Bonus Promotion')->latest()->take(6)->get();
        $page_data = Page::where('language_id',$current_language_id)->first();
        return view('front.privacy', compact('page_data','demoContest','liveContest','forexCashbackRebate','cryptoBonusPromotion'));
    }
}
