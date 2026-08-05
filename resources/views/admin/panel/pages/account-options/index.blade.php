<x-admin.page-header
    title="Account Options"
    description='Table: <code>account_options</code> — one row per broker account configuration.'
>
    <x-slot:actions>
        @if(isset($broker_id))
            <a href="{{ route('admin_account_options_create', $broker_id) }}" class="btn-bc btn-bc-primary">
                <i class="fas fa-plus"></i> Add Account Option
            </a>
        @else
            <button class="btn-bc btn-bc-primary" type="button">
                <i class="fas fa-plus"></i> Add Account Option
            </button>
        @endif
    </x-slot:actions>
</x-admin.page-header>

<div class="bc-card">
    <div class="filters-bar">
        <select name="broker_id">
            <option value="">All Brokers</option>
        </select>
        <select name="account_type">
            <option value="">All account_type</option>
        </select>
    </div>
    <div class="table-wrap">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>broker_id</th>
                    <th>account_type</th>
                    <th>account_currency</th>
                    <th>min_deposit</th>
                    <th>max_leverage</th>
                    <th>spread_type</th>
                    <th>spread_value</th>
                    <th>swap_free</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accountOptions as $option)
                    <tr>
                        <td>{{ $option->broker_id }}</td>
                        <td>{{ $option->account_type }}</td>
                        <td>{{ $option->account_currency }}</td>
                        <td>${{ number_format((float) ($option->min_deposit ?? 0), 0) }}</td>
                        <td>{{ $option->max_leverage }}</td>
                        <td>{{ $option->spread_type }}</td>
                        <td>{{ $option->spread_value }}</td>
                        <td>
                            <span class="bc-badge {{ $option->swap_free ? 'bc-badge-success' : 'bc-badge-muted' }}">
                                {{ $option->swap_free ? '1' : '0' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_account_options_edit', [$option->broker_id, $option->id]) }}" class="btn-bc btn-bc-ghost btn-bc-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-muted text-center">No account options found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
