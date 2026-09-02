<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Support\BestBrokerGuideDefinition;
use App\Support\BestBrokerGuideMetrics;
use App\Support\BrokerCategorySignals;
use App\Support\BrokerFacts;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Collection;

class BestBrokerGuideService
{
    /** Below this many matches the guide widens to the whole database. */
    private const MIN_ENTRIES = 3;

    private const SHORTLIST_SIZE = 10;

    /** A metric needs this share of the shortlist to earn a column in the comparison. */
    private const COVERAGE_THRESHOLD = 0.4;

    /** @return array<string, mixed>|null */
    public function build(string $slug, ?Collection $brokers = null): ?array
    {
        $guide = BestBrokerGuideDefinition::forSlug($slug);

        if ($guide === null) {
            return null;
        }

        $matches = $brokers ?? BrokerListingFilter::brokersFor($slug);
        $matchCount = $matches->count();
        $rankedFromAll = false;

        if ($matchCount < self::MIN_ENTRIES) {
            $matches = Broker::query()->where('is_scam', false)->get();
            $rankedFromAll = true;
        }

        $preferredCountry = BrokerTaxonomy::resolvePreferredCountry();
        $countrySlug = $preferredCountry['slug'] !== 'global' ? $preferredCountry['slug'] : null;

        $ranked = $matches
            ->sortByDesc(fn (Broker $broker) => BrokerCategorySignals::fit($broker, $slug)['score'])
            ->values()
            ->take(self::SHORTLIST_SIZE);

        $guide = $this->replaceTokens($guide, [
            '{year}' => (string) date('Y'),
            '{month}' => now()->format('F'),
            '{country}' => $preferredCountry['name'],
            '{winner}' => $ranked->first()?->name ?? 'our top-rated broker',
        ]);

        $entries = $ranked
            ->map(fn (Broker $broker, int $index) => $this->buildEntry($broker, $index + 1, $slug, $countrySlug))
            ->all();

        $coverage = BestBrokerGuideMetrics::coverage($entries);
        $metrics = $this->comparableMetrics($coverage, BestBrokerGuideMetrics::distinctValues($entries));
        $bestInClass = BestBrokerGuideMetrics::bestInClass($entries);

        $entries = $this->attachAwards($entries, $bestInClass, array_column($metrics, 'key'));

        return [
            'slug' => $slug,
            'type' => $guide['type'],
            'label' => BrokerListingFilter::labelFor($slug),
            'guide' => $guide,
            'country' => $preferredCountry,
            'country_matches' => count(array_filter($entries, fn (array $e) => $e['in_country'])),
            'ranked_from_all' => $rankedFromAll,
            'match_count' => $rankedFromAll ? $matches->count() : $matchCount,
            'updated_at' => now()->format('F j, Y'),
            'entries' => $entries,
            'winner' => $entries[0] ?? null,
            'pillars' => BrokerCategorySignals::pillarsFor($slug),
            'metrics' => $metrics,
            'metric_groups' => $this->metricGroups($metrics),
            'coverage' => $coverage,
            'best_in_class' => $bestInClass,
            'highlights' => BestBrokerGuideMetrics::highlights($entries),
            'nav' => $this->navigation($guide, $entries),
            'is_empty' => $entries === [],
            'editorial_team' => EditorialAssignmentService::guideTeamFor($ranked->first()),
            'editorial_credits' => EditorialAssignmentService::guideCreditsFor($ranked->first()),
            'primary_author' => EditorialAssignmentService::primaryGuideAuthor($ranked->first()),
        ];
    }

    /** @return array<string, mixed> */
    private function buildEntry(Broker $broker, int $rank, string $slug, ?string $countrySlug): array
    {
        $fit = BrokerCategorySignals::fit($broker, $slug);
        $facts = BestBrokerGuideMetrics::factsFor($broker);

        return [
            'rank' => $rank,
            'id' => (int) $broker->id,
            'name' => $broker->name,
            'broker' => $broker,
            'logo_url' => $broker->logo ? asset($broker->logo) : null,
            'initial' => mb_strtoupper(mb_substr($broker->name, 0, 1)),
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
            'fit' => $fit,
            'facts' => $facts,
            'signals' => BrokerCategorySignals::signals($broker, $slug),
            'awards' => [],
            'headline' => BestBrokerGuideMetrics::oneLiner($broker),
            'pros' => BestBrokerGuideMetrics::prosList($broker),
            'cons' => BestBrokerGuideMetrics::consList($broker),
            'tagged' => BrokerListingFilter::isTagged($broker, $slug),
            'in_country' => $countrySlug !== null && BrokerListingFilter::matches($broker, $countrySlug),
            'micro_account' => BrokerFacts::for($broker)['has_micro_account'],
        ];
    }

