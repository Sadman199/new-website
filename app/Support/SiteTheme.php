<?php

namespace App\Support;

use App\Models\Setting;

class SiteTheme
{
    public const DEFAULT_PRIMARY = '#007AAD';
    public const DEFAULT_DARK = '#0C1D32';
    public const DEFAULT_LIGHT = '#D9E2E9';

    public static function setting(): ?Setting
    {
        static $cached = null;

        if ($cached === null) {
            $cached = Setting::query()->find(1);
        }

        return $cached;
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

        return [
            '--bc-primary' => $primary,
            '--bc-primary-dark' => self::primaryDark(),
            '--bc-primary-light' => self::primaryLight(),
            '--bc-dark' => $dark,
            '--bc-light' => $light,
            '--bc-primary-rgb' => self::toRgbString($primary),
            '--bc-dark-rgb' => self::toRgbString($dark),
            '--bc-light-rgb' => self::toRgbString($light),
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
