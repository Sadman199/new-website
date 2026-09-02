<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BrokerTaxonomyTerm extends Model
{
    public const TYPE_CATEGORY = 'category';

    public const TYPE_REGION = 'region';

    public const TYPE_COUNTRY = 'country';

    /** @var array<string, array<string, string>>|null */
    private static ?array $loaded = null;

    protected $fillable = [
        'type',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => static::flushMemoryCache());
        static::deleted(fn () => static::flushMemoryCache());
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function allDescriptions(): array
    {
        if (self::$loaded !== null) {
            return self::$loaded;
        }

        $empty = [
            self::TYPE_CATEGORY => [],
            self::TYPE_REGION => [],
            self::TYPE_COUNTRY => [],
        ];

        if (! Schema::hasTable('broker_taxonomy_terms')) {
            return self::$loaded = $empty;
        }

        $loaded = $empty;

        foreach (static::query()->get(['type', 'slug', 'description']) as $term) {
            $description = trim((string) $term->description);

            if ($description === '' || ! isset($loaded[$term->type])) {
                continue;
            }

            $loaded[$term->type][$term->slug] = $description;
        }

        return self::$loaded = $loaded;
    }

    public static function descriptionFor(string $type, string $slug): ?string
    {
        $value = self::allDescriptions()[$type][$slug] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }

    public static function descriptionForSlug(string $slug): ?string
    {
        $type = \App\Support\BrokerListingFilter::slugType($slug);

        return $type ? self::descriptionFor($type, $slug) : null;
    }

    public static function flushMemoryCache(): void
    {
        self::$loaded = null;
    }
}
