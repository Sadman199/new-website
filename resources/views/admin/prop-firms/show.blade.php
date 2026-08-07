@extends('admin.layout.app')

@section('heading', 'All Prop Firms')

@section('button')
<a href="{{ route('admin_prop_firms_create') }}" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle"></i> Add New</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <form method="GET" class="form-row align-items-end">
                <div class="col-md-3 form-group mb-0">
                    <label class="small text-muted">Search</label>
                    <input type="search" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Name or slug…">
                </div>
                <div class="col-md-2 form-group mb-0">
                    <label class="small text-muted">Category</label>
                    <select name="category_id" class="form-control form-control-sm">
                        <option value="">All</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group mb-0">
                    <label class="small text-muted">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 form-group mb-0">
                    <label class="small text-muted">Sort</label>
                    <select name="sort" class="form-control form-control-sm">
                        @foreach(['created_at' => 'Created', 'name' => 'Name', 'trust_score' => 'Trust Score', 'overall_rating' => 'Overall Rating'] as $key => $label)
                            <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 form-group mb-0">
                    <label class="small text-muted">Dir</label>
                    <select name="direction" class="form-control form-control-sm">
                        <option value="desc" @selected($direction === 'desc')>Desc</option>
                        <option value="asc" @selected($direction === 'asc')>Asc</option>
                    </select>
                </div>
                <div class="col-md-2 form-group mb-0">
                    <button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin_prop_firms_bulk') }}" id="bulk-form">
                @csrf
                <div class="d-flex flex-wrap mb-3 align-items-center">
                    <select name="action" class="form-control form-control-sm mr-2" style="width:auto;">
                        <option value="">Bulk action…</option>
                        <option value="activate">Activate</option>
                        <option value="deactivate">Deactivate</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Apply bulk action to selected items?')">Apply</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th width="3%"><input type="checkbox" id="check-all"></th>
                                <th width="8%">Logo</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Trust</th>
                                <th>Featured</th>
                                <th>Verified</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th width="14%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($propFirms as $firm)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $firm->id }}" class="row-check"></td>
                                <td>
                                    @if($firm->logo)
                                        <img src="{{ asset($firm->logo) }}" alt="" class="img-thumbnail" style="max-height:40px;">
                                    @else
                                        <span class="badge badge-light">—</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $firm->name }}</strong><br>
                                    <small class="text-muted">{{ $firm->slug }}</small>
                                </td>
                                <td>{{ $firm->category?->name ?? '—' }}</td>
                                <td>{{ $firm->trust_score ?? '—' }}</td>
                                <td>@if($firm->is_featured)<span class="badge badge-warning">Yes</span>@else—@endif</td>
                                <td>@if($firm->is_verified)<span class="badge badge-info">Yes</span>@else—@endif</td>
                                <td>@if($firm->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td>
                                <td>{{ $firm->created_at?->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin_prop_firms_edit', $firm->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin_prop_firms_delete', $firm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this prop firm?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="10" class="text-center text-muted py-4">No prop firms found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
            {{ $propFirms->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('check-all')?.addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endpush
