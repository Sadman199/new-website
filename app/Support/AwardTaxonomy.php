<?php

namespace App\Support;

use App\Models\Broker;
use Illuminate\Support\Collection;

class AwardTaxonomy
{
    /** @return array<string, array{name: string, description: string, color: string, category: ?string, filter: ?string}> */
    public static function definitions(): array
    {
        $year = (int) date('Y');

        return [
            'most-trusted' => [
                'name' => 'Best Broker ' . $year,
                'description' => 'Featured brokers with strong regulation, high ratings, and verified client trust.',
                'color' => 'amber',
                'category' => null,
                'filter' => 'featured',
            ],
            'fast-execution' => [
                'name' => 'Fast Execution',
                'description' => 'Brokers recognized for low-latency execution and scalping-friendly conditions.',
                'color' => 'purple',
                'category' => 'scalping-brokers',
                'filter' => null,
            ],
            'ecn-raw' => [
                'name' => 'ECN / Raw Spread',
                'description' => 'Low-spread brokers offering raw ECN-style pricing and transparent commissions.',
                'color' => 'rose',
                'category' => 'low-spread-brokers',
                'filter' => 'ecn',
            ],
            'top-trusted' => [
                'name' => 'Top Trusted Broker',
                'description' => 'Highly rated, regulated brokers with strong investor protection.',
                'color' => 'emerald',
                'category' => null,
                'filter' => 'regulated_high_rating',
            ],
            'beginner-friendly' => [
                'name' => 'Best for Beginners',
                'description' => 'Accessible minimum deposits, education, and beginner-friendly platforms.',
                'color' => 'blue',
                'category' => 'brokers-for-beginners',
                'filter' => null,
            ],
            'low-spread' => [
                'name' => 'Low Spread Broker',
                'description' => 'Consistently competitive spreads for cost-conscious active traders.',
                'color' => 'pink',
                'category' => 'low-spread-brokers',
                'filter' => null,
            ],
            'social-trading' => [
                'name' => 'Social Trading',
                'description' => 'Brokers with copy trading and social investing features.',
                'color' => 'indigo',
                'category' => 'social-trading-brokers',
                'filter' => null,
            ],
            'mobile-trading' => [
                'name' => 'Best Mobile Platform',
                'description' => 'Strong mobile apps and web trading for on-the-go investors.',
                'color' => 'cyan',
                'category' => 'trading-apps-brokers',
                'filter' => null,
            ],
        ];
    }

    public static function labelFor(string $slug): string
    {
        return self::definitions()[$slug]['name']
            ?? BrokerTaxonomy::categories()[$slug]
            ?? ucwords(str_replace('-', ' ', $slug));
    }

    /** @return Collection<int, Broker> */
    public static function brokersFor(string $slug, ?Collection $brokers = null): Collection
    {
        $definition = self::definitions()[$slug] ?? null;

        if ($definition === null) {
            return BrokerListingFilter::brokersFor($slug, $brokers);
        }

        $brokers = ($brokers ?? Broker::query()->where('is_scam', false)->get())
            ->filter(fn (Broker $broker) => ! $broker->is_scam)
            ->values();

        if (! empty($definition['category'])) {
            $brokers = BrokerListingFilter::brokersFor($definition['category'], $brokers);
        }

        $filtered = match ($definition['filter'] ?? null) {
            'featured' => $brokers->filter(fn (Broker $broker) => (bool) $broker->featured_broker),
            'regulated_high_rating' => $brokers->filter(
                fn (Broker $broker) => $broker->isRegulated() && (float) $broker->rating >= 4.0
            ),
            'ecn' => $brokers->filter(function (Broker $broker) {
                $haystack = strtolower(implode(' ', array_filter([
                    (string) $broker->commission,
                    (string) $broker->pricing,
                    implode(', ', $broker->accountTypeLabelList()),
                ])));

                return str_contains($haystack, 'ecn') || str_contains($haystack, 'raw');
            }),
            default => $brokers,
        };

        return $filtered->sortByDesc('rating')->values();
    }
}
