@extends('admin.layout.app')

@section('heading', 'All Subscribers')

@section('main_content')
<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="example1">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Subscriber Email</th>
                                <th>Status</th>
                                <th>Actions</th> <!-- Add a new column for actions -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->email }}</td>
                                <td>
                                    @if($row->status === null)
                                    Pending Review
                                    @elseif($row->status === 'active')
                                    Active
                                    @elseif($row->status === 'inactive')
                                    Inactive
                                    @else
                                    {{ ucfirst($row->status) }}
                                    @endif
                                </td>
                                <td>
                                    <!-- Accept button only if status is null (pending review) -->
                                    <form action="{{ route('subscriber.accept', $row->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success">Accept</button>
                                    </form>

                                    <!-- Decline button only if status is null (pending review) -->
                                    <form action="{{ route('subscriber.decline', $row->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger">Decline</button>
                                    </form>

                                    <!-- Reply button to send an email to the subscriber -->
                                    <a href="mailto:{{ $row->email }}?subject=Reply from Brokers Court&body=Hello, please find the response below."
                                        class="btn btn-info">
                                        Reply
                                    </a>

                                    <!-- Delete button -->
                                    <form action="{{ route('subscriber.delete', $row->id) }}" method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection