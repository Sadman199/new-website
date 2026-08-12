<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\ActivityLog;
use App\Services\UserSessionPreferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        private UserSessionPreferenceService $sessionPreferences
    ) {}
    public function showForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        Helpers::read_json();
        return view('front.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::guard('web')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'These credentials do not match our records.');
        }

        // Rotate the session ID without destroying other guards (admin/author).
        $request->session()->regenerate();

        $user = Auth::guard('web')->user();

        if ($user->status === 'banned') {
            Auth::guard('web')->logout();
            $this->safeSessionReset($request);
            return back()->with('error', 'Your account has been suspended. Please contact support.');
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        ActivityLog::record('login', 'Logged in', $user->id);

        $cookie = $this->sessionPreferences->applyPreferredCountry($user);

        $response = redirect()->intended(route('user.profile'))
            ->with('success', 'Welcome back, ' . $user->name . '!');

        return $this->sessionPreferences->attachCookieToResponse($response, $cookie);
    }

    public function logout(Request $request)
    {
        $userId = Auth::guard('web')->id();

        Auth::guard('web')->logout();
        $this->safeSessionReset($request);

        if ($userId) {
            ActivityLog::record('logout', 'Logged out', $userId);
        }

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }

    /**
     * Invalidate the whole session only when no other auth guards are still active.
     * This prevents front-user logout from kicking out an admin (or author) session
     * that shares the same browser cookie.
     */
    private function safeSessionReset(Request $request): void
    {
        $otherGuardsActive = Auth::guard('admin')->check() || Auth::guard('author')->check();

        if ($otherGuardsActive) {
            $request->session()->regenerateToken();
            return;
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
