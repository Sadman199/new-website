@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Top Advertisements')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'top'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Advertisements</p>
                <h1 class="ab-header__title">Update Top Advertisement</h1>
                <p class="ab-header__sub">The banner shown at the very top of public pages.</p>
            </div>
        </header>

        @if($errors->any())
            <div class="ab-banner" role="alert">
                <h3>Please fix {{ $errors->count() }} {{ \Illuminate\Support\Str::plural('issue', $errors->count()) }} before saving</h3>
                <ol>
                    @foreach($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ol>
            </div>
        @endif

        <form action="{{ route('admin_top_ad_update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Top banner</h2>
                    <p>Upload an image, optional click URL, and choose whether visitors see it.</p>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label>Current image</label>
                        @if(!empty($top_ad_data->top_ad))
                            <img id="top_ad_preview" class="ab-ad-preview" src="{{ asset('uploads/'.$top_ad_data->top_ad) }}" alt="Top Ad">
                        @else
                            <div id="top_ad_preview_placeholder" class="ab-ad-placeholder">
                                <i class="fas fa-image" aria-hidden="true"></i> No image uploaded yet
                            </div>
                            <img id="top_ad_preview" src="" alt="" class="ab-ad-preview d-none">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="top_ad">Change photo</label>
                        <input type="file" name="top_ad" id="top_ad" class="form-control-file @error('top_ad') is-invalid @enderror" accept="image/*">
                        <p class="ab-note mt-1 mb-0">JPG, PNG, GIF, WEBP — max 5MB. Leave empty to keep the current image.</p>
                        @error('top_ad')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group">
                        <label for="top_ad_url">Click URL</label>
                        <input type="url" name="top_ad_url" id="top_ad_url" class="form-control @error('top_ad_url') is-invalid @enderror"
                               value="{{ old('top_ad_url', $top_ad_data->top_ad_url) }}" placeholder="https://">
                        @error('top_ad_url')<small class="ab-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="form-group mb-0">
                        <label for="top_ad_status">Status</label>
                        <select name="top_ad_status" id="top_ad_status" class="form-control" required>
                            <option value="Show" @selected(old('top_ad_status', $top_ad_data->top_ad_status) === 'Show')>Show</option>
                            <option value="Hide" @selected(old('top_ad_status', $top_ad_data->top_ad_status) === 'Hide')>Hide</option>
                        </select>
                    </div>
                </div>
            </section>
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Save top ad
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('top_ad')?.addEventListener('change', function () {
    if (!this.files || !this.files[0]) return;
    var preview = document.getElementById('top_ad_preview');
    var placeholder = document.getElementById('top_ad_preview_placeholder');
    var reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    };
    reader.readAsDataURL(this.files[0]);
});
</script>
@endpush
