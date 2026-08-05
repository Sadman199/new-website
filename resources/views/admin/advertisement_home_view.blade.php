@extends('admin.layout.app')

@section('heading', 'Home Advertisements')

@section('main_content')
<div class="section-body">
    <div class="container-fluid">
        <form action="{{ route('admin_home_ad_update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Above Search Ad -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Above Search Advertisement</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Existing Photo</label>
                                <div class="mb-3">
                                    <img src="{{ asset('Uploads/'.$home_ad_data->above_search_ad) }}" alt="Above Search Ad" class="img-fluid rounded" style="max-width: 150px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Change Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="above_search_ad" name="above_search_ad">
                                    <label class="custom-file-label" for="above_search_ad">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">URL</label>
                                <input type="text" class="form-control" name="above_search_ad_url" value="{{ $home_ad_data->above_search_ad_url }}">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Status</label>
                                <select name="above_search_ad_status" class="form-control custom-select">
                                    <option value="Show" @if($home_ad_data->above_search_ad_status == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if($home_ad_data->above_search_ad_status == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Above Footer Ad -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Above Footer Advertisement</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Existing Photo</label>
                                <div class="mb-3">
                                    <img src="{{ asset('Uploads/'.$home_ad_data->above_footer_ad) }}" alt="Above Footer Ad" class="img-fluid rounded" style="max-width: 150px;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Change Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="above_footer_ad" name="above_footer_ad">
                                    <label class="custom-file-label" for="above_footer_ad">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">URL</label>
                                <input type="text" class="form-control" name="above_footer_ad_url" value="{{ $home_ad_data->above_footer_ad_url }}">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Status</label>
                                <select name="above_footer_ad_status" class="form-control custom-select">
                                    <option value="Show" @if($home_ad_data->above_footer_ad_status == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if($home_ad_data->above_footer_ad_status == 'Hide') selected @endif>Hide</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary btn-lg">Update Advertisements</button>
            </div>
        </form>
    </div>
</div>
@endsection