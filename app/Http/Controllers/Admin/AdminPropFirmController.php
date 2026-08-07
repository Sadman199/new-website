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
            'total' => PropFirm::count(),
            'active' => PropFirm::where('is_active', true)->count(),
            'featured' => PropFirm::where('is_featured', true)->count(),
            'verified' => PropFirm::where('is_verified', true)->count(),
            'programs' => PropFirmProgram::count(),
            'reviews' => PropFirmReview::count(),
            'faqs' => PropFirmFaq::count(),
            'categories' => PropFirmCategory::count(),
        ];

        $recent = PropFirm::with('category')->latest()->take(8)->get();

        return view('admin.prop-firms.dashboard', compact('stats', 'recent'));
    }

    public function show(Request $request)
    {
        $query = PropFirm::with('category');

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where(function ($sub) use ($search) {
                $sub->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('prop_firm_category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->boolean('verified'));
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = ['name', 'trust_score', 'overall_rating', 'sort_order', 'created_at'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $propFirms = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();
        $categories = PropFirmCategory::orderBy('name')->get();

        return view('admin.prop-firms.show', compact('propFirms', 'categories', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.prop-firms.create', $this->formData(new PropFirm()));
    }

    public function store(PropFirmRequest $request)
    {
        $propFirm = $this->propFirmAdmin->save(new PropFirm(), $request);

        return redirect()
            ->route('admin_prop_firms_edit', $propFirm->id)
            ->with('success', 'Prop firm created successfully.');
    }

    public function edit(int $id)
    {
        $propFirm = PropFirm::with(['programs', 'faqs', 'attributes'])->findOrFail($id);

        return view('admin.prop-firms.edit', $this->formData($propFirm));
    }

    public function update(PropFirmRequest $request, int $id)
    {
        $propFirm = PropFirm::findOrFail($id);
        $this->propFirmAdmin->save($propFirm, $request);

        return redirect()
            ->route('admin_prop_firms_edit', $propFirm->id)
            ->with('success', 'Prop firm updated successfully.');
    }

    public function delete(int $id)
    {
        $propFirm = PropFirm::findOrFail($id);
        $this->propFirmAdmin->delete($propFirm);

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

        $ids = $request->input('ids', []);

        match ($request->input('action')) {
            'delete' => PropFirm::whereIn('id', $ids)->each(fn (PropFirm $firm) => $this->propFirmAdmin->delete($firm)),
            'activate' => PropFirm::whereIn('id', $ids)->update(['is_active' => true]),
            'deactivate' => PropFirm::whereIn('id', $ids)->update(['is_active' => false]),
        };

        return redirect()
            ->route('admin_prop_firms_show')
            ->with('success', 'Bulk action completed successfully.');
    }

    /** @return array<string, mixed> */
    protected function formData(PropFirm $propFirm): array
    {
        return [
            'propFirm' => $propFirm,
            'categories' => PropFirmCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'attributes' => PropFirmAttribute::where('is_active', true)->orderBy('group')->orderBy('sort_order')->orderBy('name')->get(),
        ];
    }
}
