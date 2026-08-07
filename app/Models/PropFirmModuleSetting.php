<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropFirmModuleSetting extends Model
{
    protected $fillable = [
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['settings' => static::defaultSettings()]
        );
    }

    /** @return array<string, mixed> */
    public static function defaultSettings(): array
    {
        return [
            'default_sort_order' => 'sort_order',
            'enable_reviews' => true,
            'enable_faqs' => true,
            'enable_programs' => true,
        ];
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->settings ?? static::defaultSettings();

        return $settings[$key] ?? $default;
    }

    public function setMany(array $values): void
    {
        $this->settings = array_merge($this->settings ?? static::defaultSettings(), $values);
        $this->save();
    }
}
