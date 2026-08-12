<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AdminNavService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSearchController extends AdminController
{
    public function __invoke(Request $request, AdminNavService $nav): JsonResponse
    {
        return response()->json([
            'results' => $nav->search((string) $request->query('q', '')),
        ]);
    }
}
