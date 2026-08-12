<?php

namespace App\Http\Middleware;

use App\Models\Language;
use App\Services\BrokerAdminService;
use App\Services\CountryBrokersService;
use App\Services\GlobalViewDataService;
use App\Services\PageContextService;
use App\Services\SiteTranslationService;
use App\Support\CountryBrokersPlacement;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class SharePreferredCountry
{
    public function handle(Request $request, Closure $next)
    {
        $this->shareLanguageContext();

        $countryBrokersService = app(CountryBrokersService::class);
        $preferredCountry = $countryBrokersService->resolvePreferredCountry();
        $countrySlug = $preferredCountry['slug'] ?? 'global';
        $selector = $countryBrokersService->countriesForSelector();

        View::share('brokerCountries', $selector);
        View::share('listedCountries', BrokerAdminService::listedCountriesWithFlags());
        View::share('listedRegions', BrokerAdminService::listedRegionsWithFlags());
        View::share('preferredCountry', $preferredCountry);
        View::share('countryBrokers', $countryBrokersService->forCountry($countrySlug, 6));
        View::share('countryBrokersCount', $countryBrokersService->countForCountry($countrySlug));
        View::share('countryBrokersUrl', $countryBrokersService->brokersPageUrl($countrySlug));
        View::share('defaultTopBrokers', $countryBrokersService->globalTopRated(4));
        View::share('showCountryBrokersStrip', CountryBrokersPlacement::shouldShowStrip($request));

        app(PageContextService::class)->share();

        return $next($request);
    }

    private function shareLanguageContext(): void
    {
        $shortName = (string) (session('session_short_name') ?? config('site-locale.default', 'en'));

        session(['session_short_name' => $shortName]);

        $language = Language::query()->where('short_name', $shortName)->first()
            ?? Language::query()->where('is_default', 'Yes')->first();

        $translations = app(SiteTranslationService::class)->setLocale($shortName);
        $translations->shareToViews();
        $translations->applyLegacyConstants();

        App::setLocale($shortName);

        View::share('current_language_short', $shortName);
        View::share('current_language', $language);
        View::share('global_current_language_id', app(GlobalViewDataService::class)->currentLanguageId($shortName));
    }
}
