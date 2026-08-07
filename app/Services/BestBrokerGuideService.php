<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Support\BestBrokerGuideDefinition;
use App\Support\BestBrokerGuideMetrics;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Collection;

class BestBrokerGuideService
{
    /** @return array<string, mixed>|null */
    public function build(string $slug, ?Collection $brokers = null): ?array
    {
        $guide = BestBrokerGuideDefinition::forSlug($slug);

        if ($guide === null) {
            return null;
        }

        $brokers = $brokers ?? BrokerListingFilter::brokersFor($slug);
        $preferredCountry = BrokerTaxonomy::resolvePreferredCountry();
        $year = (int) date('Y');
        $month = now()->format('F');

        $ranked = $brokers
            ->sortByDesc(fn (Broker $broker) => BestBrokerGuideMetrics::guideScore($broker, $slug, $guide['type']))
            ->values()
            ->take(10);

        $winner = $ranked->first();
        $replacements = [
            '{year}' => (string) $year,
            '{month}' => $month,
            '{country}' => $preferredCountry['name'],
            '{winner}' => $winner?->name ?? 'our top-rated broker',
        ];

        $guide = $this->replaceTokens($guide, $replacements);
        $scoreLabel = $guide['score_label'] ?? 'Overall score';

        $entries = $ranked->map(function (Broker $broker, int $index) use ($guide, $scoreLabel, $slug) {
            $rank = $index + 1;
            $metrics = BestBrokerGuideMetrics::values($broker, $slug, $guide['type']);
            $isWinner = $rank === 1;

            return [
                'rank' => $rank,
                'broker' => $broker,
                'score' => BestBrokerGuideMetrics::guideScore($broker, $slug, $guide['type']),
                'score_label' => $scoreLabel,
                'metrics' => $metrics,
                'one_liner' => BestBrokerGuideMetrics::oneLiner($broker),
                'recommended_for' => BestBrokerGuideMetrics::recommendedFor($broker, $guide, $isWinner),
                'pros' => BestBrokerGuideMetrics::prosList($broker),
                'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
                'visit_url' => $broker->visit_site ?: $broker->url,
                'logo_url' => $broker->logo ? asset($broker->logo) : null,
            ];
        });

        $tables = [];

        foreach ($guide['comparison_tables'] as $tableKey => $columns) {
            $tables[$tableKey] = [
                'columns' => $columns,
                'rows' => collect($columns)->map(function (array $column) use ($entries) {
                    return [
                        'key' => $column['key'],
                        'label' => $column['label'],
                        'cells' => $entries->mapWithKeys(fn (array $entry) => [
                            $entry['broker']->id => $entry['metrics'][$column['key']] ?? '—',
                        ])->all(),
                    ];
                })->all(),
            ];
        }

        $winnerBroker = $ranked->first();

        return [
            'slug' => $slug,
            'type' => $guide['type'],
            'label' => BrokerListingFilter::labelFor($slug),
            'guide' => $guide,
            'country' => $preferredCountry,
            'updated_at' => now()->format('F j, Y'),
            'entries' => $entries,
            'winner' => $entries->first(),
            'tables' => $tables,
            'toc' => $this->tableOfContents($guide, $entries, $entries->isNotEmpty()),
            'is_empty' => $entries->isEmpty(),
            'editorial_team' => EditorialAssignmentService::guideTeamFor($winnerBroker),
            'editorial_credits' => EditorialAssignmentService::guideCreditsFor($winnerBroker),
            'primary_author' => EditorialAssignmentService::primaryGuideAuthor($winnerBroker),
        ];
    }

    /** @param  array<string, mixed>  $guide */
    private function tableOfContents(array $guide, Collection $entries, bool $hasContent): array
    {
        $toc = [];

        if ($hasContent) {
            $toc[] = ['id' => 'broker-scores', 'label' => 'Broker scores'];
            $toc[] = ['id' => 'top-pick', 'label' => 'Top pick'];
        }

        $toc = array_merge(
            $toc,
            collect($guide['sections'] ?? [])
                ->map(fn (array $section) => [
                    'id' => $section['id'],
                    'label' => $section['title'],
                ])
                ->all()
        );

        foreach ($entries as $entry) {
            $toc[] = [
                'id' => 'broker-'.$entry['rank'],
                'label' => '#'.$entry['rank'].' '.$entry['broker']->name,
            ];
        }

        if ($hasContent) {
            $toc[] = ['id' => 'find-match', 'label' => $guide['cta_title'] ?? 'Find my match'];
        }

        $toc[] = ['id' => 'page-author', 'label' => 'Author'];
        $toc[] = ['id' => 'methodology', 'label' => $guide['methodology']['title'] ?? 'Methodology'];
        $toc[] = ['id' => 'faq', 'label' => 'FAQ'];
        $toc[] = ['id' => 'latest-blog', 'label' => 'Latest blog'];

        return $toc;
    }

    /** @param  array<string, mixed>  $guide */
    private function replaceTokens(array $guide, array $replacements): array
    {
        array_walk_recursive($guide, function (&$value) use ($replacements) {
            if (is_string($value)) {
                $value = str_replace(array_keys($replacements), array_values($replacements), $value);
            }
        });

        return $guide;
    }
}
