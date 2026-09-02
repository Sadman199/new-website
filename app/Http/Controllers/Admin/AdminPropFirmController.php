<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmRequest;
use App\Models\PropFirm;
use App\Models\PropFirmAttribute;
use App\Models\PropFirmCategory;
use App\Models\PropFirmFaq;
use App\Models\PropFirmProgram;
use App\Models\PropFirmReview;
use App\Services\PropFirmAdminService;
use Illuminate\Http\Request;

class AdminPropFirmController extends Controller
{
    public function __construct(protected PropFirmAdminService $propFirmAdmin)
    {
    }

    public function dashboard()
    {
        $stats = [
            'total' => PropFirm::query()->count(),
            'active' => PropFirm::query()->where('is_active', true)->count(),
            'featured' => PropFirm::query()->where('is_featured', true)->count(),
            'verified' => PropFirm::query()->where('is_verified', true)->count(),
            'programs' => PropFirmProgram::query()->count(),
            'reviews' => PropFirmReview::query()->count(),
            'faqs' => PropFirmFaq::query()->count(),
            'categories' => PropFirmCategory::query()->count(),
        ];

        $recent = PropFirm::query()
            ->with('category')
            ->withCount('programs')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.prop-firms.dashboard', compact('stats', 'recent'));
    }

    public function show(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->get('q', '')),
            'category_id' => $request->integer('category_id') ?: '',
            'status' => (string) $request->get('status', ''),
            'sort' => (string) $request->get('sort', 'newest'),
        ];

        $query = PropFirm::query()->with('category')->withCount('programs');

        if ($filters['q'] !== '') {
            $search = $filters['q'];
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%')
                    ->orWhere('headquarters', 'like', '%'.$search.'%');
            });
        }

        if ($filters['category_id'] !== '') {
            $query->where('prop_firm_category_id', $filters['category_id']);
        }

        match ($filters['status']) {
            'active' => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            'featured' => $query->where('is_featured', true),
            'verified' => $query->where('is_verified', true),
            default => null,
        };

        match ($filters['sort']) {
            'name' => $query->orderBy('name'),
            'trust' => $query->orderByDesc('trust_score')->orderBy('name'),
            'rating' => $query->orderByDesc('overall_rating')->orderBy('name'),
            default => $query->latest(),
        };

        $propFirms = $query->paginate(20)->withQueryString();
        $categories = PropFirmCategory::query()->orderBy('name')->get(['id', 'name']);

        $stats = [
            'total' => PropFirm::query()->count(),
            'active' => PropFirm::query()->where('is_active', true)->count(),
            'featured' => PropFirm::query()->where('is_featured', true)->count(),
            'verified' => PropFirm::query()->where('is_verified', true)->count(),
        ];

        return view('admin.prop-firms.show', compact('propFirms', 'categories', 'stats', 'filters'));
    }

    public function create()
    {
        return view('admin.prop-firms.create', $this->formData(new PropFirm()));
    }

    public function store(PropFirmRequest $request)
    {
        try {
            $propFirm = $this->propFirmAdmin->save(new PropFirm(), $request);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not create prop firm: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_prop_firms_edit', $propFirm->id)
            ->with('success', $propFirm->name.' was created.');
    }

    public function edit(int $id)
    {
        $propFirm = PropFirm::with(['programs', 'faqs', 'attributes', 'category'])->findOrFail($id);

        return view('admin.prop-firms.edit', $this->formData($propFirm));
    }

    public function update(PropFirmRequest $request, int $id)
    {
        try {
            $propFirm = PropFirm::findOrFail($id);
            $this->propFirmAdmin->save($propFirm, $request);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Could not update prop firm: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_prop_firms_edit', $propFirm->id)
            ->with('success', $propFirm->name.' was updated.');
    }

    public function delete(int $id)
    {
        try {
            $propFirm = PropFirm::findOrFail($id);
            $this->propFirmAdmin->delete($propFirm);
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Could not delete prop firm: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_prop_firms_show')
            ->with('success', 'Prop firm deleted successfully.');
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'action' => ['required', 'in:delete,activate,deactivate'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:prop_firms,id'],
        ]);

        try {
            $ids = $request->input('ids', []);

            match ($request->input('action')) {
                'delete' => PropFirm::query()->whereIn('id', $ids)->each(fn (PropFirm $firm) => $this->propFirmAdmin->delete($firm)),
                'activate' => PropFirm::query()->whereIn('id', $ids)->update(['is_active' => true]),
                'deactivate' => PropFirm::query()->whereIn('id', $ids)->update(['is_active' => false]),
            };
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->back()
                ->with('error', 'Could not complete bulk action: '.$e->getMessage());
        }

        return redirect()
            ->route('admin_prop_firms_show')
            ->with('success', 'Bulk action completed successfully.');
    }

    /** @return array<string, mixed> */
    protected function formData(PropFirm $propFirm): array
    {
        return [
            'propFirm' => $propFirm,
            'categories' => PropFirmCategory::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'attributes' => PropFirmAttribute::query()->where('is_active', true)->orderBy('group')->orderBy('sort_order')->orderBy('name')->get(),
        ];
    }
}
