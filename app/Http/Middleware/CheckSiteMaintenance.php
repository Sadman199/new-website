<?php

namespace App\Http\Middleware;

use App\Support\SiteTheme;
use Closure;
use Illuminate\Http\Request;

class CheckSiteMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (! SiteTheme::isMaintenanceMode()) {
            return $next($request);
        }

        if ($request->is('admin*') || $request->is('author*')) {
            return $next($request);
        }

        if ($request->is('login') || $request->is('register') || $request->is('auth/*')) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => SiteTheme::maintenanceMessage(),
            ], 503);
        }

        return response()->view('front.maintenance', [
            'message' => SiteTheme::maintenanceMessage(),
            'siteName' => SiteTheme::siteName(),
        ], 503);
    }
}
