<?php

namespace App\Http\Controllers\Front;

use App\Models\Broker;
use App\Services\CountryBrokersService;
use App\Services\GlobalViewDataService;
use Illuminate\Support\Str;

class BrokerCountryController extends FrontController
{
    public function showBrokersByCountry(string $country)
    {
        $this->bootFront();

        $country = urldecode($country);
        $countryService = app(CountryBrokersService::class);
        $slug = Str::slug($country);
        $meta = $countryService->countryMeta($slug);

        $countryQuery = Broker::query()
            ->where('is_scam', false)
            ->whereNotNull('rating');

        if ($meta) {
            $countryQuery->whereRaw('LOWER(TRIM(country)) = ?', [Str::lower(trim($meta['db_name']))]);
        } else {
            $countryQuery->whereRaw('LOWER(TRIM(country)) = ?', [Str::lower(trim($country))]);
        }

        $paginatedBrokers = (clone $countryQuery)
            ->with('accountOptions')
            ->orderByDesc('rating')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $featured_brokers = Broker::query()
            ->where('featured_broker', 1)
            ->latest()
            ->take(6)
            ->get();

        $f_broker_country = (clone $countryQuery)
            ->where('featured_broker', true)
            ->orderByDesc('rating')
            ->take(5)
            ->get();

        $top_brokers = (clone $countryQuery)
            ->orderByDesc('rating')
            ->take(5)
            ->get();

        $metrics = [
            ['value' => $paginatedBrokers->total(), 'label' => 'Matching Brokers'],
            ['value' => $featured_brokers->count(), 'label' => 'Featured Brokers'],
            ['value' => $top_brokers->count(), 'label' => 'Top Rated Brokers'],
        ];

        $accountTypes = [
            'Standard Accounts',
            'Islamic Account',
            'ECN Accounts',
            'Classic Account',
            'Copy Trading Accounts',
            'VIP Accounts',
            'Raw Account',
            'Micro Accounts',
        ];

        $globals = app(GlobalViewDataService::class);

        return view('front.brokers.brokers_by_country', [
            'paginatedBrokers' => $paginatedBrokers,
            'country' => $country,
            'f_broker_country' => $f_broker_country,
            'top_brokers' => $top_brokers,
            'featured_brokers' => $featured_brokers,
            'metrics' => $metrics,
            'accountTypes' => $accountTypes,
            'recommended_brokers' => $this->recommendedBrokers(),
            'topRatedBrokers' => $globals->topRatedBrokers(),
        ]);
    }
}
