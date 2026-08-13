<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthorLoginController extends Controller
{
    public function index()
    {
        if (Auth::guard('author')->check()) {
            return redirect()->route('author_home');
        }

        return view('author.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->ensureIsNotRateLimited($request);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('author')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            return redirect()->intended(route('author_home'));
        }

        RateLimiter::hit($this->throttleKey($request), 60);

        throw ValidationException::withMessages([
            'email' => __('The email or password you entered is incorrect.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('author')->logout();

        $otherGuardsActive = Auth::guard('web')->check() || Auth::guard('admin')->check();

        if ($otherGuardsActive) {
            $request->session()->regenerateToken();
        } else {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('author_login')->with('success', 'You have been logged out successfully.');
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('Too many login attempts. Please try again in :seconds seconds.', [
                'seconds' => $seconds,
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        return Str::lower((string) $request->input('email')) . '|author|' . $request->ip();
    }
}
