<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Services\PageContextService;
use App\Services\RecommendedBrokersService;

abstract class FrontController extends Controller
{
    protected function bootFront(): void
    {
        Helpers::read_json();
    }

    protected function pageContext(): PageContextService
    {
        return app(PageContextService::class);
    }

    protected function recommendedBrokers(int $limit = 5)
    {
        return app(RecommendedBrokersService::class)->forSidebar($limit);
    }
}
