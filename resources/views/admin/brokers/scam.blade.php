@extends('admin.layout.app')

@section('heading', 'Scam / Flagged Brokers')

@section('button')
<a href="{{ route('admin_broker_show') }}" class="btn btn-secondary btn-lg">
    <i class="fas fa-arrow-left"></i> All Brokers
</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i> Flagged Scam Brokers</h4>
        </div>
        <div class="card-body">
            @if($brokers->isEmpty())
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle mr-2"></i>
                No brokers are currently flagged as scam. To flag one, edit a broker and enable the
                <strong>Scam / Risk Flag</strong> option.
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">Name</th>
                            <th width="10%">Logo</th>
                            <th width="40%">Warning / Reason</th>
                            <th width="12%">Reported</th>
                            <th width="13%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brokers as $broker)
                        <tr>
                            <td>{{ $loop->iteration + ($brokers->currentPage() - 1) * $brokers->perPage() }}</td>
                            <td><strong>{{ $broker->name }}</strong></td>
                            <td>
                                @if ($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" class="img-thumbnail" style="max-height: 45px;">
                                @else
                                <span class="badge badge-light">No logo</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ \Illuminate\Support\Str::limit($broker->scam_reason, 160) ?: '—' }}</td>
                            <td>{{ $broker->scam_reported_date ? \Carbon\Carbon::parse($broker->scam_reported_date)->format('M d, Y') : '—' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin_broker_edit', $broker->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('scam_brokers') }}" target="_blank" class="btn btn-sm btn-secondary" title="View public page">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        @if(!$brokers->isEmpty())
        <div class="card-footer bg-white">
            {{ $brokers->links('pagination::bootstrap-4') }}
        </div>
        @endif
    </div>
</div>
@endsection
