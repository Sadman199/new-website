@extends('front.layout.app')
@section('title', $page_title)
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="b_breadcrumb_wrapper">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-lg-7 col-md-12">
                    <div class="hero-content">
                        <h2 class="b_c_h">{{ $promo_type }}</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $promo_type }}</li>
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
        <div class="col-lg-9 col-md-12">
            <div class="f_dynamic_content">
                @if ($promo_type === 'Forex Deposit Bonus')
                        <div>
                            <p>Boost your trading experience with a <strong>Forex Deposit Bonus</strong>. Get additional funds when you make a deposit with participating brokers. Take advantage of this opportunity to maximize your trading capital and increase potential profits.</p>
                        </div>
                    @elseif ($promo_type === 'Forex No Deposit Bonus')
                        <div>
                            <p>Start trading without any investment using a <strong>No Deposit Bonus</strong>. Perfect for beginners, this bonus allows you to explore forex trading without risking your own money. Claim your bonus today and start trading!</p>
                        </div>
                    @elseif ($promo_type === 'Forex Live Contest')
                        <div>
                            <p>Participate in <strong>Forex Live Contests</strong> and compete against other traders for exciting prizes. Show off your trading skills in real market conditions and claim your rewards. Join now and prove your expertise!</p>
                        </div>
                    @elseif ($promo_type === 'Forex Demo Contest')
                        <div>
                            <p>Join <strong>Forex Demo Contests</strong> to test your trading strategies without any risk. Compete in simulated trading environments and win attractive prizes. Ideal for refining your skills and gaining experience.</p>
                        </div>
                    @elseif ($promo_type === 'Forex Cashback Rebate')
                        <div>
                            <p>Save on trading costs with <strong>Forex Cashback Rebates</strong>. Earn cashback on every trade you make and enjoy reduced trading expenses. Discover brokers offering generous rebate programs today!</p>
                        </div>
                    @elseif ($promo_type === 'Crypto Bonus Promotion')
                        <div>
                            <p>Embrace the world of cryptocurrencies with exclusive <strong>Crypto Bonus Promotions</strong>. Receive special bonuses when trading cryptocurrencies with participating brokers. Don't miss out on these exciting offers!</p>
                        </div>
                    @endif
            </div>
        </div>


        <div class="row">
            <div class="col-lg-9 col-md-12">
                @if ($forexBonuses->isEmpty())
                    <p class="text-center text-muted n_a_moment_text">No bonuses available at the moment.</p>
                @else
                    <div class="row">
                        @foreach ($forexBonuses as $bonus)
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="b_blog_card horizontal-card">
                                    <div class="card-content">
                                        <div class="blog-image-wrapper">
                                            @if ($bonus->feature_image)
                                            <img src="{{ asset($bonus->feature_image) }}" class="b_b_c_img" alt="Forex Bonus - {{ $bonus->title }}">
                                            @else
                                            <p>No image available.</p>
                                            @endif


                                            <div class="b_img_wrapper">
                                                @if (\Carbon\Carbon::parse($bonus->expiry_date)->isFuture())
                                                    <div class="animated-bell">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="bell-icon">
                                                            <path d="M12 2a6 6 0 016 6v4.5l1.82 3.64a1 1 0 01-.91 1.36H5.09a1 1 0 01-.91-1.36L6 12.5V8a6 6 0 016-6zm0 20a3 3 0 003-3H9a3 3 0 003 3z" />
                                                        </svg>
                                                    </div>
                                                    <p class="b_e_date">Upcoming: {{ \Carbon\Carbon::parse($bonus->expiry_date)->diffForHumans() }}</p>
                                                @else
                                                    <p class="b_e_date">Ended on: {{ \Carbon\Carbon::parse($bonus->expiry_date)->diffForHumans() }}</p>
                                                @endif
                                            </div>


                                        </div>
                                        <div class="card-details">
                                            <h3 class="blog-heading">{{ Str::limit($bonus->title, 100) }}</h3>
                                            <!-- <span class="blog-date">{{ \Carbon\Carbon::parse($bonus->publish_date)->format('F d, Y') }}</span> -->
                                            <p class="blog-description">{{ Str::limit(strip_tags($bonus->prize), 200) }}</p>
                                            
                                            <div class="blog-actions">
                                                <div class="b_btn_wrapper">
                                                    <div class="b_t">
                                                        <p>{{ $bonus->bonus_category }}</p>
                                                    </div>
                                                    
                                                    <a href="{!! $bonus->affiliate_link !!}" target="_blank" rel="noopener noreferrer" class="c_now_btn">
                                                        Claim Now
                                                    </a>

                                                    <a href="{{ route('deposit-bonuses.detail', $bonus->slug) }}" class="r_more_btn">
                                                        Read More
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="sidebar">
                    <div class="s_bar_wrapper">
                        <div class="side_bar_add">
                            <span class="l_b_h"> Octa Deposit Bonus</span>
                            @foreach($global_sidebar_top_ad as $row)
                                <div class="ad-sidebar">
                                    @if($row->sidebar_ad_url == '')
                                        <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="">
                                    @else
                                        <a href="{{ $row->sidebar_ad_url }}"><img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt=""></a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="featured-brokers">
                            <span class="l_b_h">Featured Brokers</span>
                            <ul>
                                @foreach ($featured_brokers as $broker)
                                <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}"
                                    class="broker-side-card-link">
                                    <li class="broker_side_card">
                                        <div class="b_c_c_content">
                                            @if ($broker->logo)
                                            <img src="{{ asset($broker->logo) }}"
                                                alt="{{ $broker->name }} Broker Logo" class="">
                                            @else
                                            <p>No logo available.</p>
                                            @endif
                                            <span>{{ $broker->name }} Review 2024</span>
                                        </div>
                                        <i class="fas fa-chevron-right"></i>
                                    </li>
                                </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $forexBonuses->links() }}
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
    handleAdBannersForAllPages('.b_breadcrumb_wrapper', {
        offset: 200, // Adjust as needed
        fadeDuration: 400,
        slideDuration: 600,
    });
    });
</script>


@endsection


