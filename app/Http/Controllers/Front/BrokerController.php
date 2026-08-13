<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForexBonus;
use App\Models\Broker; 
use App\Models\Review;
use App\Models\Page;
use App\Models\HomeAdvertisement;
use App\Models\AccountOption;
use App\Models\Language;
use App\Helper\Helpers;
use App\Services\BestBrokerGuideService;
use App\Services\BlogIndexService;
use App\Services\BestBrokersIndexService;
use App\Services\BrokerReviewsIndexService;
use App\Services\BrokerAssessmentService;
use App\Services\BrokerReviewScoreService;
use App\Services\EditorialAssignmentService;
use App\Support\BrokerListingFilter;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class BrokerController extends Controller
{
    private const CATEGORY_SLUG_ALIASES = [
        'micro-account-brokers' => 'micro-accounts-brokers',
        'beginner-friendly-brokers' => 'brokers-for-beginners',
        'copy-trading-brokers' => 'copytrading-brokers',
    ];

    /** @var array<string, string> */
    private const PLATFORM_GUIDE_SLUGS = [
        'mt4' => 'mt4-brokers',
        'mt5' => 'mt5-brokers',
    ];

    /** @var string[] */
    private const LEGACY_BROKER_PATH_RESERVED = [
        'compare',
        'comparison',
        'filter',
        'award',
        'platform',
        'regulation',
    ];

    private const REVIEW_SLUG_ALIASES = [
        'db-investing-review' => 'db-investing',
        'exness-review' => 'exness',
        'just-markets-review' => 'JustMarkets',
        'tickmill-review' => 'tickmill',
        'xm-review' => 'xm',
        'fbs-review' => 'fbs',
        'fp-markets-review' => 'fpmarkets',
        'robo-forex-review' => 'roboforex',
        'one-royal-review' => 'oneroyal',
        'assetsfx-review' => 'assetsfx-broker',
    ];

    public function bestBrokersIndex(BestBrokersIndexService $indexService)
    {
        Helpers::read_json();

        $toplists = $indexService->toplists();
        $filterGroups = $indexService->filterGroups();

        return view('front.brokers.best_brokers_index', compact('toplists', 'filterGroups'));
    }

    public function reviewsIndex(
        BrokerReviewsIndexService $reviewsIndexService,
        BrokerAssessmentService $assessmentService
    ) {
        Helpers::read_json();

        $brokersPayload = \Illuminate\Support\Facades\Cache::remember('broker_reviews_index_v2', 1800, function () use ($reviewsIndexService, $assessmentService) {
            return Broker::query()
                ->where('is_scam', false)
                ->with(['accountOptions' => fn ($query) => $query->ordered()])
                ->withCount(['reviews as approved_review_count' => function ($query) {
                    $query->where('status', 1);
                }])
                ->orderByDesc('rating')
                ->orderBy('name')
                ->get()
                ->map(function (Broker $broker) use ($reviewsIndexService, $assessmentService) {
                    $payload = $reviewsIndexService->serialize($broker);
                    $payload['performance'] = $assessmentService->cardMetrics($broker);

                    return $payload;
                })
                ->values()
                ->all();
        });

        $marketFilters = $reviewsIndexService->marketFilters();

        return view('front.brokers.reviews_index', [
            'brokersPayload' => collect($brokersPayload),
            'marketFilters' => $marketFilters,
        ]);
    }

    public function reviewDetail($slug)
    {
        $brokerSlug = $this->resolveReviewSlug($slug);
        $broker = Broker::where('slug', $brokerSlug)->firstOrFail();
        $canonical = self::reviewSlugFor($broker);

        if ($slug !== $canonical) {
            return redirect()->route('broker_detail', ['slug' => $canonical], 301);
        }

        return $this->detail($brokerSlug);
    }

    public static function reviewSlugFor(Broker $broker): string
    {
        foreach (self::REVIEW_SLUG_ALIASES as $reviewSlug => $brokerSlug) {
            if ($brokerSlug === $broker->slug) {
                return $reviewSlug;
            }
        }

        if (Str::endsWith($broker->slug, '-review')) {
            return $broker->slug;
        }

        return $broker->slug . '-review';
    }

    private function resolveCategorySlug(string $slug): string
    {
        return self::CATEGORY_SLUG_ALIASES[$slug] ?? $slug;
    }

    private function resolveReviewSlug(string $slug): string
    {
        if (isset(self::REVIEW_SLUG_ALIASES[$slug])) {
            return self::REVIEW_SLUG_ALIASES[$slug];
        }

        if (Broker::where('slug', $slug)->exists()) {
            return $slug;
        }

        $baseSlug = Str::endsWith($slug, '-review')
            ? substr($slug, 0, -strlen('-review'))
            : $slug;

        if (Broker::where('slug', $baseSlug)->exists()) {
            return $baseSlug;
        }

        $broker = Broker::query()
            ->where(function ($query) use ($slug, $baseSlug) {
                $query->where('slug', $baseSlug)
                    ->orWhereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [$baseSlug])
                    ->orWhereRaw('LOWER(REPLACE(name, " ", "-")) = ?', [$slug]);
            })
            ->first();

        if ($broker) {
            return $broker->slug;
        }

        abort(404);
    }

    public function detail($slug)
    {
        Helpers::read_json(); // Optional helper functionality
    
        // Determine the current language
        if (!session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }
    
        // Get the current language ID
        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
    
        // Fetch the broker details by slug
        $broker = Broker::with([
            'faqs',
            'accountOptions',
            'guides',
            'forexBonuses' => fn ($q) => $q->where('promotion_status', '!=', 'expired')->latest('publish_date'),
            'writtenByAuthor',
            'editedByAuthor',
            'factCheckedByAuthor',
            'writtenByAdmin',
            'editedByAdmin',
            'factCheckedByAdmin',
        ])->where('slug', $slug)->firstOrFail();

        $editorialCredits = EditorialAssignmentService::creditsFor($broker);
        $editorialTeam = EditorialAssignmentService::teamFor($broker);
    
        // Fetch the page data for the current language
        $page_data = Page::where('language_id', $current_language_id)->first();
    
        // Fetch the latest 5 brokers
        $brokers = Broker::latest()->take(5)->get();
    
        // Fetch approved reviews for the broker (eager load author for verified badge)
        $approved_reviews = $broker->reviews()->where('status', 1)->with('user')->latest()->get();
        foreach ($approved_reviews as $review) {
            $review->formatted_date = $review->created_at->format('M d, Y');
            $review->time_ago = $review->created_at->diffForHumans();
        }

        $reviewStats = [
            'count' => $approved_reviews->count(),
            'average' => round((float) $approved_reviews->avg('rating'), 1),
        ];

        $userReview = auth('web')->check()
            ? $broker->reviews()->where('user_id', auth('web')->id())->first()
            : null;
    
        // Fetch the featured brokers
        $featured = Broker::where('featured_broker', 1)->get();
    
        // Fetch the home advertisement data
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
    
        $faqs = $broker->faqs;
        $account_options = $broker->accountOptions->filter(function ($option) {
            return ($option->is_active ?? true) !== false;
        });

        $reviewPageMeta = [
            'updated_at' => $broker->updated_at
                ? $broker->updated_at->format('M j, Y')
                : now()->format('M j, Y'),
            'updated_relative' => $broker->updated_at
                ? $broker->updated_at->diffForHumans()
                : null,
        ];

        $scoreBreakdown = app(BrokerReviewScoreService::class)->breakdown($broker);
        $snapshot = \App\Support\BrokerReviewPresenter::decisionSnapshot($broker, $reviewStats);

        $compare_brokers = Broker::query()
            ->where('id', '!=', $broker->id)
            ->where('is_scam', false)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderByDesc('rating')
            ->limit(8)
            ->get();

        $publishedGuides = app(\App\Services\BrokerGuideService::class)->publishedGuidesForBroker($broker);
        $guideHubTitle = app(\App\Services\BrokerGuideHubService::class)->titleFor($broker);
        $guideHubDescription = app(\App\Services\BrokerGuideHubService::class)->description();

        $reviewToc = \App\Support\BrokerReviewPresenter::tableOfContents($broker, $account_options, $scoreBreakdown['has_scores']);
        $reviewJsonLd = \App\Support\BrokerReviewJsonLd::graph(
            $broker,
            $approved_reviews,
            $faqs,
            $reviewStats,
            $editorialTeam,
            $snapshot,
        );

        // Return the view and pass all necessary data
        return view('front.brokers.broker_detail', compact(
            'broker',
            'page_data',
            'approved_reviews',
            'brokers',
            'home_ad_data',
            'featured',
            'compare_brokers',
            'faqs',
            'account_options',
            'editorialCredits',
            'editorialTeam',
            'reviewStats',
            'userReview',
            'reviewPageMeta',
            'reviewToc',
            'scoreBreakdown',
            'snapshot',
            'publishedGuides',
            'guideHubTitle',
            'guideHubDescription',
            'reviewJsonLd',
        ));
    }
    


