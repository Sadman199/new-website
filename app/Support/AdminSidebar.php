<?php

namespace App\Support;

use Illuminate\Support\Facades\Request;

class AdminSidebar
{
    /** @param  array<string, mixed>  $item */
    public static function isActive(array $item): bool
    {
        if (isset($item['children'])) {
            foreach ($item['children'] as $child) {
                if (self::matches($child['match'] ?? '')) {
                    return true;
                }
            }

            return self::matches($item['match'] ?? '');
        }

        if (! empty($item['match_exclude']) && Request::is($item['match_exclude'])) {
            return false;
        }

        return self::matches($item['match'] ?? '');
    }

    /** @param  array<string, mixed>  $child */
    public static function isChildActive(array $child): bool
    {
        return self::matches($child['match'] ?? '');
    }

    /** @param  string|array<int, string>  $patterns */
    private static function matches(string|array $patterns): bool
    {
        if ($patterns === '' || $patterns === []) {
            return false;
        }

        foreach ((array) $patterns as $pattern) {
            if (Request::is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /** @param  array<string, int>  $badges */
    public static function badgeCount(array $item, array $badges): ?int
    {
        $key = $item['badge'] ?? null;

        if (! $key || ! isset($badges[$key])) {
            return null;
        }

        $count = (int) $badges[$key];

        return $count > 0 ? $count : null;
    }
}
