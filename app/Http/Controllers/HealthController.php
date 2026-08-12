<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $payload = [
            'status' => 'ok',
            'app' => config('app.env'),
            'database' => 'ok',
            'checked_at' => now()->toIso8601String(),
        ];

        try {
            DB::connection()->select('select 1 as ok');
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'app' => config('app.env'),
                'database' => 'failed',
                'message' => app()->environment('local') ? $e->getMessage() : 'Database unavailable',
                'checked_at' => now()->toIso8601String(),
            ], 503);
        }

        return response()->json($payload);
    }
}
