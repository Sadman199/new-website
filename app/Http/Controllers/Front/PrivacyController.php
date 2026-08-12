<?php

namespace App\Http\Controllers\Front;

class PrivacyController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        return view('front.pages.privacy');
    }
}
