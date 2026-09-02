<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\AwardTaxonomy;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use App\Support\RichText;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TopBrokersIndexService
{
    /** @return array<int, array{key: string, label: string}> */
    public function quickFilters(): array
    {
        return [
            ['key' => 'low-spread', 'label' => 'Low spread'],
            ['key' => 'beginner', 'label' => 'Beginner'],
            ['key' => 'mt5', 'label' => 'MT5'],
            ['key' => 'low-deposit', 'label' => 'Low deposit'],
            ['key' => 'regulated', 'label' => 'Regulated'],
            ['key' => 'copy', 'label' => 'Copy'],
        ];
    }

    /** @return array<int, array{label: string, url: string}> */
    public function styleLinks(): array
    {
        return [
            ['label' => 'Low-cost', 'url' => route('brokers.best', ['slug' => 'low-spread-brokers'])],
            ['label' => 'Beginner', 'url' => route('brokers.best', ['slug' => 'brokers-for-beginners'])],
            ['label' => 'Professional', 'url' => route('brokers.best', ['slug' => 'high-leverage'])],
            ['label' => 'MT5', 'url' => route('brokers.best', ['slug' => 'mt5-brokers'])],
            ['label' => 'High leverage', 'url' => route('brokers.best', ['slug' => 'high-leverage'])],
            ['label' => 'Copy trading', 'url' => route('brokers.best', ['slug' => 'copytrading-brokers'])],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function awardCategories(?Collection $brokers = null): array
    {
        $brokers = $brokers ?? Broker::query()->where('is_scam', false)->orderByDesc('rating')->get();
        $cards = [];

        foreach (AwardTaxonomy::definitions() as $slug => $definition) {
            $matches = AwardTaxonomy::brokersFor($slug, $brokers);
            $topBrokers = $matches->take(4);
            $winner = $matches->first();

            $cards[] = [
                'slug' => $slug,
                'name' => $definition['name'],
                'description' => $definition['description'],
                'color' => $definition['color'],
                'broker_count' => $matches->count(),
                'url' => route('awards.show', ['award' => AwardTaxonomy::routeSlugFor($slug)]),
                'top_broker' => $winner?->name,
                'broker_logos' => $topBrokers->map(fn (Broker $broker) => [
                    'name' => $broker->name,
                    'logo' => $broker->logo ? asset($broker->logo) : null,
                ])->all(),
            ];
        }

        return $cards;
    }

    /** @return array<int, array<string, mixed>> */
    public function brokerCategoryLinks(?Collection $brokers = null): array
    {
        $brokers = $brokers ?? Broker::query()->where('is_scam', false)->get();
        $links = [];

        foreach (BrokerTaxonomy::categories() as $slug => $label) {
            $matches = BrokerListingFilter::brokersFor($slug, $brokers);

            $links[] = [
                'slug' => $slug,
                'title' => BrokerTaxonomy::categoryGuideHeading($slug),
                'label' => $label,
                'description' => $this->categoryDescription($slug, $label),
                'icon' => $this->categoryIcon($slug),
                'url' => route('brokers.best', ['slug' => $slug]),
                'broker_count' => $matches->count(),
            ];
        }

        return $links;
    }

    private function categoryIcon(string $slug): string
    {
        return match ($slug) {
            'low-spread-brokers' => 'fa-chart-line',
            'free-withdrawal-brokers' => 'fa-hand-holding-usd',
            'mt4-brokers', 'mt5-brokers' => 'fa-desktop',
            'micro-accounts-brokers' => 'fa-coins',
            'copytrading-brokers', 'social-trading-brokers' => 'fa-users',
            'scalping-brokers' => 'fa-bolt',
            'trading-apps-brokers' => 'fa-mobile-alt',
            'brokers-for-beginners' => 'fa-seedling',
            'high-leverage' => 'fa-arrows-alt-v',
            'ea-brokers' => 'fa-robot',
            'trading-signals-brokers' => 'fa-signal',
            'mam-brokers', 'pamm-brokers' => 'fa-layer-group',
            default => 'fa-compass',
        };
    }

    private function categoryDescription(string $slug, string $label): string
    {
        $custom = \App\Support\RichText::toPlainText(\App\Models\BrokerTaxonomyTerm::descriptionFor('category', $slug));

        if ($custom) {
            return $custom;
        }

        return match ($slug) {
            'ea-brokers' => 'Brokers that support automated Expert Advisor strategies.',
            'trading-signals-brokers' => 'Platforms with integrated or third-party trading signals.',
            'mam-brokers' => 'Multi-account manager solutions for professional traders.',
            'pamm-brokers' => 'Percentage allocation modules for managed account investing.',
            'low-spread-brokers' => 'Ranked by competitive spreads and overall trading costs.',
            'brokers-for-beginners' => 'Low barriers, education, and beginner-friendly platforms.',
            'high-leverage' => 'Flexible leverage for active and experienced traders.',
            default => "Compare {$label} brokers by fees, platforms, and trust.",
        };
    }

    /** @return array<string, mixed> */
    public function pageData(BrokerReviewsIndexService $reviewsIndexService): array
    {
        return Cache::remember('top_brokers_index_page_v6', 1800, function () use ($reviewsIndexService) {
            $brokers = Broker::query()
                ->where('is_scam', false)
                ->with(['accountOptions' => fn ($query) => $query->ordered()])
                ->withCount(['reviews as approved_review_count' => function ($query) {
                    $query->where('status', 1);
                }])
                ->orderByDesc('rating')
                ->orderBy('name')
                ->get();

            $payload = $brokers->map(function (Broker $broker) use ($reviewsIndexService) {
                $item = $reviewsIndexService->serialize($broker);
                $item['tags'] = $this->tagsFor($broker);
                $item['highlights'] = $this->highlightsFor($broker, $item['tags']);
                $item['sort_rating'] = (float) ($broker->rating ?? 0);
                $item['sort_low_cost'] = $this->lowCostScore($broker, $item);

                return $item;
            })->values()->all();

            $collection = collect($payload);

            return [
                'brokers' => $payload,
                'total_brokers' => $brokers->count(),
                'editor_picks' => $this->editorPicks($collection),
                'updated_label' => 'Updated ' . now()->format('F Y'),
                'award_categories' => $this->awardCategories($brokers),
                'broker_categories' => $this->brokerCategoryLinks($brokers),
                'regulation_tabs' => $this->regulationTabs($brokers, $payload),
            ];
        });
    }

    /**
     * Group brokers into regulator tabs so visitors can browse brokers by regulatory body.
     * All regulators found in the dataset are returned — the view renders them in a
     * horizontally scrollable tab bar rather than truncating the list.
     *
     * @param  \Illuminate\Support\Collection<int, Broker>  $brokers
     * @param  array<int, array<string, mixed>>  $payload  Serialized broker cards, keyed by broker id below
     * @return array<int, array{slug: string, label: string, broker_count: int, brokers: array<int, array<string, mixed>>}>
     */
    private function regulationTabs(Collection $brokers, array $payload, int $perTab = 8): array
    {
        $byId = collect($payload)->keyBy('id');
        $grouped = [];

        foreach ($brokers as $broker) {
            $item = $byId->get($broker->id);
            if (! $item) {
                continue;
            }

            foreach ($broker->regulationList() as $regulator) {
                $label = trim((string) strip_tags((string) $regulator));
                $label = trim(preg_replace('/\s+/', ' ', $label) ?? '');

                // Guard against malformed data (rich text pasted into the regulation field
                // instead of a short regulator name) leaking into the tab bar.
                if ($label === '' || mb_strlen($label) > 40) {
                    continue;
                }

                $slug = Str::slug($label);
                if ($slug === '') {
                    continue;
                }

                $grouped[$slug]['label'] = $grouped[$slug]['label'] ?? $label;
                $grouped[$slug]['items'][] = $item;
            }
        }

        $tabs = [];

        foreach ($grouped as $slug => $data) {
            $items = collect($data['items'])
                ->unique('id')
                ->sortByDesc('sort_rating')
                ->take($perTab)
                ->values()
                ->all();

            if ($items === []) {
                continue;
            }

            $tabs[] = [
                'slug' => $slug,
                'label' => $data['label'],
                'broker_count' => count($items),
                'brokers' => $items,
            ];
        }

        usort($tabs, fn (array $a, array $b) => $b['broker_count'] <=> $a['broker_count']);

        return $tabs;
    }

    /** @param  array<string, mixed>  $item */
    private function lowCostScore(Broker $broker, array $item): float
    {
        $feeLevel = strtolower((string) ($broker->fee_level ?? ''));
        $base = match ($feeLevel) {
            'low' => 5.0,
            'medium' => 3.5,
            'high' => 2.0,
            default => 3.0,
        };

        if (isset($item['fee_score']) && $item['fee_score'] !== null) {
            $base = max($base, (float) $item['fee_score']);
        }

        return $base;
    }

    /** @return array<int, string> */
    private function tagsFor(Broker $broker): array
    {
        $tags = [];
        $platforms = strtolower(implode(' ', $broker->platformList()));
        $categories = strtolower(implode(' ', $broker->brokerCategoryList()));
        $haystack = $platforms . ' ' . $categories . ' ' . strtolower(RichText::toPlainText($broker->top_feature) ?? '');

        if (strtolower((string) ($broker->fee_level ?? '')) === 'low' || str_contains(strtolower(RichText::toPlainText($broker->spreads) ?? ''), 'low')) {
            $tags[] = 'low-spread';
        }

        if ($broker->demo_account_available || ($broker->minimum_deposit !== null && (float) $broker->minimum_deposit <= 10)) {
            $tags[] = 'beginner';
        }

        if (str_contains($platforms, 'mt5') || str_contains($platforms, 'metatrader 5')) {
            $tags[] = 'mt5';
        }

        if ($broker->minimum_deposit !== null && (float) $broker->minimum_deposit <= 50) {
            $tags[] = 'low-deposit';
        }

        if ($broker->isRegulated()) {
            $tags[] = 'regulated';
        }

        if (str_contains($haystack, 'copy')) {
            $tags[] = 'copy';
        }

        return array_values(array_unique($tags));
    }

    /** @param  array<int, string>  $tags */
    private function highlightsFor(Broker $broker, array $tags): array
    {
        $labels = [
            'low-spread' => 'Low cost',
            'beginner' => 'Beginner friendly',
            'mt5' => 'MT5',
            'low-deposit' => 'Low deposit',
            'regulated' => 'Regulated',
            'copy' => 'Copy trading',
        ];

        $highlights = [];
        foreach ($tags as $tag) {
            if (isset($labels[$tag])) {
                $highlights[] = $labels[$tag];
            }
        }

        if ($highlights === [] && RichText::toPlainText($broker->top_feature)) {
            $highlights[] = Str::limit(RichText::toPlainText($broker->top_feature), 24);
        }

        return array_slice($highlights, 0, 3);
    }

    /** @param  \Illuminate\Support\Collection<int, array<string, mixed>>  $brokers */
    private function editorPicks($brokers): array
    {
        if ($brokers->isEmpty()) {
            return [];
        }

        $overall = $brokers->sortByDesc('sort_rating')->first();
        $lowCost = $brokers->sortByDesc('sort_low_cost')->first();
        $beginner = $brokers
            ->filter(fn (array $broker) => in_array('beginner', $broker['tags'] ?? [], true))
            ->sortByDesc('sort_rating')
            ->first() ?? $brokers->sortByDesc('sort_rating')->skip(1)->first();

        $picks = [
            ['key' => 'overall', 'label' => 'Best overall', 'broker' => $overall],
            ['key' => 'low-cost', 'label' => 'Best low cost', 'broker' => $lowCost],
            ['key' => 'beginner', 'label' => 'Best beginner', 'broker' => $beginner],
        ];

        $usedIds = [];
        $uniquePicks = [];

        foreach ($picks as $pick) {
            $broker = $pick['broker'] ?? null;
            if (! $broker) {
                continue;
            }

            $id = $broker['id'] ?? $broker['slug'] ?? null;
            if ($id !== null && in_array($id, $usedIds, true)) {
                $replacement = $brokers->first(function (array $candidate) use ($usedIds) {
                    $candidateId = $candidate['id'] ?? $candidate['slug'] ?? null;

                    return $candidateId !== null && ! in_array($candidateId, $usedIds, true);
                });
                $pick['broker'] = $replacement;
                $broker = $replacement;
            }

            if (! $broker) {
                continue;
            }

            $id = $broker['id'] ?? $broker['slug'] ?? null;
            if ($id !== null) {
                $usedIds[] = $id;
            }

            $uniquePicks[] = $pick;
        }

        return $uniquePicks;
    }
}
