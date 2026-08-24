@extends('admin.layout.app')

@section('heading', 'Top Advertisements')

@section('main_content')
<div class="section-body py-4">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

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
                                    @if(!empty($top_ad_data->top_ad))
                                        <img id="top_ad_preview"
                                             src="{{ asset('uploads/'.$top_ad_data->top_ad) }}"
                                             alt="Top Ad"
                                             class="img-fluid rounded border"
                                             style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                    @else
                                        <div id="top_ad_preview_placeholder"
                                             class="rounded border d-flex align-items-center justify-content-center text-muted"
                                             style="height: 120px; background: #f8f9fa; font-size: 0.85rem;">
                                            <i class="fas fa-image mr-2"></i> No image uploaded yet
                                        </div>
                                        <img id="top_ad_preview" src="" alt="" class="img-fluid rounded border d-none"
                                             style="max-width: 100%; max-height: 150px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Change Photo
                                    <small class="text-muted font-weight-normal">(jpg, png, gif, webp — max 5MB)</small>
                                </label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('top_ad') is-invalid @enderror"
                                           id="top_ad"
                                           name="top_ad"
                                           accept="image/*"
                                           onchange="previewTopAd(this)">
                                    <label class="custom-file-label" for="top_ad">Choose file</label>
                                    @error('top_ad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">URL</label>
                                <input type="url"
                                       class="form-control @error('top_ad_url') is-invalid @enderror"
                                       name="top_ad_url"
                                       value="{{ old('top_ad_url', $top_ad_data->top_ad_url) }}"
                                       placeholder="https://example.com">
                                @error('top_ad_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Status</label>
                                <select name="top_ad_status" class="form-control custom-select @error('top_ad_status') is-invalid @enderror" required>
                                    <option value="Show" @selected(old('top_ad_status', $top_ad_data->top_ad_status) === 'Show')>Show</option>
                                    <option value="Hide" @selected(old('top_ad_status', $top_ad_data->top_ad_status) === 'Hide')>Hide</option>
                                </select>
                                @error('top_ad_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

@push('scripts')
<script>
function previewTopAd(input) {
    if (!input.files || !input.files[0]) return;
    const preview = document.getElementById('top_ad_preview');
    const placeholder = document.getElementById('top_ad_preview_placeholder');
    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    };
    reader.readAsDataURL(input.files[0]);
    const label = input.nextElementSibling;
    if (label) label.textContent = input.files[0].name;
}
</script>
@endpush
