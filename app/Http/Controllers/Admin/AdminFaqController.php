<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\FaqRequest;
use App\Models\Broker;
use App\Models\Faq;

class AdminFaqController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return Faq::class;
    }

    protected function formRequestClass(): string
    {
        return FaqRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_faq_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.faq_show',
            'create' => 'admin.faq_create',
            'edit' => 'admin.faq_edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'faq_data';
    }

    protected function editModelKey(): string
    {
        return 'faq_data';
    }

    protected function createViewData(): array
    {
        return ['brokers' => $this->brokers()];
    }

    protected function editViewData($model): array
    {
        return ['brokers' => $this->brokers()];
    }

    private function brokers()
    {
        return Broker::query()->orderBy('name')->get(['id', 'name']);
    }
}
