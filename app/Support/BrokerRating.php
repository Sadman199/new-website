<?php

namespace App\Support;

final class BrokerRating
{
    /**
     * Normalize legacy 10-point broker values to the site's public 5-point scale.
     */
    public static function outOfFive(float|int|string|null $rating): ?float
    {
        if ($rating === null || $rating === '') {
            return null;
        }

        $value = max(0, (float) $rating);

        return (float) min(5, $value > 5 ? $value / 2 : $value);
    }

    public static function percent(float|int|string|null $rating): float
    {
        $value = self::outOfFive($rating);

        return $value === null ? 0 : round(($value / 5) * 100, 2);
    }
}
