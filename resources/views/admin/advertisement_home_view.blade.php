@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Home Advertisements')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'home'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Advertisements</p>
                <h1 class="ab-header__title">Home Ads</h1>
                <p class="ab-header__sub">Banners above the homepage search and above the footer.</p>
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

        <form action="{{ route('admin_home_ad_update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ab-ad-grid">
                <section class="ab-section">
                    <div class="ab-section__head">
                        <h2>Above search</h2>
                        <p>Shown above the homepage search bar.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="form-group">
                            @if($home_ad_data->above_search_ad)
                                <img id="search_ad_preview" class="ab-ad-preview" src="{{ asset('uploads/' . $home_ad_data->above_search_ad) }}" alt="Above Search Ad">
                            @else
                                <div id="search_ad_preview_placeholder" class="ab-ad-placeholder">
                                    <i class="fas fa-image" aria-hidden="true"></i> No image uploaded yet
                                </div>
                                <img id="search_ad_preview" src="" alt="" class="ab-ad-preview d-none">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="above_search_ad">Upload new image</label>
                            <input type="file" name="above_search_ad" id="above_search_ad" class="form-control-file" accept="image/*">
                            <p class="ab-note mt-1 mb-0">JPG, PNG, GIF, WEBP — max 5MB.</p>
                        </div>
                        <div class="form-group">
                            <label for="above_search_ad_url">Click URL</label>
                            <input type="url" name="above_search_ad_url" id="above_search_ad_url" class="form-control"
                                   value="{{ old('above_search_ad_url', $home_ad_data->above_search_ad_url) }}" placeholder="https://">
                        </div>
                        <div class="form-group mb-0">
                            <label for="above_search_ad_status">Status</label>
                            <select name="above_search_ad_status" id="above_search_ad_status" class="form-control">
                                <option value="Show" @selected(old('above_search_ad_status', $home_ad_data->above_search_ad_status) === 'Show')>Show</option>
                                <option value="Hide" @selected(old('above_search_ad_status', $home_ad_data->above_search_ad_status) === 'Hide')>Hide</option>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="ab-section">
                    <div class="ab-section__head">
                        <h2>Above footer</h2>
                        <p>Shown above the homepage footer.</p>
                    </div>
                    <div class="ab-section__body">
                        <div class="form-group">
                            @if($home_ad_data->above_footer_ad)
                                <img id="footer_ad_preview" class="ab-ad-preview" src="{{ asset('uploads/' . $home_ad_data->above_footer_ad) }}" alt="Above Footer Ad">
                            @else
                                <div id="footer_ad_preview_placeholder" class="ab-ad-placeholder">
                                    <i class="fas fa-image" aria-hidden="true"></i> No image uploaded yet
                                </div>
                                <img id="footer_ad_preview" src="" alt="" class="ab-ad-preview d-none">
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="above_footer_ad">Upload new image</label>
                            <input type="file" name="above_footer_ad" id="above_footer_ad" class="form-control-file" accept="image/*">
                            <p class="ab-note mt-1 mb-0">JPG, PNG, GIF, WEBP — max 5MB.</p>
                        </div>
                        <div class="form-group">
                            <label for="above_footer_ad_url">Click URL</label>
                            <input type="url" name="above_footer_ad_url" id="above_footer_ad_url" class="form-control"
                                   value="{{ old('above_footer_ad_url', $home_ad_data->above_footer_ad_url) }}" placeholder="https://">
                        </div>
                        <div class="form-group mb-0">
                            <label for="above_footer_ad_status">Status</label>
                            <select name="above_footer_ad_status" id="above_footer_ad_status" class="form-control">
                                <option value="Show" @selected(old('above_footer_ad_status', $home_ad_data->above_footer_ad_status) === 'Show')>Show</option>
                                <option value="Hide" @selected(old('above_footer_ad_status', $home_ad_data->above_footer_ad_status) === 'Hide')>Hide</option>
                            </select>
                        </div>
                    </div>
                </section>
            </div>
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">
                    <i class="fas fa-save" aria-hidden="true"></i>
                    Save home ads
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewAdImage(input, previewId, placeholderId) {
    if (!input.files || !input.files[0]) return;
    var preview = document.getElementById(previewId);
    var placeholder = document.getElementById(placeholderId);
    var reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
    };
    reader.readAsDataURL(input.files[0]);
}
document.getElementById('above_search_ad')?.addEventListener('change', function () {
    previewAdImage(this, 'search_ad_preview', 'search_ad_preview_placeholder');
});
document.getElementById('above_footer_ad')?.addEventListener('change', function () {
    previewAdImage(this, 'footer_ad_preview', 'footer_ad_preview_placeholder');
});
</script>
@endpush
