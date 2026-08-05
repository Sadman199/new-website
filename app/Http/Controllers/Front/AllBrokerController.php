<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Language;
use App\Models\Broker;
use App\Helper\Helpers;

class AllBrokerController extends Controller
{
    public function index(Request $request)
    {
        // Load helper JSON data (if any)
        Helpers::read_json();

        // Get the current language short name from session or default language
        $current_short_name = session()->get('session_short_name') 
            ?? Language::where('is_default', 'Yes')->value('short_name');

        // Get the language ID using the short name
        $current_language_id = Language::where('short_name', $current_short_name)->value('id');

        // Fetch the page data for the current language (e.g., Brokers page)
        $page_data = Page::where('language_id', $current_language_id)->first();

        // Start the broker query
        $all_brokers = Broker::query();

        // Apply filters if provided and valid
        if ($request->filled('minimum_deposit') && $request->minimum_deposit !== 'Any Amount') {
            $all_brokers->where('minimum_deposit', $request->minimum_deposit);
        }

        if ($request->filled('platform') && $request->platform !== 'All Platforms') {
            $all_brokers->where('platforms', 'LIKE', '%' . $request->platform . '%');
        }

        if ($request->filled('regulation') && $request->regulation !== 'All Regulators') {
            $all_brokers->where('regulation', 'LIKE', '%' . $request->regulation . '%');
        }

        $all_brokers = $all_brokers
            ->orderBy('id', 'desc')        // latest first
            ->orderBy('rating', 'desc')    // then rating
            ->paginate(20)
            ->withQueryString();


        // Prepare filter options for the view

        // Distinct minimum deposits available
        $min_deposits = Broker::select('minimum_deposit')->distinct()->orderBy('minimum_deposit')->get();

        // Platforms list (converted to objects manually)
        $platforms_list = [
            'MetaTrader 4', 'MetaTrader 5', 'cTrader',
            'WebTrader', 'xStation', 'ThinkorSwim',
            'TradingView', 'Interactive Brokers', 'SaxoTrader'
        ];

        $platforms = collect($platforms_list)->map(function ($platform) {
            return (object)['platforms' => $platform];
        });

        // Regulations list
        $reg_list = [
            'CySEC (Cyprus)', 'FCA (UK)', 'ASIC (Australia)', 'NFA/CFTC (USA)', 
            'FSCA (South Africa)', 'FSA (Seychelles)', 'BaFin (Germany)', 
            'MAS (Singapore)', 'JFSA (Japan)', 'FINMA (Switzerland)', 'IIROC (Canada)',
            'FSC (Mauritius)', 'CIMA (Cayman Islands)', 'SFC (Hong Kong)', 
            'CONSOB (Italy)', 'CNMV (Spain)',
        ];

        $regulations = collect($reg_list)->map(function ($regulation) {
            return (object)['regulation' => $regulation];
        });

        // If the request is AJAX, return only the brokers grid partial view
        if ($request->ajax()) {
            return view('front.partials.brokers_grid', compact('all_brokers'))->render();
        }

        // Return the main brokers page with all required data
        return view('front.brokers.all_brokers', compact(
            'page_data', 
            'all_brokers', 
            'min_deposits', 
            'platforms', 
            'regulations'
        ));
    }
}

