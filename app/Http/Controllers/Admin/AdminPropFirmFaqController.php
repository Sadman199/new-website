<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmFaqRequest;
use App\Models\PropFirm;
use App\Models\PropFirmFaq;
use Illuminate\Http\Request;

class AdminPropFirmFaqController extends Controller
{
    public function show(Request $request)
    {
        $query = PropFirmFaq::with('propFirm');

        if ($request->filled('prop_firm_id')) {
            $query->where('prop_firm_id', $request->integer('prop_firm_id'));
        }

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where('question', 'like', '%' . $search . '%');
        }

        $faqs = $query->orderBy('sort_order')->latest()->paginate(20)->withQueryString();
        $propFirms = PropFirm::orderBy('name')->get(['id', 'name']);

        return view('admin.prop-firms.faqs.show', compact('faqs', 'propFirms'));
    }

    public function create()
    {
        return view('admin.prop-firms.faqs.create', [
            'faq' => new PropFirmFaq(),
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function store(PropFirmFaqRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        PropFirmFaq::create($data);

        return redirect()->route('admin_prop_firm_faqs_show')->with('success', 'FAQ created successfully.');
    }

    public function edit(int $id)
    {
        $faq = PropFirmFaq::findOrFail($id);

        return view('admin.prop-firms.faqs.edit', [
            'faq' => $faq,
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function update(PropFirmFaqRequest $request, int $id)
    {
        $faq = PropFirmFaq::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);
        $faq->update($data);

        return redirect()->route('admin_prop_firm_faqs_show')->with('success', 'FAQ updated successfully.');
    }

    public function delete(int $id)
    {
        PropFirmFaq::findOrFail($id)->delete();

        return redirect()->route('admin_prop_firm_faqs_show')->with('success', 'FAQ deleted successfully.');
    }
}
