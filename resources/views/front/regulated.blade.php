@extends('front.layout.app')
@section('title', 'Regulated Forex Brokers | Safe and Trusted Trading Options')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_sub">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-lg-7 col-md-12">
                    <div class="hero-content">
                    <h2 class="b_c_h">Discover the Best Regulated Forex Brokers for Safe Trading</h2>
                    <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Regulated Forex Brokers
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
                <div class="f_dynamic_content">
                <p>Explore a curated list of top-regulated forex brokers, ensuring a secure and reliable trading experience. Choose a broker that aligns with your trading goals and offers transparent services.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($regulatedBrokers as $broker)
            <div class="col-md-4 mb-4">
                <div class="broker-layer">
                    <div class="broker-background" style="background-image: url('{{ asset($broker->logo ?? 'default-logo.png') }}');"></div>
                    <div class="broker-content">
                        <div class="broker-header">
                            <a href="{{ $broker->url }}"><h4>{{ $broker->name }}</h4></a>
                            <p class="broker-leverage"><strong>Leverage:</strong> {{ $broker->leverage }}</p>
                        </div>
                        <div class="broker-footer">
                            <div class="rating-wrapper">
                                <div class="rating">
                                    <?php
                                    $rating = $broker->rating;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<span class="star filled">&#9733;</span>';
                                        } elseif ($i - 0.5 == $rating) {
                                            echo '<span class="star half">&#9733;</span>';
                                        } else {
                                            echo '<span class="star">&#9734;</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="review-button">Read Review</a>
                        </div>
                    </div>
                </div>
            </div> 
            @endforeach
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
    <script>
        $(document).ready(function () {
        handleAdBannersForAllPages('.breadcrumb_wrapper_by_sub', {
            offset: 200, // Adjust as needed
            fadeDuration: 400,
            slideDuration: 600,
        });
        });
    </script>
</div>
@endsection