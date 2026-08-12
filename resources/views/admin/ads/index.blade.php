@extends('admin.layout.app')

@section('heading', 'Popup & Campaign Ads')

@section('button')
<a href="{{ route('admin_ads_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Ad</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin_ads_index') }}" class="form-row align-items-end">
                    <div class="form-group col-md-4 mb-2">
                        <label>Search</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Title or category">
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="">All types</option>
                            @foreach(['popup','banner','image','video','text','custom'] as $t)
                                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 mb-2">
                        <label>Status</label>
                        <select name="active" class="form-control">
                            <option value="">All</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3 mb-2">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Filter</button>
                        <a href="{{ route('admin_ads_index') }}" class="btn btn-light">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ads List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="example1">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Trigger</th>
                                <th>Priority</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th style="min-width:180px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ads as $ad)
                            <tr>
                                <td>{{ $ad->id }}</td>
                                <td class="text-center">
                                    @if($ad->image)
                                        <img src="{{ $ad->image_url }}" alt="" style="max-width:90px;max-height:60px;object-fit:cover" class="rounded">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $ad->title }}</strong>
                                    @if($ad->category)
                                        <div class="small text-muted">{{ $ad->category }}</div>
                                    @endif
                                </td>
                                <td><span class="badge badge-info">{{ $ad->type }}</span></td>
                                <td>
                                    @if($ad->type === 'popup')
                                        <span class="badge badge-secondary">{{ $ad->trigger_type }}</span>
                                        {{ $ad->trigger_value }}{{ $ad->trigger_type === 'scroll' ? '%' : ($ad->trigger_type === 'time' ? 's' : 'm') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $ad->priority }}</td>
                                <td class="small">
                                    {{ optional($ad->start_date)->format('Y-m-d') ?? '—' }}
                                    →
                                    {{ optional($ad->end_date)->format('Y-m-d') ?? '—' }}
                                </td>
                                <td>
                                    @if($ad->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Off</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin_ads_edit', $ad->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin_ads_toggle', $ad->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" title="Toggle"><i class="fas fa-power-off"></i></button>
                                    </form>
                                    <form action="{{ route('admin_ads_delete', $ad->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this ad?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No ads yet. Create your first popup ad.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $ads->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
