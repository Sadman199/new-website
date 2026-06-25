@extends('admin.layout.app')

@section('heading', 'Forex Brokers Management')

@section('button')
<a href="{{ route('admin_broker_create') }}" class="btn btn-primary btn-lg">
    <i class="fas fa-plus-circle"></i> Add New Broker
</a>
@endsection

@section('main_content')
<div class="section-body">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h4 class="mb-0">Forex Brokers List</h4>
        </div>
        <div class="card-body">
            @if($brokers->isEmpty())
            <div class="alert alert-info alert-dismissible fade show">
                <i class="fas fa-info-circle mr-2"></i>
                No brokers found. Would you like to 
                <a href="{{ route('admin_broker_create') }}" class="alert-link">add one</a>?
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Name</th>
                            <th width="20%">Logo</th>
                            <th width="20%">Rating</th>
                            <th width="30%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brokers as $broker)
                        <tr>
                            <td>{{ $loop->iteration + ($brokers->currentPage() - 1) * $brokers->perPage() }}</td>
                            <td>
                                <strong>{{ $broker->name }}</strong>
                                @if($broker->is_featured)
                                <span class="badge badge-warning ml-2">Featured</span>
                                @endif
                            </td>
                            <td>
                                @if ($broker->logo)
                                <img src="{{ asset($broker->logo) }}" 
                                     alt="{{ $broker->name }} logo"
                                     class="img-thumbnail"
                                     style="max-height: 50px;">
                                @else
                                <span class="badge badge-light">No logo</span>
                                @endif
                            </td>
                            <td>
                                <div class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= ($broker->rating ?? 0) ? 'text-warning' : 'text-secondary' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin_broker_edit', $broker->id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin_broker_delete', $broker->id) }}" 
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete {{ $broker->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin_broker_show', $broker->id) }}" 
                                       class="btn btn-sm btn-secondary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
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

@push('styles')
<style>
    .star-rating {
        font-size: 0.9rem;
    }
    .star-rating .fas.fa-star {
        margin-right: 2px;
    }
    table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush
