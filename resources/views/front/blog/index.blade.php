@php
    $posts = $posts ?? collect();
    $activeTab = $activeTab ?? 'all';
    $activeTabName = $activeTabName ?? 'All';
    $isFiltered = $activeTab !== 'all';
    $pageTitle = $pageTitle ?? 'Forex & Broker Blog — Analysis, News & Guides | BrokersCourt';
    $pageDescription = $pageDescription ?? 'Independent BrokersCourt journalism on brokers, markets, regulation, and trading.';
    $canonicalParams = [];
    if ($isFiltered) {
        $canonicalParams['category'] = $activeTab;
    }
    if (method_exists($posts, 'currentPage') && $posts->currentPage() > 1) {
        $canonicalParams['page'] = $posts->currentPage();
    }
@endphp

@extends('front.layout.app')

@section('title')
{!! $pageTitle !!}
@endsection
@section('meta_description')
{!! $pageDescription !!}
@endsection
@section('canonical')
{!! route('blog', $canonicalParams) !!}
@endsection

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-index.css') }}?v=14">
@endpush

@section('main_content')
<div class="bli-page" id="bli-app" data-blog-url="{{ route('blog') }}">
    <header class="bli-hero">
        <div class="container">
            <nav class="bli-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Blog</span>
            </nav>

            <p class="bli-hero__eyebrow">
                <span class="bli-hero__eyebrow-dot" aria-hidden="true"></span>
                Editorial
            </p>
            <h1 class="bli-hero__title">Blog</h1>
            <p class="bli-hero__lead">Independent coverage of brokers, markets, and trading.</p>
        </div>
    </header>

    <div class="container bli-body">
        @if(count($tabs))
            <nav class="bli-tabs" aria-label="Blog categories" data-blog-tabs>
                <button type="button" class="bli-tabs__btn" data-blog-tabs-prev aria-label="Previous categories">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="bli-tabs__viewport">
                    <div class="bli-tabs__track" data-blog-tabs-track>
                        @foreach($tabs as $tab)
                            <a href="{{ $tab['url'] }}"
                               class="bli-tabs__tab {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
                               data-blog-tab="{{ $tab['slug'] }}"
                               @if($activeTab === $tab['slug']) aria-current="page" @endif>
                                {{ $tab['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="bli-tabs__btn" data-blog-tabs-next aria-label="Next categories">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </nav>
        @endif

        <div id="bli-feed" class="bli-feed">
            @include('front.blog.partials.feed')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/blog-index.js') }}?v=3" defer></script>
@endpush
