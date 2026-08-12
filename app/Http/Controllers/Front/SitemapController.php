<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(SitemapService $sitemap): Response
    {
        return response($sitemap->toXml(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function robots(): Response
    {
        $sitemap = url('/sitemap.xml');
        $body = <<<TXT
User-agent: *
Allow: /

Disallow: /admin
Disallow: /admin/
Disallow: /login
Disallow: /register
Disallow: /logout
Disallow: /profile
Disallow: /profile/
Disallow: /author/
Disallow: /search
Disallow: /search/
Disallow: /broker-promos/load-more
Disallow: /broker-live-search
Disallow: /search/suggest
Disallow: /home/recommended-brokers
Disallow: /broker-match/recommend
Disallow: /country/switch

Sitemap: {$sitemap}

TXT;

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
