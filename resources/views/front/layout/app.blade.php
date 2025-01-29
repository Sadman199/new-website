@if(!session()->get('session_short_name'))
    @php
    $current_short_name = $global_short_name;
    @endphp
@else
    @php
    $current_short_name = session()->get('session_short_name');
    @endphp
@endif
<!DOCTYPE html>
<html lang="en">

            <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.">
            <meta name="keywords" content="Forex Brokers, Forex Broker Comparison, Broker Reviews, Forex Deals, Top Forex Brokers">
            <meta name="author" content="BrokersCourt">
            <meta name="robots" content="index, follow">
            <title>@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')</title>

            <!-- Google Tag Manager -->
            <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-W3MTNWPW"></script>
            <!-- End Google Tag Manager -->

            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="website">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:title" content="@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')">
            <meta property="og:description" content="BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.">
            <meta property="og:image" content="{{ asset('uploads/'.$global_setting_data->favicon) }}">

            <!-- Twitter -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:creator" content="@BrokersCourt">
            <meta name="twitter:title" content="@yield('title', 'BrokersCourt - Compare and Find Top Forex Brokers, Reviews, and Deals')">
            <meta name="twitter:description" content="BrokersCourt helps you compare and find top forex brokers, read expert reviews, and grab exclusive deals on trading accounts.">
            <meta name="twitter:image" content="{{ asset('uploads/'.$global_setting_data->favicon) }}">

            <link rel="icon" type="image/png" href="{{ asset('uploads/'.$global_setting_data->favicon) }}">
            @include('front.layout.styles')
            @include('front.layout.scripts')
            @include('front.layout.responsive')

            <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-6212352ed76fda0a"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      

        <style>
            .website-menu,
            .website-menu .bg-primary,
            .acme-news-ticker-label,
            .search-section button[type="submit"],
            .home-content .left .news-total-item .see-all a,
            .video-content,
            .footer ul.social li a,
            .footer input[type="submit"],
            .widget .poll button,
            .related-news .owl-nav .owl-prev,
            .related-news .owl-nav .owl-next,
            .bg-website,
            .page-item.active .page-link {
                background: #{{ $global_setting_data->theme_color_1 }}!important;
            }

            .acme-news-ticker,
            .page-item.active .page-link {
                border-color: #{{ $global_setting_data->theme_color_1 }}!important;
            }

            ul.my-news-ticker li a,
            .home-content .left .news-total-item .left-side h3 a:hover,
            .home-content .left .news-total-item .right-side-item .right h2 a:hover,
            .home-content .left .news-total-item .left-side .date-user .user a:hover, 
            .home-content .left .news-total-item .left-side .date-user .date a:hover,
            .home-content .left .news-total-item .right-side-item .right .date-user .user a:hover, 
            .home-content .left .news-total-item .right-side-item .right .date-user .date a:hover,
            .widget .news-item .right h2 a:hover,
            .widget .news-item .right .date-user .user a:hover, 
            .widget .news-item .right .date-user .date a:hover,
            .video-carousel .owl-nav .owl-prev,
            .video-carousel .owl-nav .owl-next,
            .category-page-post-item h3 a:hover,
            .category-page-post-item .date-user .user a:hover, 
            .category-page-post-item .date-user .date a:hover,
            .related-news .item h3 a:hover,
            .related-news .item .date-user .user a:hover, 
            .related-news .item .date-user .date a:hover,
            .accordion-button:not(.collapsed),
            .login-form a,
            ul.pagination .page-link {
                color: #{{ $global_setting_data->theme_color_1 }}!important;
            }


            .home-main .inner .text-inner .category span, 
            .home-main .inner .text-inner .category span a,
            .home-content .left .news-total-item .left-side .category span, 
            .home-content .left .news-total-item .left-side .category span a,
            .home-content .left .news-total-item .right-side-item .right .category span, 
            .home-content .left .news-total-item .right-side-item .right .category span a,
            .category-page-post-item .category span, 
            .category-page-post-item .category span a,
            .tag-section-content span {
                background: #{{ $global_setting_data->theme_color_2 }}!important;
            }

            .nav-pills .nav-link.active,
            .page-item.active .page-link {
                color: #fff!important;
            }
        </style>
    </head>
    <body>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W3MTNWPW"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
         </noscript>
        <div class="heading-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 d-flex align-items-center">
                        <!-- <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('uploads/'.$global_setting_data->logo) }}" alt="">
                            </a>
                        </div> -->
                    </div>
                    <div class="col-md-8">
                    </div>
                </div>
            </div>
        </div>

        @include('front.layout.nav')

        @yield('main_content')
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">Forex Demo Contest</h2>
                            @if($demoContest->isNotEmpty())
                                @foreach($demoContest as $contest)
                                <ul class="contest-card">
                                  <li class="contest-title"><a href="">{{ Str::limit($contest->title, 50) }}</a></li> 
                               </ul>
                                @endforeach
                            @else
                                <p>No demo contests available at the moment.</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">Forex Live Contest</h2>
                            @if($liveContest->isNotEmpty())
                                @foreach($liveContest as $contest)
                                <ul class="contest-card">
                                    <li class="contest-title"><a href="">{{ Str::limit($contest->title, 50) }}</a></li> 
                                </ul>
                                @endforeach
                                
                            @else
                                <p>No live contests available at the moment.</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">Forex Cashback Rebate</h2>
                            @if($forexCashbackRebate->isNotEmpty())
                                   @foreach($forexCashbackRebate as $rebate)
                                <ul class="contest-card">
                                    <li class="contest-title"><a href="">{{ Str::limit($rebate->title, 50) }}</a></li> 
                                </ul>
                                    @endforeach
                            @else
                                <p>No cashback rebates available at the moment.</p>
                            @endif
                           

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">Crypto Bonus Promotion</h2>
                            @if($cryptoBonusPromotion->isNotEmpty())
                                @foreach($cryptoBonusPromotion as $promotion)
                                <ul class="contest-card">
                                <li class="contest-title"><a href="">{{ Str::limit($promotion->title, 50) }}</a></li> 
                                </ul>
                                @endforeach
                            @else
                            <p>No crypto bonus promotions available at the moment.</p>
                        @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-4">
                        <div class="f_item">
                        <h2 class="heading">Meet Brokers Court: Your Forex Guide</h2>
                        <p class="f_dec">Brokers Court is your trusted guide in the world of forex trading. We specialize in promoting top-tier forex brokers, providing in-depth reviews, exclusive promotions, and unbiased insights to help traders make informed decisions. Whether you're a seasoned professional or a beginner, Brokers Court empowers you with the resources and knowledge to succeed in the competitive forex market.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">{{ FOOTER_COL_2_HEADING }}</h2>
                            <ul class="useful-links">
                                <li><a href="{{ route('home') }}">{{ HOME }}</a></li>

                                @if(isset($page_data) && $page_data->terms_status == 'Show')
                                    <li><a href="{{ route('terms') }}">{{ $page_data->terms_title }}</a></li>
                                @elseif(!isset($page_data)) 
                                    <!-- Optional: Add a default link for terms if $page_data is not set -->
                                    <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                                @endif

                                @if(isset($page_data) && $page_data->privacy_status == 'Show')
                                    <li><a href="{{ route('privacy') }}">{{ $page_data->privacy_title }}</a></li>
                                @elseif(!isset($page_data))
                                    <!-- Optional: Add a default link for privacy if $page_data is not set -->
                                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                                @endif

                                @if(isset($page_data) && $page_data->disclaimer_status == 'Show')
                                    <li><a href="{{ route('disclaimer') }}">{{ $page_data->disclaimer_title }}</a></li>
                                @elseif(!isset($page_data))
                                    <!-- Optional: Add a default link for disclaimer if $page_data is not set -->
                                    <li><a href="{{ route('disclaimer') }}">Disclaimer</a></li>
                                @endif

                                @if(isset($page_data) && $page_data->contact_status == 'Show')
                                    <li><a href="{{ route('contact') }}">{{ $page_data->contact_title }}</a></li>
                                @elseif(!isset($page_data))
                                    <!-- Optional: Add a default link for contact if $page_data is not set -->
                                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                @endif
                            </ul>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="f_item">
                            <h2 class="heading">{{ FOOTER_COL_3_HEADING }}</h2>
                            <div class="list-item">
                                <div class="f_location">
                                    <i class="fas fa-map-marker-alt"></i> New York, USA
                                </div>
                            </div>

                            <div class="list-item">
                                <div class="left">
                                    <i class="far fa-envelope"></i>
                                </div>
                                <div class="right">
                                   info@brokerscourt.com
                                </div>
                            </div>
                            <div class="list-item">
                                <div class="left">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="right">
                                    {{ FOOTER_PHONE }}
                                </div>
                            </div>
                            <ul class="social">
                                @foreach($global_social_item_data as $item)
                                <li><a href="{{ $item->url }}" target="_blank"><i class="{{ $item->icon }}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="copyright">
            Copyright © 2024 brokerscourt.com
        </div>
     
        <div class="scroll-top">
            <i class="fas fa-angle-up"></i>
        </div>
          
        @include('front.layout.scripts_footer')

             <!-- SweetAlert -->
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        <!-- iziToast -->
        @if($errors->any())
            @foreach($errors->all() as $error)
                <script>
                    iziToast.error({
                        title: '',
                        position: 'topRight',
                        message: '{{ $error }}',
                    });
                </script>
            @endforeach
        @endif
   </body>
</html>