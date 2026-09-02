<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrokerRequest;
use App\Models\AccountOption;
use App\Models\Broker;
use App\Services\BrokerAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBrokerController extends Controller
{
    public function __construct(protected BrokerAdminService $brokerAdmin)
    {
    }

    public function show(Request $request)
    {
        $filters = $this->listingFilters($request);
        $query = $this->filteredBrokers($request);

        $brokers = $query
            ->withCount(['accountOptions', 'reviews', 'guides'])
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Broker::query()->count(),
            'featured' => Broker::query()->where('featured_broker', true)->count(),
            'scam' => Broker::query()->where('is_scam', true)->count(),
            'missing_logo' => Broker::query()->where(function ($q) {
                $q->whereNull('logo')->orWhere('logo', '');
            })->count(),
        ];

        return view('admin.brokers.show', compact('brokers', 'stats', 'filters'));
    }

    public function scam(Request $request)
    {
        $brokers = Broker::query()
            ->where('is_scam', true)
            ->when(trim((string) $request->get('q', '')) !== '', function ($query) use ($request) {
                $search = trim((string) $request->get('q'));
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', '%'.$search.'%')
                        ->orWhere('slug', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('scam_reported_date')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.brokers.scam', compact('brokers'));
    }

    public function create()
    {
        return view('admin.brokers.create', [
            'broker' => new Broker(),
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function store(BrokerRequest $request)
    {
        try {
            $broker = $this->brokerAdmin->save(new Broker(), $request);
            app(\App\Services\BrokerGuideService::class)->ensureGuidesForBroker($broker);

            if (! $broker->id) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Broker was saved but no ID was returned. Please check the brokers list.');
            }

            return redirect()
                ->route('admin_broker_edit', ['id' => $broker->id])
                ->with('success', $broker->name.' was created. You can add account options next.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create broker: '.$e->getMessage());
        }
    }

    public function view($id)
    {
        $broker = Broker::query()
            ->withCount(['accountOptions', 'reviews', 'guides', 'faqs', 'forexBonuses'])
            ->findOrFail($id);

        return view('admin.brokers.view', [
            'broker' => $broker,
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function edit($id)
    {
        $broker = Broker::query()
            ->withCount(['accountOptions', 'guides'])
            ->findOrFail($id);

        return view('admin.brokers.edit', [
            'broker' => $broker,
            'formOptions' => $this->formOptions(),
        ]);
    }

    public function update(BrokerRequest $request, $id)
    {
        try {
            $broker = Broker::findOrFail($id);
            $this->brokerAdmin->save($broker, $request);

            return redirect()
                ->route('admin_broker_edit', ['id' => $broker->id])
                ->with('success', $broker->name.' was updated.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update broker: '.$e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $broker = Broker::findOrFail($id);
            $name = $broker->name;

            DB::transaction(function () use ($broker) {
                AccountOption::where('broker_id', $broker->id)->delete();
                $this->brokerAdmin->delete($broker);
            });

            return redirect()
                ->route('admin_broker_show')
                ->with('success', $name.' was deleted.');
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Could not delete broker: '.$e->getMessage());
        }
    }

    /** @return array<string, string> */
    protected function listingFilters(Request $request): array
    {
        return [
            'q' => trim((string) $request->get('q', '')),
            'status' => (string) $request->get('status', ''),
            'sort' => (string) $request->get('sort', 'newest'),
        ];
    }

    protected function filteredBrokers(Request $request)
    {
        $filters = $this->listingFilters($request);
        $query = Broker::query();

        if ($filters['q'] !== '') {
            $search = $filters['q'];
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('country', 'like', '%'.$search.'%')
                    ->orWhere('title', 'like', '%'.$search.'%');
            });
        }

        match ($filters['status']) {
            'featured' => $query->where('featured_broker', true),
            'scam' => $query->where('is_scam', true),
            'live' => $query->where('is_scam', false),
            'no-logo' => $query->where(function ($q) {
                $q->whereNull('logo')->orWhere('logo', '');
            }),
            default => null,
        };

        return match ($filters['sort']) {
            'name' => $query->orderBy('name'),
            'rating' => $query->orderByDesc('rating')->orderBy('name'),
            'trust' => $query->orderByDesc('trust_score')->orderBy('name'),
            default => $query->orderByDesc('created_at')->orderBy('name'),
        };
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
            'taxonomyDescriptions' => \App\Models\BrokerTaxonomyTerm::allDescriptions(),
        ];
    }
}
