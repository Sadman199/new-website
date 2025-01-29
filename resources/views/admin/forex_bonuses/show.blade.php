@extends('admin.layout.app')

@section('heading', 'Deposit Bonus')

@section('button')
<a href="{{ route('admin_forex_bonus_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <!-- Check if there are any Forex Bonus posts -->
        @if($forexBonuses->count() > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th> <!-- Added Image column -->
                    <th>Publish Date</th>
                    <th>Author</th>
                    <th>Promo Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forexBonuses as $forexBonus)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $forexBonus->title }}</td>

                    <td>
                        <!-- Displaying the feature image -->
                        @if ($forexBonus->feature_image)
                        <img src="{{ asset($forexBonus->feature_image) }}" alt="Feature Image"
                            style="width:100px">
                        @else
                        <p>No image available.</p>
                        @endif
                    </td>


                    <td>{{ \Carbon\Carbon::parse($forexBonus->publish_date)->format('d M Y') }}</td>
                    <td>{{ $forexBonus->author_name }}</td>
                    <td>{{ $forexBonus->promo_type }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('admin_forex_bonus_edit', $forexBonus->id) }}"
                            class="btn btn-primary btn-sm">Edit</a>

                        <!-- Delete Button -->
                        <form action="{{ route('admin_forex_bonus_delete', $forexBonus->id) }}" method="POST"
                            style="display:inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete this Forex Bonus?');">
                            @csrf
                            @method('DELETE')
                            <!-- Change this line -->
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $forexBonuses->links() }}
        </div>

        @else
        <div class="alert alert-warning">
            No Forex Bonus posts found.
        </div>
        @endif
    </div>
</div>
</div>

@endsection