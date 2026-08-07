<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmProgramRequest;
use App\Models\PropFirm;
use App\Models\PropFirmProgram;
use Illuminate\Http\Request;

class AdminPropFirmProgramController extends Controller
{
    public function show(Request $request)
    {
        $query = PropFirmProgram::with('propFirm');

        if ($request->filled('prop_firm_id')) {
            $query->where('prop_firm_id', $request->integer('prop_firm_id'));
        }

        if ($search = trim((string) $request->get('q', ''))) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $programs = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $propFirms = PropFirm::orderBy('name')->get(['id', 'name']);

        return view('admin.prop-firms.programs.show', compact('programs', 'propFirms'));
    }

    public function create()
    {
        return view('admin.prop-firms.programs.create', [
            'program' => new PropFirmProgram(),
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function store(PropFirmProgramRequest $request)
    {
        $data = $request->validated();
        $data['news_trading'] = $request->boolean('news_trading');
        $data['weekend_holding'] = $request->boolean('weekend_holding');
        $data['ea_allowed'] = $request->boolean('ea_allowed');
        $data['copy_trading'] = $request->boolean('copy_trading');
        $data['hedging'] = $request->boolean('hedging');
        $data['refund_available'] = $request->boolean('refund_available');
        $data['is_active'] = $request->boolean('is_active', true);

        PropFirmProgram::create($data);

        return redirect()->route('admin_prop_firm_programs_show')->with('success', 'Program created successfully.');
    }

    public function edit(int $id)
    {
        $program = PropFirmProgram::findOrFail($id);

        return view('admin.prop-firms.programs.edit', [
            'program' => $program,
            'propFirms' => PropFirm::orderBy('name')->get(),
        ]);
    }

    public function update(PropFirmProgramRequest $request, int $id)
    {
        $program = PropFirmProgram::findOrFail($id);
        $data = $request->validated();
        $data['news_trading'] = $request->boolean('news_trading');
        $data['weekend_holding'] = $request->boolean('weekend_holding');
        $data['ea_allowed'] = $request->boolean('ea_allowed');
        $data['copy_trading'] = $request->boolean('copy_trading');
        $data['hedging'] = $request->boolean('hedging');
        $data['refund_available'] = $request->boolean('refund_available');
        $data['is_active'] = $request->boolean('is_active', true);

        $program->update($data);

        return redirect()->route('admin_prop_firm_programs_show')->with('success', 'Program updated successfully.');
    }

    public function delete(int $id)
    {
        PropFirmProgram::findOrFail($id)->delete();

        return redirect()->route('admin_prop_firm_programs_show')->with('success', 'Program deleted successfully.');
    }
}
