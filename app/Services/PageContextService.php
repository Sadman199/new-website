<?php

namespace App\Services;

use App\Models\HomeAdvertisement;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageContextService
{
    public const CACHE_TTL = 3600;

    public function __construct(private GlobalViewDataService $globals)
    {
    }

    public function languageId(): int
    {
        return $this->globals->currentLanguageId();
    }

    public function shortName(): string
    {
        return (string) config('site-locale.default', 'en');
    }

    public function pageData(): ?Page
    {
        $languageId = $this->languageId();

        return Cache::remember(
            "page_context_data_{$languageId}",
            self::CACHE_TTL,
            fn () => Page::query()->where('language_id', $languageId)->first()
        );
    }

    public function homeAdData(): HomeAdvertisement
    {
        return Cache::remember('page_context_home_ad', self::CACHE_TTL, function () {
            return HomeAdvertisement::query()->find(1) ?? new HomeAdvertisement();
        });
    }

    /** @return array<string, mixed> */
    public function payload(): array
    {
        return [
            'page_data' => $this->pageData(),
            'home_ad_data' => $this->homeAdData(),
            'current_short_name' => $this->shortName(),
            'current_language_id' => $this->languageId(),
        ];
    }

    public function share(): void
    {
        foreach ($this->payload() as $key => $value) {
            view()->share($key, $value);
        }
    }

    public static function flush(): void
    {
        Cache::forget('page_context_home_ad');

        foreach (Language::query()->pluck('id') as $languageId) {
            Cache::forget("page_context_data_{$languageId}");
        }
    }
}
