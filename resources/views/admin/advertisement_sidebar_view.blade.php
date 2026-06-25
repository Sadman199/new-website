@extends('admin.layout.app')

@section('heading', 'Sidebar Advertisements')

@section('button')
<a href="{{ route('admin_sidebar_ad_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Advertisement List</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 5%">#</th>
                                        <th scope="col" class="text-center" style="width: 25%">Photo</th>
                                        <th scope="col" style="width: 30%">URL</th>
                                        <th scope="col" class="text-center" style="width: 15%">Location</th>
                                        <th scope="col" class="text-center" style="width: 25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sidebar_ad_data as $row)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-center align-middle">
                                            <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="Ad Image" class="img-fluid rounded" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ $row->sidebar_ad_url }}" target="_blank" class="text-primary">{{ Str::limit($row->sidebar_ad_url, 50) }}</a>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->sidebar_ad_location }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin_sidebar_ad_edit', $row->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="{{ route('admin_sidebar_ad_delete', $row->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this advertisement?');" title="Delete">
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