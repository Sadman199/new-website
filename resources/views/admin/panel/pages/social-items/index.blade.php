<x-admin.page-header title="Social Links" description="Footer and header social media icons.">
    <x-slot:actions>
        <a href="{{ route('admin_social_item_create') }}" class="btn-bc btn-bc-primary">
            <i class="fas fa-plus"></i> Add Link
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>icon</th>
                    <th>url</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($socialItems as $item)
                    <tr>
                        <td>
                            @if($item->icon)
                                <i class="{{ $item->icon }}"></i>
                            @endif
                            {{ $item->name ?? $item->icon }}
                        </td>
                        <td>{{ $item->url }}</td>
                        <td>
                            <a href="{{ route('admin_social_item_edit', $item->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted text-center">No social links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
