<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\PropFirmIndexService;
use Illuminate\Http\Request;

class PropFirmController extends Controller
{
    public function index(Request $request, PropFirmIndexService $indexService)
    {
        return view('front.prop-firms.index', $indexService->buildIndex($request));
    }

    public function category(Request $request, PropFirmIndexService $indexService, string $slug)
    {
        $data = $indexService->buildIndex($request, $slug);

        if (! $data['activeCategory']) {
            abort(404);
        }

        return view('front.prop-firms.index', $data);
    }

    public function show(PropFirmIndexService $indexService, string $slug)
    {
        return view('front.prop-firms.show', $indexService->buildDetail($slug));
    }
}
