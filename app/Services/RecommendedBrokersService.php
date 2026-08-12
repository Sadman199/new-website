<?php

namespace App\Services;

use App\Models\Broker;
use Illuminate\Support\Facades\Cache;

class RecommendedBrokersService
{
    public const CACHE_TTL = 3600;

    /** @return \Illuminate\Database\Eloquent\Collection<int, Broker> */
    public function forSidebar(int $limit = 5)
    {
        return Cache::remember("recommended_brokers_sidebar_{$limit}", self::CACHE_TTL, function () use ($limit) {
            return Broker::query()
                ->where('is_scam', false)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take($limit)
                ->get(['id', 'name', 'slug', 'logo', 'rating', 'minimum_deposit']);
        });
    }

    public static function flush(): void
    {
        foreach ([5, 6] as $limit) {
            Cache::forget("recommended_brokers_sidebar_{$limit}");
        }
    }
}
