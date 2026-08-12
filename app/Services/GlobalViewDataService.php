<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\Broker;
use App\Models\Language;
use App\Models\Setting;
use App\Models\SidebarAdvertisement;
use App\Models\SocialItem;
use App\Models\TopAdvertisement;
use Illuminate\Support\Facades\Cache;

class GlobalViewDataService
{
    public const CACHE_TTL = 3600;

    private const GLOBAL_KEY = 'global_view_data_v2';

    private const TOP_BROKERS_KEY = 'global_top_rated_brokers_v2';

    private const FEATURED_BROKERS_KEY = 'global_featured_brokers_v1';

    private const SPOTLIGHT_BROKERS_KEY = 'global_spotlight_brokers_v1';

    /** Share cached globals with all views. */
    public function share(): void
    {
        foreach ($this->payload() as $key => $value) {
            view()->share($key, $value);
        }
    }

    /** @return array<string, mixed> */
    public function payload(): array
    {
        return Cache::remember(self::GLOBAL_KEY, self::CACHE_TTL, function () {
            $defaultLang = Language::query()->where('is_default', 'Yes')->first();

            return [
                'global_top_ad_data' => TopAdvertisement::query()->find(1) ?? new TopAdvertisement(),
                'global_sidebar_top_ad' => SidebarAdvertisement::query()
                    ->where('sidebar_ad_location', 'Top')
                    ->get(),
                'global_sidebar_bottom_ad' => SidebarAdvertisement::query()
                    ->where('sidebar_ad_location', 'Bottom')
                    ->get(),
                'global_social_item_data' => SocialItem::query()->get(),
                'global_setting_data' => Setting::query()->find(1) ?? new Setting(),
                'global_language_data' => Language::query()->get(),
                'global_short_name' => $defaultLang?->short_name ?? 'en',
                'global_popup_ads' => $this->popupAds(),
            ];
        });
    }

    public function currentLanguageId(?string $sessionShortName = null): int
    {
        $shortName = $sessionShortName
            ?? session()->get('session_short_name')
            ?? config('site-locale.default', 'en');

        if (! $shortName) {
            return (int) Cache::remember('global_default_language_id', self::CACHE_TTL, function () {
                return (int) (Language::query()->where('is_default', 'Yes')->value('id') ?? 1);
            });
        }

        return (int) Cache::remember("global_language_id_{$shortName}", self::CACHE_TTL, function () use ($shortName) {
            return (int) (
                Language::query()->where('short_name', $shortName)->value('id')
                ?? Language::query()->where('is_default', 'Yes')->value('id')
                ?? 1
            );
        });
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Broker> */
    public function topRatedBrokers()
    {
        return Cache::remember(self::TOP_BROKERS_KEY, self::CACHE_TTL, function () {
            return Broker::query()
                ->where('is_scam', false)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take(6)
                ->get();
        });
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Broker> */
    public function featuredBrokers()
    {
        return Cache::remember(self::FEATURED_BROKERS_KEY, self::CACHE_TTL, function () {
            $featured = Broker::query()
                ->where('is_scam', false)
                ->where('featured_broker', true)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take(6)
                ->get();

            if ($featured->isNotEmpty()) {
                return $featured;
            }

            return $this->topRatedBrokers()->take(5);
        });
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, Broker> */
    public function spotlightBrokers()
    {
        return Cache::remember(self::SPOTLIGHT_BROKERS_KEY, self::CACHE_TTL, function () {
            return Broker::query()
                ->where('is_scam', false)
                ->whereNotNull('rating')
                ->orderByDesc('rating')
                ->take(12)
                ->get();
        });
    }

    /** @return \Illuminate\Support\Collection<int, mixed> */
    private function popupAds()
    {
        try {
            return Ad::active()->popups()->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    public static function flush(): void
    {
        Cache::forget(self::GLOBAL_KEY);
        Cache::forget(self::TOP_BROKERS_KEY);
        Cache::forget(self::FEATURED_BROKERS_KEY);
        Cache::forget(self::SPOTLIGHT_BROKERS_KEY);
        Cache::forget('global_default_language_id');
        Cache::forget('site_setting_v1');
        Cache::forget('footer_index_v2');
        Cache::forget('scam_brokers_index_v1');
        Cache::forget('broker_reviews_index_v2');
        Cache::forget('homepage_top_rated_brokers_v2');
        PageContextService::flush();
        RecommendedBrokersService::flush();
        CountryBrokersService::flush();
    }
}
