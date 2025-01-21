<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Models\Broker; 
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\AccountOption;
use App\Models\Language;
use App\Helper\Helpers;

use Illuminate\Http\Request;

class BrokerTypeController extends Controller
{
    public function showRegulatedBrokers()
    {

        Helpers::read_json(); 
        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }
            $current_language_id = Language::where('short_name', $current_short_name)->first()->id;
        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
        $regulatedBrokers = Broker::whereHas('accountOptions', function($query) {
            $query->where('is_regulated', true);
        })->get();

        return view('front.regulated', compact('regulatedBrokers','page_data','home_ad_data'));
    }

    public function showNonRegulatedBrokers()
    {
        Helpers::read_json(); 
            if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }
            $current_language_id = Language::where('short_name', $current_short_name)->first()->id;

        $page_data = Page::where('language_id', $current_language_id)->first();

        $nonRegulatedBrokers = Broker::whereHas('accountOptions', function($query) {
            $query->where('is_regulated', false);
        })->get();

        return view('front.nonregulated', compact('nonRegulatedBrokers','page_data'));
    }
}
