@extends('admin.layout.app')

@section('heading', 'Edit Sub Category')

@section('button')
<a href="{{ route('admin_sub_category_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Sub Category</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_sub_category_update', $sub_category_single->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <ul class="nav nav-tabs mb-4" id="subCategoryTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">Sub Category Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Additional Settings</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="subCategoryTabsContent">
                                <!-- Sub Category Information Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Sub Category Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="sub_category_name" value="{{ old('sub_category_name', $sub_category_single->sub_category_name) }}" placeholder="Enter sub category name" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Slug <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="slug" value="{{ old('slug', $sub_category_single->slug) }}" placeholder="e.g., sub-category-name" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="font-weight-bold">Select Category <span class="text-danger">*</span></label>
                                                <select name="category_id" class="form-control custom-select" required>
                                                    @foreach($categories as $row)
                                                        <option value="{{ $row->id }}" @if($sub_category_single->category_id == $row->id) selected @endif>{{ $row->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Sub Category Order <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="sub_category_order" value="{{ old('sub_category_order', $sub_category_single->sub_category_order) }}" placeholder="Enter order number" required>
                                            </div>
                                            @include('admin.partials.language_id_field', ['language_id' => $sub_category_single->language_id])
                                        </div>
                                    </div>
                                </div>
                                <!-- Additional Settings Tab -->
                                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Show on Menu?</label>
                                                <select name="show_on_menu" class="form-control custom-select">
                                                    <option value="Show" @if($sub_category_single->show_on_menu == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($sub_category_single->show_on_menu == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Show on Home?</label>
                                                <select name="show_on_home" class="form-control custom-select">
                                                    <option value="Show" @if($sub_category_single->show_on_home == 'Show') selected @endif>Show</option>
                                                    <option value="Hide" @if($sub_category_single->show_on_home == 'Hide') selected @endif>Hide</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Sub Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection