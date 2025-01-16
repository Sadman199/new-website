<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use DB;

class AdminCategoryController extends Controller
{
    // Show all categories
    public function show()
    {
        $categories = Category::with('rLanguage')->orderBy('category_order', 'asc')->get();
        return view('admin.category_show', compact('categories'));
    }

    // Show the create category form
    public function create()
    {
        return view('admin.category_create');
    }

    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'category_order' => 'required'
        ]);

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->slug = $request->slug;
        $category->show_on_menu = $request->show_on_menu;
        $category->category_order = $request->category_order;
        $category->language_id = $request->language_id;
        $category->save();

        return redirect()->route('admin_category_show')->with('success', 'Data is added successfully.');
    }

    // Show the edit form for a category
    public function edit($id)
    {
        $category_single = Category::findOrFail($id);  // Using findOrFail to throw an exception if not found
        return view('admin.category_edit', compact('category_single'));
    }

    // Update an existing category
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name' => 'required',
            'category_order' => 'required'
        ]);

        $category = Category::findOrFail($id);  // Using findOrFail to throw an exception if not found
        $category->category_name = $request->category_name;
        $category->slug = $request->slug;
        $category->show_on_menu = $request->show_on_menu;
        $category->category_order = $request->category_order;
        $category->language_id = $request->language_id;
        $category->save(); // Instead of update() we can use save() directly after changes

        return redirect()->route('admin_category_show')->with('success', 'Data is updated successfully.');
    }

    // Delete a category
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $category_single = Category::findOrFail($id);  // Using findOrFail to ensure the category exists
            $category_single->delete();  // Deleting the category

            DB::commit();  // Commit the transaction

            return redirect()->route('admin_category_show')->with('success', 'Data is deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();  // Rollback transaction if any error occurs

            return redirect()->route('admin_category_show')->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
