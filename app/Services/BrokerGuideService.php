<?php

namespace App\Services;

use App\Http\Controllers\Front\BrokerController;
use App\Models\Broker;
use App\Models\BrokerGuide;
use App\Models\BrokerGuideTopic;
use Illuminate\Support\Collection;

class BrokerGuideService
{
    public function __construct(
        private readonly BrokerGuideContextService $contextService,
        private readonly BrokerGuideTopicService $topicService,
    ) {}

    /** Ensure all active topic guides exist for a broker (creates drafts). */
    public function ensureGuidesForBroker(Broker $broker): void
    {
        if (! $this->guidesTableExists()) {
            return;
        }

        $this->topicService->seedDefaultsIfEmpty();

        $existingTopicIds = $broker->guides()->pluck('broker_guide_topic_id')->all();

        foreach ($this->topicService->activeTopics() as $topic) {
            if (in_array($topic->id, $existingTopicIds, true)) {
                continue;
            }

            $broker->guides()->create([
                'broker_guide_topic_id' => $topic->id,
                'title' => $topic->title,
                'summary' => $topic->default_summary,
                'status' => BrokerGuide::STATUS_DRAFT,
                'sort_order' => $topic->sort_order,
            ]);
        }
    }

    public function syncTopicToAllBrokers(BrokerGuideTopic $topic): void
    {
        if (! $topic->is_active) {
            return;
        }

        Broker::query()->orderBy('id')->chunkById(50, function ($brokers) use ($topic) {
            foreach ($brokers as $broker) {
                $broker->guides()->firstOrCreate(
                    ['broker_guide_topic_id' => $topic->id],
                    [
                        'title' => $topic->title,
                        'summary' => $topic->default_summary,
                        'status' => BrokerGuide::STATUS_DRAFT,
                        'sort_order' => $topic->sort_order,
                    ]
                );
            }
        });
    }

    /** @return Collection<int, BrokerGuide> */
    public function guidesForAdmin(Broker $broker): Collection
    {
        $this->ensureGuidesForBroker($broker);

        return $broker->guides()->with('topic')->ordered()->get()
            ->sortBy(fn (BrokerGuide $guide) => [$guide->topic?->sort_order ?? 999, $guide->id])
            ->values();
    }

    /** @return Collection<int, BrokerGuide> */
    public function publishedGuidesForBroker(Broker $broker): Collection
    {
        if (! $this->guidesTableExists()) {
            return collect();
        }

        $this->ensureGuidesForBroker($broker);

        $context = $this->contextService->forBroker($broker);

        return $broker->guides()
            ->with('topic')
            ->published()
            ->ordered()
            ->get()
            ->filter(fn (BrokerGuide $guide) => $guide->topic && $this->isGuideVisible($guide, $context))
            ->sortBy(fn (BrokerGuide $guide) => [$guide->topic?->sort_order ?? 999, $guide->id])
            ->values();
    }

    public function findPublishedGuide(Broker $broker, string $topicSlug): ?BrokerGuide
    {
        if (! $this->guidesTableExists()) {
            return null;
        }

        $topic = BrokerGuideTopic::query()->active()->where('slug', $topicSlug)->first();

        if (! $topic) {
            return null;
        }

        $this->ensureGuidesForBroker($broker);

        $guide = $broker->guides()
            ->with('topic')
            ->where('broker_guide_topic_id', $topic->id)
            ->published()
            ->first();

        if (! $guide) {
            return null;
        }

        $context = $this->contextService->forBroker($broker);

        return $this->isGuideVisible($guide, $context) ? $guide : null;
    }

    public function save(BrokerGuide $guide, array $data): BrokerGuide
    {
        $guide->fill([
            'title' => $data['title'],
            'summary' => $data['summary'] ?? null,
            'content' => $data['content'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'status' => $data['status'] ?? BrokerGuide::STATUS_DRAFT,
        ]);
        $guide->save();

        return $guide->fresh(['broker', 'topic']);
    }

    public function publicUrl(BrokerGuide $guide): string
    {
        $broker = $guide->broker ?? Broker::find($guide->broker_id);
        $guide->loadMissing('topic');

        return route('broker.guide.show', [
            'slug' => BrokerController::reviewSlugFor($broker),
            'topic' => $guide->topicSlug(),
        ]);
    }

    /** @param array<string, mixed> $context */
    private function isGuideVisible(BrokerGuide $guide, array $context): bool
    {
        if (! $guide->topic?->is_active) {
            return false;
        }

        if ($guide->topic->requires_swap_free) {
            return (bool) ($context['has_swap_free'] ?? false);
        }

        return true;
    }

    private function guidesTableExists(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('broker_guides');
    }
}
