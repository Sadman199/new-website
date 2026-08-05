<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Language;
use App\Helper\Helpers;
use App\Services\RegulatedBrokersIndexService;

class BrokerTypeController extends Controller
{
    public function showRegulatedBrokers(RegulatedBrokersIndexService $indexService)
    {
        Helpers::read_json();

        if (! session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        $brokers = $indexService->brokers();
        $brokersPayload = $brokers
            ->map(fn ($broker) => $indexService->serialize($broker))
            ->values();
        $regulatorFilters = $indexService->regulatorFilters($brokers);
        $tierFilters = $indexService->tierFilters();
        $stats = $indexService->stats($brokers);
        $trustHighlights = $indexService->trustHighlights($brokers, $stats);

        return view('front.brokers.regulated', compact(
            'brokersPayload',
            'regulatorFilters',
            'tierFilters',
            'stats',
            'trustHighlights',
            'page_data',
            'home_ad_data'
        ));
    }

    public function showNonRegulatedBrokers()
    {
        Helpers::read_json();
        if (! session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        $page_data = Page::where('language_id', $current_language_id)->first();

        $nonRegulatedBrokers = Broker::where(function ($query) {
            $query->where(function ($inner) {
                $inner->whereNull('regulation')
                    ->orWhere('regulation', '[]')
                    ->orWhere('regulation', '');
            })->where('investor_protection', false);
        })->get();

        return view('front.nonregulated', compact('nonRegulatedBrokers', 'page_data'));
    }
}
