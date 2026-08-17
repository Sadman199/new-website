<?php

namespace App\Http\Controllers\Front;

use App\Models\Broker;
use App\Services\BrokerBattleService;
use App\Services\BrokerComparisonService;
use App\Services\FooterIndexService;
use Illuminate\Http\Request;

class BrokerComparisonController extends FrontController
{
    public function __construct(
        protected BrokerComparisonService $comparisonService,
        protected BrokerBattleService $battleService,
    ) {
    }

    public function compare(string $broker1_slug, string $broker2_slug)
    {
        $this->bootFront();

        $canonical = BrokerComparisonService::canonicalPairUrl($broker1_slug, $broker2_slug);
        if ($canonical !== route('brokers.compare', [
            'broker1_slug' => $broker1_slug,
            'broker2_slug' => $broker2_slug,
        ])) {
            return redirect()->to($canonical, 301);
        }

        $broker1 = Broker::query()->where('slug', $broker1_slug)->firstOrFail();
        $broker2 = Broker::query()->where('slug', $broker2_slug)->firstOrFail();

        $comparison = $this->comparisonService->buildPairComparison($broker1, $broker2);
        $shareUrl = BrokerComparisonService::canonicalPairUrl($broker1_slug, $broker2_slug);

        return view('front.comparison.broker_comparison_result', [
            'broker1' => $broker1,
            'broker2' => $broker2,
            'comparison' => $comparison,
            'shareUrl' => $shareUrl,
            'popularComparisons' => app(FooterIndexService::class)->popularComparisons(),
            'comparisonJsonLd' => \App\Support\BrokerComparisonJsonLd::graph($broker1, $broker2, $comparison, $shareUrl),
        ]);
    }

    public function battle(string $broker1_slug, string $broker2_slug)
    {
        $this->bootFront();

        abort_if(
            strcasecmp($broker1_slug, $broker2_slug) === 0,
            404,
            'Choose two different brokers for a battle.'
        );

        $canonical = BrokerBattleService::canonicalBattleUrl($broker1_slug, $broker2_slug);
        if ($canonical !== route('brokers.battle', [
            'broker1_slug' => $broker1_slug,
            'broker2_slug' => $broker2_slug,
        ])) {
            return redirect()->to($canonical, 301);
        }

        $broker1 = Broker::query()->where('slug', $broker1_slug)->where('is_scam', false)->firstOrFail();
        $broker2 = Broker::query()->where('slug', $broker2_slug)->where('is_scam', false)->firstOrFail();

        $comparison = $this->comparisonService->buildPairComparison($broker1, $broker2);
        $battle = $this->battleService->buildBattle($broker1, $broker2, $comparison);
        $shareUrl = BrokerBattleService::canonicalBattleUrl($broker1_slug, $broker2_slug);

        $catalog = $this->comparisonService->allBrokersForCompare()
            ->map(fn (Broker $broker) => $this->comparisonService->serializeBroker($broker))
            ->values();

        return view('front.comparison.broker_battle', [
            'broker1' => $broker1,
            'broker2' => $broker2,
            'comparison' => $comparison,
            'battle' => $battle,
            'shareUrl' => $shareUrl,
            'brokersPayload' => $catalog,
            'popularComparisons' => app(FooterIndexService::class)->popularComparisons(),
            'comparisonJsonLd' => \App\Support\BrokerComparisonJsonLd::graph(
                $broker1,
                $broker2,
                $comparison,
                $shareUrl,
                'battle'
            ),
        ]);
    }

    public function getComparison(Request $request)
    {
        $slug1 = $request->broker1_id;
        $slug2 = $request->broker2_id;

        Broker::query()->where('slug', $slug1)->firstOrFail();
        Broker::query()->where('slug', $slug2)->firstOrFail();

        return redirect()->to(BrokerComparisonService::canonicalPairUrl($slug1, $slug2));
    }

    public function showBrokerComparison()
    {
        $this->bootFront();

        $allBrokers = $this->comparisonService->allBrokersForCompare();

        return view('front.comparison.broker_comparison', [
            'brokersPayload' => $allBrokers
                ->map(fn (Broker $broker) => $this->comparisonService->serializeBroker($broker))
                ->values(),
            'suggestedBrokers' => $this->comparisonService->suggestedBrokers(6),
            'tabGroups' => $this->comparisonService->tabGroups(),
            'popularComparisons' => app(FooterIndexService::class)->popularComparisons(),
        ]);
    }
}
