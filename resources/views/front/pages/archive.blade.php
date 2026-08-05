@extends('front.layout.app')
@section('title', 'Blog Archive | Discover Forex Insights and Broker Tips')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_comparison">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                    <div class="hero-content">
                        <h2 class="b_c_h">{{ ALL_POSTS_OF }} {{ $updated_date }}</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item">{{ ARCHIVE }}</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $updated_date }}</li>
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
            <!-- Main Content -->
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="row">
                    <div class="">
                        @if(count($post_data_archive))
                        @foreach($post_data_archive as $item)
                        <!-- Card Columns -->
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="site_card">
                                <div class="c_card_image">
                                    <div class="tag_card">{{ $item->rSubCategory->sub_category_name }}</div>
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
                        <!-- Pagination -->
                        <div class="col-12">
                            {{ $post_data_archive->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4 col-sm-12 sidebar-col">
                @include('front.layout.sidebar')
            </div>
        </div>

    </div>
</div>
@endsection