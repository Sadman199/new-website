<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BrokerAlternativePageRequest;
use App\Models\Broker;
use App\Models\BrokerAlternativeItem;
use App\Models\BrokerAlternativePage;
use App\Services\BrokerAlternativesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBrokerAlternativesController extends AdminController
{
    public function show(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'status' => (string) $request->get('status', ''),
        ];

        $query = BrokerAlternativePage::query()->with(['broker', 'items']);

        if ($filters['q'] !== '') {
            $query->whereHas('broker', function ($brokerQuery) use ($filters) {
                $brokerQuery->where('name', 'like', '%'.$filters['q'].'%')
                    ->orWhere('slug', 'like', '%'.$filters['q'].'%');
            });
        }

        if ($filters['status'] === 'published') {
            $query->where('is_published', true);
        } elseif ($filters['status'] === 'draft') {
            $query->where('is_published', false);
        }

        $pages = $query->latest('updated_at')->paginate(20)->withQueryString();

        return view('admin.broker-alternatives.show', compact('pages', 'filters'));
    }

    public function create()
    {
        $page = new BrokerAlternativePage(['is_published' => false, 'faqs' => []]);
        $brokers = $this->brokerOptions();
        $usedBrokerIds = BrokerAlternativePage::query()->pluck('broker_id')->map(fn ($id) => (int) $id)->all();

        return view('admin.broker-alternatives.create', compact('page', 'brokers', 'usedBrokerIds'));
    }

    public function store(BrokerAlternativePageRequest $request)
    {
        $page = DB::transaction(function () use ($request) {
            $page = BrokerAlternativePage::create($request->safe()->except('alternative_broker_ids'));
            $this->syncItems($page, $request->input('alternative_broker_ids', []));

            return $page;
        });

        BrokerAlternativesService::flush();

        return $this->flashSuccess('admin_broker_alternatives_edit', 'Alternatives page created.', ['id' => $page->id]);
    }

    public function edit($id)
    {
        $page = $this->findOrFail(BrokerAlternativePage::class, $id, ['broker', 'items']);
        $brokers = $this->brokerOptions();
        $usedBrokerIds = BrokerAlternativePage::query()->where('id', '!=', $page->id)->pluck('broker_id')->map(fn ($id) => (int) $id)->all();
        $selectedIds = $page->items->pluck('alternative_broker_id')->map(fn ($id) => (int) $id)->all();

        return view('admin.broker-alternatives.edit', compact('page', 'brokers', 'usedBrokerIds', 'selectedIds'));
    }

    public function update(BrokerAlternativePageRequest $request, $id)
    {
        $page = $this->findOrFail(BrokerAlternativePage::class, $id);

        DB::transaction(function () use ($request, $page) {
            $page->update($request->safe()->except('alternative_broker_ids'));
            $this->syncItems($page, $request->input('alternative_broker_ids', []));
        });

        BrokerAlternativesService::flush();

        return $this->flashSuccess('admin_broker_alternatives_edit', 'Alternatives page updated.', ['id' => $page->id]);
    }

    public function toggle($id)
    {
        $page = $this->findOrFail(BrokerAlternativePage::class, $id);
        $page->update(['is_published' => ! $page->is_published]);
        BrokerAlternativesService::flush();

        $state = $page->is_published ? 'published' : 'unpublished';

        return $this->flashSuccess('admin_broker_alternatives_show', 'Page '.$state.'.');
    }

    public function delete($id)
    {
        $page = $this->findOrFail(BrokerAlternativePage::class, $id);
        $page->delete();
        BrokerAlternativesService::flush();

        return $this->flashSuccess('admin_broker_alternatives_show', 'Alternatives page deleted.');
    }

    protected function brokerOptions()
    {
        return Broker::query()
            ->where('is_scam', false)
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);
    }

    /** @param array<int, int> $ids */
    protected function syncItems(BrokerAlternativePage $page, array $ids): void
    {
        $ids = array_values(array_unique(array_filter($ids, fn ($id) => (int) $id !== (int) $page->broker_id)));

        BrokerAlternativeItem::query()->where('page_id', $page->id)->delete();

        foreach ($ids as $index => $brokerId) {
            BrokerAlternativeItem::create([
                'page_id' => $page->id,
                'alternative_broker_id' => $brokerId,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
