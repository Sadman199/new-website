<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\AwardTaxonomy;
use Illuminate\Support\Collection;

class AwardsIndexService
{
    /** @return Collection<int, Broker> */
    public function baseBrokers(): Collection
    {
        return Broker::query()
            ->where('is_scam', false)
            ->orderByDesc('rating')
            ->get();
    }

    /** @return array<int, array<string, mixed>> */
    public function awardCards(?Collection $brokers = null): array
    {
        $brokers = $brokers ?? $this->baseBrokers();
        $cards = [];

        foreach (AwardTaxonomy::definitions() as $slug => $definition) {
            $matches = AwardTaxonomy::brokersFor($slug, $brokers);
            $topBrokers = $matches->take(5);

            $cards[] = [
                'slug' => $slug,
                'name' => $definition['name'],
                'description' => $definition['description'],
                'color' => $definition['color'],
                'broker_count' => $matches->count(),
                'url' => route('awards.show', ['award' => AwardTaxonomy::routeSlugFor($slug)]),
                'broker_logos' => $topBrokers->map(fn (Broker $broker) => [
                    'name' => $broker->name,
                    'logo' => $broker->logo ? asset($broker->logo) : null,
                ])->all(),
                'top_broker' => $topBrokers->first()?->name,
            ];
        }

        return $cards;
    }

    /** @return array<string, int|float|string> */
    public function stats(?Collection $brokers = null): array
    {
        $brokers = $brokers ?? $this->baseBrokers();

        return [
            'total_brokers' => $brokers->count(),
            'featured_brokers' => $brokers->where('featured_broker', true)->count(),
            'award_categories' => count(AwardTaxonomy::definitions()),
            'average_rating' => round((float) $brokers->whereNotNull('rating')->avg('rating'), 1),
        ];
    }

    /** @return array<int, array{title: string, description: string}> */
    public function evaluationPillars(): array
    {
        return [
            [
                'title' => 'Regulation & Security',
                'description' => 'Verification of licences, fund protection, and regulatory tier.',
            ],
            [
                'title' => 'Trading Conditions',
                'description' => 'Spreads, commissions, execution quality, and fee transparency.',
            ],
            [
                'title' => 'Platform & Technology',
                'description' => 'Platform stability, mobile apps, and charting tools.',
            ],
            [
                'title' => 'Client Experience',
                'description' => 'Support quality, education, and verified user feedback.',
            ],
        ];
    }
}
