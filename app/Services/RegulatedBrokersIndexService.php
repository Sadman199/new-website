<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RegulatedBrokersIndexService
{
    /** @return \Illuminate\Database\Eloquent\Builder<Broker> */
    public function baseQuery()
    {
        return Broker::query()
            ->where('is_scam', false)
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNotNull('regulation')
                        ->where('regulation', '!=', '[]')
                        ->where('regulation', '!=', '');
                })->orWhere('investor_protection', true);
            });
    }

    /** @return Collection<int, Broker> */
    public function brokers(): Collection
    {
        return $this->baseQuery()
            ->orderByDesc('rating')
            ->orderBy('name')
            ->get();
    }

    /** @return array<string, string> slug => label */
    public function regulatorFilters(Collection $brokers): array
    {
        $filters = [];

        foreach ($brokers as $broker) {
            foreach ($broker->regulationList() as $regulator) {
                $slug = Str::slug($regulator);
                if ($slug !== '') {
                    $filters[$slug] = $regulator;
                }
            }
        }

        asort($filters);

        return $filters;
    }

    /** @return array<string, string> */
    public function tierFilters(): array
    {
        return [
            '1' => 'Tier 1',
            '2' => 'Tier 2',
            '3' => 'Tier 3',
        ];
    }

    /** @return array<string, int|float> */
    public function stats(Collection $brokers): array
    {
        return [
            'regulated_count' => $brokers->count(),
            'tier_one_count' => $brokers->where('regulatory_tier', 1)->count(),
            'investor_protection_count' => $brokers->where('investor_protection', true)->count(),
            'segregation_count' => $brokers->where('segregation_of_funds', true)->count(),
            'average_rating' => round((float) $brokers->whereNotNull('rating')->avg('rating'), 1),
        ];
    }

    /** @return array<int, array{title: string, value: string}> */
    public function trustHighlights(Collection $brokers, array $stats): array
    {
        return [
            [
                'title' => 'Investor protection',
                'value' => $stats['investor_protection_count'] . ' of ' . $stats['regulated_count'] . ' brokers',
            ],
            [
                'title' => 'Segregated funds',
                'value' => $stats['segregation_count'] . ' brokers offer segregation',
            ],
            [
                'title' => 'Tier 1 regulation',
                'value' => $stats['tier_one_count'] . ' tier-1 regulated brokers',
            ],
            [
                'title' => 'Average rating',
                'value' => number_format($stats['average_rating'], 1) . ' / 5 across the list',
            ],
        ];
    }

    /** @return array<string, mixed> */
    public function serialize(Broker $broker): array
    {
        $regulators = $broker->regulationList();
        $regulatorSlugs = array_values(array_unique(array_filter(array_map(
            fn (string $regulator) => Str::slug($regulator),
            $regulators
        ))));

        return [
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'logo' => $broker->logo ? asset($broker->logo) : null,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'regulatory_tier' => $this->tierLabel($broker->regulatory_tier),
            'regulatory_tier_key' => $broker->regulatory_tier ? (string) $broker->regulatory_tier : '',
            'regulation_summary' => $regulators !== []
                ? implode(', ', array_slice($regulators, 0, 3)) . (count($regulators) > 3 ? ' +' . (count($regulators) - 3) : '')
                : 'Investor protection only',
            'regulator_slugs' => $regulatorSlugs,
            'spreads' => trim((string) ($broker->spreads ?: '')) ?: null,
            'minimum_deposit' => $broker->minimum_deposit !== null
                ? '$' . number_format((float) $broker->minimum_deposit, 0)
                : null,
            'investor_protection' => $broker->investor_protection ? 'Yes' : 'No',
            'segregation_of_funds' => $broker->segregation_of_funds ? 'Yes' : 'No',
            'country' => trim((string) ($broker->country ?: '')) ?: null,
            'top_feature' => trim((string) ($broker->top_feature ?: '')),
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
        ];
    }

    protected function tierLabel(?int $tier): string
    {
        return match ($tier) {
            1 => 'Tier 1',
            2 => 'Tier 2',
            3 => 'Tier 3',
            default => '—',
        };
    }
}
