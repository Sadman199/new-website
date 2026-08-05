<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\AuthorsIndexService;

class AuthorsController extends Controller
{
    public function index(AuthorsIndexService $authorsIndexService)
    {
        return view('front.authors.index', $authorsIndexService->buildIndex());
    }
}
