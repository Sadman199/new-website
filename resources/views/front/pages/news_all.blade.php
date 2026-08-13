@extends('front.layout.app')
@section('title', $section_title . ' | Expert Forex Coverage | BrokersCourt')
@section('meta_description', 'Dive into ' . strtolower($section_title) . ' and stay updated with the most insightful articles and forex trends on BrokersCourt.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-listing.css') }}?v=1">
@endpush

@section('main_content')
<div class="bll-page">
    <header class="bll-hero">
        <div class="container">
            <div class="bll-hero__row">
                <h1 class="bll-hero__title">
                    Explore <span class="bll-hero__accent">{{ $section_title }}</span>
                </h1>
                <nav aria-label="Breadcrumb">
                    <ol class="bll-breadcrumb">
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home" aria-hidden="true"></i> {{ __('Home') }}
                            </a>
                        </li>
                        <li class="bll-breadcrumb__sep" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                        <li class="bll-breadcrumb__current">{{ $section_title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>

    @include('front.homepage.inc.top_ad')

    <div id="post-list" class="bll-body">
        <div class="container">
            @if($posts->isNotEmpty())
                <div class="row g-4 mb-4">
                    @foreach($posts as $item)
                        @php
                            $userData = $item->author_id == 0
                                ? \App\Models\Admin::find($item->admin_id)
                                : $item->author;
                            $updatedDate = $item->updated_at->format('M j, Y');
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4">
                            <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
                        </div>
                    @endforeach
                </div>
            @else
                <p class="bll-empty">No articles found.</p>
            @endif

            @if ($posts->hasPages())
                <nav class="bll-pagination" aria-label="Pagination">
                    @if ($posts->onFirstPage())
                        <span class="bll-page-link--disabled">&laquo;</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}" class="bll-page-link">&laquo;</a>
                    @endif

                    @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                        @if ($page == $posts->currentPage())
                            <span class="bll-page-link--current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="bll-page-link bll-page-link--ghost">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}" class="bll-page-link">&raquo;</a>
                    @else
                        <span class="bll-page-link--disabled">&raquo;</span>
                    @endif
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
