@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Edit Prop Firm')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($propFirm->mediaUrl())
                        <img src="{{ $propFirm->mediaUrl() }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($propFirm->name, 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Edit prop firm</p>
                    <h1 class="ab-header__title">{{ $propFirm->name }}</h1>
                    <p class="ab-header__sub">{{ $propFirm->slug }} @if($propFirm->category) · {{ $propFirm->category->name }} @endif</p>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($propFirm->slug)
                    <a href="{{ route('prop_firms.show', $propFirm->slug) }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                @endif
                <a href="{{ route('admin_prop_firms_show') }}" class="ab-btn ab-btn--ghost">All firms</a>
            </div>
        </header>

        @include('admin.prop-firms._nav', ['active' => 'firms'])

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

        <div class="ab-layout">
            <nav class="ab-nav" aria-label="Form sections">
                <a href="#identity" data-ab-nav class="is-active">1. Identity</a>
                <a href="#funding" data-ab-nav>2. Funding</a>
                <a href="#ratings" data-ab-nav>3. Ratings</a>
                <a href="#programs" data-ab-nav>4. Programs</a>
                <a href="#attributes" data-ab-nav>5. Attributes</a>
                <a href="#faqs" data-ab-nav>6. FAQs</a>
                <a href="#seo" data-ab-nav>7. SEO</a>
                <a href="#publish" data-ab-nav>8. Publish</a>
            </nav>

            <form action="{{ route('admin_prop_firms_update', $propFirm->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.prop-firms._form')
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.prop-firms._form_scripts')
@endpush
