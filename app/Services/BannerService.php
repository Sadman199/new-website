<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Support\Collection;

class BannerService
{
    /**
     * Active, in-schedule banners for a placement, ranked by priority.
     *
     * @return Collection<int, Banner>
     */
    public function forPlacement(string $placement, ?int $brokerId = null, ?string $pagePath = null, int $limit = 12): Collection
    {
        $query = Banner::query()
            ->with('brokers:id,name,slug')
            ->currentlyDisplayable()
            ->forPlacement($placement)
            ->ranked();

        if ($brokerId) {
            $query->where(function ($q) use ($brokerId) {
                $q->where('targeting', 'website_wide')
                    ->orWhere(function ($inner) use ($brokerId) {
                        $inner->whereIn('targeting', ['specific_broker', 'multiple_brokers'])
                            ->whereHas('brokers', fn ($brokers) => $brokers->where('brokers.id', $brokerId));
                    });
            });
        }

        if ($pagePath) {
            $normalized = $this->normalizePath($pagePath);
            $query->where(function ($q) use ($normalized) {
                $q->where('targeting', '!=', 'specific_page')
                    ->orWhere('page_path', $normalized)
                    ->orWhere('page_path', ltrim($normalized, '/'));
            });
        }

        return $query->limit($limit)->get();
    }

    public function firstForPlacement(string $placement, ?int $brokerId = null, ?string $pagePath = null): ?Banner
    {
        return $this->forPlacement($placement, $brokerId, $pagePath, 1)->first();
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '/';
        }

        return str_starts_with($path, '/') ? $path : '/'.$path;
    }
}
