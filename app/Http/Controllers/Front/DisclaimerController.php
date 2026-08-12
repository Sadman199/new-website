<?php

namespace App\Http\Controllers\Front;

class DisclaimerController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        return view('front.pages.disclaimer');
    }
}
