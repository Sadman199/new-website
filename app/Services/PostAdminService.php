<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostContentType;
use App\Models\Tag;
use App\Services\Admin\PublicUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PostAdminService
{
    public function __construct(protected PublicUploadService $uploads)
    {
    }

    public function save(Post $post, Request $request): Post
    {
        $creating = ! $post->exists;

        $post->post_title = $request->input('post_title');
        $post->post_detail = $request->input('post_detail');
        $post->sub_category_id = $request->input('sub_category_id');
        $post->language_id = $request->input('language_id');
        $post->author = $request->input('author');
        $post->meta_title = $request->input('meta_title');
        $post->meta_description = $request->input('meta_description');
        $post->meta_keywords = $request->input('meta_keywords');

        $this->fillIfPresent($post, $request, [
            'status', 'focus_keyword', 'canonical_url',
            'og_title', 'og_description', 'schema_type', 'image_alt', 'image_caption',
        ]);

        if (Schema::hasColumn('posts', 'excerpt')) {
            $excerpt = trim(preg_replace('/\s+/', ' ', strip_tags((string) $request->input('excerpt'))) ?? '');
            $post->excerpt = $excerpt !== '' ? mb_substr($excerpt, 0, 500) : null;
        }

        if (Schema::hasColumn('posts', 'content_type')) {
            $post->content_type = $this->ensureContentType((string) $request->input('content_type', 'article'));
        }

        $post->is_share = $request->boolean('is_share');
        if ($request->exists('is_comment')) {
            $post->is_comment = $request->boolean('is_comment');
        } elseif ($creating) {
            $post->is_comment = true;
        }

        foreach ([
            'is_featured', 'is_breaking', 'featured_homepage', 'featured_blog',
            'is_editors_pick', 'is_popular', 'show_author', 'show_related_posts',
            'robots_index', 'robots_follow',
        ] as $flag) {
            if (Schema::hasColumn('posts', $flag)) {
                $post->{$flag} = $request->boolean($flag);
            }
        }

        $this->applyDates($post, $request);

        $slugSource = $request->input('slug') ?: $request->input('post_title');
        $post->slug = $this->uniqueSlug((string) $slugSource, $post->id, $request->input('post_title'));

        if (Schema::hasColumn('posts', 'reading_time')) {
            $post->reading_time = Post::estimateReadingTime($post->post_detail);
        }

        if ($creating) {
            $post->visitors = 1;
            $post->admin_id = $post->admin_id ?: (Auth::guard('admin')->id() ?: 0);
            $post->author_id = $post->author_id ?: 0;
        }

        $this->handleUpload($request, 'post_photo', 'post_photo_', $post, 'post_photo');
        $this->handleUpload($request, 'og_image', 'post_og_', $post, 'og_image');
        $this->handleUpload($request, 'social_image', 'post_social_', $post, 'social_image');

        EditorialAssignmentService::applyFromRequest($post, $request);

        if ($post->written_by_author_id) {
            $post->author_id = $post->written_by_author_id;
            $post->admin_id = 0;
        } elseif ($post->written_by_admin_id) {
            $post->author_id = 0;
            $post->admin_id = $post->written_by_admin_id;
        } elseif ($creating && ! $post->admin_id && Auth::guard('admin')->check()) {
            $post->admin_id = Auth::guard('admin')->id();
            $post->author_id = 0;
        }

        $post->save();

        $this->syncTags($post, (string) $request->input('tags', ''));
        $this->syncBrokers($post, $request->input('broker_ids', []));
        $this->syncRelatedPosts($post, $request->input('related_post_ids', []));
        $this->syncRelatedCategories($post, $request->input('related_category_ids', []));

        return $post->fresh();
    }

    public function delete(Post $post): void
    {
        foreach (['post_photo', 'og_image', 'social_image'] as $field) {
            $this->deleteStoredFile($post->{$field} ?? null);
        }

        Tag::query()->where('post_id', $post->id)->delete();

        if (Schema::hasTable('post_broker')) {
            $post->brokers()->detach();
        }
        if (Schema::hasTable('post_related')) {
            $post->relatedPosts()->detach();
        }
        if (Schema::hasTable('post_related_category')) {
            $post->relatedCategories()->detach();
        }

        $post->delete();
    }

    public function duplicate(Post $post): Post
    {
        $post->loadMissing(['tags', 'brokers', 'relatedPosts', 'relatedCategories']);

        $copy = $post->replicate();
        $copy->post_title = $post->post_title.' (Copy)';
        $copy->slug = $this->uniqueSlug($copy->post_title, null, $copy->post_title);
        $copy->visitors = 1;
        $copy->admin_id = Auth::guard('admin')->id() ?: $post->admin_id;

        if (Schema::hasColumn('posts', 'status')) {
            $copy->status = 'draft';
        }
        if (Schema::hasColumn('posts', 'publish_at')) {
            $copy->publish_at = null;
        }
        if (Schema::hasColumn('posts', 'scheduled_at')) {
            $copy->scheduled_at = null;
        }

        $copy->save();

        $this->syncTags($copy, $post->tags->pluck('tag_name')->implode(', '));

        if (Schema::hasTable('post_broker')) {
            $copy->brokers()->sync($post->brokers()->pluck('brokers.id')->all());
        }
        if (Schema::hasTable('post_related')) {
            $copy->relatedPosts()->sync(
                $post->relatedPosts()->pluck('posts.id')->reject(fn ($id) => (int) $id === (int) $copy->id)->take(6)->all()
            );
        }
        if (Schema::hasTable('post_related_category')) {
            $copy->relatedCategories()->sync($post->relatedCategories()->pluck('categories.id')->all());
        }

        return $copy;
    }

    public function setStatus(Post $post, string $status): Post
    {
        if (! Schema::hasColumn('posts', 'status') || ! array_key_exists($status, Post::statuses())) {
            return $post;
        }

        $post->status = $status;

        if ($status === 'published' && Schema::hasColumn('posts', 'publish_at') && ! $post->publish_at) {
            $post->publish_at = now();
        }

        if ($status !== 'scheduled' && Schema::hasColumn('posts', 'scheduled_at')) {
            $post->scheduled_at = $status === 'published' ? $post->scheduled_at : $post->scheduled_at;
        }

        $post->save();

        return $post;
    }

    public function ensureContentType(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 'article';
        }

        $slug = Str::slug($value) ?: 'article';

        if (! Schema::hasTable('post_content_types')) {
            return $slug;
        }

        $existing = PostContentType::query()
            ->where(function ($query) use ($slug, $value) {
                $query->where('slug', $slug)
                    ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($value)]);
            })
            ->first();

        if ($existing) {
            if (! $existing->is_active) {
                $existing->is_active = true;
                $existing->save();
            }

            return $existing->slug;
        }

        $type = PostContentType::query()->create([
            'name' => Str::title(str_replace(['-', '_'], ' ', $value)),
            'slug' => PostContentType::uniqueSlug($value),
            'is_active' => true,
            'sort_order' => (int) PostContentType::query()->max('sort_order') + 1,
        ]);

        return $type->slug;
    }

    public function uniqueSlug(string $slug, ?int $ignoreId, mixed $title): string
    {
        $base = Str::slug($slug) ?: Str::slug((string) $title) ?: 'post-'.Str::lower(Str::random(6));
        $candidate = $base;
        $suffix = 2;

        while (
            Post::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    protected function applyDates(Post $post, Request $request): void
    {
        if (Schema::hasColumn('posts', 'publish_at')) {
            $post->publish_at = $request->filled('publish_at')
                ? $request->date('publish_at')
                : ($post->publish_at ?: ($request->input('status') === 'published' ? now() : $post->publish_at));
        }

        if (Schema::hasColumn('posts', 'scheduled_at')) {
            $post->scheduled_at = $request->filled('scheduled_at')
                ? $request->date('scheduled_at')
                : null;
        }

        if (($request->input('status') === 'scheduled') && Schema::hasColumn('posts', 'scheduled_at')) {
            $goLive = $post->scheduled_at ?: ($request->filled('publish_at') ? $request->date('publish_at') : now()->addHour());
            $post->scheduled_at = $goLive;
            if (Schema::hasColumn('posts', 'publish_at')) {
                $post->publish_at = $goLive;
            }
        }

        if ($request->input('status') === 'published' && Schema::hasColumn('posts', 'publish_at') && ! $post->publish_at) {
            $post->publish_at = now();
        }
    }

    protected function fillIfPresent(Post $post, Request $request, array $fields): void
    {
        foreach ($fields as $field) {
            if (Schema::hasColumn('posts', $field)) {
                $post->{$field} = $request->input($field);
            }
        }
    }

    protected function handleUpload(Request $request, string $input, string $prefix, Post $post, string $column): void
    {
        if (! Schema::hasColumn('posts', $column) || ! $request->hasFile($input)) {
            return;
        }

        $current = $post->{$column};
        $stored = $this->uploads->replaceFromRequest($request, $input, $this->storedPath($current), 'uploads', $prefix);

        if ($stored) {
            $post->{$column} = basename($stored);
        }
    }

    protected function deleteStoredFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $this->uploads->delete($this->storedPath($filename));
    }

    protected function storedPath(?string $filename): ?string
    {
        if (! filled($filename)) {
            return null;
        }

        $relative = ltrim((string) $filename, '/');

        return str_starts_with($relative, 'uploads/') ? $relative : 'uploads/'.$relative;
    }

    protected function syncTags(Post $post, mixed $raw): void
    {
        if (is_array($raw)) {
            $names = $raw;
        } else {
            $names = preg_split('/,/', (string) $raw) ?: [];
        }

        $names = array_values(array_unique(array_filter(array_map('trim', $names))));

        Tag::query()->where('post_id', $post->id)->delete();

        foreach ($names as $name) {
            Tag::query()->create([
                'post_id' => $post->id,
                'tag_name' => $name,
            ]);
        }
    }

    protected function syncBrokers(Post $post, mixed $ids): void
    {
        if (! Schema::hasTable('post_broker')) {
            return;
        }

        $post->brokers()->sync($this->idList($ids));
    }

    protected function syncRelatedPosts(Post $post, mixed $ids): void
    {
        if (! Schema::hasTable('post_related')) {
            return;
        }

        $ids = array_values(array_filter(
            $this->idList($ids),
            fn (int $id) => $id !== (int) $post->id
        ));

        $post->relatedPosts()->sync(array_slice($ids, 0, 6));
    }

    protected function syncRelatedCategories(Post $post, mixed $ids): void
    {
        if (! Schema::hasTable('post_related_category')) {
            return;
        }

        $post->relatedCategories()->sync($this->idList($ids));
    }

    /** @return array<int, int> */
    protected function idList(mixed $ids): array
    {
        if (! is_array($ids)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }
}
