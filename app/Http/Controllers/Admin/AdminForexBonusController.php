<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\ForexBonus;
use App\Services\EditorialAssignmentService;
use App\Services\ForexBonusAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminForexBonusController extends Controller
{
    public function __construct(protected ForexBonusAdminService $service)
    {
    }

    public function show()
    {
        $forexBonuses = ForexBonus::with('broker')
            ->orderByDesc('publish_date')
            ->paginate(10);

        return view('admin.forex_bonuses.show', compact('forexBonuses'));
    }

    public function create()
    {
        return view('admin.forex_bonuses.create', $this->formData());
    }

    public function store(Request $request)
    {
        $request->validate($this->rules());

        $this->service->save(new ForexBonus(), $request);

        return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus created successfully!');
    }

    public function edit($id)
    {
        $forexBonus = ForexBonus::findOrFail($id);

        return view('admin.forex_bonuses.edit', array_merge(compact('forexBonus'), $this->formData()));
    }

    public function update(Request $request, $id)
    {
        $request->validate($this->rules($id));

        $forexBonus = ForexBonus::findOrFail($id);
        $this->service->save($forexBonus, $request);

        return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus updated successfully!');
    }

    public function delete($id)
    {
        $forexBonus = ForexBonus::findOrFail($id);
        $this->service->delete($forexBonus);

        return redirect()->route('admin_forex_bonus_show')->with('success', 'Forex Bonus deleted successfully!');
    }

    protected function formData(): array
    {
        return [
            'brokers' => Broker::orderBy('name')->get(['id', 'name', 'slug']),
            'editorialOptions' => EditorialAssignmentService::allAssigneeOptions(),
        ];
    }

    protected function rules(?int $id = null): array
    {
        $slugRule = $id
            ? 'required|string|max:255|unique:forex_bonuses,slug,' . $id
            : 'required|string|max:255|unique:forex_bonuses,slug';

        return [
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'broker_id' => 'nullable|exists:brokers,id',
            'publish_date' => 'required|date',
            'author_name' => 'nullable|string|max:255',
            'promo_type' => 'required|in:Forex Deposit Bonus,Forex No Deposit Bonus,Forex Live Contest,Forex Demo Contest,Forex Cashback Rebate,Crypto Bonus Promotion',
            'description' => 'required|string',
            'feature_image' => ($id ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp,avif,gif|max:5120',
            'link' => 'required|url',
            'affiliate_link' => 'nullable|url',
            'participate' => 'required|string',
            'how_to_participate' => 'required|string',
            'details' => 'required|string',
            'general_terms' => 'required|string',
            'prize' => 'required',
            'eligibility_criteria' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'min_deposit' => 'nullable|numeric|min:0',
            'bonus_amount' => 'nullable|numeric|min:0',
            'bonus_percentage' => 'nullable|numeric|min:0|max:1000',
            'bonus_type_details' => 'nullable|string',
            'terms_conditions_url' => 'nullable|url',
            'bonus_category' => 'nullable|string|max:255',
            'promotion_status' => 'nullable|in:ongoing,limited-time,expired',
            'is_featured' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'written_assignee' => 'nullable|string',
            'edited_assignee' => 'nullable|string',
            'fact_checked_assignee' => 'nullable|string',
        ];
    }
}
