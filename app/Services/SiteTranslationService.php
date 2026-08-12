<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SiteTranslationService
{
    public const CACHE_TTL = 3600;

    private string $locale = 'en';

    /** @var array<string, string> */
    private array $lines = [];

    public function setLocale(?string $locale = null): self
    {
        $this->locale = (string) config('site-locale.default', 'en');
        $this->lines = $this->loadLines($this->locale);

        return $this;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    public function translate(string $key, ?string $default = null): string
    {
        return $this->lines[$key] ?? $default ?? $key;
    }

    /** @return array<string, string> */
    public function all(): array
    {
        return $this->lines;
    }

    public function shareToViews(): void
    {
        view()->share('site_locale', $this->locale);
        view()->share('site_t', fn (string $key, ?string $default = null) => $this->translate($key, $default));
    }

    public function applyLegacyConstants(): void
    {
        foreach ($this->lines as $key => $value) {
            if (! is_string($key) || ! is_scalar($value)) {
                continue;
            }

            if (! defined($key)) {
                define($key, (string) $value);
            }
        }
    }

    /** @return array<string, string> */
    private function loadLines(string $locale): array
    {
        return Cache::remember("site_translations_{$locale}", self::CACHE_TTL, function () use ($locale) {
            $path = resource_path("languages/{$locale}.json");

            if (! is_file($path)) {
                return [];
            }

            $decoded = json_decode((string) file_get_contents($path), true);

            return is_array($decoded) ? $decoded : [];
        });
    }

    public static function flush(?string $locale = null): void
    {
        Cache::forget('site_translations_'.($locale ?? config('site-locale.default', 'en')));
    }
}
