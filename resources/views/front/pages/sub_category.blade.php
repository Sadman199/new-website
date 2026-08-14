@extends('front.layout.app')
@section('title', $sub_category_data->sub_category_name . ' | Explore Forex Topics | BrokersCourt')
@section('meta_description', 'Explore expert insights and resources on ' . $sub_category_data->sub_category_name . '. Stay informed with the latest updates, strategies, and guides in forex trading at BrokersCourt.')
@section('canonical', route('category', ['slug' => $sub_category_data->slug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/blog-listing.css') }}?v=2">
@endpush

@section('main_content')
<div class="bll-page">
    <header class="bll-hero">
        <div class="container">
            <div class="bll-hero__row">
                <h1 class="bll-hero__title">
                    Latest Insights on
                    <span class="bll-hero__accent">{{ $sub_category_data->sub_category_name ?? 'Subcategory not found' }}</span>
                </h1>
                <nav aria-label="Breadcrumb">
                    <ol class="bll-breadcrumb">
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home" aria-hidden="true"></i> {{ HOME }}
                            </a>
                        </li>
                        <li class="bll-breadcrumb__sep" aria-hidden="true"><i class="fas fa-chevron-right"></i></li>
                        <li class="bll-breadcrumb__current">{{ $sub_category_data->sub_category_name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>

    @include('front.homepage.inc.top_ad')

    <div class="bll-body">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    @if($post_data->isNotEmpty())
                        <div class="row g-4">
                            @foreach($post_data as $item)
                                @php
                                    $userData = $item->author_id == 0
                                        ? \App\Models\Admin::find($item->admin_id)
                                        : $item->author;
                                    $updatedDate = $item->updated_at->format('M j, Y');
                                @endphp
                                <div class="col-12 col-md-6">
                                    <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="bll-empty">No posts found in this category.</p>
                    @endif

                    @if($post_data->hasPages())
                        <div class="mt-4">{{ $post_data->links() }}</div>
                    @endif
                </div>

                <div class="col-12 col-lg-4">
                    <div class="bll-sidebar">
                        <x-bonus-ad-card
                            title="MT5 by OneRoyal – Live Now"
                            badge="Now Available"
                            :ads="$global_sidebar_top_ad"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
