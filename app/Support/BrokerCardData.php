<?php

namespace App\Support;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use Illuminate\Support\Str;

class BrokerCardData
{
    /** @param  Broker|array<string, mixed>  $broker */
    public static function from(mixed $broker): array
    {
        if ($broker instanceof Broker) {
            return self::fromModel($broker);
        }

        return self::fromArray(is_array($broker) ? $broker : []);
    }

    public static function fromModel(Broker $broker): array
    {
        $regs = $broker->regulationList();
        $platforms = $broker->platformList();
        $min = $broker->minimum_deposit;
        $logo = $broker->logo ? asset($broker->logo) : null;

        return self::fromArray([
            'id' => $broker->id,
            'name' => $broker->name,
            'slug' => $broker->slug,
            'logo' => $logo,
            'rating' => $broker->rating !== null ? round((float) $broker->rating, 1) : null,
            'review_url' => route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
            'visit_url' => $broker->open_live ?: $broker->visit_site ?: $broker->url,
            'minimum_deposit' => $min !== null ? '$' . number_format((float) $min, 0) : '—',
            'leverage' => $broker->leverage ?: '—',
            'spreads' => $broker->spreads ?: '—',
            'platforms' => $platforms ? implode(', ', array_slice($platforms, 0, 3)) : '—',
            'regulation_summary' => $regs ? implode(', ', array_slice($regs, 0, 3)) : null,
            'is_featured' => (bool) $broker->featured_broker,
            'is_regulated' => $broker->isRegulated(),
            'top_feature' => trim((string) ($broker->top_feature ?? '')),
            'short_description' => Str::limit(trim(strip_tags((string) ($broker->short_description ?? ''))), 140),
            'review_count' => (int) ($broker->approved_review_count ?? 0),
            'markets' => $broker->marketList(),
            'is_award_winner' => (bool) $broker->featured_broker,
            'regulatory_tier' => $broker->regulatory_tier ? 'Tier ' . $broker->regulatory_tier : null,
            'regulatory_tier_key' => (string) ($broker->regulatory_tier ?? ''),
            'regulator_slugs' => array_map(static fn ($item) => Str::slug((string) $item), $regs),
            'investor_protection' => $broker->investor_protection ? 'Yes' : 'No',
        ]);
    }

    /** @param  array<string, mixed>  $broker */
    public static function fromArray(array $broker): array
    {
        $markets = $broker['markets'] ?? [];
        if (is_string($markets)) {
            $markets = array_values(array_filter(explode(',', $markets)));
        }

        $regs = $broker['regulator_slugs'] ?? [];
        if (is_string($regs)) {
            $regs = array_values(array_filter(explode(',', $regs)));
        }

        return [
            'id' => $broker['id'] ?? null,
            'name' => (string) ($broker['name'] ?? 'Broker'),
            'slug' => (string) ($broker['slug'] ?? ''),
            'logo' => $broker['logo'] ?? null,
            'rating' => isset($broker['rating']) && $broker['rating'] !== null ? (float) $broker['rating'] : null,
            'review_url' => $broker['review_url'] ?? '#',
            'visit_url' => $broker['visit_url'] ?? null,
            'minimum_deposit' => $broker['minimum_deposit'] ?? null,
            'leverage' => $broker['leverage'] ?? null,
            'spreads' => $broker['spreads'] ?? null,
            'platforms' => $broker['platforms'] ?? null,
            'regulation_summary' => $broker['regulation_summary'] ?? null,
            'is_featured' => (bool) ($broker['is_featured'] ?? false),
            'is_regulated' => (bool) ($broker['is_regulated'] ?? false),
            'top_feature' => $broker['top_feature'] ?? null,
            'short_description' => $broker['short_description'] ?? null,
            'review_count' => (int) ($broker['review_count'] ?? 0),
            'markets' => $markets,
            'performance' => is_array($broker['performance'] ?? null) ? $broker['performance'] : [],
            'is_award_winner' => (bool) ($broker['is_award_winner'] ?? false),
            'award_label' => $broker['award_label'] ?? null,
            'is_best_match' => (bool) ($broker['is_best_match'] ?? false),
            'is_match' => (bool) ($broker['is_match'] ?? false),
            'regulatory_tier' => $broker['regulatory_tier'] ?? null,
            'regulatory_tier_key' => (string) ($broker['regulatory_tier_key'] ?? ''),
            'regulator_slugs' => $regs,
            'investor_protection' => $broker['investor_protection'] ?? null,
        ];
    }
}
