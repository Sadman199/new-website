<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Mail\Websitemail;
use App\Models\Author;
use App\Models\Broker;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\SubCategory;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Services\EditorialAssignmentService;
use App\Services\PostAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AdminPostController extends Controller
{
    public function __construct(protected PostAdminService $posts)
    {
    }

    public function show(Request $request)
    {
        $filters = $this->listingFilters($request);
        $query = $this->filteredPosts($request);

        $relations = [
            'rSubCategory.rCategory',
            'rLanguage',
            'author',
            'writtenByAuthor',
        ];

        if (Schema::hasTable('post_broker')) {
            $relations[] = 'brokers:id,name';
        }

        $posts = $query
            ->with($relations)
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Post::query()->count(),
            'published' => $this->statusCount('published'),
            'draft' => $this->statusCount('draft'),
            'scheduled' => $this->statusCount('scheduled'),
            'featured' => Schema::hasColumn('posts', 'is_featured')
                ? Post::query()->where(function ($q) {
                    $q->where('is_featured', true)
                        ->orWhere('featured_homepage', true)
                        ->orWhere('featured_blog', true)
                        ->orWhere('is_editors_pick', true);
                })->count()
                : 0,
        ];

        return view('admin.posts.index', [
            'posts' => $posts,
            'stats' => $stats,
            'filters' => $filters,
            'formOptions' => $this->formOptions(null, true),
        ]);
    }

    public function create()
    {
        return view('admin.posts.create', [
            'post' => new Post([
                'status' => 'published',
                'content_type' => 'article',
                'schema_type' => 'Article',
                'is_share' => true,
                'is_comment' => true,
                'show_author' => true,
                'show_related_posts' => true,
                'robots_index' => true,
                'robots_follow' => true,
                'language_id' => app(\App\Services\GlobalViewDataService::class)->currentLanguageId(),
            ]),
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function store(PostRequest $request)
    {
        try {
            $post = $this->posts->save(new Post(), $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create blog: '.$e->getMessage());
        }

        if ($request->boolean('subscriber_send_option') && $post->isPubliclyVisible()) {
            $this->notifySubscribers($post);
        }

        return redirect()
            ->route('admin_post_edit', $post->id)
            ->with('success', 'Blog created.');
    }

    public function view($id)
    {
        $post = $this->loadPost($id);

        return view('admin.posts.view', [
            'post' => $post,
            'credits' => EditorialAssignmentService::creditsForPost($post),
        ]);
    }

    public function edit($id)
    {
        $post = $this->loadPost($id);

        return view('admin.posts.edit', [
            'post' => $post,
            'formOptions' => $this->formOptions($post),
        ]);
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::query()->findOrFail($id);

        try {
            $this->posts->save($post, $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update blog: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_post_edit', $post->id)
            ->with('success', 'Blog updated.');
    }

    public function delete($id)
    {
        $post = Post::query()->findOrFail($id);

        try {
            $this->posts->delete($post);
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('admin_post_show')
                ->with('error', 'Could not delete blog: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_post_show')
            ->with('success', 'Blog deleted.');
    }

    public function delete_tag($id, $id1)
    {
        $tag = Tag::query()->where('id', $id)->where('post_id', $id1)->first();
        abort_unless($tag, 404);
        $tag->delete();

        return redirect()
            ->route('admin_post_edit', $id1)
            ->with('success', 'Tag removed.');
    }

    public function duplicate($id)
    {
        $post = $this->loadPost($id);
        $copy = $this->posts->duplicate($post);

        return redirect()
            ->route('admin_post_edit', $copy->id)
            ->with('success', 'Draft copy created.');
    }

    public function status($id, $status)
    {
        $post = Post::query()->findOrFail($id);
        $this->posts->setStatus($post, (string) $status);

        return redirect()
            ->back()
            ->with('success', 'Status updated to '.$post->statusLabel().'.');
    }

    protected function loadPost($id): Post
    {
        $relations = [
            'rSubCategory.rCategory',
            'rLanguage',
            'author',
            'admin',
            'tags',
            'writtenByAuthor',
            'editedByAuthor',
            'factCheckedByAuthor',
            'writtenByAdmin',
            'editedByAdmin',
            'factCheckedByAdmin',
        ];

        if (Schema::hasTable('post_broker')) {
            $relations[] = 'brokers:id,name,logo,slug';
        }
        if (Schema::hasTable('post_related')) {
            $relations[] = 'relatedPosts:id,post_title,slug';
        }
        if (Schema::hasTable('post_related_category')) {
            $relations[] = 'relatedCategories:id,category_name';
        }

        return Post::query()->with($relations)->findOrFail($id);
    }

    /** @return array<string, mixed> */
    protected function listingFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->get('q', '')),
            'category_id' => $request->integer('category_id') ?: '',
            'sub_category_id' => $request->integer('sub_category_id') ?: '',
            'author_id' => $request->integer('author_id') ?: '',
            'broker_id' => $request->integer('broker_id') ?: '',
            'content_type' => (string) $request->get('content_type', ''),
            'status' => (string) $request->get('status', ''),
            'language_id' => $request->integer('language_id') ?: '',
            'featured' => (string) $request->get('featured', ''),
            'from' => (string) $request->get('from', ''),
            'to' => (string) $request->get('to', ''),
        ];
    }

    protected function filteredPosts(Request $request)
    {
        $filters = $this->listingFilters($request);

        $query = Post::query()->orderByDesc('id');

        if ($filters['q'] !== '') {
            $term = $filters['q'];
            $query->where(function ($sub) use ($term) {
                $sub->where('post_title', 'like', '%'.$term.'%')
                    ->orWhere('slug', 'like', '%'.$term.'%')
                    ->orWhere('excerpt', 'like', '%'.$term.'%');
            });
        }

        if ($filters['sub_category_id'] !== '') {
            $query->where('sub_category_id', $filters['sub_category_id']);
        } elseif ($filters['category_id'] !== '') {
            $query->whereHas('rSubCategory', fn ($q) => $q->where('category_id', $filters['category_id']));
        }

        if ($filters['author_id'] !== '') {
            $authorId = $filters['author_id'];
            $query->where(function ($sub) use ($authorId) {
                $sub->where('author_id', $authorId)
                    ->orWhere('written_by_author_id', $authorId);
            });
        }

        if ($filters['broker_id'] !== '' && Schema::hasTable('post_broker')) {
            $query->whereHas('brokers', fn ($q) => $q->where('brokers.id', $filters['broker_id']));
        }

        if ($filters['content_type'] !== '' && Schema::hasColumn('posts', 'content_type')) {
            $query->where('content_type', $filters['content_type']);
        }

        if ($filters['status'] !== '' && Schema::hasColumn('posts', 'status')) {
            $query->where('status', $filters['status']);
        }

        if ($filters['language_id'] !== '') {
            $query->where('language_id', $filters['language_id']);
        }

        if ($filters['featured'] !== '' && Schema::hasColumn('posts', 'is_featured')) {
            $query->where(function ($sub) {
                $sub->where('is_featured', true)
                    ->orWhere('featured_homepage', true)
                    ->orWhere('featured_blog', true)
                    ->orWhere('is_editors_pick', true)
                    ->orWhere('is_popular', true);
            });
        }

        if ($filters['from'] !== '') {
            $query->whereDate(Schema::hasColumn('posts', 'publish_at') ? 'publish_at' : 'created_at', '>=', $filters['from']);
        }

        if ($filters['to'] !== '') {
            $query->whereDate(Schema::hasColumn('posts', 'publish_at') ? 'publish_at' : 'created_at', '<=', $filters['to']);
        }

        return $query;
    }

    protected function statusCount(string $status): int
    {
        if (! Schema::hasColumn('posts', 'status')) {
            return $status === 'published' ? Post::query()->count() : 0;
        }

        return Post::query()->where('status', $status)->count();
    }

    /** @return array<string, mixed> */
    protected function formOptions(?Post $post = null, bool $listing = false): array
    {
        $subCategories = SubCategory::query()
            ->with('rCategory:id,category_name')
            ->orderBy('sub_category_name')
            ->get(['id', 'sub_category_name', 'category_id']);

        $relatedPostQuery = Post::query()
            ->orderByDesc('id')
            ->limit(250)
            ->get(['id', 'post_title']);

        if ($post?->id) {
            $relatedPostQuery = $relatedPostQuery->reject(fn (Post $item) => $item->id === $post->id)->values();
        }

        return [
            'subCategories' => $subCategories,
            'categories' => Category::query()->orderBy('category_name')->get(['id', 'category_name']),
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name', 'is_scam']),
            'authors' => Author::query()->orderBy('name')->get(['id', 'name']),
            'languages' => Language::query()->orderBy('name')->get(['id', 'name', 'short_name']),
            'relatedPosts' => $relatedPostQuery,
            'tagSuggestions' => Tag::query()->select('tag_name')->distinct()->orderBy('tag_name')->limit(80)->pluck('tag_name'),
            'contentTypes' => $this->contentTypeOptions($post),
            'statuses' => Post::statuses(),
            'schemaTypes' => Post::schemaTypes(),
            'editorialOptions' => EditorialAssignmentService::allAssigneeOptions(),
        ];
    }

    /** @return array<string, string> */
    protected function contentTypeOptions(?Post $post = null): array
    {
        $types = Post::contentTypes();
        $current = $post?->content_type;

        if ($current && ! isset($types[$current])) {
            $types[$current] = $post->contentTypeLabel();
        }

        return $types;
    }

    protected function notifySubscribers(Post $post): void
    {
        $url = $post->publicUrl();
        if (! $url) {
            return;
        }

        $subject = 'A new post is published';
        $message = 'Hi, A new post is published into our website. Please go to see that post:<br>';
        $message .= '<a target="_blank" href="'.e($url).'">'.e($post->post_title).'</a>';

        foreach (Subscriber::query()->where('status', 'Active')->get() as $row) {
            try {
                Mail::to($row->email)->send(new Websitemail($subject, $message));
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
