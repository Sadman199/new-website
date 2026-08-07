<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PropFirmCategoryRequest;
use App\Models\PropFirmCategory;
use Illuminate\Support\Str;

class AdminPropFirmCategoryController extends Controller
{
    public function show()
    {
        $categories = PropFirmCategory::withCount('propFirms')->orderBy('sort_order')->orderBy('name')->paginate(20);

        return view('admin.prop-firms.categories.show', compact('categories'));
    }

    public function create()
    {
        return view('admin.prop-firms.categories.create', ['category' => new PropFirmCategory()]);
    }

    public function store(PropFirmCategoryRequest $request)
    {
        $category = new PropFirmCategory($request->validated());
        $category->slug = $category->slug ?: Str::slug($category->name);
        $category->is_active = $request->boolean('is_active', true);
        $category->save();

        return redirect()->route('admin_prop_firm_categories_show')->with('success', 'Category created successfully.');
    }

    public function edit(int $id)
    {
        $category = PropFirmCategory::findOrFail($id);

        return view('admin.prop-firms.categories.edit', compact('category'));
    }

    public function update(PropFirmCategoryRequest $request, int $id)
    {
        $category = PropFirmCategory::findOrFail($id);
        $category->fill($request->validated());
        $category->slug = $category->slug ?: Str::slug($category->name);
        $category->is_active = $request->boolean('is_active', true);
        $category->save();

        return redirect()->route('admin_prop_firm_categories_show')->with('success', 'Category updated successfully.');
    }

    public function delete(int $id)
    {
        PropFirmCategory::findOrFail($id)->delete();

        return redirect()->route('admin_prop_firm_categories_show')->with('success', 'Category deleted successfully.');
    }
}
