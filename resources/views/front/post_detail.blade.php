@extends('front.layout.app')
<title>{{ $post_detail->post_title }} | In-Depth Forex Broker Reviews | BrokersCourt Blog</title>
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_post_detail">
        <div class="container">
             <!-- Featured Image Section Below -->
             <div class="featured-photo-wrapper">
                <div class="featured-photo">
                    <img src="{{ asset('uploads/'.$post_detail->post_photo) }}" alt="Featured Image" class="img-fluid rounded-lg shadow-lg">
                </div>
            </div>
            <div class="b_hero_content">
                <div class="post-content-wrapper">
                    <h2 class="b_c_h text-center">{{ $post_detail->post_title }}</h2>
                    <nav>
                        <ol class="breadcrumb b_center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('category', $post_detail->rSubCategory->slug) }}">
                                    {{ $post_detail->rSubCategory->sub_category_name }}
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="sub">
                    <div class="item">
                        <b><i class="fas fa-user"></i></b>
                        <a class="author_name" href="#">{{ $user_data->name }}</a>
                    </div>
                    <div class="item">
                        <b><i class="fas fa-edit"></i></b>
                        <a class="c_name" href="{{ route('category', $post_detail->rSubCategory->slug) }}">{{ $post_detail->rSubCategory->sub_category_name }}</a>
                    </div>
                    <div class="item">
                        <b><i class="fas fa-clock"></i></b>
                        @php
                        $ts = strtotime($post_detail->updated_at);
                        $updated_date = date('d F, Y',$ts);
                        @endphp
                        {{ $updated_date }}
                    </div>
                    <div class="item">
                        <b><i class="fas fa-eye"></i></b>
                        {{ $post_detail->visitors }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="page-content s_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <!-- <div class="featured-photo">
                    <img src="{{ asset('uploads/'.$post_detail->post_photo) }}" alt="">
                </div> -->
                <div class="main-text">
                    {!! $post_detail->post_detail !!}
                </div>
                <div class="p_d_tags">
                    <h2 class="section_title">{{ TAGS }}</h2>
                    <div class="tag_container">
                        <div class="tag_wrapper">
                            @foreach($tag_data as $item)
                                <div class="tag-item">
                                    <a href="{{ route('tag_posts_show', $item->tag_name) }}">
                                        <span class="s_tag">{{ $item->tag_name }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                        
                <div class="related-news">
                    <div class="related-news-heading">
                        <h2>{{ RELATED_NEWS }}</h2>
                    </div>
                    <div class="related-post-carousel owl-carousel owl-theme">
                    @foreach($related_post_array as $item)
                            @continue($item->id == $post_detail->id)

                            @php
                                // Get the user data
                                $user_data = $item->author_id == 0
                                    ? \App\Models\Admin::find($item->admin_id)
                                    : \App\Models\Author::find($item->author_id);

                                // Format the updated date
                                $updated_date = $item->updated_at->format('d F, Y');
                            @endphp

                            <div class="item">
                                <div class="site_card">
                                    <div class="c_card_image">
                                        <div class="tag_card">{{ $item->rSubCategory->sub_category_name }}</div>
                                        <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="{{ $item->post_title }}">
                                    </div>
                                    <div class="c_card_content">
                                        <div class="c_card_dec">
                                            <h3>
                                                @if($item->rSubCategory)
                                                    <a class="c_c_title" href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                        {{ Str::limit($item->post_title, 30) }}
                                                    </a>
                                                @else
                                                    <span>{{ Str::limit($item->post_title, 30) }}</span>
                                                @endif
                                            </h3>
                                            <div class="date-user">
                                                <div class="user">
                                                    <a href="javascript:void(0);">{{ $user_data->name }}</a>
                                                </div>
                                                <div class="date">
                                                    <a href="javascript:void(0);">{{ $updated_date }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
            <div class="col-lg-3 col-md-6 sidebar_col">
               <div class="p_d_sidebar">
                 @include('front.layout.sidebar')
               </div>
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
    handleAdBannersForAllPages('.breadcrumb_wrapper_by_post_detail', {
        offset: 250,
    });
    });
</script>
@endsection