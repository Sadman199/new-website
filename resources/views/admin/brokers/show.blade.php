@extends('admin.layout.app')

@section('heading', 'All Forex Broker')

@section('button')
<a href="{{ route('admin_broker_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <!-- Check if there are any brokers to display -->
        @if($brokers->isEmpty())
        <p>No brokers found.</p>
        @else
        <!-- Table to display broker information -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Logo</th>
                    <th>URL</th>
                    <th>Country</th>
                    <th>Associated Countries</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brokers as $broker)
                <tr>
                    <td>{{ $broker->name }}</td>

                    <td>
                        <!-- Displaying the Broker Logo -->
                        @if ($broker->logo)
                            <img src="{{ asset($broker->logo) }}" alt="Broker Logo" style="width:100px">
                        @else
                            <p>No logo available</p>
                        @endif

                    </td>

                    <td><a href="{{ $broker->url }}" target="_blank">{{ $broker->url }}</a></td>
                    <td>{{ $broker->country }}</td>

                    <!-- Display associated countries as a comma-separated list -->
                    <td>
                        @if($broker->associated_countries)
                        @foreach($broker->associated_countries as $country)
                        {{ $country }},
                        @endforeach
                        @else
                        No Associated Countries
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin_broker_edit', $broker->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Delete Form -->
                        <form action="{{ route('admin_broker_delete', $broker->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this broker?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>


@endsection