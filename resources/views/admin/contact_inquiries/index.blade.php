@extends('admin.layout.app')

@section('heading', 'Contact Inquiries')

@section('main_content')
<div class="section-body">
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group" aria-label="Filter inquiries">
                <a href="{{ route('admin_contact_inquiries.index') }}"
                   class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                    All ({{ $counts['all'] }})
                </a>
                <a href="{{ route('admin_contact_inquiries.index', ['status' => 'new']) }}"
                   class="btn btn-sm {{ $status === 'new' ? 'btn-primary' : 'btn-outline-primary' }}">
                    New ({{ $counts['new'] }})
                </a>
                <a href="{{ route('admin_contact_inquiries.index', ['status' => 'read']) }}"
                   class="btn btn-sm {{ $status === 'read' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Read ({{ $counts['read'] }})
                </a>
                <a href="{{ route('admin_contact_inquiries.index', ['status' => 'archived']) }}"
                   class="btn btn-sm {{ $status === 'archived' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Archived ({{ $counts['archived'] }})
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($inquiries->count())
                        <table class="table table-bordered table-striped" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inquiries as $inquiry)
                                    <tr class="{{ $inquiry->isNew() ? 'table-warning' : '' }}">
                                        <td>{{ $inquiry->id }}</td>
                                        <td>{{ $inquiry->created_at->format('M j, Y g:i A') }}</td>
                                        <td>{{ $inquiry->name }}</td>
                                        <td>{{ $inquiry->email }}</td>
                                        <td>{{ Str::limit($inquiry->subject, 40) }}</td>
                                        <td>
                                            @if($inquiry->status === 'new')
                                                <span class="badge badge-warning">New</span>
                                            @elseif($inquiry->status === 'read')
                                                <span class="badge badge-info">Read</span>
                                            @else
                                                <span class="badge badge-secondary">Archived</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin_contact_inquiries.show', $inquiry) }}" class="btn btn-primary btn-sm">View</a>
                                            @if($inquiry->status !== 'archived')
                                                <form action="{{ route('admin_contact_inquiries.archive', $inquiry) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-secondary btn-sm">Archive</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin_contact_inquiries.destroy', $inquiry) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Delete this inquiry?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $inquiries->links() }}
                    @else
                        <p class="text-muted mb-0">No contact inquiries yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
