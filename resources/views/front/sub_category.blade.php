@extends('front.layout.app')
@section('title', 'Explore Categories | Browse Forex and Broker Topics')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_sub">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                    <div class="hero-content">
                    <h2 class="b_c_h">Latest Insights on {{ $sub_category_data->sub_category_name ?? 'Subcategory not found' }}</h2>
                    <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $sub_category_data->sub_category_name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-content s_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="category-page">
                    <div class="row">
                        @if($post_data->isNotEmpty())
                            @foreach($post_data as $item)
                                <div class="col-lg-4 col-md-6">
                                    <div class="site_card">
                                        <div class="c_card_image">
                                            <div class="tag_card">{{ $sub_category_data->sub_category_name }}</div>
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="{{ $item->post_title }}">
                                        </div>
                                        <div class="c_card_content">
                                            <div class="l_heading">
                                                <h1>{{ $loop->iteration }}</h1>
                                            </div>
                                            <div class="c_card_dec">
                                                <h3>
                                                    <a class="c_c_title" href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                        {{ Str::limit(strip_tags($item->post_title), 50) }}
                                                    </a>
                                                </h3>
                                                <p>
                                                    @php
                                                        $author = $item->author_id == 0 
                                                            ? \App\Models\Admin::find($item->admin_id) 
                                                            : $item->author;
                                                    @endphp
                                                    {{ $author->name }} &bull; {{ $item->updated_at->format('d F, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <span class="text-danger">{{ NO_POST_FOUND }}</span>
                        @endif
                        <div class="col-md-12">
                            {{ $post_data->links() }}
                        </div>
                       
                    </div>
                </div>
                <div class="tag_container">
                 <h2 class="tag-heading">Explore Tags</h2>
                    <div class="tag_wrapper">
                        @foreach($filtered_tags as $item)
                            <div class="tag-item">
                                <a href="{{ route('tag_posts_show', $item->tag_name) }}">
                                    <span class="s_tag">{{ $item->tag_name }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 sidebar-col">
                @include('front.layout.sidebar')
            </div>
        </div>
    </div>
    <section class="site_add">
        @if($home_ad_data->above_footer_ad_status == 'Show' || $home_ad_data->above_search_ad_status == 'Show')
            <div class="container">
                <div class="row">
                    @if($home_ad_data->above_footer_ad_status == 'Show')
                    <div class="col-md-8">
                        @if($home_ad_data->above_footer_ad_url == '')
                            <img class="add_image_left" src="{{ asset('uploads/'.$home_ad_data->above_footer_ad) }}" alt="">
                        @else
                            <a href="{{ $home_ad_data->above_footer_ad_url }}">
                                <img class="add_image_left" src="{{ asset('uploads/'.$home_ad_data->above_footer_ad) }}" alt="">
                            </a>
                        @endif
                    </div>
                    @endif

                    @if($home_ad_data->above_search_ad_status == 'Show')
                    <div class="col-md-8">
                        <div class="add_image-wrapper">
                            @if($home_ad_data->above_search_ad_url == '')
                                <img class="add_image_right" src="{{ asset('uploads/'.$home_ad_data->above_search_ad) }}" alt="">
                            @else
                                <a href="{{ $home_ad_data->above_search_ad_url }}">
                                    <img class="add_image_right" src="{{ asset('uploads/'.$home_ad_data->above_search_ad) }}" alt="">
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif
    </section>
</div>
<script>
    $(document).ready(function () {
    handleAdBannersForAllPages('.breadcrumb_wrapper_by_sub', {
        offset: 250,
    });
    });
</script>
@endsection