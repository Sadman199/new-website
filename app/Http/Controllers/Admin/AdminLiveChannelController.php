<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LiveChannelRequest;
use App\Models\LiveChannel;

class AdminLiveChannelController extends AdminResourceController
{
    protected function modelClass(): string
    {
        return LiveChannel::class;
    }

    protected function formRequestClass(): string
    {
        return LiveChannelRequest::class;
    }

    protected function indexRoute(): string
    {
        return 'admin_live_channel_show';
    }

    protected function views(): array
    {
        return [
            'index' => 'admin.live_channel_show',
            'create' => 'admin.live_channel_create',
            'edit' => 'admin.live_channel_edit',
        ];
    }

    protected function indexCollectionKey(): string
    {
        return 'live_channels';
    }

    protected function editModelKey(): string
    {
        return 'live_channel_data';
    }
}
