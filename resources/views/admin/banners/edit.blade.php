@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'Edit Banner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-banners.css') }}?v=8">
@endpush

@section('main_content')
<div class="ab-page ab-page--hub ab-page--banners">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Banners</p>
                <h1 class="ab-header__title">{{ $banner->title }}</h1>
                <p class="ab-header__sub">Update creative, targeting, and schedule.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_banners_index') }}" class="ab-btn ab-btn--ghost">All banners</a>
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

        <form action="{{ route('admin_banners_update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="ab-banner-form" novalidate>
            @csrf
            @include('admin.banners._form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-banners.js') }}?v=7" defer></script>
@endpush
