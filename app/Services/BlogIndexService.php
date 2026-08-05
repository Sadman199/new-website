<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Author;
use App\Models\Language;
use App\Models\Post;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class BlogIndexService
{
    public const CARDS_PER_TAB = 20;

    /** @var array<int, string> */
    private const BADGE_COLORS = [
        'markets' => '#16a34a',
        'broker' => '#2563eb',
        'analysis' => '#0891b2',
        'regulation' => '#9333ea',
        'default' => '#64748b',
    ];

    public function resolveLanguageId(): int
    {
        $shortName = session()->get('session_short_name')
            ?? optional(Language::where('is_default', 'Yes')->first())->short_name
            ?? 'en';

        return (int) (optional(Language::where('short_name', $shortName)->first())->id ?? 1);
    }

    /** @return array<string, mixed> */
    public function buildIndex(int $languageId, ?string $subcategorySlug = null): array
    {
        $tabs = $this->subcategoryTabs($languageId);
        $activeTab = $subcategorySlug ?: 'all';

        $query = Post::query()
            ->with(['rSubCategory'])
            ->where('language_id', $languageId);

        if ($subcategorySlug) {
            $query->whereHas('rSubCategory', fn ($q) => $q->where('slug', $subcategorySlug));
        }

        $posts = $query
            ->orderByDesc('id')
            ->limit(self::CARDS_PER_TAB)
            ->get();

        $latestHeadline = $posts->first();

        $activeTabMeta = collect($tabs)->firstWhere('slug', $activeTab) ?? $tabs[0];

        return [
            'tabs' => $tabs,
            'activeTab' => $activeTab,
            'activeTabName' => $activeTabMeta['name'] ?? 'All News',
            'stats' => $this->stats($languageId),
            'latestHeadline' => $latestHeadline ? $this->serializePost($latestHeadline) : null,
            'cards' => $posts->map(fn (Post $post) => $this->serializePost($post))->all(),
            'cardLimit' => self::CARDS_PER_TAB,
            'cardCount' => $posts->count(),
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public function subcategoryTabs(int $languageId): array
    {
        $totalPosts = min(Post::where('language_id', $languageId)->count(), self::CARDS_PER_TAB);

        $tabs = [[
            'slug' => 'all',
            'name' => 'All News',
            'count' => $totalPosts,
            'url' => route('blog'),
        ]];

        $subcategories = SubCategory::query()
            ->where('language_id', $languageId)
            ->where('show_on_menu', 'Show')
            ->orderBy('sub_category_order')
            ->withCount(['rPost as posts_count' => fn ($q) => $q->where('language_id', $languageId)])
            ->get()
            ->filter(fn (SubCategory $sub) => $sub->posts_count > 0);

        foreach ($subcategories as $sub) {
            $tabs[] = [
                'slug' => $sub->slug,
                'name' => $sub->sub_category_name,
                'count' => min((int) $sub->posts_count, self::CARDS_PER_TAB),
                'url' => route('blog', ['subcategory' => $sub->slug]),
            ];
        }

        return $tabs;
    }

    /** @return array<string, int> */
    public function stats(int $languageId): array
    {
        $posts = Post::where('language_id', $languageId);

        return [
            'total_posts' => (clone $posts)->count(),
            'subcategories' => SubCategory::where('language_id', $languageId)
                ->whereHas('rPost', fn ($q) => $q->where('language_id', $languageId))
                ->count(),
            'total_views' => (int) (clone $posts)->sum('visitors'),
            'authors' => Author::whereHas('legacyPosts', fn ($q) => $q->where('language_id', $languageId))->count(),
        ];
    }

    /** @return array<string, mixed> */
    public function serializePost(Post $post): array
    {
        $sub = $post->rSubCategory;

        return [
            'id' => $post->id,
            'title' => $post->post_title,
            'slug' => $post->slug,
            'excerpt' => $this->excerpt($post),
            'photo' => $post->post_photo ? asset('uploads/' . $post->post_photo) : asset('uploads/default.png'),
            'url' => $sub
                ? route('news_detail', ['subcategory_slug' => $sub->slug, 'post_slug' => $post->slug])
                : '#',
            'subcategory' => [
                'name' => $sub?->sub_category_name ?? 'General',
                'slug' => $sub?->slug,
                'color' => $this->badgeColor($sub?->sub_category_name ?? ''),
            ],
            'author' => $this->authorName($post),
            'date' => $post->updated_at->format('M j, Y'),
            'date_short' => $post->updated_at->format('M j'),
            'read_time' => $this->readTimeMinutes($post),
            'views' => (int) $post->visitors,
        ];
    }

    private function excerpt(Post $post, int $limit = 140): string
    {
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
        $words = str_word_count(strip_tags((string) $post->post_detail));

        return max(1, (int) ceil($words / 200));
    }

    private function authorName(Post $post): string
    {
        if ($post->author_id && (int) $post->author_id !== 0) {
            $author = Author::find($post->author_id);

            return $author?->name ?? (string) ($post->getAttributes()['author'] ?? 'Editor');
        }

        if ($post->admin_id) {
            $admin = Admin::find($post->admin_id);

            return $admin?->name ?? 'Editor';
        }

        $legacyAuthor = $post->getAttributes()['author'] ?? null;

        return is_string($legacyAuthor) && $legacyAuthor !== '' ? $legacyAuthor : 'BrokersCourt Editorial';
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
