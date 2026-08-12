<?php

namespace App\Http\Controllers\Admin\Panel;

use App\Models\BrokerReport;
use App\Models\User;
use App\Services\UserNotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PanelBrokerReportsController extends PanelBaseController
{
    public function __construct(
        private UserNotificationService $notifications
    ) {}

    public function index(Request $request)
    {
        $status = $request->get('status');

        $reports = BrokerReport::query()
            ->with('broker')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return $this->render('admin.panel.pages.broker-reports.index', [
            'title' => 'Broker Safety Reports',
            'pageTitle' => 'Broker Safety Management',
            'reports' => $reports,
            'statuses' => BrokerReport::STATUSES,
            'activeStatus' => $status,
        ]);
    }

    public function update(Request $request, BrokerReport $report)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(BrokerReport::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $previousStatus = $report->status;
        $report->update($validated);

        if ($previousStatus !== $report->status) {
            $this->notifyReporter($report);
        }

        return back()->with('success', 'Report updated successfully.');
    }

    protected function notifyReporter(BrokerReport $report): void
    {
        $user = $report->user_id
            ? User::find($report->user_id)
            : User::query()->where('email', $report->reporter_email)->first();

        if ($user) {
            $this->notifications->notifyReportUpdated($user, $report);
        }
    }
}
