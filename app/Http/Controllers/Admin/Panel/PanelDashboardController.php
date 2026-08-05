<?php

namespace App\Http\Controllers\Admin\Panel;

class PanelDashboardController extends PanelBaseController
{
    public function index()
    {
        $activities = $this->recentActivity()->map(function ($log) {
            return [
                'icon' => $this->activityIcon($log->action),
                'icon_class' => $this->activityIconClass($log->action),
                'title' => $log->description ?: $log->label,
                'subtitle' => optional($log->user)->name . ' · ' . $log->created_at?->diffForHumans(),
            ];
        });

        return $this->render('admin.panel.pages.dashboard', [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'stats' => $this->dashboardStats(),
            'recentActivity' => $activities,
        ]);
    }

    private function activityIcon(string $action): string
    {
        return match ($action) {
            'review_submitted' => 'fas fa-star',
            'login', 'logout', 'registered' => 'fas fa-user',
            'verified_by_admin' => 'fas fa-check',
            default => 'fas fa-bell',
        };
    }

    private function activityIconClass(string $action): string
    {
        return match ($action) {
            'review_submitted' => 'yellow',
            'verified_by_admin' => 'green',
            'login', 'registered' => 'blue',
            default => 'yellow',
        };
    }
}
