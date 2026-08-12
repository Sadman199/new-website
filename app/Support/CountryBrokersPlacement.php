<?php

namespace App\Support;

use Illuminate\Http\Request;

class CountryBrokersPlacement
{
    /**
     * Routes where a country-specific broker strip adds conversion value
     * (discovery, comparison, promotions — not legal, auth, or editorial content).
     *
     * @var array<int, string>
     */
    private const STRIP_ROUTES = [
        'find_my_broker',
        'all_brokers',
        'broker.reviews.index',
        'broker_detail',
        'brokers.best.index',
        'brokers.best',
        'regulated_brokers',
        'non_regulated_brokers',
        'broker_by_country',
        'brokers.by.regulation',
        'brokers.search',
        'broker.scam_checker',
        'broker.scam_checker.show',
        'scam_brokers',
        'scam_broker_detail',
        'promotions.index',
        'promotions.tab',
        'deposit-bonuses.detail',
        'no-deposit-bonuses.detail',
        'live-contests.detail',
        'demo-contests.detail',
        'cashback-rebates.detail',
        'crypto-bonuses.detail',
        'awards.index',
        'prop_firms.index',
        'prop_firms.category',
        'prop_firms.show',
    ];

    public static function shouldShowStrip(?Request $request = null): bool
    {
        $request = $request ?? request();

        if (! $request->route()) {
            return false;
        }

        if ($request->routeIs('home')) {
            return false;
        }

        $slug = BrokerTaxonomy::resolvePreferredCountry()['slug'] ?? 'global';

        if ($slug === 'global') {
            return false;
        }

        return $request->routeIs(self::STRIP_ROUTES);
    }
}
