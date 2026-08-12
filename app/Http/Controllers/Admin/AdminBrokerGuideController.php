<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrokerGuideRequest;
use App\Models\Broker;
use App\Models\BrokerGuide;
use App\Models\BrokerGuideTopic;
use App\Services\BrokerGuideService;

class AdminBrokerGuideController extends Controller
{
    public function __construct(
        protected BrokerGuideService $guideService,
    ) {}

    public function index(int $broker_id)
    {
        $broker = Broker::withCount(['accountOptions', 'guides'])->with('accountOptions')->findOrFail($broker_id);
        $guides = $this->guideService->guidesForAdmin($broker);

        return view('admin.broker_guides.index', compact('broker', 'guides'));
    }

    public function edit(int $broker_id, string $topic_slug)
    {
        $topic = BrokerGuideTopic::query()->where('slug', $topic_slug)->firstOrFail();
        $broker = Broker::withCount(['accountOptions', 'guides'])->with('accountOptions')->findOrFail($broker_id);
        $this->guideService->ensureGuidesForBroker($broker);

        $guide = BrokerGuide::query()
            ->with('topic')
            ->where('broker_id', $broker_id)
            ->where('broker_guide_topic_id', $topic->id)
            ->firstOrFail();

        return view('admin.broker_guides.edit', compact('broker', 'guide', 'topic'));
    }

    public function update(BrokerGuideRequest $request, int $broker_id, string $topic_slug)
    {
        $topic = BrokerGuideTopic::query()->where('slug', $topic_slug)->firstOrFail();

        $guide = BrokerGuide::query()
            ->where('broker_id', $broker_id)
            ->where('broker_guide_topic_id', $topic->id)
            ->firstOrFail();

        $this->guideService->save($guide, $request->validated());

        return redirect()
            ->route('admin_broker_guides_index', $broker_id)
            ->with('success', 'Guide updated successfully.');
    }
}
