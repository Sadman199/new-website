<?php

namespace App\Services;

use App\Models\Broker;
use App\Support\SiteTheme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrokerOgImageService
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    public function publicUrl(Broker $broker): string
    {
        if ($this->cachedPath($broker) && is_file($this->cachedPath($broker))) {
            return asset('uploads/og/'.$this->fileName($broker)).'?v='.filemtime($this->cachedPath($broker));
        }

        if ($this->canGenerate()) {
            return route('og.broker', ['slug' => $broker->listingSlug()]);
        }

        return $this->fallbackUrl($broker);
    }

    public function ensureGenerated(Broker $broker): ?string
    {
        $path = $this->cachedPath($broker);
        if (is_file($path) && filemtime($path) >= $this->cacheFingerprint($broker)) {
            return $path;
        }

        if (! $this->canGenerate()) {
            return null;
        }

        $binary = $this->renderPng($broker);
        if ($binary === null) {
            return null;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $binary);

        return $path;
    }

    public function canGenerate(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatetruecolor');
    }

    public function fallbackUrl(Broker $broker): string
    {
        $logo = trim((string) ($broker->logo ?? ''));
        if ($logo !== '') {
            return SiteTheme::ogImageUrl($logo);
        }

        return SiteTheme::logoUrl();
    }

    private function fileName(Broker $broker): string
    {
        $slug = Str::slug($broker->listingSlug() ?: ('broker-'.$broker->id));

        return $slug.'-og.png';
    }

    private function cachedPath(Broker $broker): string
    {
        return public_path('uploads/og/'.$this->fileName($broker));
    }

    private function cacheFingerprint(Broker $broker): int
    {
        $updated = optional($broker->updated_at)->getTimestamp() ?? 0;
        $rating = (string) ($broker->rating ?? '');
        $name = (string) $broker->name;

        return max($updated, 1) + crc32($name.'|'.$rating.'|'.(string) $broker->logo);
    }

    private function renderPng(Broker $broker): ?string
    {
        $width = self::WIDTH;
        $height = self::HEIGHT;
        $img = imagecreatetruecolor($width, $height);
        if ($img === false) {
            return null;
        }

        imagesavealpha($img, true);

        $midnight = imagecolorallocate($img, 12, 29, 50);
        $ocean = imagecolorallocate($img, 0, 122, 173);
        $ice = imagecolorallocate($img, 217, 226, 233);
        $white = imagecolorallocate($img, 255, 251, 252);
        $muted = imagecolorallocate($img, 148, 163, 184);
        $gold = imagecolorallocate($img, 251, 191, 36);
        $danger = imagecolorallocate($img, 248, 113, 113);

        imagefilledrectangle($img, 0, 0, $width, $height, $midnight);

        // Accent band
        imagefilledrectangle($img, 0, 0, $width, 12, $ocean);
        imagefilledrectangle($img, 0, $height - 18, $width, $height, $ocean);

        // Soft glow orb
        $glow = imagecolorallocatealpha($img, 0, 122, 173, 100);
        imagefilledellipse($img, 980, 180, 520, 520, $glow);

        $isScam = (bool) $broker->is_scam;
        $eyebrow = $isScam ? 'SCAM WARNING' : 'BROKER REVIEW';
        $title = Str::limit((string) $broker->name, 42, '…');
        $subtitle = $isScam
            ? 'Flagged by BrokersCourt safety review'
            : 'Independent review · fees, safety & platforms';

        $font = $this->fontPath();
        $this->drawText($img, $eyebrow, 64, 72, $isScam ? $danger : $ocean, 22, $font, true);
        $this->drawText($img, $title, 64, 170, $white, 54, $font, true);
        $this->drawText($img, $subtitle, 64, 240, $muted, 24, $font, false);

        $rating = $broker->rating !== null ? number_format((float) $broker->rating, 1) : null;
        $trust = $broker->trust_score !== null ? (int) $broker->trust_score : null;

        $metaY = 340;
        if ($rating !== null) {
            $this->drawText($img, $rating.'/5', 64, $metaY, $gold, 48, $font, true);
            $this->drawText($img, 'Editor rating', 64, $metaY + 48, $muted, 20, $font, false);
        }
        if ($trust !== null) {
            $this->drawText($img, (string) $trust, 280, $metaY, $ice, 48, $font, true);
            $this->drawText($img, 'Trust score', 280, $metaY + 48, $muted, 20, $font, false);
        }

        $this->drawText($img, SiteTheme::siteName(), 64, 560, $ice, 26, $font, true);
        $this->drawText($img, 'brokerscourt.com', 64, 598, $muted, 20, $font, false);

        $this->pasteLogo($img, $broker, 860, 280, 240);

        ob_start();
        imagepng($img, null, 6);
        $binary = ob_get_clean();
        imagedestroy($img);

        return is_string($binary) ? $binary : null;
    }

    private function pasteLogo($img, Broker $broker, int $x, int $y, int $box): void
    {
        $logoPath = $this->resolveLogoPath($broker);
        if ($logoPath === null) {
            $panel = imagecolorallocatealpha($img, 19, 40, 67, 40);
            imagefilledrectangle($img, $x, $y, $x + $box, $y + $box, $panel);
            $initial = strtoupper(substr((string) $broker->name, 0, 1));
            $this->drawText($img, $initial, $x + 85, $y + 150, imagecolorallocate($img, 255, 255, 255), 72, $this->fontPath(), true);

            return;
        }

        $logo = @$this->loadImage($logoPath);
        if ($logo === false) {
            return;
        }

        $lw = imagesx($logo);
        $lh = imagesy($logo);
        if ($lw < 1 || $lh < 1) {
            imagedestroy($logo);

            return;
        }

        $scale = min(($box - 40) / $lw, ($box - 40) / $lh);
        $tw = (int) max(1, round($lw * $scale));
        $th = (int) max(1, round($lh * $scale));
        $dx = $x + (int) (($box - $tw) / 2);
        $dy = $y + (int) (($box - $th) / 2);

        $panel = imagecolorallocatealpha($img, 255, 255, 255, 20);
        imagefilledrectangle($img, $x, $y, $x + $box, $y + $box, $panel);
        imagecopyresampled($img, $logo, $dx, $dy, 0, 0, $tw, $th, $lw, $lh);
        imagedestroy($logo);
    }

    private function resolveLogoPath(Broker $broker): ?string
    {
        $logo = trim((string) ($broker->logo ?? ''));
        if ($logo === '') {
            return null;
        }

        $relative = ltrim(parse_url($logo, PHP_URL_PATH) ?: $logo, '/');
        $candidates = [
            public_path($relative),
            public_path(ltrim($logo, '/')),
            base_path($relative),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /** @return resource|false */
    private function loadImage(string $path)
    {
        $info = @getimagesize($path);
        if (! is_array($info)) {
            return false;
        }

        return match ($info[2] ?? null) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($path),
            default => false,
        };
    }

    private function fontPath(): ?string
    {
        $candidates = [
            resource_path('fonts/DejaVuSans.ttf'),
            'C:\\Windows\\Fonts\\arial.ttf',
            'C:\\Windows\\Fonts\\segoeui.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function drawText($img, string $text, int $x, int $y, $color, int $size, ?string $font, bool $bold): void
    {
        if ($font && function_exists('imagettftext')) {
            imagettftext($img, $size * 0.75, 0, $x, $y, $color, $font, $text);
            if ($bold) {
                imagettftext($img, $size * 0.75, 0, $x + 1, $y, $color, $font, $text);
            }

            return;
        }

        $builtIn = $bold ? 5 : 4;
        imagestring($img, $builtIn, $x, max(0, $y - 16), $text, $color);
    }
}
