@extends('front.layout.app')
<title>{{ ucfirst($tag_name) }} | Forex Content by Tag | BrokersCourt</title>
<div id="loader-overlay">
    <div class="loader"></div>
</div>
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_tag">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="hero-content">
                        <h2 class="b_c_h">Discover All Posts Tagged {{ $tag_name }}</h2>

                        <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> {{ $tag_name }}
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
            <div class="col-lg-9 col-md-6">
                <div class="category-page">
                    <div class="row">
                    @if($all_posts->isNotEmpty())
                            @foreach($all_posts as $item)
                                @continue(!in_array($item->id, $all_post_ids))
                                <div class="col-lg-4 col-md-12">
                                    <div class="category_card">
                                        <div class="c_card_image">
                                            <div class="tag_card">
                                                {{ $item->rSubCategory->sub_category_name ?? 'No sub-category available' }}
                                            </div>
                                            <img src="{{ asset('uploads/' . $item->post_photo) }}" alt="{{ $item->post_title }}">
                                        </div>
                                        <div class="c_card_content">
                                            <div class="l_heading">
                                                <h1>{{ $loop->iteration }}</h1>
                                            </div>
                                            <div class="c_card_dec">
                                                <h3>
                                                    <a class="c_c_title" 
                                                    href="{{ $item->rSubCategory ? route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) : '#' }}">
                                                        {{ Str::limit(strip_tags($item->post_title), 40) }}
                                                    </a>
                                                </h3>
                                                <p>
                                                    @php
                                                        $author = $item->author_id ? $item->author : \App\Models\Admin::find($item->admin_id);
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
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 sidebar-col">
                @include('front.layout.sidebar')
            </div>
        </div>
    </div>
</div>
@endsection