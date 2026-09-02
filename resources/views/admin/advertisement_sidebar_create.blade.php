@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Add Sidebar Ad')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        @include('admin.ads._nav', ['active' => 'sidebar'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Sidebar Ads</p>
                <h1 class="ab-header__title">Add Sidebar Ad</h1>
                <p class="ab-header__sub">Upload an image and choose top or bottom placement.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_sidebar_ad_show') }}" class="ab-btn ab-btn--ghost">All sidebar ads</a>
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

        <form action="{{ route('admin_sidebar_ad_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Ad details</h2>
                </div>
                <div class="ab-section__body">
                    <div class="form-group">
                        <label for="sidebar_ad">Photo <span class="text-danger">*</span></label>
                        <input type="file" name="sidebar_ad" id="sidebar_ad" class="form-control-file" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="sidebar_ad_url">Click URL <span class="text-danger">*</span></label>
                        <input type="url" name="sidebar_ad_url" id="sidebar_ad_url" class="form-control" value="{{ old('sidebar_ad_url') }}" placeholder="https://" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="sidebar_ad_location">Location</label>
                        <select name="sidebar_ad_location" id="sidebar_ad_location" class="form-control" required>
                            <option value="Top" @selected(old('sidebar_ad_location') === 'Top')>Top</option>
                            <option value="Bottom" @selected(old('sidebar_ad_location') === 'Bottom')>Bottom</option>
                        </select>
                    </div>
                </div>
            </section>
            <div class="ab-save">
                <button type="submit" class="ab-btn ab-btn--primary">Create sidebar ad</button>
                <a href="{{ route('admin_sidebar_ad_show') }}" class="ab-btn ab-btn--ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
