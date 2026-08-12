<?php

namespace App\Services\Admin;

use App\Models\Broker;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\ContactInquiry;
use App\Models\Faq;
use App\Models\ForexBonus;
use App\Models\Post;
use App\Models\PropFirm;
use App\Models\Review;
use App\Models\SubCategory;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminDashboardService
{
    private const CACHE_TTL = 300;

    /** @return array<string, mixed> */
    public function build(): array
    {
        return Cache::remember('admin_dashboard_v1', self::CACHE_TTL, function () {
            $pendingReviews = Review::query()->where('status', 0)->count();
            $cmsStats = ['total' => 0, 'published' => 0, 'draft' => 0];

            try {
                $cmsStats = [
                    'total' => CmsPage::query()->count(),
                    'published' => CmsPage::query()->where('status', 'published')->count(),
                    'draft' => CmsPage::query()->where('status', 'draft')->count(),
                ];
            } catch (\Throwable) {
                // cms_pages table may not be migrated yet
            }

            return [
                'stats' => [
                    'brokers' => Broker::query()->where('is_scam', false)->count(),
                    'scam_brokers' => Broker::query()->where('is_scam', true)->count(),
                    'reviews' => Review::query()->count(),
                    'pending_reviews' => $pendingReviews,
                    'bonuses' => ForexBonus::query()->count(),
                    'posts' => Post::query()->count(),
                    'categories' => Category::query()->count(),
                    'subcategories' => SubCategory::query()->count(),
                    'cms_pages' => $cmsStats['total'],
                    'cms_published' => $cmsStats['published'],
                    'cms_draft' => $cmsStats['draft'],
                    'prop_firms' => PropFirm::query()->count(),
                    'faqs' => Faq::query()->count(),
                    'subscribers' => Subscriber::query()->where('status', 'Active')->count(),
                    'contact_new' => ContactInquiry::query()->where('status', ContactInquiry::STATUS_NEW)->count(),
                ],
                'recent_posts' => Post::query()
                    ->latest('id')
                    ->take(5)
                    ->get(['id', 'post_title', 'created_at']),
                'pending_review_items' => Review::query()
                    ->with('broker:id,name')
                    ->where('status', 0)
                    ->latest('id')
                    ->take(5)
                    ->get(['id', 'name', 'rating', 'broker_id', 'created_at']),
                'recent_inquiries' => ContactInquiry::query()
                    ->latest('id')
                    ->take(5)
                    ->get(['id', 'name', 'subject', 'status', 'created_at']),
            ];
        });
    }

    /** @return array<int, array<string, mixed>> */
    public function quickActions(): array
    {
        return [
            [
                'label' => 'Add Broker',
                'description' => 'Create a new broker profile',
                'route' => 'admin_broker_create',
                'icon' => 'briefcase',
            ],
            [
                'label' => 'New CMS Page',
                'description' => 'Build a landing or legal page',
                'route' => 'admin_cms_pages_create',
                'icon' => 'layer-group',
            ],
            [
                'label' => 'Write Post',
                'description' => 'Publish news or insights',
                'route' => 'admin_post_create',
                'icon' => 'pen',
            ],
            [
                'label' => 'Add Bonus',
                'description' => 'Promote a forex offer',
                'route' => 'admin_forex_bonus_create',
                'icon' => 'gift',
            ],
            [
                'label' => 'Review Queue',
                'description' => 'Moderate user submissions',
                'route' => 'reviews.pending',
                'icon' => 'star-half-alt',
            ],
            [
                'label' => 'Site Settings',
                'description' => 'Logo, SEO & brand colors',
                'route' => 'admin_setting',
                'icon' => 'cog',
            ],
        ];
    }

    public function greetingName(): string
    {
        $admin = Auth::guard('admin')->user();

        return $admin?->name ?: 'Admin';
    }

    public static function flush(): void
    {
        Cache::forget('admin_dashboard_v1');
    }
}
