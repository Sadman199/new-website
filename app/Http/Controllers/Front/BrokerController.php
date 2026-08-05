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
use App\Services\BestBrokersIndexService;
use App\Services\BrokerReviewsIndexService;
use App\Services\EditorialAssignmentService;
use App\Support\BrokerTaxonomy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class BrokerController extends Controller
{
    private const CATEGORY_SLUG_ALIASES = [
        'micro-accounts-brokers' => 'micro-account-brokers',
        'copytrading-brokers' => 'copy-trading-brokers',
        'brokers-for-beginners' => 'beginner-friendly-brokers',
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

    public function reviewsIndex(BrokerReviewsIndexService $reviewsIndexService)
    {
        Helpers::read_json();

        $brokers = Broker::query()
            ->where('is_scam', false)
            ->withCount(['reviews as approved_review_count' => function ($query) {
                $query->where('status', 1);
            }])
            ->orderByDesc('rating')
            ->orderBy('name')
            ->get();

        $brokersPayload = $brokers
            ->map(fn (Broker $broker) => $reviewsIndexService->serialize($broker))
            ->values();

        $marketFilters = $reviewsIndexService->marketFilters();

        return view('front.brokers.reviews_index', compact('brokersPayload', 'marketFilters'));
    }

    public function reviewDetail($slug)
    {
        return $this->detail($this->resolveReviewSlug($slug));
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

        $broker = Broker::all()->first(function (Broker $broker) use ($slug, $baseSlug) {
            $nameSlug = Str::slug($broker->name);

            return $broker->slug === $baseSlug
                || $nameSlug === $baseSlug
                || $nameSlug . '-review' === $slug
                || Str::slug($broker->name . ' review') === $slug;
        });

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
    
        // Fetch the featured brokers
        $featured = Broker::where('featured_broker', 1)->get();
    
        // Fetch the home advertisement data
        $home_ad_data = HomeAdvertisement::where('id', 1)->first();
    
        // Fetch all brokers for comparison, excluding the current broker
        $compare_brokers = Broker::where('id', '!=', $broker->id)
        ->inRandomOrder()
        ->limit(15)
        ->get();        $faqs = $broker->faqs;
        $account_options = $broker->accountOptions->filter(function ($option) {
            return ($option->is_active ?? true) !== false;
        });

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
        ));
    }
    


public function liveSearch(Request $request)
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
    // Filter brokers by award
    $brokers = Broker::all()->filter(function ($broker) use ($award) {
        $types = $broker->account_types;

        // Decode JSON string to array if necessary
        if (is_string($types)) {
            $types = json_decode($types, true);
        }

        if (!is_array($types)) return false;

        // Check if the award slug exists in the broker's account_types array
        return in_array($award, $types);
    });

    // Paginate filtered brokers
    $paginatedBrokers = new \Illuminate\Pagination\LengthAwarePaginator(
        $brokers->forPage(request()->get('page', 1), 10),
        $brokers->count(),
        10,
        request()->get('page', 1),
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // Featured brokers filtered by award/account type
    $featured_brokers = $brokers->filter(function ($broker) {
        return $broker->featured_broker == 1;
    })->take(6);

    // Top rated brokers filtered by award/account type
    $top_brokers = $brokers->sortByDesc('rating')->take(5);

    // Recommended brokers (overall top rated, not award-specific)
    $recommended_brokers = Broker::orderBy('rating', 'desc')->take(5)->get();

    // Metrics
    $metrics = [
        ['value' => $paginatedBrokers->count(), 'label' => 'Matching Brokers'],
        ['value' => $featured_brokers->count(), 'label' => 'Featured Brokers'],
        ['value' => $top_brokers->count(), 'label' => 'Top Rated Brokers'],
    ];

         return view('front.brokers.listing', [
            'paginatedBrokers' => $paginatedBrokers,
            'featured_brokers' => $featured_brokers,
            'top_brokers' => $top_brokers,
            'recommended_brokers' => $recommended_brokers,
            'metrics' => $metrics,
            'awardName' => $award, // rename here to match view
        ]);

}




    public function bestBrokers($slug, BestBrokerGuideService $guideService)
    {
        $slug = $this->resolveCategorySlug($slug);
        $guidePage = $guideService->build($slug);

        abort_if($guidePage === null, 404);

        return view('front.brokers.best_broker_guide', compact('guidePage'));
    }

}