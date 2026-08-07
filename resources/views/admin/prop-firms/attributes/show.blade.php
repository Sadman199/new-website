@extends('admin.layout.app')
@section('heading', 'Prop Firm Attributes')
@section('button')<a href="{{ route('admin_prop_firm_attributes_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Attribute</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow"><div class="card-body table-responsive">
<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th>Name</th><th>Slug</th><th>Group</th><th>Used By</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($attributes as $attr)<tr><td>{{ $attr->name }}</td><td>{{ $attr->slug }}</td><td>{{ $attr->group ?? '—' }}</td><td>{{ $attr->prop_firms_count }}</td><td>@if($attr->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td><td><a href="{{ route('admin_prop_firm_attributes_edit', $attr->id) }}" class="btn btn-sm btn-primary">Edit</a><form action="{{ route('admin_prop_firm_attributes_delete', $attr->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No attributes yet.</td></tr>@endforelse</tbody></table>
{{ $attributes->links() }}</div></div></div>
@endsection
