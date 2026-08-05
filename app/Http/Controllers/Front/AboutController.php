<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Language;
use App\Helper\Helpers;

class AboutController extends Controller
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
        
        $page_data = Page::where('language_id',$current_language_id)->first();
        return view('front.pages.about', compact('page_data'));
    }
}
