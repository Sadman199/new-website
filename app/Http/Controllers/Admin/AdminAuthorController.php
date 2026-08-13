<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\Websitemail;
use Throwable;

class AdminAuthorController extends Controller
{
    public function show()
    {
        $authors = Author::query()
            ->withCount([
                'postsWritten as written_posts_count',
                'postsEdited as edited_posts_count',
                'postsFactChecked as fact_checked_posts_count',
            ])
            ->orderBy('name')
            ->get();

        return view('admin.author_show', compact('authors'));
    }

    public function create()
    {
        return view('admin.author_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors',
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'bio' => 'nullable|string|max:2000',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
        ]);

        $author = new Author();
        $author->fill($request->only(['name', 'email', 'bio', 'twitter_url', 'linkedin_url', 'facebook_url']));
        $author->can_write = $request->boolean('can_write', true);
        $author->can_edit = $request->boolean('can_edit');
        $author->can_fact_check = $request->boolean('can_fact_check');
        $author->password = Hash::make($request->password);
        $author->token = '';

        if ($request->hasFile('photo')) {
            $author->photo = $this->storePhoto($request);
        }

        $author->save();

        $subject = 'Your account is created to the website';
        $message = 'Hi, your account is created successfully and now you can login to our system from the front end login page. Please go to this link: <br><br>';
        $message .= '<a href="' . route('author_login') . '">Click on this link</a>';
        $message .= '<br><br>Please see your password here and after login, change that immediately:<br>';
        $message .= e($request->password);

        try {
            Mail::to($request->email)->send(new Websitemail($subject, $message));
        } catch (Throwable $e) {
            Log::warning('Author welcome email failed to send.', [
                'author_email' => $request->email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin_author_show')->with('success', 'Author account is created successfully.');
    }

    public function edit($id)
    {
        $author_data = Author::findOrFail($id);

        return view('admin.author_edit', compact('author_data'));
    }

    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('authors')->ignore($author->id),
            ],
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'bio' => 'nullable|string|max:2000',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|min:6',
                'retype_password' => 'required|same:password',
            ]);
            $author->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $this->deletePhoto($author->photo);
            $author->photo = $this->storePhoto($request);
        }

        $author->fill($request->only(['name', 'email', 'bio', 'twitter_url', 'linkedin_url', 'facebook_url']));
        $author->can_write = $request->boolean('can_write');
        $author->can_edit = $request->boolean('can_edit');
        $author->can_fact_check = $request->boolean('can_fact_check');
        $author->save();

        return redirect()->route('admin_author_show')->with('success', 'Data is updated successfully.');
    }

    public function delete($id)
    {
        $author = Author::findOrFail($id);

        Post::query()->where('author_id', $author->id)->update(['author_id' => 0]);
        Post::query()->where('written_by_author_id', $author->id)->update(['written_by_author_id' => null]);
        Post::query()->where('edited_by_author_id', $author->id)->update(['edited_by_author_id' => null]);
        Post::query()->where('fact_checked_by_author_id', $author->id)->update(['fact_checked_by_author_id' => null]);

        $this->deletePhoto($author->photo);
        $author->delete();

        return redirect()->route('admin_author_show')->with('success', 'Author is deleted successfully.');
    }

    protected function storePhoto(Request $request): string
    {
        $ext = $request->file('photo')->extension();
        $finalName = 'author_photo_' . time() . '.' . $ext;
        $request->file('photo')->move(public_path('uploads/'), $finalName);

        return $finalName;
    }

    protected function deletePhoto(?string $photo): void
    {
        if (! $photo) {
            return;
        }

        $path = public_path('uploads/' . $photo);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
