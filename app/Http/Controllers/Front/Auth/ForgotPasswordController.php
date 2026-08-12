<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        Helpers::read_json();

        return view('front.auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        return back()
            ->with('success', 'If an account exists for that email, we sent a password reset link.');
    }
}
