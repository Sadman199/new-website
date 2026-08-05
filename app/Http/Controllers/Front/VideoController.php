<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Language;
use App\Models\HomeAdvertisement;
use App\Helper\Helpers;


class VideoController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        if(!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }    
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
        $videos = Video::where('language_id',$current_language_id)->paginate(8);
        return view('front.video_gallery', compact('videos','home_ad_data'));
    }
}
