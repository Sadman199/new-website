<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmAttributeRequest;
use App\Models\PropFirmAttribute;
use Illuminate\Support\Str;

class AdminPropFirmAttributeController extends Controller
{
    public function show()
    {
        $attributes = PropFirmAttribute::withCount('propFirms')->orderBy('group')->orderBy('sort_order')->orderBy('name')->paginate(25);

        return view('admin.prop-firms.attributes.show', compact('attributes'));
    }

    public function create()
    {
        return view('admin.prop-firms.attributes.create', ['attribute' => new PropFirmAttribute()]);
    }

    public function store(PropFirmAttributeRequest $request)
    {
        $attribute = new PropFirmAttribute($request->validated());
        $attribute->slug = $attribute->slug ?: Str::slug($attribute->name);
        $attribute->is_active = $request->boolean('is_active', true);
        $attribute->save();

        return redirect()->route('admin_prop_firm_attributes_show')->with('success', 'Attribute created successfully.');
    }

    public function edit(int $id)
    {
        $attribute = PropFirmAttribute::findOrFail($id);

        return view('admin.prop-firms.attributes.edit', compact('attribute'));
    }

    public function update(PropFirmAttributeRequest $request, int $id)
    {
        $attribute = PropFirmAttribute::findOrFail($id);
        $attribute->fill($request->validated());
        $attribute->slug = $attribute->slug ?: Str::slug($attribute->name);
        $attribute->is_active = $request->boolean('is_active', true);
        $attribute->save();

        return redirect()->route('admin_prop_firm_attributes_show')->with('success', 'Attribute updated successfully.');
    }

    public function delete(int $id)
    {
        PropFirmAttribute::findOrFail($id)->delete();

        return redirect()->route('admin_prop_firm_attributes_show')->with('success', 'Attribute deleted successfully.');
    }
}
