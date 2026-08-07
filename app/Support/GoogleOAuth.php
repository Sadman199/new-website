<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class GoogleOAuth
{
    public static function clientId(): ?string
    {
        $fromEnv = trim((string) config('services.google.client_id', ''));

        if ($fromEnv !== '') {
            return $fromEnv;
        }

        if (! self::settingsTableReady()) {
            return null;
        }

        $setting = Setting::query()->find(1);

        return filled($setting?->google_client_id) ? trim($setting->google_client_id) : null;
    }

    public static function clientSecret(): ?string
    {
        $fromEnv = trim((string) config('services.google.client_secret', ''));

        if ($fromEnv !== '') {
            return $fromEnv;
        }

        if (! self::settingsTableReady()) {
            return null;
        }

        $setting = Setting::query()->find(1);

        return filled($setting?->google_client_secret) ? trim($setting->google_client_secret) : null;
    }

    public static function redirectUri(): string
    {
        $fromEnv = trim((string) config('services.google.redirect', ''));

        if ($fromEnv !== '') {
            return $fromEnv;
        }

        return url('/auth/google/callback');
    }

    public static function isConfigured(): bool
    {
        return filled(self::clientId());
    }

    public static function supportsRedirectFlow(): bool
    {
        return filled(self::clientId()) && filled(self::clientSecret());
    }

    private static function settingsTableReady(): bool
    {
        return Schema::hasTable('settings') && Schema::hasColumn('settings', 'google_client_id');
    }
}
