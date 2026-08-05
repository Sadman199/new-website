<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminForAdminPrefix
{
    /** @var array<int, string> */
    protected array $except = [
        'admin/login',
        'admin/login-submit',
        'admin/forget-password',
        'admin/forget-password-submit',
        'admin/reset-password/*',
        'admin/reset-password-submit',
    ];

    public function handle(Request $request, Closure $next)
    {
        foreach ($this->except as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        if ($request->is('admin', 'admin/*', 'admin/panel', 'admin/panel/*')) {
            if (! Auth::guard('admin')->check()) {
                if ($request->expectsJson()) {
                    abort(401, 'Unauthenticated.');
                }

                return redirect()->guest(route('admin_login'));
            }
        }

        return $next($request);
    }
}