public function liveSearch(Request $request)
{
    return $this->search($request);
}

public function search(Request $request)
{
    $query = $request->get('query');

    if (!$query) {
        return response()->json([]);
    }

    $normalizedQuery = str_replace([' ', '-'], '', $query);

    $brokers = Broker::where('name', 'LIKE', "%{$query}%")
        ->orWhere(
            DB::raw("REPLACE(REPLACE(name,' ',''),'-','')"),
            'LIKE',
            "%{$normalizedQuery}%"
        )
        ->limit(8)
        ->get()
        ->map(function ($broker) {
            return [
                'name' => $broker->name,
                'slug' => $broker->slug,
                'logo_url' => $broker->logo
                    ? asset($broker->logo)
                    : asset('images/default-broker.png'),
            ];
        });

    return response()->json($brokers);
}




public function byAward($award)
{
    $awardKey = \App\Support\AwardTaxonomy::keyForRouteSlug($award);
    abort_if($awardKey === null, 404);

    return redirect()->route('awards.show', [
        'award' => \App\Support\AwardTaxonomy::routeSlugFor($awardKey),
    ], 301);
}




    public function bestBrokers($slug, BestBrokerGuideService $guideService, BlogIndexService $blogIndexService)
    {
        $slug = $this->resolveCategorySlug($slug);
        $guidePage = $guideService->build($slug);

        abort_if($guidePage === null, 404);

        $latestPosts = $blogIndexService->latestPosts($blogIndexService->resolveLanguageId(), 3);

        return view('front.brokers.best_broker_guide', compact('guidePage', 'latestPosts'));
    }

    public function legacyBestBrokerRedirect(string $slug)
    {
        if (in_array($slug, self::LEGACY_BROKER_PATH_RESERVED, true)) {
            abort(404);
        }

        $resolved = $this->resolveCategorySlug($slug);

        if (BrokerListingFilter::slugType($resolved) === null) {
            abort(404);
        }

        return redirect()->route('brokers.best', ['slug' => $resolved], 301);
    }

    public function legacyPlatformRedirect(string $slug)
    {
        $guideSlug = self::PLATFORM_GUIDE_SLUGS[$slug] ?? null;

        if ($guideSlug !== null && BrokerListingFilter::slugType($guideSlug) !== null) {
            return redirect()->route('brokers.best', ['slug' => $guideSlug], 301);
        }

        abort(404);
    }

}