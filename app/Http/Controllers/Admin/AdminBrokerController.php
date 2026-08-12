<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrokerRequest;
use App\Models\AccountOption;
use App\Models\Broker;
use App\Services\BrokerAdminService;
use Illuminate\Http\Request;

class AdminBrokerController extends Controller
{
    public function __construct(protected BrokerAdminService $brokerAdmin)
    {
    }

    public function show(Request $request)
    {
        $query = Broker::query();

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        $brokers = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.brokers.show', compact('brokers'));
    }

    public function scam()
    {
        $brokers = Broker::where('is_scam', true)
            ->orderByDesc('scam_reported_date')
            ->orderBy('name')
            ->paginate(10);

        return view('admin.brokers.scam', compact('brokers'));
    }

    public function create()
    {
        $broker = new Broker();

        return view('admin.brokers.create', [
            'broker' => $broker,
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function store(BrokerRequest $request)
    {
        $broker = $this->brokerAdmin->save(new Broker(), $request);
        app(\App\Services\BrokerGuideService::class)->ensureGuidesForBroker($broker);

        return redirect()
            ->route('admin_broker_edit', $broker->id)
            ->with('success', 'Broker created successfully. You can add account options next.');
    }

    public function edit($id)
    {
        $broker = Broker::findOrFail($id);

        return view('admin.brokers.edit', [
            'broker' => $broker,
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function update(BrokerRequest $request, $id)
    {
        $broker = Broker::findOrFail($id);
        $this->brokerAdmin->save($broker, $request);

        return redirect()
            ->route('admin_broker_edit', $broker->id)
            ->with('success', 'Broker updated successfully.');
    }

    public function delete($id)
    {
        try {
            $broker = Broker::findOrFail($id);
            AccountOption::where('broker_id', $broker->id)->delete();
            $this->brokerAdmin->delete($broker);

            return redirect()
                ->route('admin_broker_show')
                ->with('success', 'Broker deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting broker: ' . $e->getMessage());
        }
    }

    protected function formOptions(): array
    {
        return [
            'markets' => BrokerAdminService::marketOptions(),
            'platforms' => BrokerAdminService::platformOptions(),
            'regulations' => BrokerAdminService::regulationOptions(),
            'categoryScores' => BrokerAdminService::categoryScoreKeys(),
            'editorialOptions' => \App\Services\EditorialAssignmentService::allAssigneeOptions(),
            'feeLevels' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ],
            'brokerCategories' => BrokerAdminService::brokerCategoryOptions(),
            'regions' => BrokerAdminService::regionOptions(),
            'countryListings' => BrokerAdminService::countryListingOptions(),
        ];
    }
}
