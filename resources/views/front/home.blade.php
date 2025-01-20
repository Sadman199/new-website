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
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="section-title">Top Rated Brokers</h2>
                <div class="sponsored">
                    Sponsored <i class="fas fa-info-circle"></i>
                </div>
            </div>

            <!-- Paragraph placed directly under heading -->
            <p class="section-dec">Find the best brokers trusted by traders worldwide, offering top-notch services and
                reliable trading platforms.</p>

            @if ($top_brokers->count() > 0)
            <div class="row">
                @foreach($top_brokers as $broker)
                <div class="col-lg-4 col-md-6">
                    <div class="broker-card">
                            @if ($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="Broker Logo">
                            @else
                                <p>No logo available</p>
                            @endif

                        <div class="broker-info">
                            <div class="b_h_info">
                                <div class="text-container">
                                    <span>Trade with</span>
                                    <a class="b_c_b_name" href="">{{ $broker->name }}</a>
                                </div>
                                <a href="{{ $broker->url }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                            <div class="description-wrapper">
                                <p class="short-description hero_p">
                                    {{ Str::limit(strip_tags($broker->short_description), 30) }}
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
    <div class="news_ticker_wrapper" style="position: relative; width: 100%; height: 85px;">
  <iframe
    id="news_ticker_iframe"
    class="news_ticker"
    src="https://fxpricing.com/fx-widget/ticker-tape-widget.php?id=1,2,3,5,14,20&border=show&speed=50&click_target=blank&theme=transparent&by-cr=28A745&sl-cr=DC3545&flags=circle&d_mode=compact-name&column=ask,bid,spread&lang=en&font=Arial, sans-serif"
    width="100%"
    height="85"
    style="border: unset;"
  ></iframe>
  <!-- Invisible Overlay to capture clicks -->
  <a
    href="https://oneroyal.com/en/open-account/sign-up"
    target="_blank"
    rel="noopener noreferrer"
    style="
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10; /* Make sure the overlay is on top of the iframe */
      background: transparent;
    "
  ></a>
</div>

<div id="fx-pricing-widget-copyright">
  <span>Powered by </span><a href="https://fxpricing.com/" target="_blank">FX Pricing</a>
</div>

<style type="text/css">
  #fx-pricing-widget-copyright {
    text-align: center;
    font-size: 13px;
    font-family: sans-serif;
    margin-top: 10px;
    margin-bottom: 10px;
    color: #9db2bd;
  }
  #fx-pricing-widget-copyright a {
    text-decoration: unset;
    color: #bb3534;
    font-weight: 600;
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const tickerWrapper = document.querySelector(".news_ticker_wrapper");
    const iframe = document.getElementById("news_ticker_iframe");

    // Add a hover event to stop the scrolling (by disabling pointer events)
    tickerWrapper.addEventListener("mouseenter", function () {
      iframe.style.pointerEvents = "none"; // Stops interaction with iframe, effectively pausing ticker
    });

    // Add a hoverout event to resume the scrolling (by enabling pointer events)
    tickerWrapper.addEventListener("mouseleave", function () {
      iframe.style.pointerEvents = "auto"; // Resumes interaction with iframe, effectively starting the ticker again
    });
  });
</script>



