<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\PromotionsIndexService;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index(
        PromotionsIndexService $promotionsIndexService,
        Request $request,
        ?string $type = null,
    ) {
        $filters = $this->filtersFromRequest($request);

        $data = $promotionsIndexService->buildIndex(
            $type,
            $request->query('sort'),
            $request->boolean('featured'),
            $request->query('q'),
            $filters,
        );
        unset($data['catalog']);

        $canonical = ($data['activeTab'] ?? 'all') === 'all'
            ? route('promotions.index')
            : route('promotions.tab', ['type' => $data['activeTab']]);
        $title = 'Forex Broker Bonuses & Promotions | BrokersCourt';
        $data['promoJsonLd'] = \App\Support\PromoJsonLd::indexGraph(
            $canonical,
            $title,
            $data['cards'] ?? [],
        );

        return view('front.promotions.index', $data);
    }

    public function loadMore(Request $request, PromotionsIndexService $promotionsIndexService)
    {
        $type = (string) $request->get('type', PromotionsIndexService::TAB_ALL);
        $offset = max(0, (int) $request->get('offset', PromotionsIndexService::INITIAL_CARDS));
        $filters = $this->filtersFromRequest($request);

        $data = $promotionsIndexService->loadMore(
            $type,
            $offset,
            $request->query('sort'),
            $request->boolean('featured'),
            $request->query('q'),
            $filters,
        );

        if ($request->ajax() || $request->boolean('partial')) {
            return view('front.promotions.partials.promo_cards_batch', $data);
        }

        $activeType = $promotionsIndexService->resolveTabSlug($type);
        $params = $promotionsIndexService->buildFilterQuery(
            $promotionsIndexService->resolveSort($request->query('sort')),
            $request->boolean('featured'),
            $request->query('q') !== null ? trim((string) $request->query('q')) : null,
            $filters,
        );

        if ($activeType === PromotionsIndexService::TAB_ALL) {
            return redirect()->route('promotions.index', $params);
        }

        return redirect()->route('promotions.tab', array_merge(['type' => $activeType], $params));
    }

    /** @return array<string, mixed> */
    private function filtersFromRequest(Request $request): array
    {
        return [
            'broker' => $request->query('broker'),
            'category' => $request->query('category'),
            'status' => $request->query('status'),
            'max_min_deposit' => $request->query('max_min_deposit'),
        ];
    }
}
