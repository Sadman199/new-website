<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\Tag;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class AuthorPostController extends Controller
{
    public function show()
    {
        $posts = Post::with('rSubCategory.rCategory', 'rLanguage')
            ->where('author_id', Auth::guard('author')->id())
            ->get();

        return view('author.post_show', compact('posts'));
    }

    public function create()
    {
        $sub_categories = SubCategory::with('rCategory')->get();

        return view('author.post_create', compact('sub_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_title' => 'required',
            'post_detail' => 'required',
            'post_photo' => 'required|image|mimes:jpg,jpeg,png,gif,webp',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        $final_name = 'post_photo_' . time() . '.' . $request->file('post_photo')->extension();
        $request->file('post_photo')->move(public_path('uploads/'), $final_name);

        $author = Auth::guard('author')->user();

        $post = new Post();
        $post->sub_category_id = $request->sub_category_id;
        $post->post_title = $request->post_title;
        $post->slug = $this->uniqueSlug($request->post_title);
        $post->post_detail = $request->post_detail;
        $post->post_photo = $final_name;
        $post->visitors = 1;
        $post->author_id = $author->id;
        $post->admin_id = 0;
        if ($author->can_write) {
            $post->written_by_author_id = $author->id;
        }
        $post->is_share = $request->is_share;
        $post->is_comment = $request->is_comment;
        $post->language_id = $request->language_id;
        $post->save();

        $this->syncNewTags($post->id, $request->tags);

        if ((int) $request->subscriber_send_option === 1) {
            $this->notifySubscribers($post);
        }

        return redirect()->route('author_post_show')->with('success', 'Data is added successfully.');
    }

    public function edit($id)
    {
        $post_single = $this->ownedPost((int) $id);
        $sub_categories = SubCategory::with('rCategory')->get();
        $existing_tags = Tag::where('post_id', $post_single->id)->get();

        return view('author.post_edit', compact('post_single', 'sub_categories', 'existing_tags'));
    }

    public function update(Request $request, $id)
    {
        $post = $this->ownedPost((int) $id);

        $request->validate([
            'post_title' => 'required',
            'post_detail' => 'required',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        if ($request->hasFile('post_photo')) {
            $request->validate([
                'post_photo' => 'image|mimes:jpg,jpeg,png,gif,webp',
            ]);

            $this->deletePhoto($post->post_photo);

            $final_name = 'post_photo_' . time() . '.' . $request->file('post_photo')->extension();
            $request->file('post_photo')->move(public_path('uploads/'), $final_name);
            $post->post_photo = $final_name;
        }

        $post->sub_category_id = $request->sub_category_id;
        $post->post_title = $request->post_title;
        $post->post_detail = $request->post_detail;
        $post->is_share = $request->is_share;
        $post->is_comment = $request->is_comment;
        $post->language_id = $request->language_id;
        $post->save();

        $this->syncNewTags($post->id, $request->tags);

        return redirect()->route('author_post_show')->with('success', 'Data is updated successfully.');
    }

    public function delete_tag($id, $id1)
    {
        $post = $this->ownedPost((int) $id1);
        $tag = Tag::where('id', $id)->where('post_id', $post->id)->first();
        abort_unless($tag, 404);

        $tag->delete();

        return redirect()->route('author_post_edit', $post->id)->with('success', 'Data is deleted successfully.');
    }

    public function delete($id)
    {
        $post = $this->ownedPost((int) $id);

        $this->deletePhoto($post->post_photo);
        Tag::where('post_id', $post->id)->delete();
        $post->delete();

        return redirect()->route('author_post_show')->with('success', 'Data is deleted successfully.');
    }

    protected function ownedPost(int $id): Post
    {
        $post = Post::where('id', $id)
            ->where('author_id', Auth::guard('author')->id())
            ->first();

        abort_unless($post, 404);

        return $post;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 2;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected function syncNewTags(int $postId, ?string $tags): void
    {
        if ($tags === null || trim($tags) === '') {
            return;
        }

        $names = array_values(array_unique(array_filter(array_map('trim', explode(',', $tags)))));

        foreach ($names as $name) {
            $exists = Tag::where('post_id', $postId)->where('tag_name', $name)->exists();
            if ($exists) {
                continue;
            }

            $tag = new Tag();
            $tag->post_id = $postId;
            $tag->tag_name = $name;
            $tag->save();
        }
    }

    protected function deletePhoto(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = public_path('uploads/' . $filename);
        if (is_file($path)) {
            unlink($path);
        }
    }

    protected function notifySubscribers(Post $post): void
    {
        $subcategory = SubCategory::find($post->sub_category_id);
        if (! $subcategory || ! $post->slug) {
            return;
        }

        $subject = 'A new post is published';
        $message = 'Hi, A new post is published into our website. Please go to see that post:<br>';
        $message .= '<a target="_blank" href="' . route('news_detail', [$subcategory->slug, $post->slug]) . '">';
        $message .= e($post->post_title);
        $message .= '</a>';

        $subscribers = Subscriber::where('status', 'Active')->get();
        foreach ($subscribers as $row) {
            try {
                Mail::to($row->email)->send(new Websitemail($subject, $message));
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
