<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount('reviews');

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $filter = $request->get('filter');
        if ($filter === 'verified') {
            $query->where('is_verified', true);
        } elseif ($filter === 'unverified') {
            $query->where('is_verified', false);
        } elseif ($filter === 'banned') {
            $query->where('status', 'banned');
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'total' => User::count(),
            'verified' => User::where('is_verified', true)->count(),
            'unverified' => User::where('is_verified', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'counts', 'search', 'filter'));
    }

    public function show($id)
    {
        $user = User::withCount('reviews')->findOrFail($id);
        $reviews = $user->reviews()->with('broker')->latest()->get();
        $activities = $user->activities()->take(50)->get();

        return view('admin.users.show', compact('user', 'reviews', 'activities'));
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = true;
        $user->verified_at = now();
        $user->save();

        ActivityLog::record('verified_by_admin', 'Account verified by admin', $user->id);

        return redirect()->back()->with('success', $user->name . ' has been verified.');
    }

    public function unverify($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = false;
        $user->verified_at = null;
        $user->save();

        return redirect()->back()->with('success', $user->name . ' verification has been removed.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'banned' ? 'active' : 'banned';
        $user->save();

        $msg = $user->status === 'banned'
            ? $user->name . ' has been suspended.'
            : $user->name . ' has been re-activated.';

        return redirect()->back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;
        $user->delete();

        return redirect()->route('admin_users_index')->with('success', $name . ' has been deleted.');
    }
}
