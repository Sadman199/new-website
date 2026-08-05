<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Language;
use App\Helper\Helpers;

class PhotoController extends Controller
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

        $photos = Photo::where('language_id',$current_language_id)->paginate(8);
        return view('front.photo_gallery', compact('photos'));
    }
}
