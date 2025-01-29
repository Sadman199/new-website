@extends('front.layout.app')
@section('title', 'BrokersCourt | Forex Promotions & Bonuses for Every Trader')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                   <div class="hero-content">
                    <h2 class="b_c_h">{{ $bonus->title }}</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item">
                                    <a href="{{ $promo_route }}">{{ $promo_type }}</a>
                                </li>
                              
                            </ol>
                        </nav>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content s_padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content">
                        <div class="bonus-card">
                            <div class="bonus-card-header">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 d-flex flex-column justify-content-center align-items-center">
                                        @if ($bonus->feature_image)
                                        <img src="{{ asset($bonus->feature_image) }}" class="bonus-image" alt="Feature Image">
                                        @else
                                        <p>No image available.</p>
                                        @endif
                                    </div>

                                    <div class="col-lg-7 col-md-8 d-flex flex-column justify-content-center align-items-center">
                                        <div class="b_p_w">
                                        <div class="bonus-prize-wrapper">
                                            <!-- Image with rounded "Prize" text -->
                                            
                                            <!-- Prize Text -->
                                            <h3 class="bonus-prize">{{ strip_tags($bonus->prize) }}</h3>
                                            <div class="bonus-prize-image-wrapper position-relative">
                                                <img src="/resources/trophy_7793979.png" alt="Prize" class="b_s_img">
                                                <span class="prize-text">Prize</span>
                                            </div>
                                        </div>

                                            <div class="additional-info">
                                                <ul class="list-unstyled">
                                                    <li><strong>Expiry Date:</strong>
                                                        {{ strip_tags($bonus->expiry_date) }}</li>
                                                    <li><strong>Minimum Deposit:</strong>
                                                        ${{ strip_tags($bonus->min_deposit) }}</li>
                                                    <li><strong>Bonus Type Details:</strong>
                                                        <div class="bonus-type-details">{!!
                                                            nl2br(e(strip_tags($bonus->bonus_type_details))) !!}</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-12 d-flex justify-content-lg-center align-items-lg-center flex-column">
                                        <div class="bonus-details-card p-3 w-100">
                                            <div class="detail-item mb-2">
                                                <strong>Author:</strong>
                                                <span>{{ strip_tags($bonus->author_name) }}</span>
                                            </div>
                                            <div class="detail-item mb-2">
                                                <strong>Publish Date:</strong>
                                                <span>{{ strip_tags($bonus->publish_date) }}</span>
                                            </div>
                                            <div class="detail-item mb-2">
                                                <strong>Bonus Type:</strong>
                                                <span>{{ strip_tags($bonus->promo_type) }}</span>
                                            </div>
                                            <div class="detail-item mb-2">
                                                <strong>Bonus Category:</strong>
                                                <span>{{ strip_tags($bonus->bonus_category) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xl-3 col-md-12 order-lg-1 order-md-2 order-2">
                    <div class="broker-sticky-container">
                        <div class="side_bar_add">
                            <span class="l_b_h"> Octa Deposit Bonus</span> @foreach($global_sidebar_top_ad as $row) <div class="ad-sidebar"> @if($row->sidebar_ad_url == '') <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt=""> @else <a href="{{ $row->sidebar_ad_url }}">
                                <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="">
                            </a> @endif </div> @endforeach
                        </div>
                        <div class="featured-brokers">
                            <span class="l_b_h">Featured Brokers</span>
                            <ul> @foreach ($featured_brokers as $broker) <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="broker-side-card-link">
                                <li class="broker_side_card">
                                <div class="b_c_c_content"> @if ($broker->logo) <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} Broker Logo" class=""> @else <p>No logo available.</p> @endif <span>{{ $broker->name }} Review 2024</span>
                                </div>
                                <i class="fas fa-chevron-right"></i>
                                </li>
                            </a> @endforeach </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-6 col-md-12 order-lg-2 order-md-1 order-1">
                    <!-- Participation Details -->
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">Participation Details</h3>
                        <p class="t_d"><strong>How to Participate:</strong> {!! nl2br(e(strip_tags($bonus->how_to_participate))) !!}</p>
                        
                    </div>
                    <!-- Bonus Details -->
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">Bonus Details</h3>
                        <p class="t_d">{!! nl2br(e(strip_tags($bonus->details))) !!}</p>
                    </div>
                    <!-- General Terms -->
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">General Terms</h3>
                        <p class="t_d">{!! nl2br(e(strip_tags($bonus->general_terms))) !!}</p>
                    </div>
                    <!-- General Terms -->
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">Country Restrictions:</h3>
                        <p class="t_d">{{ strip_tags($bonus->participate) }}</p>
                    </div>
                    <!-- Eligibility Criteria -->
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">Eligibility Criteria</h3>
                        <p class="t_d">{!! nl2br(e(strip_tags($bonus->eligibility_criteria))) !!}</p>
                    </div>
                    <div class="b_c_wrapper">
                        <h3 class="r_v_h">Description</h3>
                        <p class="t_d">{{ strip_tags($bonus->description) }}</p>
                    </div>
                    <!-- Links -->
                    <!-- <div class="b_c_wrapper">
                        <h3 class="r_v_h">Links</h3>
                        <ul class="list-unstyled">
                            <li>
                                <strong>Terms & Conditions:</strong>
                            </li>
                            <li>
                                <strong>Affiliate Link:</strong>
                                <a href="{{ $bonus->affiliate_link }}" target="_blank" class="text-decoration-none">
                                    Affiliate Program
                                </a>
                            </li>
                            <li>
                                <strong>External Link:</strong>
                                <a href="{{ $bonus->link }}" target="_blank" class="text-decoration-none">
                                    Visit Bonus Page
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>
                <div class="col-lg-4 col-xl-3 col-md-12 order-lg-3 order-md-3 order-3">
                    <div class="broker-sticky-container">
                        <div class="sidebar">
                            <div class="recent-bonuses">
                                <span class="l_b_h">Related Deposit Bonuses</span>
                                <ul>
                                    @foreach($recent_deposit_bonuses as $deposit_bonus)
                                    <a href="{{ route('deposit-bonuses.detail', $deposit_bonus->slug) }}" class="">
                                        <li class="broker_side_card">
                                            <div class="b_c_c_content">
                                                @if ($deposit_bonus->feature_image)
                                                <img src="{{ asset($deposit_bonus->feature_image) }}"
                                                    alt="Forex Deposit Bonus - {{ $deposit_bonus->title }}"
                                                    class="broker-card-img">
                                                @else
                                                <p>No image available.</p>
                                                @endif
                                                <span>{{ Str::limit(strip_tags($deposit_bonus->title), 40) }}</span>
                                                
                                            </div>
                                            <i class="fas fa-chevron-right"></i>
                                        </li>
                                    </a>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="recent-bonuses">
                                <span class="l_b_h">Recent No Deposit Bonuses</span>
                                <ul>
                                    @foreach($recent_no_deposit_bonuses as $no_deposit_bonus)
                                    <a href="{{ route('no-deposit-bonuses.detail', $no_deposit_bonus->slug) }}" class="">
                                        <li class="broker_side_card">
                                            <div class="b_c_c_content">
                                                @if ($no_deposit_bonus->feature_image)
                                                <img src="{{ asset($no_deposit_bonus->feature_image) }}"
                                                    alt="Forex No Deposit Bonus - {{ $no_deposit_bonus->title }}"
                                                    class="broker-card-img">
                                                @else
                                                <p>No image available.</p>
                                                @endif
                                                <span>{{ $no_deposit_bonus->title }}</span>
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
    <script>
        $(document).ready(function () {
        handleAdBannersForAllPages('.breadcrumb_wrapper', {
            offset: 200, // Adjust as needed
            fadeDuration: 400,
            slideDuration: 600,
        });
        });
    </script>
 </div>   
@endsection