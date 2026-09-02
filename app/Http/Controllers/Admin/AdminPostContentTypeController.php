<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostContentTypeRequest;
use App\Models\Post;
use App\Models\PostContentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminPostContentTypeController extends Controller
{
    public function index()
    {
        $this->seedIfEmpty();

        $types = PostContentType::query()
            ->withCount('posts')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.posts.content_types', [
            'types' => $types,
            'type' => new PostContentType(['is_active' => true, 'sort_order' => $types->max('sort_order') + 1]),
        ]);
    }

    public function store(PostContentTypeRequest $request)
    {
        try {
            $type = new PostContentType();
            $this->fill($type, $request);
            $type->save();
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not create content type: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_post_content_types_index')
            ->with('success', 'Content type added.');
    }

    public function update(PostContentTypeRequest $request, $id)
    {
        $type = PostContentType::query()->findOrFail($id);

        try {
            $oldSlug = $type->slug;
            $this->fill($type, $request);
            $type->save();

            if ($oldSlug !== $type->slug && Schema::hasColumn('posts', 'content_type')) {
                Post::query()->where('content_type', $oldSlug)->update(['content_type' => $type->slug]);
            }
        } catch (Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not update content type: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_post_content_types_index')
            ->with('success', 'Content type updated.');
    }

    public function destroy($id)
    {
        $type = PostContentType::query()->withCount('posts')->findOrFail($id);

        if ($type->posts_count > 0) {
            return back()->with('error', 'This type is used by '.$type->posts_count.' blog(s). Reassign them first.');
        }

        $type->delete();

        return redirect()
            ->route('admin_post_content_types_index')
            ->with('success', 'Content type deleted.');
    }

    protected function fill(PostContentType $type, Request $request): void
    {
        $type->name = trim((string) $request->input('name'));
        $type->slug = PostContentType::uniqueSlug((string) ($request->input('slug') ?: $type->name), $type->id);
        $type->sort_order = (int) ($request->input('sort_order') ?? 0);
        $type->is_active = $request->boolean('is_active', true);
    }

    protected function seedIfEmpty(): void
    {
        if (! Schema::hasTable('post_content_types') || PostContentType::query()->exists()) {
            return;
        }

        foreach (Post::fallbackContentTypes() as $slug => $name) {
            PostContentType::query()->create([
                'name' => $name,
                'slug' => $slug,
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
