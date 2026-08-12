<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Broker;
use App\Models\User;
use Illuminate\Support\Collection;

class UserSavedBrokerService
{
    public function __construct(
        private readonly BrokerReviewsIndexService $reviewsIndexService,
    ) {}

    /** @return array<int, string> */
    public function brokerIds(User $user): array
    {
        return $user->savedBrokers()
            ->pluck('brokers.id')
            ->map(fn ($id) => (string) $id)
            ->all();
    }

    /** @return Collection<int, array<string, mixed>> */
    public function cardsForUser(User $user): Collection
    {
        return $user->savedBrokers()
            ->where('is_scam', false)
            ->withCount(['reviews as approved_review_count' => fn ($q) => $q->where('status', 1)])
            ->orderByDesc('user_saved_brokers.created_at')
            ->get()
            ->map(fn (Broker $broker) => $this->reviewsIndexService->serialize($broker));
    }

    public function toggle(User $user, Broker $broker): bool
    {
        if ($user->savedBrokers()->where('broker_id', $broker->id)->exists()) {
            $user->savedBrokers()->detach($broker->id);
            ActivityLog::record('broker_unsaved', 'Removed '.$broker->name.' from saved brokers', $user->id);

            return false;
        }

        $user->savedBrokers()->attach($broker->id);
        ActivityLog::record('broker_saved', 'Saved '.$broker->name, $user->id);

        return true;
    }

    public function remove(User $user, Broker $broker): void
    {
        if ($user->savedBrokers()->where('broker_id', $broker->id)->exists()) {
            $user->savedBrokers()->detach($broker->id);
            ActivityLog::record('broker_unsaved', 'Removed '.$broker->name.' from saved brokers', $user->id);
        }
    }

    public function sync(User $user, array $brokerIds): int
    {
        $validIds = Broker::query()
            ->whereIn('id', $brokerIds)
            ->where('is_scam', false)
            ->pluck('id');

        $existing = $user->savedBrokers()->pluck('brokers.id');
        $merged = $existing->merge($validIds)->unique()->values();

        $user->savedBrokers()->syncWithoutDetaching($merged->all());

        return $merged->count();
    }
}
