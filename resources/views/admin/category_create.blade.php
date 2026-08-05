@extends('admin.layout.app')

@section('heading', 'Add Category')

@section('button')
<a href="{{ route('admin_category_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Create New Category</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_category_store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="category_name" value="" placeholder="Enter category name" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Category Slug</label>
                                <input type="text" class="form-control" name="slug" value="" placeholder="e.g., category-name">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Show on Menu?</label>
                                <select name="show_on_menu" class="form-control custom-select" required>
                                    <option value="Show">Show</option>
                                    <option value="Hide">Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Category Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="category_order" value="" placeholder="Enter order number" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Select Language</label>
                                <select name="language_id" class="form-control custom-select" required>
                                    @foreach($global_language_data as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Create Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection