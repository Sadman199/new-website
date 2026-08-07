@extends('admin.layout.app')
@section('heading', 'Prop Firm Reviews')
@section('button')<a href="{{ route('admin_prop_firm_reviews_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Review</a>@endsection
@section('main_content')
<div class="section-body"><div class="card shadow mb-3"><div class="card-body">
<form method="GET" class="form-inline flex-wrap">
<input type="text" name="q" class="form-control mr-2 mb-2" placeholder="Search..." value="{{ request('q') }}">
<select name="prop_firm_id" class="form-control mr-2 mb-2"><option value="">All Firms</option>@foreach($propFirms as $firm)<option value="{{ $firm->id }}" @selected(request('prop_firm_id') == $firm->id)>{{ $firm->name }}</option>@endforeach</select>
<select name="status" class="form-control mr-2 mb-2"><option value="">All Status</option>@foreach(['pending','approved','rejected'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
<button type="submit" class="btn btn-primary mb-2">Filter</button>
</form></div></div>
<div class="card shadow"><div class="card-body table-responsive">
<table class="table table-bordered table-hover"><thead class="thead-dark"><tr><th>Title</th><th>Firm</th><th>Rating</th><th>Author</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>@forelse($reviews as $review)<tr><td>{{ Str::limit($review->title, 50) }}</td><td>{{ $review->propFirm?->name ?? '—' }}</td><td>{{ number_format($review->rating, 1) }}</td><td>{{ $review->author ?? '—' }}</td><td><span class="badge badge-{{ $review->status === 'approved' ? 'success' : ($review->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($review->status) }}</span></td><td><a href="{{ route('admin_prop_firm_reviews_edit', $review->id) }}" class="btn btn-sm btn-primary">Edit</a><form action="{{ route('admin_prop_firm_reviews_delete', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Delete</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No reviews found.</td></tr>@endforelse</tbody></table>
{{ $reviews->links() }}</div></div></div>
@endsection
