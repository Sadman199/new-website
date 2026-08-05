<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\Language;
use App\Models\TradingTool;
use App\Services\TradingCalculator;
use Illuminate\Http\Request;

class TradingToolsController extends Controller
{
    public function index()
    {
        Helpers::read_json();

        if (! session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        try {
            $tools = TradingTool::active()->get();
        } catch (\Exception $e) {
            $tools = collect($this->fallbackTools());
        }

        if ($tools->isEmpty()) {
            $tools = collect($this->fallbackTools());
        }

        $pairs = ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD', 'NZD/USD', 'EUR/GBP', 'USD/CHF', 'EUR/JPY', 'GBP/JPY'];
        $currencies = array_keys(TradingCalculator::defaultRates());
        $rates = TradingCalculator::defaultRates();

        return view('front.pages.trading_tools', compact(
            'current_short_name',
            'tools',
            'pairs',
            'currencies',
            'rates'
        ));
    }

    public function calculate(Request $request)
    {
        $tool = $request->input('tool', 'pip');
        $allowed = ['pip', 'position', 'profit', 'margin', 'risk', 'pivot', 'fibonacci', 'converter'];

        if (! in_array($tool, $allowed, true)) {
            return response()->json(['error' => 'Invalid tool'], 422);
        }

        $result = TradingCalculator::calculate($tool, $request->all());

        if (isset($result['error'])) {
            return response()->json($result, 422);
        }

        return response()->json([
            'ok' => true,
            'tool' => $tool,
            'result' => $result,
        ]);
    }

    private function fallbackTools(): array
    {
        return [
            (object) ['slug' => 'pip', 'name' => 'Pip Calculator', 'icon' => 'fas fa-exchange-alt', 'short_description' => 'Pip value and position notional'],
            (object) ['slug' => 'position', 'name' => 'Position Size', 'icon' => 'fas fa-layer-group', 'short_description' => 'Size lots from risk & stop loss'],
            (object) ['slug' => 'profit', 'name' => 'Profit / Loss', 'icon' => 'fas fa-chart-line', 'short_description' => 'Estimate trade P/L'],
            (object) ['slug' => 'margin', 'name' => 'Margin Calculator', 'icon' => 'fas fa-percentage', 'short_description' => 'Required margin by leverage'],
            (object) ['slug' => 'risk', 'name' => 'Risk Calculator', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Risk amount from balance'],
            (object) ['slug' => 'pivot', 'name' => 'Pivot Points', 'icon' => 'fas fa-crosshairs', 'short_description' => 'Support & resistance pivots'],
            (object) ['slug' => 'fibonacci', 'name' => 'Fibonacci', 'icon' => 'fas fa-wave-square', 'short_description' => 'Retracement levels'],
            (object) ['slug' => 'converter', 'name' => 'Currency Converter', 'icon' => 'fas fa-coins', 'short_description' => 'Convert major currencies'],
        ];
    }
}