    /**
     * Only keep metrics enough of the shortlist can answer, so the comparison never
     * renders a column of dashes, and only ones where brokers actually differ.
     *
     * @param  array<string, float>  $coverage
     * @param  array<string, int>  $distinct
     * @return array<int, array<string, mixed>>
     */
    private function comparableMetrics(array $coverage, array $distinct): array
    {
        $metrics = [];

        foreach (BestBrokerGuideMetrics::definitions() as $key => $definition) {
            if (($coverage[$key] ?? 0) < self::COVERAGE_THRESHOLD) {
                continue;
            }

            if (($distinct[$key] ?? 0) < 2) {
                continue;
            }

            $metrics[] = ['key' => $key, 'coverage' => $coverage[$key]] + $definition;
        }

        return $metrics;
    }

    /**
     * @param  array<int, array<string, mixed>>  $metrics
     * @return array<int, array<string, mixed>>
     */
    private function metricGroups(array $metrics): array
    {
        $groups = [];

        foreach (BestBrokerGuideMetrics::groups() as $id => $group) {
            $keys = array_values(array_filter($metrics, fn (array $m) => $m['group'] === $id));

            if ($keys !== []) {
                $groups[] = $group + ['metrics' => $keys];
            }
        }

        return $groups;
    }

    /**
     * @param  array<int, array<string, mixed>>  $entries
     * @param  array<string, array{entry_id: int, value: string}>  $bestInClass
     * @param  array<int, string>  $visibleKeys
     * @return array<int, array<string, mixed>>
     */
    private function attachAwards(array $entries, array $bestInClass, array $visibleKeys): array
    {
        $definitions = BestBrokerGuideMetrics::definitions();

        foreach ($entries as $index => $entry) {
            $awards = [];

            foreach ($bestInClass as $key => $winner) {
                // Never award a metric the comparison hides for lack of coverage.
                if ($winner['entry_id'] === $entry['id'] && in_array($key, $visibleKeys, true)) {
                    $awards[] = ['key' => $key, 'label' => $definitions[$key]['short'] ?? $key];
                }
            }

            $entries[$index]['awards'] = $awards;
        }

        return $entries;
    }

    /**
     * Grouped items for the sticky section rail that replaced the sidebar.
     *
     * @param  array<string, mixed>  $guide
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function navigation(array $guide, array $entries): array
    {
        if ($entries === []) {
            return [];
        }

        $nav = [
            ['id' => 'shortlist', 'label' => 'Shortlist', 'icon' => 'fa-trophy', 'group' => 'Overview'],
            ['id' => 'top-pick', 'label' => 'Top pick', 'icon' => 'fa-crown', 'group' => 'Overview'],
            ['id' => 'compare', 'label' => 'Compare', 'icon' => 'fa-columns', 'group' => 'Overview'],
            ['id' => 'scoring', 'label' => 'Scoring', 'icon' => 'fa-sliders-h', 'group' => 'Overview'],
        ];

        if (\App\Support\RichText::toPlainText($guide['description'] ?? null)) {
            $nav[] = ['id' => 'explainer', 'label' => 'Guide', 'icon' => 'fa-book-open', 'group' => 'Overview'];
        }

        foreach ($entries as $entry) {
            $nav[] = [
                'id' => 'broker-'.$entry['rank'],
                'label' => $entry['name'],
                'icon' => null,
                'rank' => $entry['rank'],
                'logo' => $entry['logo_url'],
                'group' => 'Reviews',
            ];
        }

        return array_merge($nav, [
            ['id' => 'find-match', 'label' => 'Find my match', 'icon' => 'fa-magic', 'group' => 'More'],
        ]);
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
