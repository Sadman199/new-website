<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ForexBonusRequest;
use App\Models\Broker;
use App\Models\ForexBonus;
use App\Services\EditorialAssignmentService;
use App\Services\ForexBonusAdminService;
use Illuminate\Http\Request;
use Throwable;

class AdminForexBonusController extends AdminController
{
    public function __construct(protected ForexBonusAdminService $service)
    {
    }

    public function show(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'promo_type' => (string) $request->get('promo_type', ''),
            'status' => (string) $request->get('status', ''),
            'broker_id' => (string) $request->get('broker_id', ''),
            'featured' => (string) $request->get('featured', ''),
            'sort' => (string) $request->get('sort', 'newest'),
        ];

        $query = ForexBonus::query()->with('broker');

        if ($filters['promo_type'] !== '' && array_key_exists($filters['promo_type'], ForexBonus::promoTypes())) {
            $query->where('promo_type', $filters['promo_type']);
        }

        if (in_array($filters['status'], ['ongoing', 'limited-time', 'expired'], true)) {
            $query->where('promotion_status', $filters['status']);
        }

        if ($filters['broker_id'] !== '') {
            $query->where('broker_id', $filters['broker_id']);
        }

        if ($filters['featured'] === '1') {
            $query->where('is_featured', true);
        } elseif ($filters['featured'] === '0') {
            $query->where('is_featured', false);
        }

        match ($filters['sort']) {
            'title' => $query->orderBy('title'),
            'expiry' => $query->orderByRaw('expiry_date is null')->orderBy('expiry_date'),
            'updated' => $query->latest('updated_at'),
            default => $query->orderByDesc('publish_date')->orderByDesc('id'),
        };

        $bonuses = $this->paginateWithSearch($query, $request, ['title', 'slug'], 12);

        return view('admin.forex_bonuses.show', [
            'bonuses' => $bonuses,
            'forexBonuses' => $bonuses,
            'filters' => $filters,
            'promoTypes' => ForexBonus::promoTypes(),
            'statuses' => ForexBonus::promotionStatuses(),
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => ForexBonus::query()->count(),
                'featured' => ForexBonus::query()->where('is_featured', true)->count(),
                'ongoing' => ForexBonus::query()->where('promotion_status', 'ongoing')->count(),
                'expired' => ForexBonus::query()->where('promotion_status', 'expired')->count(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.forex_bonuses.create', $this->formData(new ForexBonus([
            'publish_date' => now()->toDateString(),
            'promotion_status' => 'ongoing',
            'promo_type' => 'Forex Deposit Bonus',
        ])));
    }

    public function store(ForexBonusRequest $request)
    {
        try {
            $this->service->save(new ForexBonus(), $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'Could not create the bonus: '.$e->getMessage());
        }

        return redirect()->route('admin_forex_bonus_show')->with('success', 'Bonus created.');
    }

    public function view($id)
    {
        $bonus = ForexBonus::query()->with('broker')->findOrFail($id);

        return view('admin.forex_bonuses.view', [
            'bonus' => $bonus,
            'credits' => EditorialAssignmentService::creditsFor($bonus),
        ]);
    }

    public function edit($id)
    {
        $bonus = ForexBonus::query()->findOrFail($id);

        return view('admin.forex_bonuses.edit', $this->formData($bonus));
    }

    public function update(ForexBonusRequest $request, $id)
    {
        $bonus = ForexBonus::query()->findOrFail($id);

        try {
            $this->service->save($bonus, $request);
        } catch (Throwable $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'Could not save the bonus: '.$e->getMessage());
        }

        return redirect()->route('admin_forex_bonus_show')->with('success', 'Bonus saved.');
    }

    public function delete($id)
    {
        $bonus = ForexBonus::query()->findOrFail($id);
        $title = $bonus->title;
        $this->service->delete($bonus);

        return redirect()->route('admin_forex_bonus_show')->with('success', '"'.$title.'" was deleted.');
    }

    /** @return array<string, mixed> */
    protected function formData(ForexBonus $bonus): array
    {
        return [
            'bonus' => $bonus,
            'forexBonus' => $bonus,
            'brokers' => Broker::query()->orderBy('name')->get(['id', 'name', 'slug']),
            'editorialOptions' => EditorialAssignmentService::allAssigneeOptions(),
            'promoTypes' => ForexBonus::promoTypes(),
            'statuses' => ForexBonus::promotionStatuses(),
        ];
    }
}
