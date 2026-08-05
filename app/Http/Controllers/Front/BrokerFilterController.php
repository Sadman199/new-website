<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\Broker;
use App\Models\AccountOption;


class BrokerFilterController extends Controller
{


public function filterByPlatform($slug)
{
    Helpers::read_json();

    $current_short_name = session()->get('session_short_name') 
        ?? optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';

    $language = Language::where('short_name', $current_short_name)->first();
    $current_language_id = $language ? $language->id : 1;

    $platformMap = [
        'mt4'       => 'MetaTrader 4',
        'mt5'       => 'MetaTrader 5',
        'ctrader'   => 'cTrader',
        'webtrader' => 'WebTrader',
    ];

    if (!array_key_exists($slug, $platformMap)) {
        abort(404);
    }

    $platform = $platformMap[$slug];

    // Use paginate() instead of get() for pagination, 10 items per page
    $brokers = Broker::where('platforms', 'like', "%$platform%")
        ->with('accountOptions')
        ->paginate(12)
        ->withQueryString();

    $page_data = Page::where('language_id', $current_language_id)->first();

    return view('front.brokers.platform_filtered', compact(
        'brokers',
        'platform',
        'page_data',
        'current_short_name'
    ));
}




public function filterByRegulation($slug)
{
    // Step 1: Language & Session Setup
    Helpers::read_json();

    if (!session()->get('session_short_name')) {
        $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
    } else {
        $current_short_name = session()->get('session_short_name');
    }

    $language = Language::where('short_name', $current_short_name)->first();
    $current_language_id = $language ? $language->id : 1;

    // Step 2: Regulator Slug Mapping
    $regulationMap = [
        'cysec' => 'CySEC',
        'fca'   => 'FCA',
        'asic'  => 'ASIC',
        'fsca'  => 'FSCA',
        'fsa'   => 'FSA',
        'bafin' => 'BaFin',
    ];

    if (!array_key_exists($slug, $regulationMap)) {
        abort(404);
    }

    $regulator = $regulationMap[$slug];

    // Step 3: Broker Filtering by Regulator (assuming 'regulation' in accountOptions)
    $brokers = Broker::whereHas('accountOptions', function ($query) use ($regulator) {
        $query->where('regulation', 'like', "%$regulator%");
    })->with('accountOptions')->paginate(12)->withQueryString();

    // Step 4: Page content (optional)
     $page_data = Page::where('language_id',$current_language_id)->first();


    // Step 5: Return view
    return view('front.brokers.regulation_filtered', compact(
        'brokers',
        'regulator',
        'page_data',
        'current_short_name'
    ));
}

public function highLeverageBrokers()
{
    Helpers::read_json();

    $current_short_name = session()->get('session_short_name')
        ?? optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';

    $language = Language::where('short_name', $current_short_name)->first();
    $current_language_id = $language ? $language->id : 1;

    $page_data = Page::where('language_id', $current_language_id)->first();

    // Query brokers with accountOptions having max_leverage, order by max_leverage desc (in DB), paginate 10 per page
    $brokers = Broker::whereHas('accountOptions', function ($query) {
        $query->whereNotNull('max_leverage');
    })
    ->with(['accountOptions' => function ($q) {
        $q->orderBy('max_leverage', 'desc');
    }])
    // Order brokers by max leverage of their first account option (using a join or subquery)
    ->whereHas('accountOptions', function ($q) {
        $q->whereNotNull('max_leverage');
    })
    ->with('accountOptions')
    ->orderByDesc(
        // Use subquery to get max_leverage from related accountOptions per broker
        AccountOption::select('max_leverage')
            ->whereColumn('account_options.broker_id', 'brokers.id')
            ->orderByDesc('max_leverage')
            ->limit(1)
    )
    ->paginate(10)
    ->withQueryString();

    return view('front.brokers.high_leverage', compact('brokers', 'page_data', 'current_short_name'));
}

}
