@extends('front.layout.app')

@section('title', $post_detail->meta_title ?? $post_detail->post_title)
@section('meta_description', $post_detail->meta_description ?? Str::limit(strip_tags($post_detail->post_detail), 150))
@section('meta_keywords', $post_detail->meta_keywords)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/blog-post-detail.css') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/insight-cards.css') }}?v=1">
@endpush

@section('main_content')
@php
    $categoryName = $post_detail->rSubCategory->sub_category_name ?? 'Insights';
    $categorySlug = $post_detail->rSubCategory->slug ?? null;
@endphp

<div class="bpd-page">
    <header class="bpd-hero bbg-hero">
        <div class="bpd-wrap">
            <nav class="bpd-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('blog') }}">Blog</a>
                @if($categorySlug)
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('category', $categorySlug) }}">{{ $categoryName }}</a>
                @endif
            </nav>

            @if($categoryName)
                <p class="bpd-hero__eyebrow">{{ $categoryName }}</p>
            @endif

            <h1 class="bpd-hero__title">{{ $post_detail->post_title }}</h1>

            @include('front.brokers.partials.best_guide_hero_author', [
                'editorialTeam' => $editorialTeam,
                'guidePage' => $guidePageMeta,
            ])

            <div class="bpd-hero__meta">
                <span>{{ $postMeta['read_time'] }} min read</span>
                <span class="bpd-hero__dot" aria-hidden="true"></span>
                <span>{{ number_format($post_detail->visitors) }} views</span>
            </div>
        </div>
    </header>

    <div class="bpd-wrap">
        <div class="bpd-layout">
            <article class="bpd-article">
                @if($post_detail->post_photo)
                    <figure class="bpd-featured">
                        <img src="{{ asset('uploads/'.$post_detail->post_photo) }}"
                             alt="{{ $post_detail->post_title }}"
                             loading="eager">
                    </figure>
                @endif

                <div class="bpd-content rich-text">
                    {!! $post_detail->post_detail !!}
                </div>

                @if($tag_data->isNotEmpty())
                    <div class="bpd-tags">
                        <span class="bpd-tags__label">Tags</span>
                        <div class="bpd-tags__list">
                            @foreach($tag_data as $item)
                                <a href="{{ route('tag_posts_show', $item->tag_name) }}" class="bpd-tag">#{{ $item->tag_name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <aside class="bpd-sidebar" aria-label="Article sidebar">
                @include('front.blog.partials.detail_sidebar', [
                    'recommendedBrokers' => $recommendedBrokers,
                    'depositBonuses' => $depositBonuses,
                ])
            </aside>
        </div>

        @if($relatedCards->isNotEmpty())
            <section class="bpd-related" aria-labelledby="bpdRelatedTitle">
                <div class="bpd-related__head">
                    <h2 class="bpd-related__title" id="bpdRelatedTitle">Related articles</h2>
                    @if($categorySlug)
                        <a href="{{ route('category', $categorySlug) }}" class="bpd-related__link">More in {{ $categoryName }}</a>
                    @endif
                </div>
                <div class="bc-insights__grid">
                    @foreach($relatedCards as $index => $post)
                        @include('front.partials.insight_card', [
                            'index' => $index,
                            'url' => $post['url'],
                            'title' => $post['title'],
                            'photo' => $post['photo'],
                            'category' => $post['category'],
                            'date' => $post['date'],
                            'dateIso' => $post['date_iso'],
                            'readMinutes' => $post['read_time'],
                            'authorName' => $post['author'],
                            'authorPhoto' => $post['author_photo'],
                        ])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
@endpush
