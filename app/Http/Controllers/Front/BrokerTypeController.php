<?php

namespace App\Http\Controllers\Front;

use App\Models\Broker;
use App\Services\RegulatedBrokersIndexService;

class BrokerTypeController extends FrontController
{
    public function showRegulatedBrokers(RegulatedBrokersIndexService $indexService)
    {
        $this->bootFront();

        $brokers = $indexService->brokers();
        $brokersPayload = $brokers
            ->map(fn ($broker) => $indexService->serialize($broker))
            ->values();
        $stats = $indexService->stats($brokers);

        return view('front.brokers.regulated', [
            'brokersPayload' => $brokersPayload,
            'regulatorFilters' => $indexService->regulatorFilters($brokers),
            'tierFilters' => $indexService->tierFilters(),
            'stats' => $stats,
            'trustHighlights' => $indexService->trustHighlights($brokers, $stats),
        ]);
    }

    public function showNonRegulatedBrokers()
    {
        $this->bootFront();

        $nonRegulatedBrokers = Broker::query()
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNull('regulation')
                        ->orWhere('regulation', '[]')
                        ->orWhere('regulation', '');
                })->where('investor_protection', false);
            })
            ->orderByDesc('rating')
            ->get();

        return view('front.pages.nonregulated', compact('nonRegulatedBrokers'));
    }
}
