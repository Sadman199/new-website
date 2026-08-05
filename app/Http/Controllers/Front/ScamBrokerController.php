<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Language;
use App\Helper\Helpers;
use App\Services\ScamBrokersIndexService;

class ScamBrokerController extends Controller
{
    public function index(ScamBrokersIndexService $indexService)
    {
        Helpers::read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        $brokers = Broker::query()
            ->where('is_scam', true)
            ->orderByDesc('scam_reported_date')
            ->orderBy('name')
            ->get();

        $brokersPayload = $brokers
            ->map(fn (Broker $broker) => $indexService->serialize($broker))
            ->values();

        $scamCount = Broker::where('is_scam', true)->count();
        $warningFilters = $indexService->warningFilters();
        $warningSigns = $indexService->warningSigns();

        return view('front.scam-brokers', compact(
            'brokersPayload',
            'scamCount',
            'page_data',
            'home_ad_data',
            'warningFilters',
            'warningSigns'
        ));
    }

    public function show($slug)
    {
        Helpers::read_json();

        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;

        $page_data = Page::where('language_id', $current_language_id)->first();
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();

        $slug = \Illuminate\Support\Str::slug($slug);
        $broker = Broker::where('is_scam', true)->get()
            ->first(fn ($b) => $b->scam_slug === $slug || \Illuminate\Support\Str::slug((string) $b->slug) === $slug);
        abort_if(!$broker, 404);

        $relatedScam = Broker::where('is_scam', true)
            ->where('id', '!=', $broker->id)
            ->orderByDesc('scam_reported_date')
            ->take(3)
            ->get();

        $scamCount = Broker::where('is_scam', true)->count();

        return view('front.scam-broker-detail', compact('broker', 'relatedScam', 'scamCount', 'page_data', 'home_ad_data'));
    }
}
