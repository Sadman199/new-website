<x-admin.layout :title="$title ?? 'Authors'">
    <x-admin.page-header title="Authors" description="Content authors with editorial roles and panel access.">
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
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Contributions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        <tr>
                            <td>
                                <img src="{{ $author->photoUrl() }}"
                                     alt="{{ $author->name }}"
                                     class="rounded"
                                     style="width: 48px; height: 48px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $author->name }}</strong>
                                @if($author->bio)
                                    <br><small class="text-muted">{{ Str::limit($author->bio, 70) }}</small>
                                @endif
                            </td>
                            <td>{{ $author->email }}</td>
                            <td>
                                @foreach($author->roleLabels() as $role)
                                    <span class="bc-badge bc-badge-muted">{{ $role }}</span>
                                @endforeach
                                @if(empty($author->roleLabels()))
                                    <span class="text-muted">No roles</span>
                                @endif
                            </td>
                            <td class="small">
                                <div>Written: {{ $author->written_posts_count ?? 0 }}</div>
                                <div>Edited: {{ $author->edited_posts_count ?? 0 }}</div>
                                <div>Fact-Checked: {{ $author->fact_checked_posts_count ?? 0 }}</div>
                            </td>
                            <td>
                                <a href="{{ route('admin_author_edit', $author->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin_author_delete', $author->id) }}"
                                   class="btn-bc btn-bc-ghost btn-bc-sm text-danger"
                                   onclick="return confirm('Delete this author? Editorial credits on posts will be cleared.');"
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center">No authors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin.layout>
