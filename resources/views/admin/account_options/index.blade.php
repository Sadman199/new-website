@extends('admin.layout.app')

@section('heading', 'Account Options — ' . $broker->name)

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Brokers
    </a>
    <a href="{{ route('admin_account_options_create', $broker->id) }}" class="btn btn-success ml-2">
        <i class="fas fa-plus"></i> Add Account Option
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">Account Options</h4>
            <small class="text-muted">{{ $broker->name }} · {{ $broker->slug }}</small>
        </div>
        <div class="card-body">
            @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'account-options'])

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Account Type</th>
                            <th>Currency</th>
                            <th>Min Deposit</th>
                            <th>Leverage</th>
                            <th>Spread</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th width="140">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accountOptions as $option)
                            <tr>
                                <td>{{ $option->sort_order ?: $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $option->account_type }}</strong>
                                    @if($option->swap_free)
                                        <span class="badge badge-success ml-1">Swap-free</span>
                                    @endif
                                    @if($option->access_to_pro_features)
                                        <span class="badge badge-warning ml-1">Pro</span>
                                    @endif
                                </td>
                                <td>{{ $option->account_currency }}</td>
                                <td>{{ $option->min_deposit !== null ? '$' . number_format((float) $option->min_deposit, 0) : '—' }}</td>
                                <td>{{ $option->leverage_label ?? '—' }}</td>
                                <td>{{ $option->spread_label ?? '—' }}</td>
                                <td>{{ $option->commission_display ?? 'None' }}</td>
                                <td>
                                    @if($option->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin_account_options_edit', [$broker->id, $option->id]) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin_account_options_delete', [$broker->id, $option->id]) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this account option?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No account options yet.
                                    <a href="{{ route('admin_account_options_create', $broker->id) }}">Add the first one</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
