<?php

namespace App\Services;

use App\Models\PropFirm;
use App\Models\PropFirmAttribute;
use App\Models\PropFirmCategory;
use Illuminate\Support\Facades\Cache;

class PropFirmNavService
{
    /** @return array{categories: \Illuminate\Support\Collection, featured: \Illuminate\Support\Collection, topRated: \Illuminate\Support\Collection, attributes: \Illuminate\Support\Collection} */
    public function forNavbar(): array
    {
        return Cache::remember('prop_firm_nav_data_v1', 3600, function () {
            return [
                'categories' => PropFirmCategory::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(['id', 'name', 'slug']),
                'featured' => PropFirm::query()
                    ->where('is_active', true)
                    ->where('is_featured', true)
                    ->orderByDesc('trust_score')
                    ->take(5)
                    ->get(['id', 'name', 'slug', 'logo', 'trust_score', 'max_funding']),
                'topRated' => PropFirm::query()
                    ->where('is_active', true)
                    ->orderByDesc('trust_score')
                    ->take(5)
                    ->get(['id', 'name', 'slug', 'logo', 'trust_score', 'overall_rating']),
                'attributes' => PropFirmAttribute::query()
                    ->where('is_active', true)
                    ->orderBy('group')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->take(10)
                    ->get(['id', 'name', 'slug', 'group']),
            ];
        });
    }
}
