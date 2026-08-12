<?php

namespace App\Http\Controllers\Front;

class AboutController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        return view('front.pages.about');
    }
}
