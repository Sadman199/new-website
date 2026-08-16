<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use App\Helper\Helpers;
use App\Models\Broker;
use App\Services\AwardsIndexService;
use App\Services\BrokerAssessmentService;
use App\Services\BrokerReviewsIndexService;
use App\Support\AwardTaxonomy;
use App\Support\BrokerRating;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AwardController extends Controller
{
    public function index(AwardsIndexService $awardsIndexService)
    {
        Helpers::read_json();

        if (! session()->get('session_short_name')) {
            $current_short_name = optional(Language::where('is_default', 'Yes')->first())->short_name ?? 'en';
        } else {
            $current_short_name = session()->get('session_short_name');
        }

        $current_language_id = optional(Language::where('short_name', $current_short_name)->first())->id ?? 1;
        $page_data = Page::where('language_id', $current_language_id)->first();

        $brokers = $awardsIndexService->baseBrokers();
        $awardCards = $awardsIndexService->awardCards($brokers);
        $stats = $awardsIndexService->stats($brokers);
        $evaluationPillars = $awardsIndexService->evaluationPillars();

        return view('front.awards.index', compact(
            'awardCards',
            'stats',
            'evaluationPillars',
            'page_data',
            'current_language_id',
            'current_short_name'
        ));
    }

    public function show(
        string $award,
        AwardsIndexService $awardsIndexService,
        BrokerReviewsIndexService $reviewsIndexService,
        BrokerAssessmentService $assessmentService,
        Request $request
    ) {
        $awardKey = AwardTaxonomy::keyForRouteSlug($award);
        abort_if($awardKey === null, 404);

        $definition = AwardTaxonomy::definitions()[$awardKey];
        $matchedIds = AwardTaxonomy::brokersFor($awardKey)->pluck('id');

        $brokers = Broker::query()
            ->whereIn('id', $matchedIds)
            ->with(['accountOptions' => fn ($query) => $query->ordered()])
            ->withCount(['reviews as approved_review_count' => fn ($query) => $query->where('status', 1)])
            ->orderByDesc('rating')
            ->orderBy('name')
            ->get();

        $perPage = 12;
        $page = max(1, (int) $request->get('page', 1));

        $paginatedBrokers = new LengthAwarePaginator(
            $brokers->forPage($page, $perPage)->values(),
            $brokers->count(),
            $perPage,
            $page,
            ['path' => route('awards.show', ['award' => AwardTaxonomy::routeSlugFor($awardKey)]), 'query' => $request->query()]
        );

        $brokersPayload = $paginatedBrokers->getCollection()
            ->map(function (Broker $broker) use ($reviewsIndexService, $assessmentService) {
                $payload = $reviewsIndexService->serialize($broker);
                $payload['performance'] = $assessmentService->cardMetrics($broker);

                return $payload;
            })
            ->values()
            ->all();

        $awardStats = [
            'winners' => $brokers->count(),
            'average_rating' => $brokers->isNotEmpty()
                ? number_format((float) $brokers->avg(fn (Broker $broker) => BrokerRating::outOfFive($broker->rating) ?? 0), 1)
                : '—',
            'verified_reviews' => (int) $brokers->sum('approved_review_count'),
            'regulated' => $brokers->filter(fn (Broker $broker) => $broker->isRegulated())->count(),
        ];

        $allAwardCards = collect($awardsIndexService->awardCards())
            ->reject(fn (array $card) => $card['slug'] === $awardKey)
            ->take(4)
            ->values()
            ->all();

        return view('front.awards.show', [
            'awardKey' => $awardKey,
            'awardName' => $definition['name'],
            'awardDescription' => $definition['description'],
            'awardColor' => $definition['color'],
            'routeSlug' => AwardTaxonomy::routeSlugFor($awardKey),
            'brokersPayload' => $brokersPayload,
            'paginatedBrokers' => $paginatedBrokers,
            'totalBrokers' => $brokers->count(),
            'awardStats' => $awardStats,
            'relatedAwards' => $allAwardCards,
        ]);
    }
}
