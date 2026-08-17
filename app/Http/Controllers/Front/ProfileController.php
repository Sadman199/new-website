<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\ActivityLog;
use App\Services\CountryBrokersService;
use App\Services\UserNotificationService;
use App\Services\UserSavedBrokerService;
use App\Services\UserSessionPreferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct(
        private UserNotificationService $notifications,
        private UserSavedBrokerService $savedBrokers,
        private UserSessionPreferenceService $sessionPreferences,
        private CountryBrokersService $countryBrokers,
    ) {}

    public function show()
    {
        Helpers::read_json();

        $user = Auth::guard('web')->user();
        $reviews = $user->reviews()->roots()->with('broker')->latest()->get();
        $activities = $user->activities()->take(8)->get();
        $notifications = $this->notifications->recent($user->id, 5);
        $unreadNotifications = $this->notifications->unreadCount($user->id);
        $savedBrokerCards = $this->savedBrokers->cardsForUser($user);
        $brokerReports = $user->brokerReports()->with('broker')->take(20)->get();

        $stats = [
            'reviews_total' => $reviews->count(),
            'reviews_approved' => $reviews->where('status', 1)->count(),
            'reviews_pending' => $reviews->where('status', 0)->count(),
            'saved_brokers' => $savedBrokerCards->count(),
        ];

        $countryOptions = collect($this->countryBrokers->countriesForSelector())
            ->map(fn (array $country, string $slug) => [
                'slug' => $slug,
                'name' => $country['name'],
                'flag' => $country['flag'] ?? '🌍',
            ])
            ->values();

        $preferredCountrySlug = $user->preferred_country_slug
            ?? session('preferred_country')
            ?? request()->cookie('preferred_country')
            ?? 'global';

        return view('front.profile.show', compact(
            'user',
            'reviews',
            'activities',
            'stats',
            'notifications',
            'unreadNotifications',
            'savedBrokerCards',
            'brokerReports',
            'countryOptions',
            'preferredCountrySlug',
        ));
    }

    public function edit()
    {
        Helpers::read_json();
        $user = Auth::guard('web')->user();
        return view('front.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->country = $validated['country'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        if ($request->hasFile('avatar')) {
            $dir = public_path('uploads/avatars');
            if (! file_exists($dir)) {
                mkdir($dir, 0775, true);
            }

            if ($user->avatar && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move($dir, $filename);
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        $user->save();

        ActivityLog::record('profile_updated', 'Updated profile details', $user->id);

        return redirect()->route('user.profile')->with('success', 'Your profile has been updated.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'preferred_country_slug' => ['nullable', 'string', Rule::in($this->countryBrokers->selectableCountrySlugs())],
        ]);

        $cookie = $this->sessionPreferences->persistPreferredCountry(
            $user,
            $validated['preferred_country_slug'] ?? null
        );

        ActivityLog::record('preferences_updated', 'Updated account preferences', $user->id);

        $response = redirect()
            ->route('user.profile', ['tab' => 'settings'])
            ->with('success', 'Your preferences have been saved.');

        return $this->sessionPreferences->attachCookieToResponse($response, $cookie);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (! $user->hasPassword()) {
            return back()->with('error', 'Use the set-password form for Google sign-in accounts.');
        }

        $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Your current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::record('password_changed', 'Changed account password', $user->id);

        return redirect()->route('user.profile', ['tab' => 'settings'])->with('success', 'Your password has been changed.');
    }

    public function setPassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        if ($user->hasPassword()) {
            return back()->with('error', 'Your account already has a password. Use the change-password form.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLog::record('password_set', 'Set account password', $user->id);

        return redirect()->route('user.profile', ['tab' => 'settings'])->with('success', 'Password set. You can now sign in with email and password.');
    }
}
