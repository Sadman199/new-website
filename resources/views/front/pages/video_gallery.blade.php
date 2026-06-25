@extends('front.layout.app')

@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_video">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                   <div class="hero-content">
                    <h2 class="b_c_h">Watch the Latest Insights on BrokersCourt</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ VIDEO_GALLERY }}</li>
                                
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
        <div class="video-gallery">
            <div class="row">
                @foreach($videos as $item)
                <div class="col-lg-3 col-md-4">
                    <div class="video-thumb">
                        <img src="http://img.youtube.com/vi/{{ $item->video_id }}/0.jpg" alt="">
                        <div class="bg"></div>
                        <div class="icon">
                            <a href="http://www.youtube.com/watch?v={{ $item->video_id }}" class="video-button"><i class="fas fa-play"></i></a>
                        </div>
                    </div>
                    <div class="video-caption">
                        <a href="javascript:void;">{{ $item->caption }}</a>
                    </div>
                    <div class="video-date">
                        @php
                        $ts = strtotime($item->created_at);
                        $created_date = date('d F, Y',$ts);
                        @endphp
                        <i class="fas fa-calendar-alt"></i> {{ $created_date }}
                    </div>
                </div>
                @endforeach
                <div class="col-md-12">
                    {{ $videos->links() }}
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
        handleAdBannersForAllPages('.breadcrumb_wrapper_by_video', {
            offset: 200, // Adjust as needed
            fadeDuration: 400,
            slideDuration: 600,
        });
        });
    </script>
@endsection