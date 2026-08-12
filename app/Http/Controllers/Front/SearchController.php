<?php

namespace App\Http\Controllers\Front;

use App\Services\SiteSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends FrontController
{
    public function index(Request $request, SiteSearchService $siteSearch)
    {
        $query = trim((string) $request->query('q', $request->query('query', '')));
        $type = (string) $request->query('type', 'all');
        $sort = (string) $request->query('sort', 'relevance');

        $payload = $siteSearch->search($query, null, $type, $sort);

        return view('front.search.index', [
            'query' => $payload['query'],
            'type' => $payload['type'],
            'sort' => $payload['sort'],
            'groups' => $payload['groups'],
            'total' => $payload['total'],
            'counts' => $payload['counts'],
            'filters' => $payload['filters'],
            'sortOptions' => $payload['sort_options'],
        ]);
    }

    public function suggest(Request $request, SiteSearchService $siteSearch): JsonResponse
    {
        return response()->json(
            $siteSearch->suggest(trim((string) $request->query('q', $request->query('query', ''))))
        );
    }
}
