@extends('admin.layout.app')

@section('heading', 'User Management')

@section('main_content')
<div class="section-body">
    <!-- Summary cards -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-users fa-2x text-primary mr-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $counts['total'] }}</h4>
                        <small class="text-muted">Total users</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-check fa-2x text-success mr-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $counts['verified'] }}</h4>
                        <small class="text-muted">Verified</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-clock fa-2x text-warning mr-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $counts['unverified'] }}</h4>
                        <small class="text-muted">Pending verification</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="mb-0">Users</h4>
            <form method="GET" action="{{ route('admin_users_index') }}" class="form-inline">
                <select name="filter" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="verified" {{ $filter === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ $filter === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    <option value="banned" {{ $filter === 'banned' ? 'selected' : '' }}>Suspended</option>
                </select>
                <input type="text" name="q" value="{{ $search }}" class="form-control form-control-sm mr-2" placeholder="Search name or email">
                <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            @if($users->isEmpty())
                <div class="alert alert-info">No users found.</div>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Reviews</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th width="22%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->is_verified)<span class="badge badge-info ml-1">Verified</span>@endif
                                @if($user->status === 'banned')<span class="badge badge-danger ml-1">Suspended</span>@endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->country ?? '—' }}</td>
                            <td>{{ $user->reviews_count }}</td>
                            <td>
                                @if($user->is_verified)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Verified</span>
                                @else
                                    <span class="text-warning"><i class="fas fa-clock"></i> Pending</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin_users_show', $user->id) }}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>

                                    @if($user->is_verified)
                                        <form action="{{ route('admin_users_unverify', $user->id) }}" method="POST" onsubmit="return confirm('Remove verification from {{ $user->name }}?');">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-warning" title="Unverify"><i class="fas fa-user-times"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin_users_verify', $user->id) }}" method="POST" onsubmit="return confirm('Verify {{ $user->name }}?');">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-success" title="Verify"><i class="fas fa-user-check"></i></button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin_users_toggle_status', $user->id) }}" method="POST" onsubmit="return confirm('Change status for {{ $user->name }}?');">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-dark" title="Toggle suspend">
                                            <i class="fas {{ $user->status === 'banned' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin_users_delete', $user->id) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
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
        @if(!$users->isEmpty())
        <div class="card-footer bg-white">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>
@endsection
