<?php

namespace App\Http\Controllers\Admin\Panel;

use App\Models\BrokerReport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PanelBrokerReportsController extends PanelBaseController
{
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

        $report->update($validated);

        return back()->with('success', 'Report updated successfully.');
    }
}
