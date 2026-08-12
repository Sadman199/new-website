<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrokerGuideTopicRequest;
use App\Models\BrokerGuideTopic;
use App\Services\BrokerGuideHubService;
use App\Services\BrokerGuideTopicService;
use Illuminate\Http\Request;

class AdminBrokerGuideTopicController extends Controller
{
    public function __construct(
        protected BrokerGuideTopicService $topicService,
        protected BrokerGuideHubService $hubService,
    ) {}

    public function index(Request $request)
    {
        $this->topicService->seedDefaultsIfEmpty();

        $topics = BrokerGuideTopic::query()
            ->withCount('guides')
            ->ordered()
            ->get();

        return view('admin.broker_guide_topics.index', [
            'topics' => $topics,
            'contextProfiles' => BrokerGuideTopic::contextProfileOptions(),
        ]);
    }

    public function create()
    {
        return view('admin.broker_guide_topics.create', [
            'topic' => new BrokerGuideTopic(['is_active' => true, 'sort_order' => 0]),
            'contextProfiles' => BrokerGuideTopic::contextProfileOptions(),
        ]);
    }

    public function store(BrokerGuideTopicRequest $request)
    {
        $topic = $this->topicService->save(new BrokerGuideTopic(), $this->payload($request));

        return redirect()
            ->route('admin_broker_guide_topics_edit', $topic->id)
            ->with('success', 'Guide topic created. Broker drafts were synced automatically.');
    }

    public function edit(int $id)
    {
        $topic = BrokerGuideTopic::withCount('guides')->findOrFail($id);

        return view('admin.broker_guide_topics.edit', [
            'topic' => $topic,
            'contextProfiles' => BrokerGuideTopic::contextProfileOptions(),
        ]);
    }

    public function update(BrokerGuideTopicRequest $request, int $id)
    {
        $topic = BrokerGuideTopic::findOrFail($id);
        $this->topicService->save($topic, $this->payload($request));

        return redirect()
            ->route('admin_broker_guide_topics_index')
            ->with('success', 'Guide topic updated.');
    }

    public function destroy(int $id)
    {
        $topic = BrokerGuideTopic::findOrFail($id);
        $this->topicService->delete($topic);

        return redirect()
            ->route('admin_broker_guide_topics_index')
            ->with('success', 'Guide topic removed.');
    }

    public function updateHub(Request $request)
    {
        $data = $request->validate([
            'hub_title' => ['required', 'string', 'max:255'],
            'hub_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->hubService->saveSettings([
            'hub_title' => $data['hub_title'],
            'hub_description' => $data['hub_description'] ?? '',
        ]);

        return redirect()
            ->route('admin_broker_guide_topics_index')
            ->with('success', 'Hub section settings updated.');
    }

    /** @return array<string, mixed> */
    private function payload(BrokerGuideTopicRequest $request): array
    {
        return [
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'default_summary' => $request->input('default_summary'),
            'icon' => $request->input('icon'),
            'context_profile' => $request->input('context_profile'),
            'requires_swap_free' => $request->boolean('requires_swap_free'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
