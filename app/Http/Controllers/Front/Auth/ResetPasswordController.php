<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showForm(Request $request, string $token)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        Helpers::read_json();

        return view('front.auth.reset-password', [
            'token' => $token,
            'email' => old('email', $request->query('email', '')),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::query()->where('email', $request->email)->first();
        if ($user && $user->status === 'banned') {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                ActivityLog::record('password_reset', 'Reset password via email link', $user->id);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('user.login')
                ->with('success', 'Your password has been reset. You can log in now.');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', __($status));
    }
}
