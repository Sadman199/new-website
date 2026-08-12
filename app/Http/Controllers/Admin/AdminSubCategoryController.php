<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class AdminSubCategoryController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return SubCategory::class;
    }

    protected function formRequestClass(): string
    {
        return SubCategoryRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_sub_category_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.sub_category_show',
            'create' => 'admin.sub_category_create',
            'edit' => 'admin.sub_category_edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'sub_categories';
    }

    protected function editModelKey(): string
    {
        return 'sub_category_single';
    }

    protected function indexRelations(): array
    {
        return ['rCategory', 'rLanguage'];
    }

    protected function indexOrder(): array
    {
        return ['sub_category_order', 'asc'];
    }

    protected function createViewData(): array
    {
        return ['categories' => $this->orderedCategories()];
    }

    protected function editViewData($model): array
    {
        return ['categories' => $this->orderedCategories()];
    }

    protected function attributesFromValidated(array $validated): array
    {
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['sub_category_name']);
        }

        return $validated;
    }

    private function orderedCategories()
    {
        return Category::orderBy('category_order', 'asc')->get();
    }
}
