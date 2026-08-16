<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\PropFirm;
use App\Models\Review;
use App\Services\AwardsIndexService;
use App\Services\BlogIndexService;
use App\Services\BrokerPopularityService;
use App\Services\CountryBrokersService;
use App\Services\GlobalViewDataService;
use App\Support\BrokerMatchQuiz;
use App\Support\FindMyBrokerFilters;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $currentLanguageId = app(GlobalViewDataService::class)->currentLanguageId();

        $homeStats = Cache::remember('homepage_stats_v3', 3600, function () {
            $base = Broker::query()->where('is_scam', false);

            return [
                'total' => (clone $base)->count(),
                'regulated' => (clone $base)->where(function ($q) {
                    $q->where(function ($inner) {
                        $inner->whereNotNull('regulation')
                            ->where('regulation', '!=', '[]')
                            ->where('regulation', '!=', '');
                    })->orWhere('investor_protection', true);
                })->count(),
                'with_demo' => (clone $base)->where('demo_account_available', true)->count(),
                'avg_rating' => round((float) (clone $base)->whereNotNull('rating')->avg('rating'), 1),
                'reviews' => Review::query()->where('status', 1)->count(),
                'prop_firms' => PropFirm::query()->where('is_active', true)->count(),
            ];
        });

        $brokerCount = $homeStats['total'];

        $topRatedBrokers = app(CountryBrokersService::class)->globalTopRated(8);

        $bestForBeginners = Cache::remember('homepage_best_for_beginners_v2', 3600, function () {
            return Broker::query()
                ->where('is_scam', false)
                ->where('demo_account_available', true)
                ->where(function ($query) {
                    $query->where('minimum_deposit', '<=', 10)
                        ->orWhereHas('accountOptions', fn ($ao) => $ao->where('min_deposit', '<=', 10));
                })
                ->orderByDesc('rating')
                ->take(8)
                ->get();
        });

        $spreadRankings = Cache::remember('homepage_spread_rankings_v2', 3600, function () {
            return Broker::query()
                ->select('brokers.*')
                ->join('account_options', 'brokers.id', '=', 'account_options.broker_id')
                ->where('brokers.is_scam', false)
                ->orderBy('account_options.spread_value')
                ->take(8)
                ->get();
        });

        $best_leverage_brokers = Cache::remember('homepage_best_leverage_v2', 3600, function () {
            return Broker::query()
                ->where('is_scam', false)
                ->orderByDesc('leverage')
                ->take(8)
                ->get();
        });

        $bestBonuses = Cache::remember('homepage_best_bonuses_v2', 3600, function () {
            return Broker::query()
                ->where('is_scam', false)
                ->whereHas('accountOptions', function ($query) {
                    $query->whereNotNull('exclusive_offers')
                        ->orWhere('bonus_eligibility', 1);
                })
                ->orderByDesc('rating')
                ->take(8)
                ->get();
        });

        $editorialStreams = app(BlogIndexService::class)->editorialStreams($currentLanguageId);
        $recentNewsData = $editorialStreams['recent'];
        $popularNewsData = $editorialStreams['popular'];

        $awardWinners = Cache::remember('homepage_award_winners_v2', 3600, function () {
            return app(AwardsIndexService::class)->winnerHighlights();
        });

        $countrySlug = app(CountryBrokersService::class)->resolvePreferredCountry()['slug'] ?? 'global';

        $brokerSentiment = Cache::remember("homepage_broker_sentiment_v4_{$countrySlug}", 3600, function () use ($countrySlug) {
            return app(BrokerPopularityService::class)->forHomepage($countrySlug);
        });

        $searchCatalogs = FindMyBrokerFilters::homepageHeroCatalogs();
        $advancedCatalogs = FindMyBrokerFilters::catalogs();

        $quickFilterLinks = [
            ['label' => 'Low deposit ($10)', 'url' => route('find_my_broker', ['min_deposit' => 10])],
            ['label' => 'CySEC regulated', 'url' => route('find_my_broker', ['regulation' => 'cysec'])],
            ['label' => 'MetaTrader 5', 'url' => route('find_my_broker', ['platform' => 'mt5'])],
            ['label' => 'High leverage', 'url' => route('find_my_broker', ['leverage' => 1000])],
            ['label' => 'Low spreads', 'url' => route('find_my_broker', ['spread' => 'low'])],
            ['label' => 'Copy trading', 'url' => route('find_my_broker', ['features' => 'copy_trading'])],
        ];

        $matchQuizConfig = [
            'steps' => BrokerMatchQuiz::steps(),
            'options' => BrokerMatchQuiz::options(),
        ];

        $personalization = app(\App\Services\HomePersonalizationService::class)
            ->build(auth('web')->user(), $countrySlug);

        return view('front.homepage.home', [
            'homeStats' => $homeStats,
            'brokerCount' => $brokerCount,
            'topRatedBrokers' => $topRatedBrokers,
            'bestForBeginners' => $bestForBeginners,
            'spreadRankings' => $spreadRankings,
            'best_leverage_brokers' => $best_leverage_brokers,
            'bestBonuses' => $bestBonuses,
            'recentNewsData' => $recentNewsData,
            'popularNewsData' => $popularNewsData,
            'sentimentRecommended' => $brokerSentiment['recommended'],
            'sentimentScam' => $brokerSentiment['scam'],
            'sentimentRanking' => $brokerSentiment['ranking'],
            'searchCatalogs' => $searchCatalogs,
            'advancedCatalogs' => $advancedCatalogs,
            'quickFilterLinks' => $quickFilterLinks,
            'matchQuizConfig' => $matchQuizConfig,
            'personalization' => $personalization,
            'awardWinners' => $awardWinners,
        ]);
    }

    public function recommendedBrokers()
    {
        $countrySlug = app(CountryBrokersService::class)->resolvePreferredCountry()['slug'] ?? 'global';

        $sentiment = app(BrokerPopularityService::class)->forHomepage($countrySlug);

        return view('front.homepage.inc.broker_sentiment_recommended', [
            'sentimentRecommended' => $sentiment['recommended'],
            'preferredCountry' => app(CountryBrokersService::class)->resolvePreferredCountry($countrySlug),
        ]);
    }
}
