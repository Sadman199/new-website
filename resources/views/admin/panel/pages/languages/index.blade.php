<x-admin.page-header title="Languages" description="Multi-language support (English, Bengali)." />

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>short_name</th>
                    <th>is_default</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($languages as $language)
                    <tr>
                        <td>{{ $language->name }}</td>
                        <td>{{ $language->short_name }}</td>
                        <td>{{ ($language->is_default ?? false) ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin_language_edit', $language->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted text-center">No languages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
