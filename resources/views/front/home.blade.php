@extends('front.layout.app')
@section('title', 'BrokersCourt | Find, Compare, and Connect with Top Brokers')
@section('main_content')
@php
$current_short_name = session()->get('session_short_name', $global_short_name);
$current_language_id = \App\Models\Language::where('short_name', $current_short_name)->first()->id;
@endphp
@if($setting_data->news_ticker_status == "Show")
<!-- Content here -->
@endif
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<section class="hero_main_content">
    <div class="hero">
        <div class="container">
            <!-- Heading and sponsored container in flex row -->
            <div class="heading_sponsored">
                <h1 class="hero_title">Discover the Top-Rated Brokers <br> <span class="h_span">You Can Trust!</span></h1>
                <div class="sponsored">
                    Sponsored <i class="fas fa-info-circle"></i>
                </div>
            </div>

            <!-- Paragraph placed directly under heading -->
            <p class="section-dec">Explore the world’s most trusted brokers, delivering exceptional services and rock-solid trading platforms for traders like you!</p>
            @if ($top_brokers->count() > 0)
            <div class="row">
                @foreach($top_brokers as $broker)
                <div class="col-12 col-sm-6 col-md-4 col-lg-6 col-xl-4">
                    <div class="broker-card">
                        <div class="d_card_header">
                             @if ($broker->logo)
                             <div class="broker-logo">
                                <img src="{{ asset($broker->logo) }}" alt="Broker Logo">
                            </div>
                            @else
                                <p>No logo available</p>
                            @endif
                            <div class="text-container">
                                <span>Trade with</span>
                                <a class="b_c_b_name" href="">{{ $broker->name }}</a>
                                <a href="{{ $broker->url }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="broker-info">
                            <div class="description-wrapper">
                                <p class="short-description hero_p">
                                    {{ Str::limit(strip_tags($broker->title), 60) }}
                                </p>
                                <i class="info-icon fas fa-info-circle"></i>
                                <span class="full-description">
                                    {{ strip_tags($broker->short_description) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p>No top brokers found.</p>
            @endif
        </div>
    </div>
    <iframe src="https://fxpricing.com/fx-widget/ticker-tape-widget.php?id=1,2,3,5,14,20&border=show&speed=50&fcs_link=hide&click_target=blank&theme=transparent&by-cr=28A745&sl-cr=DC3545&flags=circle&d_mode=compact-name&column=ask,bid,spread&lang=en&font=Arial, sans-serif" width="100%" height="85" style="border: unset;"></iframe><div id="fx-pricing-widget-copyright"><span>Powered by </span><a href="https://fxpricing.com/" target="_blank">FX Pricing</a></div><style type="text/css">#fx-pricing-widget-copyright{text-align: center; font-size: 13px; font-family: sans-serif; margin-top: 10px; margin-bottom: 10px; color: #9db2bd;} #fx-pricing-widget-copyright a{text-decoration: unset; color: #bb3534; font-weight: 600;}</style>
</section>
</section>
<section class="hero_content s_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="hc_content">
                    <div class="s_content_wrapper">
                        <h2 class="section_title">Deposit Bonus</h2>
                        <a href="{{ route('broker.comparison') }}" class="see-all-button">
                        See All <span class="arrow-icon">→</span>
                        </a>
                    </div>

                    @foreach ($forex_bonus_data as $bonus)
                    <ul>
                        <li>
                            <div class="b_card">
                                @if ($bonus->feature_image)
                                <img src="{{ asset($bonus->feature_image) }}" class="custom-img" alt="Feature Image">
                                @else
                                <p>No image available.</p>
                                @endif
                                <div class="card_content">
                                    <h5 class="custom-card-title"></h5>
                                    <a class="b_card_heading"
                                        href="{{ route('deposit-bonuses.detail', $bonus->slug) }}">{{ Str::limit($bonus->title, 50) }}</a>
                                   
                                </div>
                            </div>
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="hc_content">
                    <div class="s_content_wrapper">
                        <h2 class="section_title">No Deposit Bonus</h2>
                        <div class="s_btn_wrapper">
                        <a href="{{ route('broker.comparison') }}" class="see-all-button">
                        See All <span class="arrow-icon">→</span>
                        </a>
                        </div>
                    </div>
                    @foreach ($noDepositBonuses as $bonus)
                    <ul>
                        <li>
                            <div class="b_card">
                            @if ($bonus->feature_image)
                            <img src="{{ asset($bonus->feature_image) }}" class="custom-img" alt="Feature Image">
                            @else
                            <p>No image available.</p>
                            @endif
                                <div class="card_content">
                                    <a class="b_card_heading"
                                        href="{{ route('no-deposit-bonuses.detail', $bonus->slug) }}">{{ Str::limit($bonus->title, 50) }}</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    @endforeach
               </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="s_content_wrapper">
                    <h2 class="section_title">Forex Tips</h2>
                    <a href="{{ route('broker.comparison') }}" class="see-all-button">
                        See All <span class="arrow-icon">→</span>
                        </a>
                </div>
                <div class="vertical-slider-wrapper">
                    <div class="vertical-slider">
                        @foreach($forex_tips as $tip)
                            <div class="f_news_wrapper">
                                <div class="f_news_content">
                                    <a class="f_tips_title" href="{{ route('news_detail', ['subcategory_slug' => $tip->rSubCategory->slug, 'post_slug' => $tip->slug]) }}">
                                        <h5>{{ Str::limit($tip->post_title, 60) }}</h5>
                                    </a>
                                    <div class="author_date">
                                        <div class="publish_warapper">
                                            @php
                                                $icons = [
                                                    '/resources/dollar-symbol.png',
                                                    '/resources/euro.png',
                                                    '/resources/performance.png',
                                                    '/resources/earth.png'
                                                ];

                                                $randomIcon = $icons[array_rand($icons)];
                                            @endphp

                                            <img src="{{ $randomIcon }}" alt="" class="pb_img">
                                            <p class="updated_date">{{ $tip->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="author_name">
                                            @php
                                                $user_data = $tip->author_id == 0 
                                                    ? \App\Models\Admin::find($tip->admin_id) 
                                                    : \App\Models\Author::find($tip->author_id);
                                            @endphp
                                            <i class="fas fa-user profile_icon"></i>
                                            {{ $user_data ? $user_data->name : 'Unknown Author' }}
                                        </p>
                                    </div>
                                </div>
                                <img class="forex_tips_img" src="{{ asset('uploads/'.$tip->post_photo) }}" alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="site_top_add">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-8 col-md-12">
                @if($global_top_ad_data->top_ad_status == 'Show')
                <div class="ad-section-1">
                    @if($global_top_ad_data->top_ad_url == '')
                        <img src="{{ asset('uploads/'.$global_top_ad_data->top_ad) }}" alt="">
                    @else
                        <a href="{{ $global_top_ad_data->top_ad_url }}"><img src="{{ asset('uploads/'.$global_top_ad_data->top_ad) }}" alt=""></a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="feature_brokers">
    <div class="container">
        <div class="row">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                <h2 class="f_b_heading">Explore Our Featured Brokers</h2>
                <a href="{{ route('broker.comparison') }}" class="see-all-button-dark">
                 Broker Comparison <span class="arrow-icon">→</span>
                    </a>

            </div>

            <p class="f_b_dec">
                Find the top forex brokers of 2024 with excellent platforms, low spreads, and tools for all traders.
            </p>
            @if ($featured_brokers->count() > 0)
            @foreach($featured_brokers as $broker)
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="broker_card">
                    <div class="l_r_wrap">
                        <div class="broker-logo">
                            @if ($broker->logo)
                            <img src="{{ asset($broker->logo) }}" alt="Broker Logo" class="broker_logo">
                            @else
                            <p>No logo available.</p>
                            @endif
                        </div>
                        <div class="rating_wrapper">
                            <div class="rating">
                            <?php
                                $rating = $broker->rating;
                                for ($i = 1; $i <= 5; $i++) {
                                    echo '<span class="star ' . ($i <= $rating ? 'filled' : ($i - 0.5 == $rating ? 'half' : '')) . '">';
                                    echo $i <= $rating || $i - 0.5 == $rating ? '&#9733;' : '&#9734;';
                                    echo '</span>';
                                }
                                ?>
                            </div>
                            <span class="rating">Overall {{ $broker->rating }}</span>
                        </div>
                    </div>
                    <!-- Upper Portion -->
                    <div class="broker_content_home b_c_home">
                         <!-- Tab Section -->
                        <div class="tab_wrapper">
                            <ul class="nav nav-tabs ac_list_wrapper" id="featuredTab{{ $broker->id }}" role="tablist">
                                <li class="nav-item h_nav" role="presentation">
                                    <a class="nav-link active mx-1" id="featured-tab1-tab{{ $broker->id }}" data-bs-toggle="tab"
                                        href="#featured-tab1{{ $broker->id }}" role="tab"
                                        aria-controls="featured-tab1{{ $broker->id }}" aria-selected="true">Summary</a>
                                </li>
                                <li class="nav-item h_nav" role="presentation">
                                    <a class="nav-link mx-1" id="featured-tab2-tab{{ $broker->id }}" data-bs-toggle="tab"
                                        href="#featured-tab2{{ $broker->id }}" role="tab"
                                        aria-controls="featured-tab2{{ $broker->id }}" aria-selected="false">Review</a>
                                </li>
                                <li class="nav-item h_nav" role="presentation">
                                    <a class="nav-link mx-1" id="featured-tab3-tab{{ $broker->id }}" data-bs-toggle="tab"
                                        href="#featured-tab3{{ $broker->id }}" role="tab"
                                        aria-controls="featured-tab3{{ $broker->id }}" aria-selected="false">Regulation</a>
                                </li>
                            </ul>
                        </div>
                          <!-- Tab Content -->
                        <div class="tab_c_wrapper">
                            <div class="tab-content" id="featuredTabContent{{ $broker->id }}">
                                <div class="tab-pane fade show active" id="featured-tab1{{ $broker->id }}" role="tabpanel"
                                    aria-labelledby="featured-tab1-tab{{ $broker->id }}">
                                    <div class="broker-info">
                                        <div class="broker-title bt_w">{{ Str::limit($broker->title, 30) }}</div>
                                        <div class="broker-details">
                                            <div class="detail_item">
                                                <span class="label bt_w">Minimum Deposit:</span>
                                                <span class="value bt_w">${{ $broker->minimum_deposit }}</span>
                                            </div>
                                            <div class="detail_item">
                                                <span class="label bt_w">Spreads:</span>
                                                <span class="value bt_w">{{ $broker->spreads }}</span>
                                            </div>
                                            <div class="detail_item">
                                                <span class="label bt_w">Leverage:</span>
                                                <span class="value bt_w">{{ $broker->leverage }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="featured-tab2{{ $broker->id }}" role="tabpanel"
                                    aria-labelledby="featured-tab2-tab{{ $broker->id }}">
                                    <p class="c_S">{{ Str::limit(strip_tags($broker->short_description), 80) }}</p>
                                    <a class="c_s_full" href="#">Read Full Review</a>
                                </div>
                                <div class="tab-pane fade" id="featured-tab3{{ $broker->id }}" role="tabpanel"
                                    aria-labelledby="featured-tab3-tab{{ $broker->id }}">
                                    <div class="b_list">{!! $broker->regulation !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Button Section -->
                    <div class="b_btn_wrapper b_c_home">
                        <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}">
                            <button class="site_button">
                                <i class="fas fa-external-link-alt"></i> Read Review
                            </button>
                        </a>
                        <a href="{!! $broker->url !!}" target="_blank" rel="noopener noreferrer">
                        <button class="site_button">
                            <i class="fas fa-external-link-alt"></i> Visit Site
                        </button>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <p>No featured brokers found.</p>
            @endif
        </div>
    </div>
</section>
<section class="broker_strengths s_padding section-muted">
    <div class="keys_trengths">
    <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start row g-5">
                <div class="col-md-3">
                    <h4 class="a_broker_heading">Best Brokers for Beginners</h4>
                </div>
                <div class="col-md-9">
                    <div class="owl-carousel best-for-beginners-slider owl-theme">
                        @foreach($bestForBeginners as $broker)
                            <div class="item">
                                <div class="b_item_wrapper">
                                <div class="b_item_single">
                                        <div class="b_image">
                                        <img src="{{ asset($broker->logo) }}" alt="" class="s_img">
                                        </div>
                                        <h5>{{ $broker->name }}</h5>
                                    </div>
                                    <div class="star-rating">
                                        <?php
                                            $rating = $broker->rating;
                                            $ratingClass = $rating == 5 ? 'full-rating' : 'partial-rating'; // Check if it's 5 stars for green or below 5 for yellow
                                        ?>
                                        <div class="stars {{ $ratingClass }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $rating ? 'filled' : ($i - 0.5 == $rating ? 'half' : '') }}">
                                                    {!! $i <= $rating || $i - 0.5 == $rating ? '&#9733;' : '&#9734;' !!}
                                                </span>
                                            @endfor
                                        </div>
                                        <span class="rating-number">({{ $rating }})</span>
                                    </div>
                                </div>
    
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
    </div>
    </div>
    <div class="spread_ranking">
    <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start row g-5">
                <div class="col-md-3">
                    <h4 class="a_broker_heading">Top Brokers with Tightest Spreads</h4>
                </div>
                <div class="col-md-9">
                    <div class="owl-carousel best-for-beginners-slider owl-theme">
                    @foreach($spreadRankings as $broker)
                            <div class="item">
                                <div class="b_item_wrapper">
                                <div class="b_item_single">
                                        <div class="b_image">
                                        <img src="{{ asset($broker->logo) }}" alt="" class="s_img">
                                        </div>
                                        <h5>{{ $broker->name }}</h5>
                                    </div>
                                    <div class="star-rating">
                                        <?php
                                            $rating = $broker->rating;
                                            $ratingClass = $rating == 5 ? 'full-rating' : 'partial-rating'; // Check if it's 5 stars for green or below 5 for yellow
                                        ?>
                                        <div class="stars {{ $ratingClass }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $rating ? 'filled' : ($i - 0.5 == $rating ? 'half' : '') }}">
                                                    {!! $i <= $rating || $i - 0.5 == $rating ? '&#9733;' : '&#9734;' !!}
                                                </span>
                                            @endfor
                                        </div>
                                        <span class="rating-number">({{ $rating }})</span>
                                    </div>
                                </div>
    
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
    </div>
    </div>
    <div class="bonus_broker">
    <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start row g-5">
                <div class="col-md-3">
                    <h4 class="a_broker_heading">Best Brokers with Bonuses</h4>
                </div>
                <div class="col-md-9">
                    <div class="owl-carousel best-for-beginners-slider owl-theme">
                            @foreach($bestBonuses as $broker)
                                <div class="item">
                                    <div class="b_item_single">
                                        <div class="b_image">
                                            <img src="{{ asset($broker->logo) }}" alt="" class="s_img">
                                        </div>
                                        <h5>{{ $broker->name }}</h5>
                                    </div>
                                    <div class="star-rating">
                                        <?php
                                            $rating = $broker->rating;
                                            $ratingClass = $rating == 5 ? 'full-rating' : 'partial-rating';
                                        ?>
                                        <div class="stars {{ $ratingClass }}">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span class="star {{ $i <= $rating ? 'filled' : ($i - 0.5 == $rating ? 'half' : '') }}">
                                                    {!! $i <= $rating || $i - 0.5 == $rating ? '&#9733;' : '&#9734;' !!}
                                                </span>
                                            @endfor
                                        </div>
                                        <span class="rating-number">({{ $rating }})</span>
                                    </div>                        
                                    @if($broker->accountOptions->first())
                                        <div class="offer_content">
                                            @if($broker->accountOptions->first()->exclusive_offers)
                                            <p>
                                                <span class="a_offfer">Offer:</span> 
                                                {{ Str::limit(strip_tags($broker->accountOptions->first()->exclusive_offers), 100, '...') }}
                                            </p>
                                            @endif
                                            <p><span class="a_offfer">Bonus Eligibility:</span> {{ $broker->accountOptions->first()->bonus_eligibility ? 'Yes' : 'No' }}</p>
                                        </div>
                                    @else
                                        <p>No promotional offers available.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

            </div>
    </div>
    </div>
</section>
<section class="leverage_section s_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section_title">Best Leverage Brokers</h2>
                <p class="section_dec">Explore the best forex brokers of 2024 with top platforms, competitive spreads, and tools for traders of all levels.</p>
            </div>
            <div class="col-md-12">
                 @if ($best_leverage_brokers->count() > 0)
                    <div class="row">
                        @foreach($best_leverage_brokers as $broker)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="broker_layer">
                                <div class="b_l_img_wrapper">
                                    <img class="broker_layer_image" alt="{{ $broker->name }} logo" src="{{ asset($broker->logo) }}" />
                                </div>
                                <div class="hover_description_wrapper">
                                    <span class="full_description_w">
                                        {{ strip_tags($broker->short_description) }}
                                    </span>
                                </div>
                                <div class="broker-content">
                                    <div class="broker-header">
                                        <a href="{{ $broker->url }}"><h4>{{ $broker->name }}</h4></a>
                                        <p class="broker-leverage"><strong>Leverage:</strong> {{ $broker->leverage }}</p>
                                    </div>
                                    <div class="broker-footer">
                                       <div class="s_btn_wrapper">
                                            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="s_btn group">
                                                <span class="overlay"></span>
                                                Read Review
                                            </a>
                                        </div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                <p>No brokers available.</p>
                @endif
            </div>
        
        </div>
    </div>
</section>
<section class="regulated_section section-muted s_padding">
    <div class="container">
        <div class="row">
           <div class="col-md-12">
                <div class="s_content_wrapper">
                    <h2 class="section_title">Trade With A Regulated Broker</h2>
                    <a href="{{ route('regulated_brokers') }}" class="see-all-button">
                    See All <span class="arrow-icon">→</span>
                    </a>
                </div>
                    
                <p class="section_dec">Trading with a regulated broker ensures security, transparency, and adherence to strict financial standards. It protects your funds and promotes a fair, trustworthy trading environment.</p>
           </div>
        </div>
        <div class="row">
            @foreach($regulatedBrokers as $broker)
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                <div class="broker_layer">
                    <div class="b_l_img_wrapper">
                        <img class="broker_layer_image" alt="{{ $broker->name }} logo" src="{{ asset($broker->logo) }}" />
                    </div>
                    <div class="hover_description_wrapper">
                        <span class="full_description_w">
                            {{ strip_tags($broker->short_description) }}
                        </span>
                    </div>
                    <div class="broker-content">
                        <div class="broker-header">
                            <a href="{{ $broker->url }}"><h4>{{ $broker->name }}</h4></a>
                            <p class="broker-leverage"><strong>Leverage:</strong> {{ $broker->leverage }}</p>
                        </div>
                        <div class="broker-footer">
                            <div class="s_btn_wrapper">
                                <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="s_btn group">
                                    <span class="overlay"></span>
                                    Read Review
                                </a>
                            </div>
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
                        </div>
                    </div>
                </div>
            </div>    
            @endforeach
        </div>
    
    </div>
</section>
<section class="e_c s_padding section-muted">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-xl-8">
                <h2 class="section_title">Live Economic Calendar</h2>
                <p class="section_dec">Stay updated with the latest global market news and financial events. This live widget brings you real-time insights and updates from major markets worldwide, helping you make informed trading decisions.
                </p>
                <div class="c_economic_calendar">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                    <div class="tradingview-widget-container__widget"></div>
                    <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-events.js" async>
                    {
                    "colorTheme": "light",
                    "isTransparent": true,
                    "width": "100%",
                    "height": "535",
                    "locale": "en",
                    "importanceFilter": "-1,0,1",
                    "countryFilter": "ar,au,br,ca,cn,fr,de,in,id,it,jp,kr,mx,ru,sa,za,tr,gb,us,eu"
                    }
                    </script>
                    </div>

                </div>
            </div>
            <div class="col-lg-5 col-xl-4">
                 <h2 class="section_title">Popular & Latest Forex News</h2>
                 <p class="section_dec">Stay ahead in trading with the latest forex updates, from market insights to central bank news.</p>
                 <div class="n_v_wrapper">
                    <ul class="nav nav-pills h_c_b_nav" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active n_p_btn" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ RECENT_NEWS }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link n_p_btn" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{ POPULAR_NEWS }}</button>
                        </li>
                    </ul>
                </div>
                <div class="widget">
                    <div class="news">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                @php
                                    $recent_news_data = \App\Models\Post::with('rSubCategory')
                                        ->where('language_id', $current_language_id)
                                        ->latest()
                                        ->take(4)
                                        ->get();
                                @endphp
                                @foreach($recent_news_data as $item)
                                    @php
                                        $user_data = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : \App\Models\Author::find($item->author_id);
                                        $updated_date = $item->updated_at->diffForHumans();
                                        @endphp
                                    <div class="news-item">
                                        <div class="right">
                                            <div class="category">
                                                <span class="badge bg-success">{{ optional($item->rSubCategory)->sub_category_name }}</span>
                                            </div>
                                            <h2>
                                                @if($item->rSubCategory)
                                                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                        {{ Str::limit($item->post_title, 60) }}
                                                    </a>
                                                @else
                                                    <span>{{ Str::limit($item->post_title, 60) }}</span>
                                                @endif
                                            </h2>
                                            <div class="date-user">
                                                <div class="user"><a href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
                                        </div>
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                @php
                                    $popular_news_data = \App\Models\Post::with('rSubCategory')
                                        ->where('language_id', $current_language_id)
                                        ->orderBy('visitors', 'desc')
                                        ->take(4)
                                        ->get();
                                @endphp
                                @foreach($popular_news_data as $item)
                                    @php
                                        $user_data = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : \App\Models\Author::find($item->author_id);
                                        $updated_date = $item->updated_at->diffForHumans();
                                        @endphp
                                    <div class="news-item">
                                        <div class="right">
                                            <div class="category">
                                                <span class="badge bg-success">{{ optional($item->rSubCategory)->sub_category_name }}</span>
                                            </div>
                                        
                                                <h2>
                                                    @if($item->rSubCategory)
                                                        <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                            {{ Str::limit($item->post_title, 60) }}
                                                        </a>
                                                    @else
                                                        <span>{{ Str::limit($item->post_title, 60) }}</span>
                                                    @endif
                                                </h2>

                                            <div class="date-user">
                                                <div class="user"><a href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
                                        </div>
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<section class="video_section">
    @if($setting_data->video_status == 'Show')
    <div class="video_content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="s_content_wrapper">
                        <h2 class="v_section_title">Recent Video Updates</h2>
                        <a href="{{ route('video_gallery') }}" class="see-all-button-dark">
                        See All <span class="arrow-icon">→</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="video-carousel owl-carousel">
                    @foreach($video_data->take($setting_data->video_total) as $item)
                            <div class="item">
                                <div class="item_card">
                                    <div class="video-thumb">
                                        <img src="http://img.youtube.com/vi/{{ $item->video_id }}/0.jpg" alt="">
                                        <div class="dark_bg"></div>
                                        <div class="icon">
                                            <a href="http://www.youtube.com/watch?v={{ $item->video_id }}" class="video-button"><i class="fas fa-play"></i></a>
                                        </div>
                                    </div>
                                    <div class="video-caption">
                                        <a href="javascript:void;">{{ Str::limit(strip_tags($item->caption), 30) }}</a>
                                        
                                    </div>
                                    <div class="video-date">
                                        <i class="fas fa-calendar-alt"></i> {{ $item->created_at->format('d F, Y') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
<section class="non_regulated_section s_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="s_content_wrapper">
                        <h2 class="section_title">Non Regulated Broker</h2>
                        <a href="{{ route('non_regulated_brokers') }}" class="see-all-button">
                        See All <span class="arrow-icon">→</span>
                        </a>
                    </div>
                <p class="section_dec">A Non-Regulated Broker operates without oversight from financial authorities, posing higher risks to traders due to limited protections and accountability. Always research thoroughly before trading.</p>
            </div>
        </div>
        <div class="row">
            <div class="non-regulated-slider owl-carousel">
                @foreach($non_regulatedBrokers as $broker)
                <div class="col-lg-12">
                    <div class="nr_wrapper">
                        <img class="n_r_b_logo" alt="{{ $broker->name }} logo" src="{{ asset($broker->logo) }}" />
                        <div class="broker-info">
                            <div class="non_tag">Non Regulated</div>
                            <div class="nr_b_info">
                                {{ $broker->name }}
                                <a href="{{ $broker->url }}" class="arrow-link">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            </div> 
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
           
        </div>
    </div>
</section>
<section class="s_n_wrapper s_padding">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center text-center">
            <div class="col-lg-7">
                <div class="s_dec_wrapper">
                    <h2>Subscribe to Our Newsletter</h2>
                    <p>Stay informed with the latest promotions, news, tips, and exclusive content delivered right to your inbox, keeping you updated and connected effortlessly.</p>
                </div>

                <form action="{{ route('subscribe') }}" method="POST" class="">
                    @csrf
                    <div class="form-group">
                        <div class="input-container">
                            <input type="email" name="email" id="email" class="form-input" placeholder="Your Email" required>
                            <button type="submit" class="submit-btn">Subscribe</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
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
(function($) {
    $(document).ready(function() {
        $("#category").on("change", function() {
            var categoryId = $("#category").val();
            if (categoryId) {
                $.ajax({
                    type: "get",
                    url: "{{ url('/subcategory-by-category/') }}" + "/" + categoryId,
                    success: function(response) {
                        $("#sub_category").html(response.sub_category_data);
                    },
                    error: function(err) {

                    }
                })
            }
        })
    });
})(jQuery);
$(document).ready(function() {
    @foreach($sub_category_data as $item)
    $('#news-carousel-{{ $item->id }}').owlCarousel({
        loop: true, 
        margin: 10, 
        nav: true, 
        dots: true,
        responsive: {
            0: {
                items: 1 
            },
            600: {
                items: 2 
            },
            1000: {
                items: 2 
            }
        }
    });
    @endforeach
});
$(document).ready(function () {
  handleAdBannersForAllPages('.hero', {
    offset: 250,
  });
});
</script>
@endsection