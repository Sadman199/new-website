<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\Admin;
use App\Mail\Websitemail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin_home');
        }

        return view('admin.login');
    }

    public function forget_password()
    {
        return view('admin.forget_password');
    }

    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin_data = Admin::where('email', $request->email)->first();
        if (! $admin_data) {
            return redirect()->back()->with('error', 'Email address not found!');
        }

        $token = hash('sha256', time());

        $admin_data->token = $token;
        $admin_data->update();

        $reset_link = url('admin/reset-password/' . $token . '/' . $request->email);
        $subject = 'Reset Password';
        $message = 'Please click on the following link: <br>';
        $message .= '<a href="' . $reset_link . '">Click here</a>';

        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return redirect()->route('admin_login')->with('success', 'Please check your email and follow the steps there');
    }

    public function login_submit(AdminLoginRequest $request)
    {
        $this->ensureIsNotRateLimited($request);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->rememberMe())) {
            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            return redirect()->intended(route('admin_home'));
        }

        RateLimiter::hit($this->throttleKey($request), 60);

        throw ValidationException::withMessages([
            'email' => __('The email or password you entered is incorrect.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $otherGuardsActive = Auth::guard('web')->check() || Auth::guard('author')->check();

        if ($otherGuardsActive) {
            $request->session()->regenerateToken();
        } else {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin_login')->with('success', 'You have been logged out successfully.');
    }

    public function reset_password($token, $email)
    {
        $admin_data = Admin::where('token', $token)->where('email', $email)->first();
        if (! $admin_data) {
            return redirect()->route('admin_login');
        }

        return view('admin.reset_password', compact('token', 'email'));
    }

    public function reset_password_submit(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
        ]);

        $admin_data = Admin::where('token', $request->token)->where('email', $request->email)->first();

        if (! $admin_data) {
            return redirect()->route('admin_login')->with('error', 'Invalid or expired reset link.');
        }

        $admin_data->password = Hash::make($request->password);
        $admin_data->token = '';
        $admin_data->update();

        return redirect()->route('admin_login')->with('success', 'Password is reset successfully');
    }

    protected function ensureIsNotRateLimited(AdminLoginRequest $request): void
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

    protected function throttleKey(AdminLoginRequest $request): string
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
