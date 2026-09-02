<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Category;
use App\Models\Language;
use App\Models\Post;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BlogIndexService
{
    public const PER_PAGE = 12;

    /** @deprecated Use PER_PAGE. Kept for callers that still reference the old constant. */
    public const CARDS_PER_TAB = 12;

    /** @var array<int, string> */
    public const INSIGHT_GRADIENTS = [
        'linear-gradient(135deg, #e8822a 0%, #1c1e24 100%)',
        'linear-gradient(135deg, #c46a16 0%, #f5a623 100%)',
        'linear-gradient(135deg, #1c1e24 0%, #44403c 100%)',
        'linear-gradient(135deg, #e8822a 0%, #fbc65c 100%)',
        'linear-gradient(135deg, #57534e 0%, #1c1e24 100%)',
        'linear-gradient(135deg, #d9a978 0%, #c46a16 100%)',
    ];

    /** @var array<int, string> */
    private const BADGE_COLORS = [
        'markets' => '#0f7a52',
        'broker' => '#c46a16',
        'analysis' => '#a1651c',
        'regulation' => '#8a5a2b',
        'default' => '#6b7280',
    ];

    public function resolveLanguageId(): int
    {
        $shortName = session()->get('session_short_name')
            ?? optional(Language::where('is_default', 'Yes')->first())->short_name
            ?? 'en';

        return (int) (optional(Language::where('short_name', $shortName)->first())->id ?? 1);
    }

    /** @return array{recent: \Illuminate\Support\Collection, popular: \Illuminate\Support\Collection} */
    public function editorialStreams(int $languageId, int $limit = 6): array
    {
        return Cache::remember("editorial_streams_v2_{$languageId}_{$limit}", 600, function () use ($languageId, $limit) {
            $base = fn () => Post::with(['rSubCategory', 'author', 'writtenByAuthor'])
                ->published()
                ->where('language_id', $languageId);

            return [
                'recent' => $base()->latest()->take($limit)->get(),
                'popular' => $base()->orderByDesc('visitors')->take($limit)->get(),
            ];
        });
    }

    /** @return array<string, mixed> */
    public function buildIndex(int $languageId, ?string $categorySlug = null, ?string $subcategorySlug = null): array
    {
        $tabs = $this->categoryTabs($languageId);
        $category = $this->resolveCategory($categorySlug, $languageId);
        $subcategory = null;

        if (! $category && $subcategorySlug) {
            $subcategory = SubCategory::query()
                ->with('rCategory')
                ->where('language_id', $languageId)
                ->where('slug', $subcategorySlug)
                ->first();

            if ($subcategory?->rCategory) {
                $category = $subcategory->rCategory;
            }
        }

        $activeTab = $category ? $category->publicSlug() : 'all';
        $activeTabName = $category?->category_name ?? 'All';

        $query = Post::query()
            ->with(['rSubCategory.rCategory', 'writtenByAuthor'])
            ->published()
            ->where('language_id', $languageId);

        if ($subcategory) {
            $query->where('sub_category_id', $subcategory->id);
        } elseif ($category) {
            $query->whereHas('rSubCategory', fn ($q) => $q->where('category_id', $category->id));
        }

        if (Schema::hasColumn('posts', 'featured_blog')) {
            $query->orderByDesc('featured_blog');
        }
        if (Schema::hasColumn('posts', 'is_featured')) {
            $query->orderByDesc('is_featured');
        }

        $posts = $query
            ->orderByDesc('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $posts->setCollection(
            $posts->getCollection()->map(fn (Post $post) => $this->serializePost($post))->values()
        );

        $isFiltered = $activeTab !== 'all';

        return [
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'activeTabName' => $activeTabName,
            'posts' => $posts,
            'pageTitle' => $isFiltered
                ? $activeTabName.' — BrokersCourt Blog'
                : 'Forex & Broker Blog — Analysis, News & Guides | BrokersCourt',
            'pageDescription' => $isFiltered
                ? 'Read BrokersCourt articles in '.$activeTabName.': independent coverage of brokers, markets, regulation, and trading.'
                : 'Independent BrokersCourt journalism on brokers, markets, regulation, and trading — researched and published by our editorial team.',
        ];
    }

    /**
     * Highest-traffic articles for the editorial sidebar.
     *
     * @return array<int, array<string, mixed>>
     */
    public function mostRead(int $languageId, ?int $excludeId = null, int $limit = 5): array
    {
        return Post::query()
            ->with(['rSubCategory', 'writtenByAuthor'])
            ->published()
            ->where('language_id', $languageId)
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->orderByDesc('visitors')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (Post $post) => $this->serializePost($post))
            ->all();
    }

    /**
     * Follow-up reading that is not already visible in the current feed.
     *
     * @param  array<int, int>  $excludeIds
     * @return array<int, array<string, mixed>>
     */
    public function readNext(int $languageId, array $excludeIds = [], int $limit = 3): array
    {
        $base = fn () => Post::query()
            ->with(['rSubCategory', 'writtenByAuthor'])
            ->published()
            ->where('language_id', $languageId)
            ->orderByDesc('visitors')
            ->orderByDesc('id')
            ->limit($limit);

        $posts = $base()
            ->when($excludeIds !== [], fn ($q) => $q->whereNotIn('id', $excludeIds))
            ->get();

        if ($posts->isEmpty()) {
            $posts = $base()->get();
        }

        return $posts->map(fn (Post $post) => $this->serializePost($post))->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function categoryTabs(int $languageId): array
    {
        $totalPosts = Post::published()->where('language_id', $languageId)->count();

        $tabs = [[
            'slug' => 'all',
            'name' => 'All',
            'count' => $totalPosts,
            'url' => route('blog'),
        ]];

        $categories = Category::query()
            ->where('language_id', $languageId)
            ->orderBy('category_order')
            ->orderBy('category_name')
            ->withCount([
                'classifiedPosts as posts_count' => fn ($q) => $q->published()->where('posts.language_id', $languageId),
            ])
            ->get()
            ->filter(fn (Category $category) => (int) $category->posts_count > 0);

        foreach ($categories as $category) {
            $slug = $category->publicSlug();
            $tabs[] = [
                'slug' => $slug,
                'name' => $category->category_name,
                'count' => (int) $category->posts_count,
                'url' => route('blog', ['category' => $slug]),
            ];
        }

        return $tabs;
    }

    /** @return array<int, array<string, mixed>> */
    public function subcategoryTabs(int $languageId): array
    {
        return $this->categoryTabs($languageId);
    }

    private function resolveCategory(?string $slug, int $languageId): ?Category
    {
        $slug = trim((string) $slug);
        if ($slug === '' || $slug === 'all') {
            return null;
        }

        $categories = Category::query()
            ->where('language_id', $languageId)
            ->orderBy('category_order')
            ->orderBy('id')
            ->get();

        return $categories->first(fn (Category $category) => $category->publicSlug() === $slug)
            ?? $categories->first(fn (Category $category) => (string) $category->id === $slug);
    }

    /** @return array<string, int> */
    public function stats(int $languageId): array
    {
        $posts = Post::published()->where('language_id', $languageId);

        return [
            'total_posts' => (clone $posts)->count(),
            'subcategories' => SubCategory::where('language_id', $languageId)
                ->whereHas('rPost', fn ($q) => $q->published()->where('language_id', $languageId))
                ->count(),
            'total_views' => (int) (clone $posts)->sum('visitors'),
            'authors' => Author::whereHas('legacyPosts', fn ($q) => $q->where('language_id', $languageId))->count(),
        ];
    }

    public static function insightGradient(int $index): string
    {
        return self::INSIGHT_GRADIENTS[$index % count(self::INSIGHT_GRADIENTS)];
    }

    /** @return array<string, mixed> */
    public function serializePost(Post $post): array
    {
        $sub = $post->rSubCategory;
        $parent = $sub?->rCategory;
        $author = $this->authorFor($post);
        $publishedAt = $post->publish_at ?? $post->created_at ?? $post->updated_at;
        $categoryName = $parent?->category_name ?: ($sub?->sub_category_name ?? 'Insights');

        return [
            'id' => $post->id,
            'title' => $post->post_title,
            'slug' => $post->slug,
            'excerpt' => $this->excerpt($post, 160),
            'photo' => $post->post_photo ? asset('uploads/' . $post->post_photo) : null,
            'url' => $sub
                ? route('news_detail', ['subcategory_slug' => $sub->slug, 'post_slug' => $post->slug])
                : '#',
            'subcategory' => [
                'name' => $sub?->sub_category_name ?? 'General',
                'slug' => $sub?->slug,
                'color' => $this->badgeColor($sub?->sub_category_name ?? ''),
            ],
            'parent_category' => $parent?->category_name,
            'category' => $categoryName,
            'content_type' => $post->content_type ?: 'article',
            'content_type_label' => Str::title(str_replace(['-', '_'], ' ', (string) ($post->content_type ?: 'article'))),
            'author' => $author['name'],
            'author_photo' => $author['photo'] ? asset('uploads/' . $author['photo']) : null,
            'author_url' => $author['url'] ?? null,
            'date' => $publishedAt?->format('M j, Y') ?? '',
            'date_iso' => $publishedAt?->toDateString() ?? '',
            'date_short' => $publishedAt?->format('M j') ?? '',
            'date_rel' => $publishedAt?->diffForHumans() ?? '',
            'read_time' => $this->readTimeMinutes($post),
            'views' => (int) $post->visitors,
        ];
    }

    private function excerpt(Post $post, int $limit = 140): string
    {
        $meta = trim((string) ($post->excerpt ?? ''));
        if ($meta !== '') {
            return Str::limit($meta, $limit);
        }

        $meta = trim((string) ($post->meta_description ?? ''));
        if ($meta !== '') {
            return Str::limit($meta, $limit);
        }

        $text = strip_tags((string) $post->post_detail);
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');

        return Str::limit($text, $limit);
    }

    private function readTimeMinutes(Post $post): int
    {
        if (! empty($post->reading_time)) {
            return max(1, (int) $post->reading_time);
        }

        $words = str_word_count(strip_tags((string) $post->post_detail));

        return max(1, (int) ceil($words / 200));
    }

    private function authorName(Post $post): string
    {
        if ($post->relationLoaded('writtenByAuthor') && $post->writtenByAuthor) {
            return $post->writtenByAuthor->name;
        }

        if ($post->written_by_author_id) {
            $author = Author::find($post->written_by_author_id);

            return $author?->name ?? 'Editor';
        }

        if ($post->author_id && (int) $post->author_id !== 0) {
            $author = $post->relationLoaded('author') ? $post->author : Author::find($post->author_id);

            return $author?->name ?? (string) ($post->getAttributes()['author'] ?? 'Editor');
        }

        if ($post->admin_id) {
            $admin = Admin::find($post->admin_id);

            return $admin?->name ?? 'Editor';
        }

        $legacyAuthor = $post->getAttributes()['author'] ?? null;

        return is_string($legacyAuthor) && $legacyAuthor !== '' ? $legacyAuthor : 'BrokersCourt Editorial';
    }

    /** @return array{name: string, photo: string|null, url: string|null} */
    public function authorFor(Post $post): array
    {
        $author = null;
        $photo = null;

        if ($post->relationLoaded('writtenByAuthor') && $post->writtenByAuthor) {
            $author = $post->writtenByAuthor;
        } elseif ($post->written_by_author_id) {
            $author = $post->writtenByAuthor ?? Author::find($post->written_by_author_id);
        } elseif ($post->author_id && (int) $post->author_id !== 0) {
            $author = $post->relationLoaded('author') ? $post->author : Author::find($post->author_id);
        }

        $author = $author instanceof Author ? $author : null;

        if ($author) {
            $photo = $author->photo;
        } elseif ($post->admin_id) {
            $photo = Admin::find($post->admin_id)?->photo;
        }

        return [
            'name' => $this->authorName($post),
            'photo' => $photo,
            'url' => $author?->profileUrl(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function latestPosts(?int $languageId = null, int $limit = 3): array
    {
        $languageId = $languageId ?? $this->resolveLanguageId();

        return Post::query()
            ->with(['rSubCategory', 'writtenByAuthor'])
            ->published()
            ->where('language_id', $languageId)
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (Post $post) => $this->serializePost($post))
            ->all();
    }

    private function badgeColor(string $name): string
    {
        $lower = strtolower($name);

        if (Str::contains($lower, ['market', 'outlook', 'gold', 'stock', 'nfp', 'cpi'])) {
            return self::BADGE_COLORS['markets'];
        }

        if (Str::contains($lower, ['broker', 'comparison', 'platform', 'metatrader'])) {
            return self::BADGE_COLORS['broker'];
        }

        if (Str::contains($lower, ['analysis', 'pattern', 'chart', 'scalp', 'swing', 'strategy', 'trading'])) {
            return self::BADGE_COLORS['analysis'];
        }

        if (Str::contains($lower, ['regulation', 'license', 'compliance', 'central bank'])) {
            return self::BADGE_COLORS['regulation'];
        }

        return self::BADGE_COLORS['default'];
    }
}
