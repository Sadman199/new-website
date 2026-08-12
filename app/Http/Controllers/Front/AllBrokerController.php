<?php

namespace App\Http\Controllers\Front;

use App\Models\Broker;
use Illuminate\Http\Request;

class AllBrokerController extends FrontController
{
    public function index(Request $request)
    {
        $this->bootFront();

        $all_brokers = $this->brokerQuery($request)
            ->orderByDesc('id')
            ->orderByDesc('rating')
            ->paginate(20)
            ->withQueryString();

        $payload = $this->filterOptions($all_brokers);

        if ($request->ajax()) {
            return view('front.partials.brokers_grid', compact('all_brokers'))->render();
        }

        return view('front.brokers.all_brokers', $payload);
    }

    public function filterBrokers(Request $request)
    {
        return $this->index($request);
    }

    private function brokerQuery(Request $request)
    {
        $query = Broker::query();

        if ($request->filled('minimum_deposit') && $request->minimum_deposit !== 'Any Amount') {
            $query->where('minimum_deposit', $request->minimum_deposit);
        }

        if ($request->filled('platform') && $request->platform !== 'All Platforms') {
            $query->where('platforms', 'LIKE', '%' . $request->platform . '%');
        }

        if ($request->filled('regulation') && $request->regulation !== 'All Regulators') {
            $query->where('regulation', 'LIKE', '%' . $request->regulation . '%');
        }

        return $query;
    }

    /** @return array<string, mixed> */
    private function filterOptions($all_brokers): array
    {
        $platforms_list = [
            'MetaTrader 4', 'MetaTrader 5', 'cTrader',
            'WebTrader', 'xStation', 'ThinkorSwim',
            'TradingView', 'Interactive Brokers', 'SaxoTrader',
        ];

        $reg_list = [
            'CySEC (Cyprus)', 'FCA (UK)', 'ASIC (Australia)', 'NFA/CFTC (USA)',
            'FSCA (South Africa)', 'FSA (Seychelles)', 'BaFin (Germany)',
            'MAS (Singapore)', 'JFSA (Japan)', 'FINMA (Switzerland)', 'IIROC (Canada)',
            'FSC (Mauritius)', 'CIMA (Cayman Islands)', 'SFC (Hong Kong)',
            'CONSOB (Italy)', 'CNMV (Spain)',
        ];

        return [
            'all_brokers' => $all_brokers,
            'min_deposits' => Broker::query()->select('minimum_deposit')->distinct()->orderBy('minimum_deposit')->get(),
            'platforms' => collect($platforms_list)->map(fn ($platform) => (object) ['platforms' => $platform]),
            'regulations' => collect($reg_list)->map(fn ($regulation) => (object) ['regulation' => $regulation]),
        ];
    }
}
