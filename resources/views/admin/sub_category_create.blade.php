@extends('admin.layout.app')

@section('heading', 'Add Sub Category')

@section('button')
<a href="{{ route('admin_sub_category_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body">
    <form action="{{ route('admin_sub_category_store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Sub Category Name *</label>
                                    <input type="text" class="form-control" name="sub_category_name" id="sub_category_name" value="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                

                                        <div class="form-group mb-3">
                                            <label>Slug *</label>
                                            <input type="text" class="form-control" name="slug" id="slug" value="">
                                        </div>
                                        <script>
                                            $(document).ready(function () {
                                                $('#sub_category_name').on('input', function () {
                                                    const slug = $(this).val()
                                                        .toLowerCase()
                                                        .trim()
                                                        .replace(/[^a-z0-9\s-]/g, '') // Remove invalid characters
                                                        .replace(/\s+/g, '-')        // Replace spaces with hyphens
                                                        .replace(/-+/g, '-');        // Collapse multiple hyphens
                                                    $('#slug').val(slug);
                                                });
                                            });
                                        </script>



                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Show on menu?</label>
                                    <select name="show_on_menu" class="form-control">
                                        <option value="Show">Show</option>
                                        <option value="Hide">Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label>Show on home?</label>
                                    <select name="show_on_home" class="form-control">
                                        <option value="Show">Show</option>
                                        <option value="Hide">Hide</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group mb-3">
                                    <label>Sub Category Order *</label>
                                    <input type="text" class="form-control" name="sub_category_order" value="">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label>Select Category *</label>
                                    <select name="category_id" class="form-control">
                                        @foreach($categories as $row)
                                        <option value="{{ $row->id }}">{{ $row->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group mb-3">
                                    <label>Select Language</label>
                                    <select name="language_id" class="form-control">
                                        @foreach($global_language_data as $row)
                                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection