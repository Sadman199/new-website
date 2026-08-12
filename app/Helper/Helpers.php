<?php
namespace App\Helper;

use App\Services\SiteTranslationService;

class Helpers
{
    public static function read_json()
    {
        $shortName = (string) config('site-locale.default', 'en');

        app(SiteTranslationService::class)->setLocale($shortName)->applyLegacyConstants();
    }
}
