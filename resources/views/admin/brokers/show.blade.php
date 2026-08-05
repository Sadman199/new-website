@extends('admin.layout.app')

@section('heading', 'Forex Brokers Management')

@section('button')
<a href="{{ route('admin_broker_create') }}" class="btn btn-primary btn-lg">
    <i class="fas fa-plus-circle"></i> Add New Broker
</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0">Forex Brokers List</h4>
            <form method="GET" action="{{ route('admin_broker_show') }}" class="form-inline mt-2 mt-md-0">
                <input type="search" name="q" class="form-control form-control-sm mr-2" placeholder="Search name or slug…" value="{{ request('q') }}">
                <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            @if($brokers->isEmpty())
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle mr-2"></i>
                No brokers found. Would you like to
                <a href="{{ route('admin_broker_create') }}" class="alert-link">add one</a>?
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="4%">#</th>
                            <th width="22%">Name</th>
                            <th width="10%">Logo</th>
                            <th width="8%">Rating</th>
                            <th width="8%">Trust</th>
                            <th width="8%">Min. Dep.</th>
                            <th width="8%">Fees</th>
                            <th width="22%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brokers as $broker)
                        <tr>
                            <td>{{ $loop->iteration + ($brokers->currentPage() - 1) * $brokers->perPage() }}</td>
                            <td>
                                <strong>{{ $broker->name }}</strong>
                                @if($broker->featured_broker)
                                    <span class="badge badge-warning ml-1">Featured</span>
                                @endif
                                @if($broker->is_scam)
                                    <span class="badge badge-danger ml-1">Scam</span>
                                @endif
                                <br><small class="text-muted">{{ $broker->slug }}</small>
                                @php
                                    $categoryCount = count($broker->brokerCategoryList());
                                    $regionCount = count($broker->regionList());
                                @endphp
                                @if($categoryCount || $regionCount)
                                    <br>
                                    @if($categoryCount)
                                        <small class="text-muted">{{ $categoryCount }} categor{{ $categoryCount === 1 ? 'y' : 'ies' }}</small>
                                    @endif
                                    @if($categoryCount && $regionCount)
                                        <small class="text-muted"> · </small>
                                    @endif
                                    @if($regionCount)
                                        <small class="text-muted">{{ $regionCount }} region{{ $regionCount === 1 ? '' : 's' }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($broker->logo)
                                    <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="img-thumbnail" style="max-height: 45px;">
                                @else
                                    <span class="badge badge-light">No logo</span>
                                @endif
                            </td>
                            <td>
                                @if($broker->rating)
                                    <span class="text-warning">{{ number_format($broker->rating, 1) }}</span>/5
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $broker->trust_score ? $broker->trust_score . '/99' : '—' }}</td>
                            <td>${{ number_format((float) ($broker->minimum_deposit ?? 0), 0) }}</td>
                            <td>{{ $broker->fee_level ? ucfirst($broker->fee_level) : '—' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin_broker_edit', $broker->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('broker_detail', $broker->slug) }}" class="btn btn-sm btn-info" title="View on site" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ route('admin_account_options_index', $broker->id) }}" class="btn btn-sm btn-secondary" title="Account Options">
                                        <i class="fas fa-layer-group"></i>
                                    </a>
                                    <form action="{{ route('admin_broker_delete', $broker->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete {{ $broker->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        @if(!$brokers->isEmpty())
        <div class="card-footer bg-white">
            {{ $brokers->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    table tbody tr:hover { background-color: rgba(0, 0, 0, 0.02); }
</style>
@endpush
