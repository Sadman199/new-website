<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\PromotionsGuideService;
use App\Services\PromotionsIndexService;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index(
        PromotionsIndexService $promotionsIndexService,
        PromotionsGuideService $promotionsGuideService,
        Request $request,
        ?string $type = null,
    ) {
        $sort = $request->query('sort');
        $featuredOnly = $request->boolean('featured');
        $search = $request->query('q');

        $data = $promotionsIndexService->buildIndex($type, $sort, $featuredOnly, $search);
        $data['guide'] = $promotionsGuideService->build(
            $data['tabs'],
            $data['stats'],
            $data['catalog'],
        );
        unset($data['catalog']);

        $canonical = ($data['activeTab'] ?? 'all') === 'all'
            ? route('promotions.index')
            : route('promotions.tab', ['type' => $data['activeTab']]);
        $title = 'Broker Promos — Bonuses, Contests & Cashback';
        $data['promoJsonLd'] = \App\Support\PromoJsonLd::indexGraph(
            $canonical,
            $title,
            $data['cards'] ?? [],
            $data['guide']['faqs'] ?? [],
        );

        return view('front.promotions.index', $data);
    }

    public function loadMore(Request $request, PromotionsIndexService $promotionsIndexService)
    {
        $type = (string) $request->get('type', PromotionsIndexService::TAB_ALL);
        $offset = max(0, (int) $request->get('offset', PromotionsIndexService::INITIAL_CARDS));
        $sort = $request->query('sort');
        $featuredOnly = $request->boolean('featured');
        $search = $request->query('q');

        $data = $promotionsIndexService->loadMore($type, $offset, $sort, $featuredOnly, $search);

        if ($request->ajax() || $request->boolean('partial')) {
            return view('front.promotions.partials.promo_cards_batch', $data);
        }

        $activeType = $promotionsIndexService->resolveTabSlug($type);
        $params = $promotionsIndexService->buildFilterQuery(
            $promotionsIndexService->resolveSort($sort),
            $featuredOnly,
            $search !== null ? trim($search) : null,
        );

        if ($activeType === PromotionsIndexService::TAB_ALL) {
            return redirect()->route('promotions.index', $params);
        }

        return redirect()->route('promotions.tab', array_merge(['type' => $activeType], $params));
    }
}
