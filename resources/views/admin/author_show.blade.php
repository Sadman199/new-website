@extends('admin.layout.app')

@section('heading', 'Authors')

@section('button')
<a href="{{ route('admin_author_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Author List</h5>
                        <a href="{{ route('admin_author_create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 5%">#</th>
                                        <th scope="col" class="text-center" style="width: 15%">Photo</th>
                                        <th scope="col" style="width: 30%">Name</th>
                                        <th scope="col" style="width: 30%">Email</th>
                                        <th scope="col" class="text-center" style="width: 20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($authors as $row)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <img src="{{ $row->photo ? asset('Uploads/'.$row->photo) : asset('Uploads/default.png') }}" alt="Author Photo" class="img-fluid rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle">{{ $row->name }}</td>
                                        <td class="align-middle">{{ $row->email }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin_author_edit', $row->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin_author_delete', $row->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this author?');" title="Delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
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
    </div>
</div>
@endsection