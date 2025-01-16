@extends('admin.layout.app')

@section('heading', 'Forex Review')

@section('button')
<a href="" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <h3>Account Options for Broker: {{ $broker->name }}</h3>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add New Account Option Button -->
        <a href="{{ route('admin_account_options_create', $broker->id) }}" class="btn btn-primary mb-3">
            Add New Account Option
        </a>

        <!-- Table of Account Options -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Account Type</th>
                    <th>Currency</th>
                    <th>Min Deposit</th>
                    <th>Max Leverage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($accountOptions as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td>{{ $option->account_type }}</td>
                        <td>{{ $option->account_currency }}</td>
                        <td>{{ $option->min_deposit }}</td>
                        <td>{{ $option->max_leverage }}</td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('admin_account_options_edit', [$broker->id, $option->id]) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin_account_options_delete', [$broker->id, $option->id]) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No account options available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>   
@endsection