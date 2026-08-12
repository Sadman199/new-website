<?php

namespace App\Services;

use App\Models\Broker;
use App\Models\BrokerGuideTopic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrokerGuideTopicService
{
    public function activeTopics(): Collection
    {
        if (! $this->topicsTableExists()) {
            return collect();
        }

        return BrokerGuideTopic::query()->active()->ordered()->get();
    }

    public function save(BrokerGuideTopic $topic, array $data): BrokerGuideTopic
    {
        $wasInactive = $topic->exists && ! $topic->is_active;

        $topic->fill([
            'slug' => $this->normalizeSlug($data['slug'] ?? $data['title']),
            'title' => $data['title'],
            'default_summary' => $data['default_summary'] ?? null,
            'icon' => $data['icon'] ?? null,
            'context_profile' => $data['context_profile'] ?? null,
            'requires_swap_free' => (bool) ($data['requires_swap_free'] ?? false),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => (bool) ($data['is_active'] ?? true),
        ])->save();

        if ($topic->wasChanged('sort_order')) {
            $topic->guides()->update(['sort_order' => $topic->sort_order]);
        }

        if ($topic->is_active && ($wasInactive || $topic->wasRecentlyCreated)) {
            app(BrokerGuideService::class)->syncTopicToAllBrokers($topic);
        }

        return $topic->fresh();
    }

    public function delete(BrokerGuideTopic $topic): void
    {
        $topic->delete();
    }

    public function slugIsAvailable(string $slug, ?int $ignoreId = null): bool
    {
        $slug = $this->normalizeSlug($slug);

        if ($slug === '') {
            return false;
        }

        $query = BrokerGuideTopic::query()->where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return ! $query->exists();
    }

    public function normalizeSlug(string $value): string
    {
        return Str::slug($value);
    }

    /** Seed default topics when table is empty. */
    public function seedDefaultsIfEmpty(): void
    {
        if (! $this->topicsTableExists() || BrokerGuideTopic::query()->exists()) {
            return;
        }

        $defaults = [
            ['slug' => 'best-account-type', 'title' => 'Which account type is best for you', 'default_summary' => 'Compare spreads, leverage, and features to pick the right account for your trading style.', 'icon' => 'fas fa-layer-group', 'context_profile' => 'account_types', 'sort_order' => 1],
            ['slug' => 'account-opening-guide', 'title' => 'Account opening guide', 'default_summary' => 'Step-by-step overview of documents, verification, and what to expect when signing up.', 'icon' => 'fas fa-file-signature', 'sort_order' => 2],
            ['slug' => 'demo-account', 'title' => 'How to open a demo account', 'default_summary' => 'Practice with virtual funds before committing real capital.', 'icon' => 'fas fa-flask', 'context_profile' => 'demo_cta', 'sort_order' => 3],
            ['slug' => 'live-account', 'title' => 'How to open a live trading account', 'default_summary' => 'Funding, verification, and platform setup for live trading.', 'icon' => 'fas fa-chart-line', 'context_profile' => 'live_cta', 'sort_order' => 4],
            ['slug' => 'islamic-account', 'title' => 'How the Islamic account works', 'default_summary' => 'Swap-free conditions, Sharia-compliant features, and eligibility.', 'icon' => 'fas fa-moon', 'requires_swap_free' => true, 'sort_order' => 5],
            ['slug' => 'deposits-withdrawals', 'title' => 'Minimum deposit & withdrawal', 'default_summary' => 'Funding methods, minimum amounts, fees, and payout timelines.', 'icon' => 'fas fa-wallet', 'context_profile' => 'deposits_withdrawals', 'sort_order' => 6],
        ];

        DB::transaction(function () use ($defaults) {
            foreach ($defaults as $row) {
                BrokerGuideTopic::create($row + ['is_active' => true]);
            }

            DB::table('broker_guide_settings')->updateOrInsert(
                ['key' => 'hub_title'],
                ['value' => config('broker-guides.hub.title')]
            );
            DB::table('broker_guide_settings')->updateOrInsert(
                ['key' => 'hub_description'],
                ['value' => config('broker-guides.hub.description')]
            );
        });
    }

    private function topicsTableExists(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('broker_guide_topics');
    }
}
