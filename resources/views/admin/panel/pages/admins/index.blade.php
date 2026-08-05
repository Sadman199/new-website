<x-admin.page-header title="Admin Users" description='Table: <code>admins</code>'>
    <x-slot:actions>
        <button class="btn-bc btn-bc-primary" type="button">
            <i class="fas fa-plus"></i> Add Admin
        </button>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>email</th>
                    <th>photo</th>
                    <th>token</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->photo ?? '—' }}</td>
                        <td>{{ $admin->token ? '—' : '—' }}</td>
                        <td>
                            <a href="{{ route('admin_profile') }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center">No admin users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
