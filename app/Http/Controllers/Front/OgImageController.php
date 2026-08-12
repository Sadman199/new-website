<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Services\BrokerOgImageService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class OgImageController extends Controller
{
    public function broker(string $slug, BrokerOgImageService $ogImages): Response
    {
        $broker = Broker::query()
            ->where('slug', $slug)
            ->orWhereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [strtolower($slug)])
            ->firstOrFail();

        $path = $ogImages->ensureGenerated($broker);
        if ($path && is_file($path)) {
            return $this->pngResponse($path);
        }

        return redirect()->away($ogImages->fallbackUrl($broker), 302);
    }

    private function pngResponse(string $path): BinaryFileResponse
    {
        return response()->file($path, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
