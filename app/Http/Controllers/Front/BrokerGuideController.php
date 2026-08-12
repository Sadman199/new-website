<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Services\BrokerGuideContextService;
use App\Services\BrokerGuideService;
use App\Services\EditorialAssignmentService;
use Illuminate\Support\Str;

class BrokerGuideController extends Controller
{
    public function __construct(
        protected BrokerGuideService $guideService,
        protected BrokerGuideContextService $contextService,
    ) {}

    public function show(string $slug, string $topic)
    {
        $brokerSlug = $this->resolveBrokerSlug($slug);

        $broker = Broker::with([
            'accountOptions',
            'guides',
            'writtenByAuthor',
            'editedByAuthor',
            'factCheckedByAuthor',
            'writtenByAdmin',
            'editedByAdmin',
            'factCheckedByAdmin',
        ])
            ->where('slug', $brokerSlug)
            ->firstOrFail();

        $guide = $this->guideService->findPublishedGuide($broker, $topic);

        if (! $guide) {
            abort(404);
        }

        $guide->setRelation('broker', $broker);

        $context = $this->contextService->forBroker($broker);
        $publishedGuides = $this->guideService->publishedGuidesForBroker($broker);
        $editorialTeam = EditorialAssignmentService::teamFor($broker);

        $reviewSlug = BrokerController::reviewSlugFor($broker);

        $guidePageMeta = [
            'updated_at' => $guide->updated_at
                ? $guide->updated_at->format('M j, Y')
                : ($broker->updated_at?->format('M j, Y') ?? ''),
            'updated_relative' => $guide->updated_at?->diffForHumans(),
        ];

        return view('front.brokers.guide_show', [
            'broker' => $broker,
            'guide' => $guide,
            'context' => $context,
            'publishedGuides' => $publishedGuides,
            'editorialTeam' => $editorialTeam,
            'reviewSlug' => $reviewSlug,
            'guidePageMeta' => $guidePageMeta,
            'topic' => $guide->topic,
        ]);
    }

    private function resolveBrokerSlug(string $slug): string
    {
        if (Broker::where('slug', $slug)->exists()) {
            return $slug;
        }

        $baseSlug = Str::endsWith($slug, '-review')
            ? substr($slug, 0, -strlen('-review'))
            : $slug;

        if (Broker::where('slug', $baseSlug)->exists()) {
            return $baseSlug;
        }

        abort(404);
    }
}
