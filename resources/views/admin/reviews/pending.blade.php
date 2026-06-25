@extends('admin.layout.app')

@section('heading', 'Pending Reviews')

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pending Reviews</h5>
                        <a href="#" class="btn btn-light btn-sm"><i class="fas fa-users"></i> User Reviews</a>
                    </div>
                    <div class="card-body">
                        <!-- Display success or error messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Table of pending reviews -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 15%">Name</th>
                                        <th scope="col" class="text-center" style="width: 15%">Email</th>
                                        <th scope="col" class="text-center" style="width: 10%">Country</th>
                                        <th scope="col" style="width: 25%">Review</th>
                                        <th scope="col" class="text-center" style="width: 10%">Rating</th>
                                        <th scope="col" class="text-center" style="width: 15%">Broker Name</th>
                                        <th scope="col" class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                        <tr>
                                            <td class="text-center align-middle">{{ $review->name }}</td>
                                            <td class="text-center align-middle">{{ $review->email }}</td>
                                            <td class="text-center align-middle">{{ $review->country ?? 'N/A' }}</td>
                                            <td class="align-middle">{{ Str::limit($review->description, 100) }}</td>
                                            <td class="text-center align-middle">{{ $review->rating }} / 5</td>
                                            <td class="text-center align-middle">{{ $review->broker->name ?? 'N/A' }}</td>
                                           <td class="text-center align-middle">
                                                <div class="d-inline-flex">
                                                    <form action="{{ route('reviews.approve', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this review?');" class="mr-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('reviews.decline', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to decline this review?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Decline">
                                                            <i class="fas fa-times"></i> Decline
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No pending reviews available</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection