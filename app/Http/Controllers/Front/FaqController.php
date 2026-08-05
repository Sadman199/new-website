<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Language;
use App\Helper\Helpers;

class FaqController extends Controller
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
        $faq_data = Faq::where('language_id',$current_language_id)->get();
        return view('front.faq', compact('page_data', 'faq_data'));
    }
}
