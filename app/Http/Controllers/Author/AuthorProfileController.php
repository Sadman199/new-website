<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthorProfileController extends Controller
{
    public function index()
    {
        return view('author.profile');
    }

    public function profile_submit(Request $request)
    {
        $author_data = Author::findOrFail(Auth::guard('author')->id());

        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('authors', 'email')->ignore($author_data->id),
            ],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required',
                'retype_password' => 'required|same:password',
            ]);
            $author_data->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpg,jpeg,png,gif,webp',
            ]);

            if ($author_data->photo) {
                $path = public_path('uploads/' . $author_data->photo);
                if (is_file($path)) {
                    unlink($path);
                }
            }

            $final_name = 'author_photo_' . time() . '.' . $request->file('photo')->extension();
            $request->file('photo')->move(public_path('uploads/'), $final_name);
            $author_data->photo = $final_name;
        }

        $author_data->name = $request->name;
        $author_data->email = $request->email;
        $author_data->save();

        return redirect()->back()->with('success', 'Profile information is saved successfully.');
    }
}
