<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\Language;
use App\Models\TradingTool;
use App\Services\TradingCalculator;
use App\Support\TradingToolsRegistry;
use Illuminate\Http\Request;

class TradingToolsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('tool')) {
            $routeSlug = TradingToolsRegistry::routeSlug((string) $request->query('tool'));

            if ($routeSlug) {
                return redirect()->route('trading.tools.show', ['slug' => $routeSlug], 301);
            }
        }

        Helpers::read_json();
        $current_short_name = $this->currentShortName();
        $tools = $this->resolveTools();

        return view('front.trading-tools.index', compact('current_short_name', 'tools'));
    }

    public function show(string $slug)
    {
        Helpers::read_json();

        $toolKey = TradingToolsRegistry::toolKey($slug);
        abort_if(! $toolKey, 404);

        $current_short_name = $this->currentShortName();
        $tools = $this->resolveTools();
        $tool = $tools->firstWhere('slug', $toolKey);
        abort_if(! $tool, 404);

        $meta = TradingToolsRegistry::meta($toolKey);

        if (TradingToolsRegistry::isWidget($toolKey)) {
            return view('front.trading-tools.show-live-markets', compact(
                'current_short_name',
                'tools',
                'tool',
                'toolKey',
                'meta',
                'slug'
            ));
        }

        $pairs = ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD', 'NZD/USD', 'EUR/GBP', 'USD/CHF', 'EUR/JPY', 'GBP/JPY'];
        $currencies = array_keys(TradingCalculator::defaultRates());
        $rates = TradingCalculator::defaultRates();

        return view('front.trading-tools.show', compact(
            'current_short_name',
            'tools',
            'tool',
            'toolKey',
            'meta',
            'pairs',
            'currencies',
            'rates',
            'slug'
        ));
    }

    public function calculate(Request $request)
    {
        $tool = $request->input('tool', 'pip');

        if (! in_array($tool, TradingToolsRegistry::allowedToolKeys(), true)) {
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

    private function currentShortName(): string
    {
        if (! session()->get('session_short_name')) {
            return optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        }

        return session()->get('session_short_name');
    }

    /** @return \Illuminate\Support\Collection<int, object> */
    private function resolveTools()
    {
        try {
            $tools = TradingTool::active()->get();
        } catch (\Exception $e) {
            $tools = collect();
        }

        if ($tools->isEmpty()) {
            $tools = collect($this->fallbackTools());
        }

        return $tools->map(function ($tool) {
            $routeSlug = TradingToolsRegistry::routeSlug($tool->slug);
            $registry = TradingToolsRegistry::meta($tool->slug);

            $tool->route_slug = $routeSlug;
            $tool->page_title = $registry['title'] ?? $tool->name;
            $tool->page_meta = $registry['meta'] ?? ($tool->short_description ?? '');
            $tool->page_about = $registry['about'] ?? ($tool->description ?? $tool->short_description ?? '');

            if ($registry && ! empty($registry['icon']) && empty($tool->icon)) {
                $tool->icon = $registry['icon'];
            }

            return $tool;
        })->filter(fn ($tool) => $tool->route_slug)->values()
            ->pipe(fn ($collection) => $this->appendMissingRegistryTools($collection));
    }

    /** @param \Illuminate\Support\Collection<int, object> $tools */
    private function appendMissingRegistryTools($tools)
    {
        $existing = $tools->pluck('slug')->all();

        foreach (TradingToolsRegistry::allToolKeys() as $key) {
            if (in_array($key, $existing, true)) {
                continue;
            }

            $registry = TradingToolsRegistry::meta($key);
            if (! $registry) {
                continue;
            }

            $tools->push((object) [
                'slug' => $key,
                'name' => $registry['title'],
                'icon' => $registry['icon'] ?? 'fas fa-calculator',
                'short_description' => $registry['about'],
                'description' => $registry['about'],
                'route_slug' => $registry['slug'],
                'page_title' => $registry['title'],
                'page_meta' => $registry['meta'],
                'page_about' => $registry['about'],
            ]);
        }

        return $tools->values();
    }

    /** @return array<int, object> */
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
            (object) ['slug' => 'live-markets', 'name' => 'Live Market Widgets', 'icon' => 'fas fa-chart-area', 'short_description' => 'Live FX rates, heatmap & calendar'],
        ];
    }
}
