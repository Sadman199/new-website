@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Edit broker')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($broker->mediaUrl())
                        <img src="{{ $broker->mediaUrl() }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Edit broker</p>
                    <h1 class="ab-header__title">{{ $broker->name }}</h1>
                    <p class="ab-header__sub">{{ $broker->slug }} @if($broker->country) · {{ $broker->country }} @endif</p>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($broker->slug)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                @endif
                <a href="{{ route('admin_broker_view', $broker->id) }}" class="ab-btn ab-btn--ghost">Overview</a>
                <a href="{{ route('admin_broker_show') }}" class="ab-btn ab-btn--ghost">All brokers</a>
            </div>
        </header>

        @include('admin.brokers._tabs', ['broker' => $broker, 'activeTab' => 'broker'])

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
                <a href="#regulation" data-ab-nav>2. Regulation</a>
                <a href="#trading" data-ab-nav>3. Trading</a>
                <a href="#seo" data-ab-nav>4. SEO</a>
                <a href="#publish" data-ab-nav>5. Publish</a>
            </nav>

            <form action="{{ route('admin_broker_update', $broker->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.brokers._form', ['broker' => $broker, 'formOptions' => $formOptions])
            </form>
        </div>
    </div>
</div>
@endsection
