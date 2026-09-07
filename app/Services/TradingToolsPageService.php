<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\TradingTool;
use App\Support\BrokerCardData;
use App\Support\BrokerFacts;
use App\Support\RichText;
use App\Support\TradingToolCategories;
use App\Support\TradingToolCopy;
use App\Support\TradingToolsRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TradingToolsPageService
{
    /** @return Collection<int, object> */
    public function resolveTools(): Collection
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
            return $this->enrichTool($tool);
        })->filter(fn ($tool) => $tool->route_slug)->values()
            ->pipe(fn ($collection) => $this->appendMissingRegistryTools($collection));
    }

    /** @return Collection<int, object> */
    public function resolveCalculators(): Collection
    {
        return $this->resolveTools()
            ->filter(fn ($tool) => ! TradingToolsRegistry::isWidget($tool->slug))
            ->values();
    }

    /**
     * @return array<int, array{key: string, label: string, intro: string, icon: string, tools: Collection}>
     */
    public function groupedForHub(): array
    {
        $tools = $this->resolveTools();
        $groups = [];

        foreach (TradingToolCategories::all() as $key => $meta) {
            $items = $tools->filter(fn ($tool) => ($tool->category_key ?? '') === $key)->values();
            if ($items->isEmpty()) {
                continue;
            }

            $groups[] = [
                'key' => $key,
                'label' => $meta['label'],
                'intro' => $meta['intro'],
                'icon' => $meta['icon'],
                'tools' => $items,
            ];
        }

        return $groups;
    }

    /**
     * @param  Collection<int, object>  $allTools
     * @return Collection<int, object>
     */
    public function relatedTools(object $tool, Collection $allTools): Collection
    {
        $ids = $this->idList($tool->related_tool_ids ?? null);
        $picked = collect();

        if ($ids !== []) {
            $byId = $allTools->filter(fn ($item) => isset($item->id))->keyBy('id');
            foreach ($ids as $id) {
                if ($byId->has($id) && (string) $byId[$id]->slug !== (string) $tool->slug) {
                    $picked->push($byId[$id]);
                }
            }
        }

        if ($picked->isNotEmpty()) {
            return $picked->values();
        }

        $defaults = TradingToolsRegistry::defaultRelated((string) $tool->slug);

        return $allTools
            ->filter(fn ($item) => in_array($item->slug, $defaults, true))
            ->sortBy(fn ($item) => array_search($item->slug, $defaults, true))
            ->values();
    }

    /** @return Collection<int, Broker> */
    public function relatedBrokers(object $tool, int $limit = 6): Collection
    {
        $ids = $this->idList($tool->related_broker_ids ?? null);

        $query = Broker::query()->where('is_scam', false)->with('alternativePage');

        if ($ids !== []) {
            $brokers = (clone $query)->whereIn('id', $ids)->get();

            return $brokers->sortBy(fn (Broker $broker) => array_search($broker->id, $ids, true))->values();
        }

        if (! TradingToolsRegistry::showsBrokerCosts((string) $tool->slug)) {
            return collect();
        }

        return $query
            ->whereNotNull('spreads')
            ->where('spreads', '!=', '')
            ->orderByDesc('rating')
            ->orderBy('name')
            ->limit($limit)
            ->get();
    }

    /**
     * @param  Collection<int, Broker>  $brokers
     * @return array<int, array<string, mixed>>
     */
    public function brokerCostCards(Collection $brokers): array
    {
        return $brokers->map(function (Broker $broker) {
            $card = BrokerCardData::from($broker);
            $facts = BrokerFacts::for($broker);
            $spread = $facts['spread'] ?? ['known' => false, 'value' => null, 'raw' => null, 'label' => null];
            $commission = trim((string) (RichText::toPlainText($broker->commission) ?? ''));

            return [
                'id' => $broker->id,
                'name' => $broker->name,
                'logo' => $card['logo'],
                'rating' => $card['rating'],
                'review_url' => $card['review_url'],
                'spreads' => $card['spreads'] ?: '—',
                'commission' => $commission !== '' ? Str::limit($commission, 80) : null,
                'minimum_deposit' => $card['minimum_deposit'] ?: '—',
                'platforms' => $card['platforms'] ?: '—',
                'spread_pips' => ! empty($spread['known']) ? $spread['value'] : null,
                'spread_known' => ! empty($spread['known']),
                'compare_url' => route('broker.comparison', ['brokers' => $broker->slug]),
                'alternatives_url' => $broker->alternativePage?->is_published
                    ? route('broker.alternatives.show', ['slug' => $broker->slug])
                    : null,
            ];
        })->values()->all();
    }

    /**
     * Compact hints for the Trading Cost Calculator broker selector.
     *
     * @return array<int, array<string, mixed>>
     */
    public function costBrokerHints(int $limit = 24): array
    {
        return $this->mapCostBrokerHints(
            Broker::query()
                ->where('is_scam', false)
                ->orderBy('name')
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Search live brokers for the Trading Cost Calculator autocomplete.
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchCostBrokerHints(string $query, int $limit = 12): array
    {
        $query = trim($query);

        $builder = Broker::query()
            ->where('is_scam', false)
            ->orderBy('name')
            ->limit($limit);

        if ($query !== '') {
            $escaped = addcslashes($query, '%_\\');
            $normalized = addcslashes(str_replace([' ', '-'], '', $query), '%_\\');

            $builder->where(function (Builder $q) use ($escaped, $normalized) {
                $q->where('name', 'like', '%'.$escaped.'%')
                    ->orWhere(
                        DB::raw("REPLACE(REPLACE(name,' ',''),'-','')"),
                        'like',
                        '%'.$normalized.'%'
                    );
            });
        }

        return $this->mapCostBrokerHints($builder->get());
    }

    /**
     * @param  Collection<int, Broker>  $brokers
     * @return array<int, array<string, mixed>>
     */
    private function mapCostBrokerHints(Collection $brokers): array
    {
        return $brokers->map(function (Broker $broker) {
            $facts = BrokerFacts::for($broker);
            $spread = $facts['spread'] ?? ['known' => false, 'value' => null, 'raw' => null];
            $commission = trim((string) (RichText::toPlainText($broker->commission) ?? ''));

            return [
                'id' => $broker->id,
                'name' => $broker->name,
                'spread_pips' => ! empty($spread['known']) ? $spread['value'] : null,
                'spread_raw' => $spread['raw'] ?: null,
                'spread_known' => ! empty($spread['known']),
                'commission' => $commission !== '' ? $commission : null,
            ];
        })->values()->all();
    }

    /** @return array<int, array{question: string, answer: string}> */
    public function faqs(object $tool): array
    {
        if ($tool instanceof TradingTool) {
            $faqs = $tool->normalizedFaqs();
            if ($faqs !== []) {
                return $faqs;
            }
        }

        return TradingToolCopy::for((string) $tool->slug)['faqs'] ?? [];
    }

    /** @return array{introduction: string, how_to_use: string, formula: string, example: string, additional: string} */
    public function pageContent(object $tool): array
    {
        $copy = TradingToolCopy::for((string) $tool->slug);

        return [
            'introduction' => $this->firstFilled(
                $tool->introduction ?? null,
                $copy['introduction'] ?? null,
                $tool->short_description ?? null,
                $tool->page_about ?? null
            ),
            'how_to_use' => $this->firstFilled($tool->how_to_use ?? null, $copy['how_to_use'] ?? null),
            'formula' => $this->firstFilled($tool->formula ?? null, $copy['formula'] ?? null),
            'example' => $this->firstFilled($tool->example ?? null, $copy['example'] ?? null),
            'additional' => $this->firstFilled(
                $tool->additional_explanation ?? null,
                $copy['additional'] ?? null,
                $tool->description ?? null
            ),
        ];
    }

    /** @return array{title: string, description: string, canonical: string, og_title: string, og_description: string} */
    public function seo(object $tool, string $routeSlug): array
    {
        $registry = TradingToolsRegistry::meta((string) $tool->slug) ?? [];
        $isWidget = TradingToolsRegistry::isWidget((string) $tool->slug);
        $defaultCanonical = $isWidget
            ? route('trading.tools.show', ['slug' => $routeSlug])
            : route('calculators.show', ['slug' => $routeSlug]);

        $title = $this->firstFilled(
            $tool->seo_title ?? null,
            ($tool->name ?? $registry['title'] ?? 'Forex Calculator').' | Forex Calculators | BrokersCourt'
        );
        $description = $this->firstFilled(
            $tool->meta_description ?? null,
            $tool->short_description ?? null,
            $registry['meta'] ?? null
        );

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $this->firstFilled($tool->canonical_url ?? null, $defaultCanonical),
            'og_title' => $this->firstFilled($tool->og_title ?? null, $title),
            'og_description' => $this->firstFilled($tool->og_description ?? null, $description),
        ];
    }

    /**
     * @param  array<int, array{question: string, answer: string}>  $faqs
     * @return array<int, array<string, mixed>>
     */
    public function jsonLd(object $tool, array $faqs, string $canonical, array $content): array
    {
        $graph = [
            [
                '@type' => 'BreadcrumbList',
                '@id' => $canonical.'#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Forex Trading Tools', 'item' => route('calculators.index')],
                    ['@type' => 'ListItem', 'position' => 3, 'name' => $tool->name ?? 'Calculator', 'item' => $canonical],
                ],
            ],
            [
                '@type' => 'WebPage',
                '@id' => $canonical.'#webpage',
                'url' => $canonical,
                'name' => $tool->name ?? 'Forex Calculator',
                'description' => $content['introduction'] ?: ($tool->short_description ?? ''),
            ],
        ];

        if ($faqs !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $canonical.'#faq',
                'mainEntity' => array_map(fn (array $faq) => [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ], $faqs),
            ];
        }

        return $graph;
    }

    public function hubJsonLd(array $groups): array
    {
        $elements = [];
        $position = 1;

        foreach ($groups as $group) {
            foreach ($group['tools'] as $tool) {
                $elements[] = [
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => $tool->name,
                    'url' => $tool->public_url,
                ];
            }
        }

        return [[
            '@type' => 'ItemList',
            '@id' => route('calculators.index').'#tools',
            'name' => 'Forex Trading Tools',
            'itemListElement' => $elements,
        ]];
    }

    public function reviewUrl(Broker $broker): string
    {
        return route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);
    }

    private function enrichTool(object $tool): object
    {
        $routeSlug = TradingToolsRegistry::routeSlug((string) $tool->slug);
        $registry = TradingToolsRegistry::meta((string) $tool->slug) ?? [];

        $tool->route_slug = $routeSlug;
        $tool->category_key = TradingToolCategories::isValid($tool->category ?? null)
            ? $tool->category
            : TradingToolsRegistry::defaultCategory((string) $tool->slug);
        $tool->page_title = $registry['title'] ?? $tool->name;
        $tool->page_meta = $registry['meta'] ?? ($tool->short_description ?? '');
        $tool->page_about = $registry['about'] ?? ($tool->description ?? $tool->short_description ?? '');
        $tool->tool_summary = $tool->short_description ?: ($registry['meta'] ?? $tool->page_about);
        $tool->tool_description = $tool->description ?: ($registry['about'] ?? $tool->short_description ?? '');
        $tool->is_widget = $routeSlug ? TradingToolsRegistry::isWidget((string) $tool->slug) : false;
        $tool->public_url = $routeSlug
            ? ($tool->is_widget
                ? route('trading.tools.show', ['slug' => $routeSlug])
                : route('calculators.show', ['slug' => $routeSlug]))
            : null;

        if (! empty($registry['icon']) && empty($tool->icon)) {
            $tool->icon = $registry['icon'];
        }

        return $tool;
    }

    /** @param Collection<int, object> $tools */
    private function appendMissingRegistryTools(Collection $tools): Collection
    {
        $existing = $tools->pluck('slug')->all();

        try {
            $stored = TradingTool::query()->pluck('slug')->all();
        } catch (\Exception $e) {
            $stored = $existing;
        }

        foreach (TradingToolsRegistry::allToolKeys() as $key) {
            if (in_array($key, $existing, true) || in_array($key, $stored, true)) {
                continue;
            }

            $registry = TradingToolsRegistry::meta($key);
            if (! $registry) {
                continue;
            }

            $tools->push($this->enrichTool((object) [
                'slug' => $key,
                'name' => $registry['title'],
                'icon' => $registry['icon'] ?? 'fas fa-calculator',
                'short_description' => $registry['about'],
                'description' => $registry['about'],
                'category' => $registry['category'] ?? TradingToolCategories::TRADING_CALCULATORS,
                'related_tool_ids' => null,
                'related_broker_ids' => null,
                'faqs' => null,
            ]));
        }

        return $tools->values();
    }

    /** @return array<int, object> */
    private function fallbackTools(): array
    {
        return [
            (object) ['slug' => 'pip', 'name' => 'Pip Calculator', 'icon' => 'fas fa-exchange-alt', 'short_description' => 'Pip value and position notional', 'category' => 'trading_calculators'],
            (object) ['slug' => 'position', 'name' => 'Position Size', 'icon' => 'fas fa-layer-group', 'short_description' => 'Size lots from risk & stop loss', 'category' => 'trading_calculators'],
            (object) ['slug' => 'profit', 'name' => 'Profit / Loss', 'icon' => 'fas fa-chart-line', 'short_description' => 'Estimate trade P/L', 'category' => 'trading_calculators'],
            (object) ['slug' => 'margin', 'name' => 'Margin Calculator', 'icon' => 'fas fa-percentage', 'short_description' => 'Required margin by leverage', 'category' => 'trading_calculators'],
            (object) ['slug' => 'risk', 'name' => 'Risk Calculator', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Risk amount from balance', 'category' => 'trading_calculators'],
            (object) ['slug' => 'cost', 'name' => 'Trading Cost Calculator', 'icon' => 'fas fa-file-invoice-dollar', 'short_description' => 'Estimate spread, commission, and swap', 'category' => 'trading_calculators'],
            (object) ['slug' => 'pivot', 'name' => 'Pivot Points', 'icon' => 'fas fa-crosshairs', 'short_description' => 'Support & resistance pivots', 'category' => 'technical_analysis'],
            (object) ['slug' => 'fibonacci', 'name' => 'Fibonacci', 'icon' => 'fas fa-wave-square', 'short_description' => 'Retracement levels', 'category' => 'technical_analysis'],
            (object) ['slug' => 'converter', 'name' => 'Currency Converter', 'icon' => 'fas fa-coins', 'short_description' => 'Convert major currencies', 'category' => 'market_tools'],
            (object) ['slug' => 'live-markets', 'name' => 'Live Market Widgets', 'icon' => 'fas fa-chart-area', 'short_description' => 'Live FX rates, heatmap & calendar', 'category' => 'market_tools'],
        ];
    }

    /** @param mixed $value */
    private function idList($value): array
    {
        $ids = [];
        foreach ((array) $value as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    private function firstFilled(?string ...$values): string
    {
        foreach ($values as $value) {
            $text = trim((string) $value);
            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }
}
