<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\Language;
use App\Services\TradingCalculator;
use App\Services\TradingToolsPageService;
use App\Support\TradingToolsRegistry;
use Illuminate\Http\Request;

class TradingToolsController extends Controller
{
    public function __construct(private TradingToolsPageService $tools)
    {
    }

    public function calculatorsIndex(Request $request)
    {
        if ($request->filled('tool')) {
            $routeSlug = TradingToolsRegistry::routeSlug((string) $request->query('tool'));

            if ($routeSlug) {
                return redirect()->route('calculators.show', ['slug' => $routeSlug], 301);
            }
        }

        Helpers::read_json();
        $current_short_name = $this->currentShortName();
        $toolGroups = $this->tools->groupedForHub();
        $calculators = $this->tools->resolveCalculators();
        $widgetTool = $this->tools->resolveTools()->firstWhere('slug', 'live-markets');
        $hubJsonLd = $this->tools->hubJsonLd($toolGroups);

        return view('front.calculators.index', compact(
            'current_short_name',
            'toolGroups',
            'calculators',
            'widgetTool',
            'hubJsonLd'
        ));
    }

    public function index(Request $request)
    {
        return redirect()->route('calculators.index', $request->query(), 301);
    }

    public function show(string $slug)
    {
        Helpers::read_json();

        $toolKey = TradingToolsRegistry::toolKey($slug);
        abort_if(! $toolKey, 404);

        $current_short_name = $this->currentShortName();
        $allTools = $this->tools->resolveTools();
        $calculators = $this->tools->resolveCalculators();
        $tool = $allTools->firstWhere('slug', $toolKey);
        abort_if(! $tool, 404);
        $calculator = $tool;

        $meta = TradingToolsRegistry::meta($toolKey);
        $pageContent = $this->tools->pageContent($tool);
        $faqs = $this->tools->faqs($tool);
        $relatedTools = $this->tools->relatedTools($tool, $allTools);
        $relatedBrokers = $this->tools->relatedBrokers($tool);
        $brokerCostCards = $this->tools->brokerCostCards($relatedBrokers);
        $showBrokerCosts = TradingToolsRegistry::showsBrokerCosts($toolKey) && $brokerCostCards !== [];
        $seo = $this->tools->seo($tool, $slug);
        $jsonLd = $this->tools->jsonLd($tool, $faqs, $seo['canonical'], $pageContent);

        $shared = [
            'current_short_name' => $current_short_name,
            'tools' => $allTools,
            'calculators' => $calculators,
            'tool' => $tool,
            'calculator' => $calculator,
            'toolKey' => $toolKey,
            'meta' => $meta,
            'slug' => $slug,
            'pageContent' => $pageContent,
            'faqs' => $faqs,
            'relatedTools' => $relatedTools,
            'relatedBrokers' => $relatedBrokers,
            'brokerCostCards' => $brokerCostCards,
            'showBrokerCosts' => $showBrokerCosts,
            'seo' => $seo,
            'jsonLd' => $jsonLd,
        ];

        if (TradingToolsRegistry::isWidget($toolKey)) {
            return view('front.trading-tools.show-live-markets', $shared);
        }

        $shared['pairs'] = ['EUR/USD', 'GBP/USD', 'USD/JPY', 'AUD/USD', 'USD/CAD', 'NZD/USD', 'EUR/GBP', 'USD/CHF', 'EUR/JPY', 'GBP/JPY'];
        $shared['currencies'] = array_keys(TradingCalculator::defaultRates());
        $shared['rates'] = TradingCalculator::defaultRates();
        $shared['costBrokerHints'] = $toolKey === 'cost' ? $this->tools->costBrokerHints() : [];
        $shared['costBrokerSearchUrl'] = $toolKey === 'cost' ? route('calculators.broker_search') : null;

        return view('front.calculators.show', $shared);
    }

    public function searchCostBrokers(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) > 80) {
            $query = mb_substr($query, 0, 80);
        }

        return response()->json([
            'brokers' => $this->tools->searchCostBrokerHints($query),
        ]);
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
}
