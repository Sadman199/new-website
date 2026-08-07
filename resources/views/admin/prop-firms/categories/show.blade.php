@extends('admin.layout.app')
@section('heading', 'Prop Firm Categories')
@section('button')<a href="{{ route('admin_prop_firm_categories_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow"><div class="card-body table-responsive">
<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th>Name</th><th>Slug</th><th>Firms</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($categories as $cat)<tr><td>{{ $cat->name }}</td><td>{{ $cat->slug }}</td><td>{{ $cat->prop_firms_count }}</td><td>{{ $cat->sort_order }}</td><td>@if($cat->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td><td><a href="{{ route('admin_prop_firm_categories_edit', $cat->id) }}" class="btn btn-sm btn-primary">Edit</a><form action="{{ route('admin_prop_firm_categories_delete', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No categories.</td></tr>@endforelse</tbody></table>
{{ $categories->links() }}</div></div></div>
@endsection
