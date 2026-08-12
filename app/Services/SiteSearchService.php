<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Author;
use App\Models\Broker;
use App\Models\CmsPage;
use App\Models\ForexBonus;
use App\Models\Post;
use App\Models\PropFirm;
use App\Models\PropFirmCategory;
use App\Models\TradingTool;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiteSearchService
{
    private const PER_TYPE_LIMIT = 15;

    private const SUGGEST_LIMIT = 8;

    /** @var array<string, array{label: string, keys: array<int, string>}> */
    public const FILTERS = [
        'all' => ['label' => 'All', 'keys' => []],
        'broker' => ['label' => 'Brokers', 'keys' => ['broker']],
        'article' => ['label' => 'Articles', 'keys' => ['article']],
        'bonus' => ['label' => 'Bonuses', 'keys' => ['bonus']],
        'prop_firm' => ['label' => 'Prop Firms', 'keys' => ['prop_firm']],
        'tool' => ['label' => 'Tools', 'keys' => ['tool']],
        'guide' => ['label' => 'Guides', 'keys' => ['guide', 'site']],
        'page' => ['label' => 'Pages', 'keys' => ['page', 'author']],
    ];

    public const SORT_OPTIONS = [
        'relevance' => 'Relevance',
        'newest' => 'Newest',
    ];

    /**
     * @return array{
     *     query: string,
     *     type: string,
     *     sort: string,
     *     groups: array<int, array<string, mixed>>,
     *     total: int,
     *     counts: array<string, int>,
     *     filters: array<string, array{label: string, keys: array<int, string>}>,
     *     sort_options: array<string, string>
     * }
     */
    public function search(
        string $query,
        ?int $languageId = null,
        string $type = 'all',
        string $sort = 'relevance',
    ): array {
        $query = trim($query);
        $type = array_key_exists($type, self::FILTERS) ? $type : 'all';
        $sort = array_key_exists($sort, self::SORT_OPTIONS) ? $sort : 'relevance';

        if ($query === '' || mb_strlen($query) < 2) {
            return [
                'query' => $query,
                'type' => $type,
                'sort' => $sort,
                'groups' => [],
                'total' => 0,
                'counts' => $this->emptyCounts(),
                'filters' => self::FILTERS,
                'sort_options' => self::SORT_OPTIONS,
            ];
        }

        $languageId = $languageId ?? app(BlogIndexService::class)->resolveLanguageId();
        $normalized = $this->normalize($query);

        $groups = array_values(array_filter([
            $this->searchBrokers($query, $normalized),
            $this->searchPosts($query, $languageId),
            $this->searchPropFirms($query),
            $this->searchBonuses($query),
            $this->searchAuthors($query),
            $this->searchCmsPages($query),
            $this->searchTradingTools($query),
            $this->searchGuides($query),
            $this->searchStaticPages($query),
        ], fn (array $group) => $group['items'] !== []));

        $groups = $this->scoreAndSortGroups($groups, $query, $sort);
        $counts = $this->buildCounts($groups);
        $groups = $this->filterGroups($groups, $type);
        $total = array_sum(array_map(fn (array $group) => count($group['items']), $groups));

        return [
            'query' => $query,
            'type' => $type,
            'sort' => $sort,
            'groups' => $groups,
            'total' => $total,
            'counts' => $counts,
            'filters' => self::FILTERS,
            'sort_options' => self::SORT_OPTIONS,
        ];
    }

    /** @return array{query: string, total: int, results: array<int, array<string, mixed>>, results_url: string} */
    public function suggest(string $query): array
    {
        $payload = $this->search($query);
        $results = [];

        foreach ($payload['groups'] as $group) {
            foreach ($group['items'] as $item) {
                $results[] = $item;
                if (count($results) >= self::SUGGEST_LIMIT) {
                    break 2;
                }
            }
        }

        return [
            'query' => $payload['query'],
            'total' => $payload['total'],
            'results' => $results,
            'results_url' => $payload['query'] !== ''
                ? route('search', ['q' => $payload['query']])
                : route('search'),
        ];
    }

    /** @return array<string, int> */
    private function emptyCounts(): array
    {
        $counts = [];
        foreach (array_keys(self::FILTERS) as $key) {
            $counts[$key] = 0;
        }

        return $counts;
    }

    /**
     * @param  array<int, array<string, mixed>>  $groups
     * @return array<string, int>
     */
    private function buildCounts(array $groups): array
    {
        $counts = $this->emptyCounts();

        foreach ($groups as $group) {
            foreach ($group['items'] as $item) {
                $counts['all']++;

                foreach (self::FILTERS as $filterKey => $filter) {
                    if ($filterKey === 'all') {
                        continue;
                    }

                    if (in_array($group['key'], $filter['keys'], true)) {
                        $counts[$filterKey]++;
                    }
                }
            }
        }

        return $counts;
    }

    /**
     * @param  array<int, array<string, mixed>>  $groups
     * @return array<int, array<string, mixed>>
     */
    private function filterGroups(array $groups, string $type): array
    {
        if ($type === 'all') {
            return $groups;
        }

        $allowedKeys = self::FILTERS[$type]['keys'];

        return array_values(array_filter(
            $groups,
            fn (array $group) => in_array($group['key'], $allowedKeys, true)
        ));
    }

    /**
     * @param  array<int, array<string, mixed>>  $groups
     * @return array<int, array<string, mixed>>
     */
    private function scoreAndSortGroups(array $groups, string $query, string $sort): array
    {
        foreach ($groups as &$group) {
            $group['items'] = array_map(function (array $item) use ($query) {
                $item['relevance'] = $this->relevanceScore(
                    $query,
                    (string) $item['title'],
                    (string) ($item['excerpt'] ?? '')
                );

                return $item;
            }, $group['items']);

            usort($group['items'], function (array $a, array $b) use ($sort) {
                if ($sort === 'newest') {
                    $dateCompare = ($b['sort_date'] ?? 0) <=> ($a['sort_date'] ?? 0);
                    if ($dateCompare !== 0) {
                        return $dateCompare;
                    }
                }

                $relevanceCompare = ($b['relevance'] ?? 0) <=> ($a['relevance'] ?? 0);
                if ($relevanceCompare !== 0) {
                    return $relevanceCompare;
                }

                return strcasecmp((string) $a['title'], (string) $b['title']);
            });
        }
        unset($group);

        if ($sort === 'relevance') {
            usort($groups, function (array $a, array $b) {
                $topA = $a['items'][0]['relevance'] ?? 0;
                $topB = $b['items'][0]['relevance'] ?? 0;

                return $topB <=> $topA;
            });
        }

        return $groups;
    }

    private function relevanceScore(string $query, string $title, string $excerpt = ''): int
    {
        $needle = Str::lower(trim($query));
        $normalizedNeedle = $this->normalize($query);
        $titleLower = Str::lower($title);
        $titleNormalized = $this->normalize($title);
        $excerptLower = Str::lower($excerpt);

        if ($needle === '') {
            return 0;
        }

        if ($titleLower === $needle || $titleNormalized === $normalizedNeedle) {
            return 100;
        }

        if (Str::startsWith($titleLower, $needle) || Str::startsWith($titleNormalized, $normalizedNeedle)) {
            return 85;
        }

        if (Str::contains($titleLower, $needle) || Str::contains($titleNormalized, $normalizedNeedle)) {
            return 70;
        }

        if ($excerpt !== '' && Str::contains($excerptLower, $needle)) {
            return 45;
        }

        return 20;
    }

    /** @return array<string, mixed> */
    private function searchBrokers(string $query, string $normalized): array
    {
        $brokers = Broker::query()
            ->where(function ($builder) use ($query, $normalized) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('title', 'like', "%{$query}%")
                    ->orWhere(
                        DB::raw("REPLACE(REPLACE(name,' ',''),'-','')"),
                        'like',
                        "%{$normalized}%"
                    );
            })
            ->orderByDesc('rating')
            ->orderBy('name')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $brokers->map(function (Broker $broker) {
            $isScam = (bool) $broker->is_scam;

            return $this->item(
                title: $broker->name,
                url: $isScam
                    ? route('scam_broker_detail', ['slug' => $broker->listingSlug()])
                    : route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]),
                type: $isScam ? 'scam_broker' : 'broker',
                typeLabel: $isScam ? 'Scam Broker' : 'Broker Review',
                excerpt: Str::limit(strip_tags((string) ($broker->short_description ?: $broker->meta_description ?: $broker->description)), 140),
                image: $broker->logo ? asset($broker->logo) : asset('images/default-broker.png'),
                meta: $isScam ? 'Reported scam' : ($broker->rating ? 'Rating '.$broker->rating.'/5' : null),
                sortDate: optional($broker->updated_at)->timestamp ?? optional($broker->created_at)->timestamp,
            );
        })->all();

        return $this->group('Broker Reviews', 'broker', $items);
    }

    /** @return array<string, mixed> */
    private function searchPosts(string $query, int $languageId): array
    {
        $posts = Post::query()
            ->with('rSubCategory')
            ->where('language_id', $languageId)
            ->where(function ($builder) use ($query) {
                $builder->where('post_title', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('post_detail', 'like', "%{$query}%")
                    ->orWhere('meta_title', 'like', "%{$query}%")
                    ->orWhere('meta_description', 'like', "%{$query}%")
                    ->orWhere('meta_keywords', 'like', "%{$query}%");
            })
            ->latest('id')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $blog = app(BlogIndexService::class);
        $items = $posts->map(function (Post $post) use ($blog) {
            $serialized = $blog->serializePost($post);

            return $this->item(
                title: (string) $serialized['title'],
                url: (string) $serialized['url'],
                type: 'article',
                typeLabel: 'Article',
                excerpt: (string) $serialized['excerpt'],
                image: $serialized['photo'],
                meta: (string) ($serialized['category'] ?? 'Insights'),
                sortDate: optional($post->created_at)->timestamp,
            );
        })->all();

        return $this->group('Articles & Insights', 'article', $items);
    }

    /** @return array<string, mixed> */
    private function searchPropFirms(string $query): array
    {
        $firms = PropFirm::query()
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('headquarters', 'like', "%{$query}%")
                    ->orWhere('meta_title', 'like', "%{$query}%")
                    ->orWhere('meta_description', 'like', "%{$query}%");
            })
            ->orderByDesc('overall_rating')
            ->orderBy('name')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $firms->map(fn (PropFirm $firm) => $this->item(
            title: $firm->name,
            url: route('prop_firms.show', ['slug' => $firm->slug]),
            type: 'prop_firm',
            typeLabel: 'Prop Firm',
            excerpt: Str::limit(strip_tags((string) ($firm->meta_description ?: $firm->description)), 140),
            image: $firm->logo ? asset($firm->logo) : null,
            meta: $firm->headquarters,
            sortDate: optional($firm->updated_at)->timestamp ?? optional($firm->created_at)->timestamp,
        ))->all();

        $categories = PropFirmCategory::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(fn (PropFirmCategory $category) => $this->item(
                title: $category->name,
                url: route('prop_firms.category', ['slug' => $category->slug]),
                type: 'prop_firm_category',
                typeLabel: 'Prop Firm Category',
                excerpt: Str::limit(strip_tags((string) $category->description), 140),
                image: null,
                meta: 'Prop firm category',
                sortDate: optional($category->updated_at)->timestamp ?? optional($category->created_at)->timestamp,
            ))
            ->all();

        return $this->group('Prop Firms', 'prop_firm', array_merge($items, $categories));
    }

    /** @return array<string, mixed> */
    private function searchBonuses(string $query): array
    {
        $bonuses = ForexBonus::query()
            ->with('broker')
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('details', 'like', "%{$query}%")
                    ->orWhere('promo_type', 'like', "%{$query}%")
                    ->orWhereHas('broker', fn ($broker) => $broker->where('name', 'like', "%{$query}%"));
            })
            ->where(function ($builder) {
                $builder->whereNull('promotion_status')
                    ->orWhereIn('promotion_status', ['ongoing', 'limited-time']);
            })
            ->latest('id')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $bonuses->map(function (ForexBonus $bonus) {
            $url = $bonus->detailUrl();

            if (! $url) {
                return null;
            }

            return $this->item(
                title: $bonus->title,
                url: $url,
                type: 'bonus',
                typeLabel: 'Promotion',
                excerpt: Str::limit(strip_tags((string) ($bonus->description ?: $bonus->details)), 140),
                image: $bonus->feature_image ? asset('uploads/'.$bonus->feature_image) : null,
                meta: $bonus->brokerDisplayName() ?: $bonus->promo_type,
                sortDate: optional($bonus->created_at)->timestamp,
            );
        })->filter()->values()->all();

        return $this->group('Promotions & Bonuses', 'bonus', $items);
    }

    /** @return array<string, mixed> */
    private function searchAuthors(string $query): array
    {
        $authors = Author::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('bio', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $authors->map(fn (Author $author) => $this->item(
            title: $author->name,
            url: $author->profileUrl(),
            type: 'author',
            typeLabel: 'Team Member',
            excerpt: Str::limit(strip_tags((string) $author->bio), 140),
            image: $author->photo ? asset('uploads/'.$author->photo) : null,
            meta: 'Editorial team',
            sortDate: optional($author->updated_at)->timestamp ?? optional($author->created_at)->timestamp,
        ))->all();

        return $this->group('Our Team', 'author', $items);
    }

    /** @return array<string, mixed> */
    private function searchCmsPages(string $query): array
    {
        $pages = CmsPage::query()
            ->where('status', 'published')
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('meta_title', 'like', "%{$query}%")
                    ->orWhere('meta_description', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $pages->map(fn (CmsPage $page) => $this->item(
            title: $page->title,
            url: route('cms_page.show', ['slug' => $page->slug]),
            type: 'page',
            typeLabel: 'Page',
            excerpt: Str::limit(strip_tags((string) ($page->meta_description ?: $page->title)), 140),
            image: null,
            meta: 'Site page',
            sortDate: optional($page->updated_at)->timestamp ?? optional($page->created_at)->timestamp,
        ))->all();

        return $this->group('Pages', 'page', $items);
    }

    /** @return array<string, mixed> */
    private function searchTradingTools(string $query): array
    {
        $tools = TradingTool::query()
            ->active()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(self::PER_TYPE_LIMIT)
            ->get();

        $items = $tools->map(fn (TradingTool $tool) => $this->item(
            title: $tool->name,
            url: route('trading.tools.show', ['slug' => $tool->slug]),
            type: 'tool',
            typeLabel: 'Trading Tool',
            excerpt: Str::limit(strip_tags((string) ($tool->short_description ?: $tool->description)), 140),
            image: null,
            meta: 'Trading tool',
            sortDate: optional($tool->updated_at)->timestamp ?? optional($tool->created_at)->timestamp,
        ))->all();

        return $this->group('Trading Tools', 'tool', $items);
    }

    /** @return array<string, mixed> */
    private function searchGuides(string $query): array
    {
        $needle = Str::lower($query);
        $items = [];

        foreach (BrokerTaxonomy::categories() as $slug => $label) {
            if ($this->matchesText($needle, $label, $slug)) {
                $items[] = $this->item(
                    title: $label,
                    url: route('brokers.best', ['slug' => $slug]),
                    type: 'guide',
                    typeLabel: 'Best Brokers Guide',
                    excerpt: 'Compare top brokers for '.$label.'.',
                    image: null,
                    meta: 'Broker guide',
                );
            }
        }

        foreach (BrokerTaxonomy::regions() as $slug => $label) {
            if ($this->matchesText($needle, $label, $slug)) {
                $items[] = $this->item(
                    title: $label,
                    url: route('brokers.best', ['slug' => $slug]),
                    type: 'guide',
                    typeLabel: 'Regional Guide',
                    excerpt: 'Browse brokers available in '.$label.'.',
                    image: null,
                    meta: 'Regional guide',
                );
            }
        }

        return $this->group('Guides & Rankings', 'guide', array_slice($items, 0, self::PER_TYPE_LIMIT));
    }

    /** @return array<string, mixed> */
    private function searchStaticPages(string $query): array
    {
        $needle = Str::lower($query);
        $items = [];

        foreach ($this->staticPages() as $page) {
            $haystack = Str::lower($page['title'].' '.implode(' ', $page['keywords']));
            if (Str::contains($haystack, $needle)) {
                $items[] = $this->item(
                    title: $page['title'],
                    url: $page['url'],
                    type: 'site',
                    typeLabel: 'Site Page',
                    excerpt: $page['excerpt'],
                    image: null,
                    meta: $page['meta'],
                );
            }
        }

        return $this->group('Site Sections', 'site', array_slice($items, 0, self::PER_TYPE_LIMIT));
    }

    /** @return array<int, array<string, mixed>> */
    private function staticPages(): array
    {
        return [
            [
                'title' => 'Broker Reviews',
                'keywords' => ['broker', 'reviews', 'ratings', 'forex'],
                'excerpt' => 'Browse independent forex broker reviews and ratings.',
                'meta' => 'Reviews hub',
                'url' => route('broker.reviews.index'),
            ],
            [
                'title' => 'Find My Broker',
                'keywords' => ['find', 'match', 'quiz', 'broker finder'],
                'excerpt' => 'Answer a few questions to get broker recommendations.',
                'meta' => 'Broker finder',
                'url' => route('find_my_broker'),
            ],
            [
                'title' => 'Compare Brokers',
                'keywords' => ['compare', 'comparison', 'side by side'],
                'excerpt' => 'Compare fees, regulation, and platforms side by side.',
                'meta' => 'Comparison tool',
                'url' => route('broker.comparison'),
            ],
            [
                'title' => 'Scam Broker Checker',
                'keywords' => ['scam', 'fraud', 'checker', 'verify'],
                'excerpt' => 'Check whether a broker name is flagged in our scam database.',
                'meta' => 'Safety tool',
                'url' => route('broker.scam_checker'),
            ],
            [
                'title' => 'Broker Promotions',
                'keywords' => ['promo', 'promotion', 'bonus', 'deposit'],
                'excerpt' => 'Active broker bonuses, contests, and cashback offers.',
                'meta' => 'Promotions hub',
                'url' => route('promotions.index'),
            ],
            [
                'title' => 'Prop Firms',
                'keywords' => ['prop', 'funding', 'funded', 'challenge'],
                'excerpt' => 'Compare prop trading firms, fees, and funding rules.',
                'meta' => 'Prop firms hub',
                'url' => route('prop_firms.index'),
            ],
            [
                'title' => 'Best Brokers',
                'keywords' => ['best', 'top', 'rankings', 'regulated'],
                'excerpt' => 'Explore curated best-broker rankings by category and country.',
                'meta' => 'Rankings hub',
                'url' => route('brokers.best.index'),
            ],
            [
                'title' => 'Blog',
                'keywords' => ['blog', 'news', 'insights', 'articles'],
                'excerpt' => 'Latest market news, broker analysis, and trading guides.',
                'meta' => 'News hub',
                'url' => route('blog'),
            ],
            [
                'title' => 'Our Methodology',
                'keywords' => ['methodology', 'research', 'editorial', 'ratings'],
                'excerpt' => 'How BrokersCourt researches and rates brokers.',
                'meta' => 'Editorial policy',
                'url' => route('methodology'),
            ],
            [
                'title' => 'Contact Us',
                'keywords' => ['contact', 'support', 'email', 'help'],
                'excerpt' => 'Get in touch with the BrokersCourt team.',
                'meta' => 'Support',
                'url' => route('contact'),
            ],
            [
                'title' => 'About Us',
                'keywords' => ['about', 'company', 'team', 'mission'],
                'excerpt' => 'Learn about BrokersCourt and our editorial mission.',
                'meta' => 'Company',
                'url' => route('about.us'),
            ],
            [
                'title' => 'Awards',
                'keywords' => ['awards', 'winners', 'best broker'],
                'excerpt' => 'BrokersCourt annual broker and prop firm awards.',
                'meta' => 'Awards hub',
                'url' => route('awards.index'),
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<string, mixed>
     */
    private function group(string $label, string $key, array $items): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'items' => $items,
        ];
    }

    /** @return array<string, mixed> */
    private function item(
        string $title,
        string $url,
        string $type,
        string $typeLabel,
        ?string $excerpt = null,
        ?string $image = null,
        ?string $meta = null,
        ?int $sortDate = null,
    ): array {
        return [
            'title' => $title,
            'url' => $url,
            'type' => $type,
            'type_label' => $typeLabel,
            'excerpt' => $excerpt,
            'image' => $image,
            'meta' => $meta,
            'sort_date' => $sortDate ?? 0,
        ];
    }

    private function normalize(string $query): string
    {
        return str_replace([' ', '-'], '', Str::lower($query));
    }

    private function matchesText(string $needle, string $label, string $slug): bool
    {
        $haystack = Str::lower($label.' '.$slug);

        return Str::contains($haystack, $needle);
    }
}
