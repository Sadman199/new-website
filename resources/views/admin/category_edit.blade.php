@extends('admin.layout.app')

@section('heading', 'Edit Category')

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
                        <h5 class="mb-0">Update Category</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_category_update', $category_single->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="font-weight-bold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="category_name" value="{{ $category_single->category_name }}" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Category Slug</label>
                                <input type="text" class="form-control" name="slug" value="{{ $category_single->slug }}" placeholder="e.g., category-name">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Show on Menu?</label>
                                <select name="show_on_menu" class="form-control custom-select" required>
                                    <option value="Show" @if($category_single->show_on_menu == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if($category_single->show_on_menu == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Category Order <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="category_order" value="{{ $category_single->category_order }}" required>
                            </div>
                            @include('admin.partials.language_id_field', ['language_id' => $category_single->language_id])
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection