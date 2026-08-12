<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CmsPageService;
use App\Support\CmsSectionRegistry;

class CmsPageController extends Controller
{
    public function __construct(protected CmsPageService $cmsPages)
    {
    }

    public function show(string $slug)
    {
        if (in_array($slug, CmsSectionRegistry::reservedSlugs(), true)) {
            abort(404);
        }

        $page = $this->cmsPages->findPublishedBySlug($slug);

        if (! $page) {
            abort(404);
        }

        $templateView = match ($page->template) {
            'landing' => 'front.cms.templates.landing',
            'legal' => 'front.cms.templates.legal',
            default => 'front.cms.templates.default',
        };

        return response()
            ->view($templateView, compact('page'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache');
    }
}
