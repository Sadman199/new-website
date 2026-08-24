@extends('admin.layout.app')

@section('heading', 'Home Advertisements')

@section('main_content')
<div class="section-body">
    <div class="container-fluid">

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

        <form action="{{ route('admin_home_ad_update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- Above Search Ad --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-search mr-2"></i>
                            <h5 class="mb-0">Above Search Advertisement</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label class="font-weight-bold">Current Image</label>
                                <div class="mb-2">
                                    @if($home_ad_data->above_search_ad)
                                        <img id="search_ad_preview"
                                             src="{{ asset('uploads/' . $home_ad_data->above_search_ad) }}"
                                             alt="Above Search Ad"
                                             class="img-fluid rounded border"
                                             style="max-height: 160px; width: 100%; object-fit: cover;">
                                    @else
                                        <div id="search_ad_preview_placeholder"
                                             class="rounded border d-flex align-items-center justify-content-center text-muted"
                                             style="height: 120px; background: #f8f9fa; font-size: 0.85rem;">
                                            <i class="fas fa-image mr-2"></i> No image uploaded yet
                                        </div>
                                        <img id="search_ad_preview" src="" alt="" class="img-fluid rounded border d-none"
                                             style="max-height: 160px; width: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Upload New Image
                                    <small class="text-muted font-weight-normal">(jpg, png, gif, webp — max 5MB)</small>
                                </label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('above_search_ad') is-invalid @enderror"
                                           id="above_search_ad"
                                           name="above_search_ad"
                                           accept="image/*"
                                           onchange="previewImage(this, 'search_ad_preview', 'search_ad_preview_placeholder')">
                                    <label class="custom-file-label" for="above_search_ad">Choose file…</label>
                                    @error('above_search_ad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Click URL</label>
                                <input type="url"
                                       class="form-control @error('above_search_ad_url') is-invalid @enderror"
                                       name="above_search_ad_url"
                                       value="{{ old('above_search_ad_url', $home_ad_data->above_search_ad_url) }}"
                                       placeholder="https://example.com">
                                @error('above_search_ad_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Status</label>
                                <select name="above_search_ad_status" class="form-control custom-select @error('above_search_ad_status') is-invalid @enderror">
                                    <option value="Show" @selected(old('above_search_ad_status', $home_ad_data->above_search_ad_status) === 'Show')>Show</option>
                                    <option value="Hide" @selected(old('above_search_ad_status', $home_ad_data->above_search_ad_status) === 'Hide')>Hide</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Above Footer Ad --}}
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-align-justify mr-2"></i>
                            <h5 class="mb-0">Above Footer Advertisement</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label class="font-weight-bold">Current Image</label>
                                <div class="mb-2">
                                    @if($home_ad_data->above_footer_ad)
                                        <img id="footer_ad_preview"
                                             src="{{ asset('uploads/' . $home_ad_data->above_footer_ad) }}"
                                             alt="Above Footer Ad"
                                             class="img-fluid rounded border"
                                             style="max-height: 160px; width: 100%; object-fit: cover;">
                                    @else
                                        <div id="footer_ad_preview_placeholder"
                                             class="rounded border d-flex align-items-center justify-content-center text-muted"
                                             style="height: 120px; background: #f8f9fa; font-size: 0.85rem;">
                                            <i class="fas fa-image mr-2"></i> No image uploaded yet
                                        </div>
                                        <img id="footer_ad_preview" src="" alt="" class="img-fluid rounded border d-none"
                                             style="max-height: 160px; width: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Upload New Image
                                    <small class="text-muted font-weight-normal">(jpg, png, gif, webp — max 5MB)</small>
                                </label>
                                <div class="custom-file">
                                    <input type="file"
                                           class="custom-file-input @error('above_footer_ad') is-invalid @enderror"
                                           id="above_footer_ad"
                                           name="above_footer_ad"
                                           accept="image/*"
                                           onchange="previewImage(this, 'footer_ad_preview', 'footer_ad_preview_placeholder')">
                                    <label class="custom-file-label" for="above_footer_ad">Choose file…</label>
                                    @error('above_footer_ad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Click URL</label>
                                <input type="url"
                                       class="form-control @error('above_footer_ad_url') is-invalid @enderror"
                                       name="above_footer_ad_url"
                                       value="{{ old('above_footer_ad_url', $home_ad_data->above_footer_ad_url) }}"
                                       placeholder="https://example.com">
                                @error('above_footer_ad_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Status</label>
                                <select name="above_footer_ad_status" class="form-control custom-select @error('above_footer_ad_status') is-invalid @enderror">
                                    <option value="Show" @selected(old('above_footer_ad_status', $home_ad_data->above_footer_ad_status) === 'Show')>Show</option>
                                    <option value="Hide" @selected(old('above_footer_ad_status', $home_ad_data->above_footer_ad_status) === 'Hide')>Hide</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-2 mb-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="fas fa-save mr-2"></i>Save Advertisements
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function previewImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = placeholderId ? document.getElementById(placeholderId) : null;

    // Update the file label text
    const label = input.closest('.custom-file').querySelector('.custom-file-label');
    if (label && input.files.length) {
        label.textContent = input.files[0].name;
    }

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            if (placeholder) placeholder.classList.add('d-none');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
