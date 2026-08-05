<x-admin.page-header title="Online Polls" description='Table: <code>online_polls</code>'>
    <x-slot:actions>
        <a href="{{ route('admin_online_poll_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Poll
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>question</th>
                    <th>yes_vote</th>
                    <th>no_vote</th>
                    <th>language_id</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($polls as $poll)
                    <tr>
                        <td>{{ $poll->question }}</td>
                        <td>{{ $poll->yes_vote ?? 0 }}</td>
                        <td>{{ $poll->no_vote ?? 0 }}</td>
                        <td>{{ $poll->language_id }}</td>
                        <td>
                            <a href="{{ route('admin_online_poll_edit', $poll->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center">No polls found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
