<x-admin.page-header title="Live Channels" description='Table: <code>live_channels</code>'>
    <x-slot:actions>
        <a href="{{ route('admin_live_channel_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Channel
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>heading</th>
                    <th>video_id</th>
                    <th>language_id</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($channels as $channel)
                    <tr>
                        <td>{{ $channel->heading }}</td>
                        <td>{{ $channel->video_id }}</td>
                        <td>{{ $channel->language_id }}</td>
                        <td>
                            <a href="{{ route('admin_live_channel_edit', $channel->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted text-center">No live channels found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