</section>
<section class="hero_content s_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="hc_content">
                    <div class="col-lg-12">
                        <h2 class="section_title">Deposit Bonus</h2>
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
                                        href="{{ route('deposit-bonuses.detail', $bonus->slug) }}">{{ Str::limit($bonus->title, 30) }}</a>
                                    <p>{{ Str::limit(strip_tags($bonus->description), 50) }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="hc_content">
                    <div class="col-lg-12">
                        <h2 class="section_title">No Deposit Bonus</h2>
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
                                        href="{{ route('no-deposit-bonuses.detail', $bonus->slug) }}">{{ Str::limit($bonus->title, 20) }}</a>
                                    <p>{{ Str::limit(strip_tags($bonus->description), 50) }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    @endforeach
               </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <ul class="nav nav-pills h_c_b_nav" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ RECENT_NEWS }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{ POPULAR_NEWS }}</button>
                    </li>
                </ul>
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
                                        $updated_date = $item->updated_at->format('d F, Y');
                                    @endphp
                                    <div class="news-item">
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                        </div>
                                        <div class="right">
                                            <div class="category">
                                                <span class="badge bg-success">{{ optional($item->rSubCategory)->sub_category_name }}</span>
                                            </div>
                                        

                                            <h2>
                                                @if($item->rSubCategory)
                                                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                        {{ Str::limit($item->post_title, 30) }}
                                                    </a>
                                                @else
                                                    <span>{{ Str::limit($item->post_title, 30) }}</span>
                                                @endif
                                            </h2>


                                            <div class="date-user">
                                                <div class="user"><a href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
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
                                        $updated_date = $item->updated_at->format('d F, Y');
                                    @endphp
                                    <div class="news-item">
                                        <div class="left">
                                            <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="">
                                        </div>
                                        <div class="right">
                                            <div class="category">
                                                <span class="badge bg-success">{{ optional($item->rSubCategory)->sub_category_name }}</span>
                                            </div>
                                        
                                                <h2>
                                                    @if($item->rSubCategory)
                                                        <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                                                            {{ Str::limit($item->post_title, 30) }}
                                                        </a>
                                                    @else
                                                        <span>{{ Str::limit($item->post_title, 30) }}</span>
                                                    @endif
                                                </h2>

                                            <div class="date-user">
                                                <div class="user"><a href="javascript:void;">{{ $user_data->name }}</a></div>
                                                <div class="date"><a href="javascript:void;">{{ $updated_date }}</a></div>
                                            </div>
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
<section class="site_top_add">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8">
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
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="f_b_heading">Explore Our Featured Brokers</h2>
            </div>
            <p class="f_b_dec">
                Find the top forex brokers of 2024 with excellent platforms, low spreads, and tools for all traders.
            </p>
            @if ($featured_brokers->count() > 0)
            @foreach($featured_brokers as $broker)
            <div class="col-lg-3 col-md-6 col-12">
                <div class="broker_card">
                    <!-- Upper Portion -->
                    <div class="broker_content">
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
                                        <div class="broker-title bt_w">{{ Str::limit($broker->title, 25) }}</div>
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
                    <div class="b_btn_wrapper">
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
                        <div class="col-lg-4 col-md-6">
                            <div class="broker-layer">
                                <!-- Background Section -->
                                <div class="broker-background" style="background-image: url('{{ asset($broker->logo ?? 'default-logo.png') }}');"></div>

                                <!-- Content Section -->
                                <div class="broker-content">
                                    <div class="broker-header">
                                        <h4>{{ $broker->name }}</h4>
                                        <p class="broker-leverage"><strong>Leverage:</strong> {{ $broker->leverage }}</p>
                                    </div>
                                    <!-- Rating and Action -->
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
                <h2 class="section_title">Trade With A Regulated Broker</h2>
                <p class="section_dec">Trading with a regulated broker ensures security, transparency, and adherence to strict financial standards. It protects your funds and promotes a fair, trustworthy trading environment.</p>
           </div>
        </div>
        <div class="row">
            @foreach($regulatedBrokers as $broker)
            <div class="col-lg-4 col-md-6">
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
            <div class="s_btn_wrapper">
                <a href="{{ route('regulated_brokers') }}" class="s_btn group">
                    <span class="overlay"></span>
                    View More
                </a>
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
                    <div class="video-heading">
                        <h2>Recent Video Updates</h2>
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
            <div class="s_btn_wrapper">
                <a href="{{ route('video_gallery') }}" class="s_btn group">
                    <span class="overlay"></span>
                    View More
                </a>
            </div>
        </div>
    </div>
    @endif
</section>
<section class="Compare_broker s_padding section-muted">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <h2 class="section_title">Compare Brokers</h2>
                <div class="compare_hub">
                    <form action="{{ route('brokers.getComparison') }}" method="POST" id="compareForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                            <h4 class="compare-subheading">Select Broker 1</h4>
                                <div class="compare-dropdown">
                                    <button type="button" class="compare-toggle" id="compare_broker1_toggle">
                                        -- Select Broker --
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </button>
                                    <ul class="compare-menu" id="compare_broker1_menu">
                                        @foreach($brokers as $broker)
                                        <li class="b_data_list" data-value="{{ $broker->slug }}">
                                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="c_logo">
                                            {{ $broker->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="broker1_id" id="compare_broker1" required>
                                    <span class="compare-error-message" id="compare_broker1_error">Please select a broker.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <h4 class="compare-subheading">Select Broker 2</h4>
                                <div class="compare-dropdown">
                                    <button type="button" class="compare-toggle" id="compare_broker2_toggle">
                                        -- Select Broker --
                                        <i class="fas fa-chevron-down toggle-icon"></i>
                                    </button>
                                    <ul class="compare-menu" id="compare_broker2_menu">
                                        @foreach($brokers as $broker)
                                        <li class="b_data_list" data-value="{{ $broker->slug }}">
                                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="c_logo">
                                            {{ $broker->name }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="broker2_id" id="compare_broker2" required>
                                    <span class="compare-error-message" id="compare_broker2_error">Please select a broker.</span>
                                </div>
                            </div>
                        </div>
                        <div class="s_btn_wrapper">
                            <button type="submit" class="s_btn group">
                                <span class="overlay"></span>
                                Compare
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
               <h2 class="section_title">Subscribe to Our Newsletter</h2>
                <!-- Subscribe Form -->
                <form action="{{ route('subscribe') }}" method="POST" class="subscribe-form">
                    @csrf
                    <p class="subscribe-description">Stay updated with the latest news and offers.</p>

                    <div class="form-group">
                        <label for="email" class="form-label">Enter Your Email</label>
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
<section class="non_regulated_section s_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section_title">⚠️ Non-Regulated Brokers</h2>
                <p class="section_dec">Proceed with caution! These brokers are <strong>not regulated</strong>, which means there is a higher risk of losing your funds. </p>
            </div>
        </div>
        <div class="row">
            @foreach($non_regulatedBrokers as $broker)
            <div class="col-lg-2 col-md-4">
                <div class="tr_broker_card">
                    <img alt="{{ $broker->name }} logo" src="{{ asset($broker->logo) }}" />
                    <div class="broker-info">
                        <div class="b_h_info">
                            <div class="text-container">
                                  <div class="non_tag">Non Regulated</div>
                                <a href="">{{ $broker->name }}</a>
                            </div>
                            <a href="{{ $broker->url }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="s_btn_wrapper">
                <a href="{{ route('non_regulated_brokers') }}" class="s_btn group">
                    <span class="overlay"></span>
                    View More
                </a>
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