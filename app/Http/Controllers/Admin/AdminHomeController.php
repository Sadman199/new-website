<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AdminDashboardService;
use Illuminate\Support\Facades\Auth;

class AdminHomeController extends AdminController
{
    public function __construct(protected AdminDashboardService $dashboard)
    {
    }

    public function index()
    {
        $data = $this->dashboard->build();

        return view('admin.home', [
            'stats' => $data['stats'],
            'recentPosts' => $data['recent_posts'],
            'pendingReviews' => $data['pending_review_items'],
            'recentInquiries' => $data['recent_inquiries'],
            'quickActions' => $this->dashboard->quickActions(),
            'adminName' => $this->dashboard->greetingName(),
            'adminPhoto' => Auth::guard('admin')->user()?->photo,
        ]);
    }
}
