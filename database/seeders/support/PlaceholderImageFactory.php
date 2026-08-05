<?php

namespace Database\Seeders\Support;

class PlaceholderImageFactory
{
    public static function ensureDirectories(): void
    {
        foreach (['uploads/placeholders', 'uploads/logos', 'uploads/forex_bonuses', 'uploads/authors'] as $dir) {
            $path = public_path($dir);
            if (! is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }

    public static function brokerLogo(string $slug, string $label, string $color = '#2563eb'): string
    {
        self::ensureDirectories();
        $relative = 'uploads/logos/placeholder-' . $slug . '.svg';
        $path = public_path($relative);

        if (! file_exists($path)) {
            $initials = self::initials($label);
            file_put_contents($path, self::svg($initials, $color, 120, 120));
        }

        return $relative;
    }

    public static function postPhoto(string $slug, string $label): string
    {
        self::ensureDirectories();
        $filename = 'placeholder-post-' . $slug . '.svg';
        $path = public_path('uploads/placeholders/' . $filename);

        if (! file_exists($path)) {
            file_put_contents($path, self::svg($label, '#0f766e', 800, 450, 28));
        }

        return $filename;
    }

    public static function bonusImage(string $slug, string $label): string
    {
        self::ensureDirectories();
        $relative = 'uploads/forex_bonuses/placeholder-' . $slug . '.svg';
        $path = public_path($relative);

        if (! file_exists($path)) {
            file_put_contents($path, self::svg($label, '#b45309', 640, 360, 24));
        }

        return $relative;
    }

    public static function authorPhoto(string $slug, string $name): string
    {
        self::ensureDirectories();
        $filename = 'placeholder-' . $slug . '.svg';
        $path = public_path('uploads/authors/' . $filename);

        if (! file_exists($path)) {
            file_put_contents($path, self::svg(self::initials($name), '#4f46e5', 200, 200));
        }

        return 'authors/' . $filename;
    }

    public static function brokerBanner(string $slug, string $label, int $variant = 1): string
    {
        self::ensureDirectories();
        $relative = 'uploads/placeholders/banner-' . $slug . '-' . $variant . '.svg';
        $path = public_path($relative);

        if (! file_exists($path)) {
            $color = $variant === 1 ? '#1e3a5f' : '#134e4a';
            file_put_contents($path, self::svg($label, $color, 1200, 400, 32));
        }

        return $relative;
    }

    protected static function initials(string $label): string
    {
        $words = preg_split('/\s+/', trim($label)) ?: [];
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials !== '' ? $initials : 'BC';
    }

    protected static function svg(string $text, string $color, int $width, int $height, int $fontSize = 36): string
    {
        $safe = htmlspecialchars($text, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $fontSize = min($fontSize, (int) ($width / max(4, strlen($text))));

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
  <rect width="100%" height="100%" fill="{$color}"/>
  <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="Arial, sans-serif" font-size="{$fontSize}" font-weight="700">{$safe}</text>
</svg>
SVG;
    }
}
