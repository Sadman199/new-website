<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Helper\Helpers;

class ForexCalculatorController extends Controller
{
    // Show the forex calculator form
    public function index()
    {
        Helpers::read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = Language::where('is_default', 'Yes')->first()->short_name;
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        // Just passing the current language short name for possible use in view
        return view('front.pages.forex_calculator', compact('current_short_name'));
    }


}
