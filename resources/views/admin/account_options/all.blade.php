@extends('admin.layout.app')

@section('heading', 'All Account Options')

@section('button')
    <a href="{{ route('admin_broker_show') }}" class="btn btn-primary">
        <i class="fas fa-briefcase"></i> Manage Brokers
    </a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0">Account Options</h4>
            <form method="GET" class="form-inline mt-2 mt-md-0">
                <select name="broker_id" class="form-control form-control-sm mr-2">
                    <option value="">All brokers</option>
                    @foreach($brokers as $b)
                        <option value="{{ $b->id }}" @selected(request('broker_id') == $b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
                <input type="search" name="q" class="form-control form-control-sm mr-2" placeholder="Search…" value="{{ request('q') }}">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Broker</th>
                            <th>Account Type</th>
                            <th>Currency</th>
                            <th>Min Dep.</th>
                            <th>Leverage</th>
                            <th>Spread</th>
                            <th>Status</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accountOptions as $option)
                            <tr>
                                <td>
                                    <a href="{{ route('admin_account_options_index', $option->broker_id) }}">
                                        {{ $option->broker?->name ?? '—' }}
                                    </a>
                                </td>
                                <td>{{ $option->account_type }}</td>
                                <td>{{ $option->account_currency }}</td>
                                <td>{{ $option->min_deposit !== null ? '$' . number_format((float) $option->min_deposit, 0) : '—' }}</td>
                                <td>{{ $option->leverage_label ?? '—' }}</td>
                                <td>{{ $option->spread_label ?? '—' }}</td>
                                <td>
                                    @if($option->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Hidden</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin_account_options_edit', [$option->broker_id, $option->id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No account options found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $accountOptions->links() }}
        </div>
    </div>
</div>
@endsection
