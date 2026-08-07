@extends('admin.layout.app')
@section('heading', 'Prop Firm Programs')
@section('button')<a href="{{ route('admin_prop_firm_programs_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Program</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow mb-3"><div class="card-body">
<form method="GET" class="form-inline flex-wrap">
<input type="text" name="q" class="form-control mr-2 mb-2" placeholder="Search..." value="{{ request('q') }}">
<select name="prop_firm_id" class="form-control mr-2 mb-2"><option value="">All Firms</option>@foreach($propFirms as $firm)<option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>@endforeach</select>
<button type="submit" class="btn btn-primary mb-2">Filter</button>
</form></div></div>
<div class="card shadow"><div class="card-body table-responsive">
<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th>Program</th><th>Prop Firm</th><th>Account Size</th><th>Entry Fee</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($programs as $program)<tr><td>{{ $program->name }}</td><td>{{ $program->propFirm?->name ?? '—' }}</td><td>{{ $program->account_size ?? '—' }}</td><td>{{ $program->entry_fee !== null ? '$'.number_format($program->entry_fee, 2) : '—' }}</td><td>@if($program->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td><td><a href="{{ route('admin_prop_firm_programs_edit', $program->id) }}" class="btn btn-sm btn-primary">Edit</a><form action="{{ route('admin_prop_firm_programs_delete', $program->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No programs found.</td></tr>@endforelse</tbody></table>
{{ $programs->links() }}</div></div></div>
@endsection
