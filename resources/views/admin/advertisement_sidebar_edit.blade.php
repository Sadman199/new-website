@extends('admin.layout.app')

@section('heading', 'Sidebar Advertisement Update')

@section('button')
<a href="{{ route('admin_sidebar_ad_show') }}" class="btn btn-primary"><i class="fas fa-eye"></i> View</a>
@endsection

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Sidebar Advertisement</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_sidebar_ad_update', $sidebar_ad_data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Existing Photo</label>
                                <div class="mb-3">
                                    <img src="{{ asset('Uploads/'.$sidebar_ad_data->sidebar_ad) }}" alt="Current Ad" class="img-fluid rounded" style="max-width: 150px; max-height: 100px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Change Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="sidebar_ad" name="sidebar_ad">
                                    <label class="custom-file-label" for="sidebar_ad">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">URL</label>
                                <input type="url" class="form-control" name="sidebar_ad_url" value="{{ $sidebar_ad_data->sidebar_ad_url }}" placeholder="https://example.com" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Location</label>
                                <select name="sidebar_ad_location" class="form-control custom-select" required>
                                    <option value="Top" @if($sidebar_ad_data->sidebar_ad_location == 'Top') selected @endif>Top</option>
                                    <option value="Bottom" @if($sidebar_ad_data->sidebar_ad_location == 'Bottom') selected @endif>Bottom</option>
                                </select>
                            </div>
                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Update Advertisement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection