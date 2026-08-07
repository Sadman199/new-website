<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\PromotionsIndexService;
use Illuminate\Http\Request;

class PromotionsController extends Controller
{
    public function index(PromotionsIndexService $promotionsIndexService, ?string $type = null)
    {
        return view('front.promotions.index', $promotionsIndexService->buildIndex($type));
    }

    public function loadMore(Request $request, PromotionsIndexService $promotionsIndexService)
    {
        $type = (string) $request->get('type', 'deposit-bonuses');
        $offset = max(0, (int) $request->get('offset', PromotionsIndexService::INITIAL_CARDS));

        $data = $promotionsIndexService->loadMore($type, $offset);

        if ($request->ajax() || $request->boolean('partial')) {
            return view('front.promotions.partials.promo_cards_batch', $data);
        }

        return redirect()->route(
            $type === 'deposit-bonuses' ? 'promotions.index' : 'promotions.tab',
            $type === 'deposit-bonuses' ? [] : ['type' => $type]
        );
    }
}
