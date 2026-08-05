<?php

namespace App\Http\Controllers\Admin\Panel;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Broker;
use App\Models\Review;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;

abstract class PanelBaseController extends Controller
{
    protected function render(string $view, array $data = [])
    {
        return view($view, array_merge($this->sharedData(), $data));
    }

    protected function sharedData(): array
    {
        return [
            'panelBadges' => $this->panelBadges(),
            'adminUser' => Auth::guard('admin')->user(),
        ];
    }

    protected function panelBadges(): array
    {
        return [
            'brokers' => Broker::count(),
            'pending_reviews' => Review::where('status', 0)->count(),
        ];
    }

    protected function dashboardStats(): array
    {
        return [
            'brokers' => Broker::count(),
            'reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 0)->count(),
            'subscribers' => Subscriber::where('status', 'Active')->count(),
            'scam_brokers' => Broker::where('is_scam', true)->count(),
        ];
    }

    protected function recentActivity(int $limit = 8)
    {
        return ActivityLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
