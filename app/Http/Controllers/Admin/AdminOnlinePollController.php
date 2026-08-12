<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\OnlinePollRequest;
use App\Models\OnlinePoll;
use Illuminate\Database\Eloquent\Model;

class AdminOnlinePollController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return OnlinePoll::class;
    }

    protected function formRequestClass(): string
    {
        return OnlinePollRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_online_poll_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.online_poll_show',
            'create' => 'admin.online_poll_create',
            'edit' => 'admin.online_poll_edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'online_poll_data';
    }

    protected function editModelKey(): string
    {
        return 'online_poll_data';
    }

    protected function indexOrder(): array
    {
        return ['id', 'desc'];
    }

    protected function beforeCreate(Model $model, array $validated): void
    {
        $model->yes_vote = 0;
        $model->no_vote = 0;
    }
}
