<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrokerGuideTopicRequest;
use App\Models\BrokerGuide;
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

        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'status' => (string) $request->get('status', ''),
        ];

        $query = BrokerGuideTopic::query()->withCount('guides');

        if ($filters['q'] !== '') {
            $term = $filters['q'];
            $query->where(function ($sub) use ($term) {
                $sub->where('title', 'like', '%'.$term.'%')
                    ->orWhere('slug', 'like', '%'.$term.'%')
                    ->orWhere('default_summary', 'like', '%'.$term.'%');
            });
        }

        if ($filters['status'] === 'active') {
            $query->where('is_active', true);
        } elseif ($filters['status'] === 'inactive') {
            $query->where('is_active', false);
        }

        $topics = $query->ordered()->get();

        $stats = [
            'total' => BrokerGuideTopic::query()->count(),
            'active' => BrokerGuideTopic::query()->where('is_active', true)->count(),
            'inactive' => BrokerGuideTopic::query()->where('is_active', false)->count(),
            'guides' => BrokerGuide::query()->count(),
        ];

        return view('admin.broker_guide_topics.index', [
            'topics' => $topics,
            'stats' => $stats,
            'filters' => $filters,
            'contextProfiles' => BrokerGuideTopic::contextProfileOptions(),
            'hub' => [
                'title' => $this->hubService->titleTemplate(),
                'description' => $this->hubService->description(),
            ],
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
        try {
            $topic = $this->topicService->save(new BrokerGuideTopic(), $this->payload($request));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create guide topic: '.$e->getMessage());
        }

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
        try {
            $topic = BrokerGuideTopic::findOrFail($id);
            $this->topicService->save($topic, $this->payload($request));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update guide topic: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_broker_guide_topics_index')
            ->with('success', 'Guide topic updated.');
    }

    public function destroy(int $id)
    {
        try {
            $topic = BrokerGuideTopic::findOrFail($id);
            $this->topicService->delete($topic);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Could not delete guide topic: '.$e->getMessage());
        }

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

        try {
            $this->hubService->saveSettings([
                'hub_title' => $data['hub_title'],
                'hub_description' => $data['hub_description'] ?? '',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not save hub settings: '.$e->getMessage());
        }

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
