@extends('admin.layout.app')
@section('heading', 'Prop Firm FAQs')
@section('button')<a href="{{ route('admin_prop_firm_faqs_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add FAQ</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow mb-3"><div class="card-body">
<form method="GET" class="form-inline flex-wrap">
<input type="text" name="q" class="form-control mr-2 mb-2" placeholder="Search..." value="{{ request('q') }}">
<select name="prop_firm_id" class="form-control mr-2 mb-2"><option value="">All Firms</option>@foreach($propFirms as $firm)<option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>@endforeach</select>
<button type="submit" class="btn btn-primary mb-2">Filter</button>
</form></div></div>
<div class="card shadow"><div class="card-body table-responsive">
<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th>Question</th><th>Firm</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($faqs as $faq)<tr><td>{{ Str::limit($faq->question, 60) }}</td><td>{{ $faq->propFirm?->name ?? '—' }}</td><td>{{ $faq->sort_order }}</td><td>@if($faq->is_active)<span class="badge badge-success">Active</span>@else<span class="badge badge-secondary">Inactive</span>@endif</td><td><a href="{{ route('admin_prop_firm_faqs_edit', $faq->id) }}" class="btn btn-sm btn-primary">Edit</a><form action="{{ route('admin_prop_firm_faqs_delete', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">No FAQs found.</td></tr>@endforelse</tbody></table>
{{ $faqs->links() }}</div></div></div>
@endsection
