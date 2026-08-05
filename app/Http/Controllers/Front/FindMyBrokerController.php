<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\BrokerFilterService;
use Illuminate\Http\Request;

class FindMyBrokerController extends Controller
{
    public function index(Request $request, BrokerFilterService $filterService)
    {
        $data = $filterService->filter($request);

        if ($request->boolean('partial') || $request->ajax()) {
            return view('front.brokers.partials.find_my_broker_results', $data);
        }

        return view('front.brokers.find_my_broker', $data);
    }
}
