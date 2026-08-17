<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        $redirect = $request->query('redirect');
        if (is_string($redirect) && $redirect !== '' && str_starts_with($redirect, url('/'))) {
            $request->session()->put('url.intended', $redirect);
        }

        Helpers::read_json();
        return view('front.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'country' => 'nullable|string|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'country' => $validated['country'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        // Privileged fields are not mass assignable; set safe defaults explicitly.
        $user->forceFill([
            'is_verified' => false,
            'status' => 'active',
        ])->save();

        Auth::guard('web')->login($user);
        // Rotate session ID without destroying admin/author sessions.
        $request->session()->regenerate();

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        ActivityLog::record('registered', 'Created a new account', $user->id);
        ActivityLog::record('login', 'Logged in', $user->id);

        return redirect()->intended(route('user.profile'))
            ->with('success', 'Welcome to BrokersCourt! Your account is pending verification by our team.');
    }
}
