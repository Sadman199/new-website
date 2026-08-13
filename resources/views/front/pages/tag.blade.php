@extends('front.layout.app')
@section('title', ucfirst($tag_name) . ' | Forex Content by Tag | BrokersCourt')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-listing.css') }}?v=1">
@endpush

@section('main_content')
<div class="bll-page">
    <header class="bll-hero">
        <div class="container">
            <div class="bll-hero__row">
                <h1 class="bll-hero__title">
                    Discover All Posts Tagged
                    <span class="bll-hero__accent">{{ $tag_name }}</span>
                </h1>
                <nav aria-label="Breadcrumb">
                    <ol class="bll-breadcrumb">
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home" aria-hidden="true"></i> {{ HOME }}
                            </a>
                        </li>
                        <li class="bll-breadcrumb__sep" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                        <li class="bll-breadcrumb__current">{{ $tag_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>

    <div class="bll-body">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    @if($all_posts->isNotEmpty())
                        <div class="row g-4">
                            @foreach($all_posts as $item)
                                @continue(!in_array($item->id, $all_post_ids))
                                <div class="col-12 col-md-6">
                                    <article class="bll-tag-card">
                                        <div class="bll-tag-card__media">
                                            <img src="{{ asset('uploads/' . $item->post_photo) }}" alt="{{ $item->post_title }}" loading="lazy" decoding="async">
                                            <span class="bll-tag-card__badge">
                                                {{ $item->rSubCategory->sub_category_name ?? 'No sub-category available' }}
                                            </span>
                                        </div>
                                        <div class="bll-tag-card__body">
                                            <h3 class="bll-tag-card__title">
                                                <a href="{{ $item->rSubCategory ? route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) : '#' }}">
                                                    {{ Str::limit(strip_tags($item->post_title), 30) }}
                                                </a>
                                            </h3>
                                            <p class="bll-tag-card__excerpt">
                                                {{ Str::limit(strip_tags($item->post_detail ?? ''), 70) }}
                                            </p>
                                            <div class="bll-tag-card__footer">
                                                <div class="bll-tag-card__meta">
                                                    @php
                                                        $author = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : $item->author;
                                                    @endphp
                                                    <span class="bll-tag-card__author">{{ $author->name ?? 'Editor' }}</span>
                                                    <span aria-hidden="true">•</span>
                                                    <time datetime="{{ $item->updated_at->toDateString() }}">{{ $item->updated_at->format('M j, Y') }}</time>
                                                </div>
                                                <span class="bll-tag-card__index {{ $loop->odd ? 'bll-tag-card__index--odd' : 'bll-tag-card__index--even' }}">
                                                    #{{ $loop->iteration }}
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                        @if($all_posts->hasPages())
                            <div class="mt-4">{{ $all_posts->links() }}</div>
                        @endif
                    @else
                        <p class="bll-empty">{{ NO_POST_FOUND }}</p>
                    @endif
                </div>

                <div class="col-12 col-lg-4">
                    @include('front.layout.sidebar')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
