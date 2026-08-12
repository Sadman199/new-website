<?php

namespace App\Http\Controllers\Front;

class MethodologyController extends FrontController
{
    public function index()
    {
        $this->bootFront();

        return view('front.pages.methodology');
    }
}
