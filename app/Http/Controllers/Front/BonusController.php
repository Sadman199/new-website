<?php

namespace App\Http\Controllers\Front;

use App\Models\ForexBonus;
use App\Services\BonusDetailService;
use App\Services\EditorialAssignmentService;
use Illuminate\Support\Str;

class BonusController extends FrontController
{
    private const PROMO_TYPES = [
        'deposit-bonuses' => 'Forex Deposit Bonus',
        'no-deposit-bonuses' => 'Forex No Deposit Bonus',
        'live-contests' => 'Forex Live Contest',
        'demo-contests' => 'Forex Demo Contest',
        'cashback-rebates' => 'Forex Cashback Rebate',
        'crypto-bonuses' => 'Crypto Bonus Promotion',
    ];

    public function showBonusByType(string $type)
    {
        if (! array_key_exists($type, self::PROMO_TYPES)) {
            abort(404);
        }

        return redirect()->route('promotions.tab', ['type' => $type], 301);
    }

    public function bonusDetail(string $slug, BonusDetailService $bonusDetailService)
    {
        $this->bootFront();

        $routeName = request()->route()?->getName();
        $bonus = ForexBonus::findForDetailRoute((string) $routeName, $slug);

        if ($bonus->detailSlug() !== Str::slug($slug)) {
            $detailRoute = $bonus->detailRouteName();

            if ($detailRoute) {
                return redirect()->route($detailRoute, $bonus->detailSlug(), 301);
            }
        }

        $editorialCredits = EditorialAssignmentService::creditsFor($bonus);

        return view('front.bonuses.bonus_detail', [
            'bonus' => $bonus,
            'detail' => $bonusDetailService->build($bonus, $editorialCredits),
            'promoJsonLd' => \App\Support\PromoJsonLd::detailGraph($bonus),
        ]);
    }
}
