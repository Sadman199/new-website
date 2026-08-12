<?php

namespace App\Http\Controllers\Front;

class TermsController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        return view('front.pages.terms');
    }
}
