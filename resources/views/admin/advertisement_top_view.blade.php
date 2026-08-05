@extends('admin.layout.app')

@section('heading', 'Top Advertisements')

@section('main_content')
<div class="section-body py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Update Top Advertisement</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin_top_ad_update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold">Existing Photo</label>
                                <div class="mb-3">
                                    <img src="{{ asset('Uploads/'.$top_ad_data->top_ad) }}" alt="Top Ad" class="img-fluid rounded" style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Change Photo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="top_ad" name="top_ad">
                                    <label class="custom-file-label" for="top_ad">Choose file</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">URL</label>
                                <input type="url" class="form-control" name="top_ad_url" value="{{ $top_ad_data->top_ad_url }}" placeholder="https://example.com" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Status</label>
                                <select name="top_ad_status" class="form-control custom-select" required>
                                    <option value="Show" @if($top_ad_data->top_ad_status == 'Show') selected @endif>Show</option>
                                    <option value="Hide" @if($top_ad_data->top_ad_status == 'Hide') selected @endif>Hide</option>
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