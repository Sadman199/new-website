<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Helper\Helpers;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        Helpers::read_json();

        $user = Auth::guard('web')->user();
        $reviews = $user->reviews()->with('broker')->latest()->get();
        $activities = $user->activities()->take(15)->get();

        $stats = [
            'reviews_total' => $reviews->count(),
            'reviews_approved' => $reviews->where('status', 1)->count(),
            'reviews_pending' => $reviews->where('status', 0)->count(),
        ];

        return view('front.profile.show', compact('user', 'reviews', 'activities', 'stats'));
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

            // Remove previous avatar if it lived in our uploads folder.
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

    public function changePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

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

        return redirect()->route('user.profile')->with('success', 'Your password has been changed.');
    }
}
