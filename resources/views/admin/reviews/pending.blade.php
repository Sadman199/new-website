@extends('admin.layout.app')

@section('heading', 'Pending Reviews')



@section('main_content')
<div class="card">
    <div class="card-body">
        <a href="" class="btn btn-success mb-3">User Reviews</a>

            <!-- Display success or error messages -->
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <!-- Table of pending reviews -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>
                        <th>Review</th>
                        <th>Rating</th>
                        <th>Broker Name</th>
                        <th>Actions</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                    <tr>
                        <td>{{ $review->name }}</td>
                        <td>{{ $review->email }}</td>
                        <td>{{ $review->country }}</td>
                        <td>{{ $review->description }}</td>
                        <td>{{ $review->rating }} / 5</td>
                        <td>{{ $review->broker->name }}</td> <!-- Add broker's name here -->
                        <td>
                            <!-- Approve Button -->
                            <form action="{{ route('reviews.approve', $review->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

                            <!-- Decline Button -->
                            <form action="{{ route('reviews.decline', $review->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        
    </div>
</div>


@endsection