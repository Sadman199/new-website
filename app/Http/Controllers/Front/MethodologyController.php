<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;

class MethodologyController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        return view('front.pages.methodology');
    }
}
