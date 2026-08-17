<?php

namespace App\Services;

use App\Models\BrokerReport;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class UserNotificationService
{
    public function notify(User $user, string $type, string $title, string $message, ?string $url = null): UserNotification
    {
        return UserNotification::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ]);
    }

    public function unreadCount(int $userId): int
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function recent(int $userId, int $limit = 10): Collection
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->latest()
            ->take($limit)
            ->get();
    }

    public function paginated(int $userId, int $perPage = 20)
    {
        return UserNotification::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function markRead(UserNotification $notification, int $userId): void
    {
        if ((int) $notification->user_id !== $userId) {
            return;
        }

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }
    }

    public function markAllRead(int $userId): void
    {
        UserNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function notifyReviewSubmitted(Review $review): void
    {
        if (! $review->user_id) {
            return;
        }

        $brokerName = $review->broker?->name ?? 'a broker';
        $isReply = $review->isReply();

        $this->notify(
            $review->user,
            'review_pending',
            $isReply ? 'Reply submitted' : 'Review submitted',
            $isReply
                ? "Your reply on {$brokerName} is pending approval."
                : "Your review for {$brokerName} is pending approval.",
            route('user.profile', ['tab' => 'overview']).'#ua-reviews'
        );
    }

    public function notifyReviewApproved(Review $review): void
    {
        if (! $review->user_id) {
            return;
        }

        $brokerName = $review->broker?->name ?? 'a broker';
        $slug = $review->broker?->slug;
        $isReply = $review->isReply();

        $this->notify(
            $review->user,
            'review_approved',
            $isReply ? 'Reply published' : 'Review published',
            $isReply
                ? "Your reply on {$brokerName} has been approved and is now live."
                : "Your review for {$brokerName} has been approved and is now live.",
            $slug ? route('broker_detail', ['slug' => $slug]) : route('user.profile', ['tab' => 'overview'])
        );
    }

    public function notifyReviewDeclined(Review $review): void
    {
        if (! $review->user_id) {
            return;
        }

        $brokerName = $review->broker?->name ?? 'a broker';
        $isReply = $review->isReply();

        $this->notify(
            $review->user,
            'review_declined',
            $isReply ? 'Reply not published' : 'Review not published',
            $isReply
                ? "Your reply on {$brokerName} was not approved. Contact us if you have questions."
                : "Your review for {$brokerName} was not approved. Contact us if you have questions.",
            route('user.profile', ['tab' => 'overview']).'#ua-reviews'
        );
    }

    public function notifyAccountVerified(User $user): void
    {
        $this->notify(
            $user,
            'account_verified',
            'Account verified',
            'Your BrokersCourt account has been verified. Your reviews now display a verified badge.',
            route('user.profile', ['tab' => 'overview'])
        );
    }

    public function notifyReportSubmitted(User $user, BrokerReport $report): void
    {
        $brokerName = $report->broker_name ?? $report->broker?->name ?? 'a broker';

        $this->notify(
            $user,
            'report_submitted',
            'Report submitted',
            "Your {$report->issueLabel()} report for {$brokerName} is pending review.",
            route('user.profile', ['tab' => 'safety'])
        );
    }

    public function notifyReportUpdated(User $user, BrokerReport $report): void
    {
        $brokerName = $report->broker_name ?? $report->broker?->name ?? 'a broker';
        $status = $report->statusLabel();

        $this->notify(
            $user,
            'report_updated',
            'Report update',
            "Your report for {$brokerName} was marked as {$status}.",
            route('user.profile', ['tab' => 'safety'])
        );
    }
}
