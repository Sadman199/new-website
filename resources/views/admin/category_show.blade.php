@extends('admin.layout.app')

@section('heading', 'Categories')

@section('button')
<a href="{{ route('admin_category_create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Category List</h5>
                        <a href="{{ route('admin_category_create') }}" class="btn btn-light btn-sm"><i class="fas fa-plus"></i> Add New</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="example1">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-center" style="width: 5%">#</th>
                                        <th scope="col" style="width: 30%">Category Name</th>
                                        <th scope="col" class="text-center" style="width: 15%">Show on Menu</th>
                                        <th scope="col" class="text-center" style="width: 10%">Order</th>
                                        <th scope="col" class="text-center" style="width: 20%">Language</th>
                                        <th scope="col" class="text-center" style="width: 20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $row)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $row->category_name }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge @if($row->show_on_menu == 'Show') badge-success @else badge-secondary @endif">
                                                {{ $row->show_on_menu }}
                                            </span>
                                        </td>
                                        <td class="text-center align-middle">{{ $row->category_order }}</td>
                                        <td class="text-center align-middle">{{ $row->rLanguage->name }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin_category_edit', $row->id) }}" class="btn btn-primary btn-sm mr-1" title="Edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin_category_delete', $row->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
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
    </div>
</div>
@endsection