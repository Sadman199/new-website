<x-admin.page-header title="Trading Tools" description="Calculator tools shown on /trading-tools.">
    <x-slot:actions>
        <button class="btn-bc btn-bc-primary" type="button">
            <i class="fas fa-plus"></i> Add Tool
        </button>
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>name</th>
                    <th>slug</th>
                    <th>icon</th>
                    <th>short_description</th>
                    <th>is_active</th>
                    <th>sort_order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tools as $tool)
                    <tr>
                        <td>{{ $tool->name }}</td>
                        <td>{{ $tool->slug }}</td>
                        <td>{{ $tool->icon }}</td>
                        <td>{{ $tool->short_description }}</td>
                        <td>
                            <div class="toggle-switch {{ $tool->is_active ? 'on' : '' }}"></div>
                        </td>
                        <td>{{ $tool->sort_order }}</td>
                        <td>
                            <a href="{{ route('admin_trading_tools_edit', $tool->id) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted text-center">No trading tools found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
