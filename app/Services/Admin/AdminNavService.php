<?php

namespace App\Services\Admin;

use App\Models\Broker;
use App\Models\ContactInquiry;
use App\Models\Review;
use Illuminate\Support\Collection;

class AdminNavService
{
    /** @return array<int, array<string, string>> */
    public function notifications(): array
    {
        $items = [];

        $pendingCount = Review::query()->where('status', 0)->count();
        if ($pendingCount > 0) {
            $items[] = [
                'type' => 'reviews',
                'title' => 'Pending reviews',
                'message' => "{$pendingCount} " . ($pendingCount === 1 ? 'review needs' : 'reviews need') . ' moderation.',
                'url' => route('reviews.pending'),
                'time' => 'Action needed',
            ];
        }

        $newInquiries = ContactInquiry::query()
            ->where('status', ContactInquiry::STATUS_NEW)
            ->latest('id')
            ->take(3)
            ->get(['id', 'name', 'subject', 'created_at']);

        foreach ($newInquiries as $inquiry) {
            $items[] = [
                'type' => 'inquiry',
                'title' => $inquiry->subject ?: 'New inquiry',
                'message' => 'From ' . ($inquiry->name ?: 'Unknown'),
                'url' => route('admin_contact_inquiries.index'),
                'time' => $inquiry->created_at?->diffForHumans() ?? '',
            ];
        }

        Review::query()
            ->with('broker:id,name')
            ->where('status', 0)
            ->latest('id')
            ->take(2)
            ->get()
            ->each(function (Review $review) use (&$items) {
                if (count($items) >= 8) {
                    return;
                }
                $items[] = [
                    'type' => 'review',
                    'title' => 'Review by ' . ($review->name ?: 'User'),
                    'message' => ($review->broker?->name ?? 'Unknown broker') . ' · ' . number_format((float) $review->rating, 1) . ' ★',
                    'url' => route('reviews.pending'),
                    'time' => $review->created_at?->diffForHumans() ?? '',
                ];
            });

        return array_slice($items, 0, 8);
    }

    public function notificationCount(): int
    {
        return Review::query()->where('status', 0)->count()
            + ContactInquiry::query()->where('status', ContactInquiry::STATUS_NEW)->count();
    }

    /** @return Collection<int, array<string, string>> */
    public function navLinks(): Collection
    {
        $links = collect();

        foreach (config('admin-sidebar.sections', []) as $section) {
            foreach ($section['items'] as $item) {
                if (isset($item['route'])) {
                    $links->push([
                        'label' => $item['label'],
                        'url' => route($item['route']),
                        'icon' => $item['icon'] ?? 'fas fa-link',
                    ]);
                }

                foreach ($item['children'] ?? [] as $child) {
                    $links->push([
                        'label' => $child['label'],
                        'url' => route($child['route']),
                        'icon' => 'fas fa-angle-right',
                    ]);
                }
            }
        }

        return $links->unique('url')->values();
    }

    /** @return array<int, array<string, string>> */
    public function search(string $query, int $limit = 10): array
    {
        $query = trim($query);
        if (strlen($query) < 2) {
            return [];
        }

        $needle = mb_strtolower($query);
        $results = [];

        foreach ($this->navLinks() as $link) {
            if (str_contains(mb_strtolower($link['label']), $needle)) {
                $results[] = [
                    'type' => 'page',
                    'label' => $link['label'],
                    'url' => $link['url'],
                ];
            }
        }

        Broker::query()
            ->where('is_scam', false)
            ->where('name', 'like', '%' . $query . '%')
            ->orderByDesc('rating')
            ->take($limit)
            ->get(['id', 'name', 'slug'])
            ->each(function (Broker $broker) use (&$results) {
                $results[] = [
                    'type' => 'broker',
                    'label' => $broker->name,
                    'url' => route('admin_broker_edit', $broker->id),
                ];
            });

        return array_slice($results, 0, $limit);
    }
}
