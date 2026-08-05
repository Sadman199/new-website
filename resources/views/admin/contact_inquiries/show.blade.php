@extends('admin.layout.app')

@section('heading', 'Contact Inquiry #' . $inquiry->id)

@section('main_content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $inquiry->subject }}</h4>
                    <div>
                        <a href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: ' . $inquiry->subject) }}"
                           class="btn btn-info btn-sm">Reply by email</a>
                        <a href="{{ route('admin_contact_inquiries.index') }}" class="btn btn-secondary btn-sm">Back to list</a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Received</dt>
                        <dd class="col-sm-9">{{ $inquiry->created_at->format('M j, Y g:i A') }}</dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">
                            @if($inquiry->status === 'new')
                                <span class="badge badge-warning">New</span>
                            @elseif($inquiry->status === 'read')
                                <span class="badge badge-info">Read</span>
                            @else
                                <span class="badge badge-secondary">Archived</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $inquiry->name }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd>

                        <dt class="col-sm-3">Subject</dt>
                        <dd class="col-sm-9">{{ $inquiry->subject }}</dd>

                        <dt class="col-sm-3">IP address</dt>
                        <dd class="col-sm-9">{{ $inquiry->ip_address ?: '—' }}</dd>

                        <dt class="col-sm-3">User agent</dt>
                        <dd class="col-sm-9"><small class="text-muted">{{ $inquiry->user_agent ?: '—' }}</small></dd>

                        <dt class="col-sm-3">Message</dt>
                        <dd class="col-sm-9">
                            <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;">{{ $inquiry->message }}</div>
                        </dd>
                    </dl>
                </div>
                <div class="card-footer">
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
