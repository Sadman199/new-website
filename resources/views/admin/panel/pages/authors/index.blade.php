<x-admin.page-header title="Authors" description="Content authors with panel access.">
    <x-slot:actions>
        <a href="{{ route('admin_author_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Author
        </a>
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
                @forelse($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->email }}</td>
                        <td>{{ $author->photo ?? '—' }}</td>
                        <td>—</td>
                        <td>
                            <a href="{{ route('admin_author_edit', $author->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-center">No authors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
