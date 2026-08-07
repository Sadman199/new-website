<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Support\GoogleOAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        if (! GoogleOAuth::supportsRedirectFlow()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in is not fully configured. Add Google Client ID and Secret in Admin → Settings or your .env file.');
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $query = http_build_query([
            'client_id' => GoogleOAuth::clientId(),
            'redirect_uri' => GoogleOAuth::redirectUri(),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    public function callback(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        if (! GoogleOAuth::supportsRedirectFlow()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in is not fully configured. Add Google Client ID and Secret in Admin → Settings or your .env file.');
        }

        if ($request->has('error')) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in was cancelled.');
        }

        $expectedState = $request->session()->pull('google_oauth_state');
        if (! $expectedState || ! hash_equals($expectedState, (string) $request->query('state'))) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. Please try again.');
        }

        $code = $request->query('code');
        if (! $code) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. No authorization code received.');
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => GoogleOAuth::clientId(),
            'client_secret' => GoogleOAuth::clientSecret(),
            'redirect_uri' => GoogleOAuth::redirectUri(),
            'grant_type' => 'authorization_code',
        ]);

        if (! $tokenResponse->successful()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed while exchanging credentials.');
        }

        $accessToken = $tokenResponse->json('access_token');
        if (! $accessToken) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. Access token missing.');
        }

        $profileResponse = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v2/userinfo');

        if (! $profileResponse->successful()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed while fetching your profile.');
        }

        return $this->authenticateFromProfile($request, $profileResponse->json());
    }

    public function credential(Request $request)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.profile');
        }

        if (! GoogleOAuth::isConfigured()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in is not configured. Add your Google Client ID in Admin → Settings or .env.');
        }

        $request->validate([
            'credential' => 'required|string',
        ]);

        $tokenResponse = Http::get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->credential,
        ]);

        if (! $tokenResponse->successful()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. Invalid credential.');
        }

        $payload = $tokenResponse->json();

        if (($payload['aud'] ?? null) !== GoogleOAuth::clientId()) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. Token audience mismatch.');
        }

        if (($payload['email_verified'] ?? 'false') !== 'true' && ($payload['email_verified'] ?? false) !== true) {
            return redirect()->route('user.login')
                ->with('error', 'Google sign-in failed. Email is not verified.');
        }

        $profile = [
            'id' => $payload['sub'] ?? null,
            'email' => $payload['email'] ?? null,
            'name' => $payload['name'] ?? ($payload['given_name'] ?? 'Google User'),
            'picture' => $payload['picture'] ?? null,
        ];

        return $this->authenticateFromProfile($request, $profile);
    }

    private function authenticateFromProfile(Request $request, array $profile)
    {
        $googleId = $profile['id'] ?? null;
        $email = $profile['email'] ?? null;
        $name = $profile['name'] ?? ($profile['given_name'] ?? 'Google User');
        $avatar = $profile['picture'] ?? null;

        if (! $googleId || ! $email) {
            return redirect()->route('user.login')
                ->with('error', 'Google did not provide the required account information.');
        }

        $user = User::query()->where('google_id', $googleId)->first();
        $isNewUser = false;

        if (! $user) {
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                if ($user->google_id && $user->google_id !== $googleId) {
                    return redirect()->route('user.login')
                        ->with('error', 'This email is already linked to a different Google account.');
                }

                $user->google_id = $googleId;
            } else {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => null,
                ]);

                $user->forceFill([
                    'is_verified' => false,
                    'status' => 'active',
                    'email_verified_at' => now(),
                ])->save();

                $isNewUser = true;
                ActivityLog::record('registered', 'Created account via Google', $user->id);
            }
        }

        if ($user->status === 'banned') {
            return redirect()->route('user.login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        if ($avatar && ! $user->avatar) {
            $user->avatar = $avatar;
        }

        if (! $user->name && $name) {
            $user->name = $name;
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        ActivityLog::record('login', 'Logged in via Google', $user->id);

        return redirect()->intended(route('user.profile'))
            ->with('success', $isNewUser
                ? 'Welcome to BrokersCourt! Your Google account has been connected.'
                : 'Welcome back, ' . $user->name . '!');
    }
}
