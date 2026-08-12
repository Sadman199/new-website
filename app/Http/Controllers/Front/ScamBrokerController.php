<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Services\ScamBrokerDetailService;
use Illuminate\Support\Str;

class ScamBrokerController extends Controller
{
    public function index(\App\Services\ScamBrokersIndexService $indexService)
    {
        $brokersPayload = \Illuminate\Support\Facades\Cache::remember('scam_brokers_index_v1', 1800, function () use ($indexService) {
            return Broker::query()
                ->where('is_scam', true)
                ->orderByDesc('scam_reported_date')
                ->orderBy('name')
                ->get()
                ->map(fn (Broker $broker) => $indexService->serialize($broker))
                ->values();
        });

        return view('front.scam-brokers', [
            'brokersPayload' => $brokersPayload,
            'scamCount' => $brokersPayload->count(),
            'page_data' => null,
            'home_ad_data' => null,
            'warningFilters' => $indexService->warningFilters(),
            'warningSigns' => $indexService->warningSigns(),
            'stats' => $indexService->stats(),
            'warningCounts' => $indexService->warningFilterCounts($brokersPayload),
        ]);
    }

    public function show(string $slug, ScamBrokerDetailService $detailService)
    {
        $slug = Str::slug($slug);

        $broker = Broker::query()
            ->where('is_scam', true)
            ->where(function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [$slug]);
            })
            ->first();

        abort_if(! $broker, 404);

        $detail = $detailService->build($broker);
        $related = $detailService->relatedBrokers($broker);
        $scamCount = $detailService->scamCount();
        $warningFilters = app(\App\Services\ScamBrokersIndexService::class)->warningFilters();

        return view('front.scam-brokers.show', compact(
            'detail',
            'related',
            'scamCount',
            'warningFilters',
        ));
    }
}
