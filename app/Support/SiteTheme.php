<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SiteTheme
{
    public const DEFAULT_PRIMARY = '#007AAD';
    public const DEFAULT_DARK = '#0C1D32';
    public const DEFAULT_LIGHT = '#D9E2E9';

    public static function setting(): ?Setting
    {
        return Cache::remember('site_setting_v1', 3600, function () {
            return Setting::query()->find(1);
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('site_setting_v1');
    }

    public static function defaultAttributes(): array
    {
        return [
            'video_total' => '6',
            'video_status' => 'Show',
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'top_bar_date_status' => 'Show',
            'top_bar_email' => 'info@brokerscourt.com',
            'top_bar_email_status' => 'Show',
            'theme_color_1' => self::DEFAULT_PRIMARY,
            'theme_color_2' => self::DEFAULT_DARK,
            'theme_color_3' => self::DEFAULT_LIGHT,
            'site_name' => 'BrokersCourt',
            'site_tagline' => 'Independent broker reviews, comparisons, and trading education.',
            'contact_phone' => '+44 7577 309951',
            'footer_copyright' => null,
            'default_meta_description' => self::defaultMetaDescription(),
            'maintenance_mode' => 'Hide',
            'maintenance_message' => null,
            'show_broker_spotlight' => 'Show',
            'show_quick_access_drawer' => 'Show',
            'analytic_id' => '',
            'analytic_status' => 'Hide',
            'disqus_code' => '',
            'google_client_id' => null,
            'google_client_secret' => null,
        ];
    }

    public static function primary(): string
    {
        return self::normalizeHex(self::setting()?->theme_color_1, self::DEFAULT_PRIMARY);
    }

    public static function dark(): string
    {
        return self::normalizeHex(self::setting()?->theme_color_2, self::DEFAULT_DARK);
    }

    public static function light(): string
    {
        return self::normalizeHex(self::setting()?->theme_color_3, self::DEFAULT_LIGHT);
    }

    public static function primaryDark(): string
    {
        return self::shade(self::primary(), -12);
    }

    public static function primaryLight(): string
    {
        return self::shade(self::primary(), 18);
    }

    public static function cssVariables(): array
    {
        $primary = self::primary();
        $dark = self::dark();
        $light = self::light();

        $primaryDark = self::primaryDark();
        $primaryLight = self::primaryLight();
        $primaryRgb = self::toRgbString($primary);

        // Core tokens + aliases used by page CSS so admin brand colors propagate site-wide.
        return [
            '--bc-primary' => $primary,
            '--bc-primary-dark' => $primaryDark,
            '--bc-primary-light' => $primaryLight,
            '--bc-dark' => $dark,
            '--bc-light' => $light,
            '--bc-ice' => $light,
            '--bc-white' => '#FFFBFC',
            '--bc-bg' => '#f4f6f9',
            '--bc-surface' => '#ffffff',
            '--bc-text' => $dark,
            '--bc-muted' => '#64748b',
            '--bc-border' => '#e2e8f0',
            '--bc-primary-soft' => 'rgba(' . $primaryRgb . ', 0.1)',
            '--bc-accent' => $primaryLight,
            '--bc-shadow' => '0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 24px rgba(15, 23, 42, 0.06)',
            '--bc-shadow-lg' => '0 4px 6px rgba(15, 23, 42, 0.04), 0 20px 40px rgba(15, 23, 42, 0.08)',
            '--bc-radius-sm' => '8px',
            '--bc-radius-md' => '12px',
            '--bc-radius-lg' => '16px',
            '--bc-radius' => '12px',
            '--bc-container' => '1320px',
            '--bc-section-spacing' => '80px',
            '--bc-nav-height' => '4rem',
            '--bc-transition' => '0.2s ease',
            '--bc-font' => "Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
            '--bc-primary-rgb' => $primaryRgb,
            '--bc-dark-rgb' => self::toRgbString($dark),
            '--bc-light-rgb' => self::toRgbString($light),

            '--hero-ocean' => $primary,
            '--hero-ocean-dark' => $primaryDark,
            '--picks-ocean' => $primary,
            '--trust-ocean' => $primary,
            '--trust-ocean-dark' => $primaryDark,
            '--insights-ocean' => $primary,
            '--explore-ocean' => $primary,
            '--explore-ocean-dark' => $primaryDark,
            '--match-ocean' => $primary,
            '--nav-ocean' => $primary,
            '--mf-ocean' => $primary,
            '--awd-ocean' => $primary,
            '--cmp-ocean' => $primary,
            '--sbi-ocean' => $primary,
            '--sbd-ocean' => $primary,
            '--bpd-ocean' => $primary,
            '--bli-ocean' => $primary,
            '--bpr-ocean' => $primary,
            '--cms-ocean' => $primary,
            '--br-ocean' => $primary,
            '--bsd-ocean' => $primary,
            '--tt-ocean' => $primary,
            '--pf-ocean' => $primary,
            '--bsc-ocean' => $primary,
            '--bbd-ocean' => $primary,
            '--aui-ocean' => $primary,
            '--bri-ocean' => $primary,
            '--mk-ocean' => $primary,
            '--brb-ocean' => $primary,
            '--bbh-ocean' => $primary,
            '--cti-ocean' => $primary,
            '--ts-ocean' => $primary,
        ];
    }

    public static function cssBlock(): string
    {
        $lines = array_map(
            fn (string $key, string $value) => sprintf('%s:%s', $key, $value),
            array_keys(self::cssVariables()),
            array_values(self::cssVariables())
        );

        return ':root{' . implode(';', $lines) . '}';
    }

    public static function defaults(): array
    {
        return [
            'theme_color_1' => self::DEFAULT_PRIMARY,
            'theme_color_2' => self::DEFAULT_DARK,
            'theme_color_3' => self::DEFAULT_LIGHT,
        ];
    }

    public static function isMaintenanceMode(): bool
    {
        return (self::setting()?->maintenance_mode ?? 'Hide') === 'Show';
    }

    public static function maintenanceMessage(): string
    {
        $message = trim((string) (self::setting()?->maintenance_message ?? ''));

        return $message !== ''
            ? $message
            : 'We are performing scheduled maintenance. Please check back soon.';
    }

    public static function showBrokerSpotlight(): bool
    {
        return (self::setting()?->show_broker_spotlight ?? 'Show') !== 'Hide';
    }

    public static function showQuickAccessDrawer(): bool
    {
        return (self::setting()?->show_quick_access_drawer ?? 'Show') !== 'Hide';
    }

    public static function siteName(): string
    {
        $name = trim((string) (self::setting()?->site_name ?? ''));

        return $name !== '' ? $name : 'BrokersCourt';
    }

    public static function siteTagline(): string
    {
        $tagline = trim((string) (self::setting()?->site_tagline ?? ''));

        return $tagline !== ''
            ? $tagline
            : 'Independent broker reviews, comparisons, and trading education — researched by our editorial team.';
    }

    public static function contactPhone(): string
    {
        return trim((string) (self::setting()?->contact_phone ?? '+44 7577 309951'));
    }

    public static function defaultMetaDescription(): string
    {
        $description = trim((string) (self::setting()?->default_meta_description ?? ''));

        return $description !== ''
            ? $description
            : 'BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.';
    }

    public static function logoUrl(): string
    {
        return self::uploadUrl(self::setting()?->logo, 'logo.png');
    }

    public static function faviconUrl(): string
    {
        return self::uploadUrl(self::setting()?->favicon, 'favicon.png');
    }

    public static function uploadUrl(?string $filename, string $fallback): string
    {
        $filename = trim((string) $filename);
        if ($filename === '' || str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
            $filename = $fallback;
        }

        $relative = 'uploads/' . ltrim($filename, '/');
        $absolute = public_path($relative);
        $version = is_file($absolute) ? (string) filemtime($absolute) : (string) (self::setting()?->updated_at?->timestamp ?? time());

        return asset($relative) . '?v=' . $version;
    }

    public static function ogImageUrl(?string $override = null): string
    {
        $override = trim((string) $override);

        if ($override === '') {
            return self::logoUrl();
        }

        if (str_starts_with($override, 'http://') || str_starts_with($override, 'https://')) {
            return $override;
        }

        return asset(ltrim($override, '/'));
    }

    public static function footerCopyright(): ?string
    {
        $text = trim((string) (self::setting()?->footer_copyright ?? ''));

        return $text !== '' ? $text : null;
    }

    public static function normalizeHex(?string $value, string $fallback): string
    {
        $value = strtoupper(trim((string) $value));

        if ($value === '') {
            return strtoupper($fallback);
        }

        if ($value[0] !== '#') {
            $value = '#' . $value;
        }

        return preg_match('/^#[0-9A-F]{6}$/', $value) ? $value : strtoupper($fallback);
    }

    private static function shade(string $hex, int $percent): string
    {
        $hex = ltrim(self::normalizeHex($hex, self::DEFAULT_PRIMARY), '#');
        $rgb = [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];

        foreach ($rgb as $index => $channel) {
            if ($percent < 0) {
                $rgb[$index] = max(0, $channel + (int) round($channel * ($percent / 100)));
            } else {
                $rgb[$index] = min(255, $channel + (int) round((255 - $channel) * ($percent / 100)));
            }
        }

        return sprintf('#%02X%02X%02X', $rgb[0], $rgb[1], $rgb[2]);
    }

    private static function toRgbString(string $hex): string
    {
        $hex = ltrim(self::normalizeHex($hex, self::DEFAULT_PRIMARY), '#');

        return sprintf(
            '%d,%d,%d',
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        );
    }
}
