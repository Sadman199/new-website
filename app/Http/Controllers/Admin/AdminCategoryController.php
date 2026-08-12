<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;

class AdminCategoryController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return Category::class;
    }

    protected function formRequestClass(): string
    {
        return CategoryRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_category_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.category_show',
            'create' => 'admin.category_create',
            'edit' => 'admin.category_edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'categories';
    }

    protected function editModelKey(): string
    {
        return 'category_single';
    }

    protected function indexRelations(): array
    {
        return ['rLanguage'];
    }

    protected function indexOrder(): array
    {
        return ['category_order', 'asc'];
    }
}
