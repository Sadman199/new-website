@extends('front.layout.app')
@section('title', 'BrokersCourt | Choose the Right Broker by Account Type')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>


<div class="page-top">
    <div class="breadcrumb_wrapper_by_account">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                    <div class="hero-content">
                        <h2 class="b_c_h">Leading Options for {{ $type }}</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Brokers for {{ $type }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="type_of_a_c_wrapper s_padding">
        <div class="container">
            <div class="row">
            <div class="col-md-8">
                <div class="f_dynamic_content">
                    @if ($type === 'Standard Accounts')
                        <div>
                            <p><strong>Standard Accounts</strong> are designed for traders of all experience levels. They provide a balanced trading environment with competitive spreads, access to major financial instruments, and reliable execution. This account type is ideal for those seeking simplicity and consistency.</p>
                        </div>
                    @elseif ($type === 'Islamic Account')
                        <div>
                            <p><strong>Islamic Accounts</strong> cater to traders who follow Sharia law. These accounts ensure compliance by eliminating swaps and interest fees on overnight positions. They provide a fair trading environment without compromising religious principles.</p>
                        </div>
                    @elseif ($type === 'ECN Accounts')
                        <div>
                            <p><strong>ECN Accounts</strong> are perfect for traders looking for direct access to liquidity providers. With ultra-low spreads, lightning-fast execution, and no dealing desk intervention, this account is tailored for professionals and high-frequency traders.</p>
                        </div>
                    @elseif ($type === 'Classic Account')
                        <div>
                            <p><strong>Classic Accounts</strong> are a great choice for traditional traders who value a straightforward experience. With simple features, competitive conditions, and no hidden surprises, these accounts are perfect for those who appreciate stability in their trading.</p>
                        </div>
                    @elseif ($type === 'Copy Trading Accounts')
                        <div>
                            <p><strong>Copy Trading Accounts</strong> revolutionize trading by allowing you to follow and replicate the strategies of experienced traders. Whether you're new to trading or prefer a hands-off approach, this account type empowers you to grow alongside market experts.</p>
                        </div>
                    @elseif ($type === 'VIP Accounts')
                        <div>
                            <p><strong>VIP Accounts</strong> are designed for elite traders who demand the best. Enjoy exclusive privileges, including personal account managers, priority customer support, tighter spreads, and customized solutions to suit your trading needs.</p>
                        </div>
                    @elseif ($type === 'Raw Account')
                        <div>
                            <p><strong>Raw Accounts</strong> offer direct market access with raw spreads from liquidity providers. With minimal transaction costs and precise execution, this account is perfect for traders who prioritize cost efficiency and transparency.</p>
                        </div>
                    @elseif ($type === 'Micro Accounts')
                        <div>
                            <p><strong>Micro Accounts</strong> are tailored for beginners or those who want to start trading with smaller amounts. Trade in smaller lot sizes, manage risk effectively, and gain hands-on experience without a significant financial commitment.</p>
                        </div>
                    @else
                        <div>
                            <p>Discover the benefits of <strong>{{ $type }}</strong> and find out how this account type can elevate your trading experience. Each account is tailored to meet specific trading preferences and needs.</p>
                        </div>
                    @endif
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        @if($brokers->isNotEmpty())
                            @foreach($brokers as $broker)
                                <div class="col-md-4">
                                    <div class="ac_broker_card">
                                        <!-- Upper Portion -->
                                        <div class="broker_content">
                                            <div class="ac_l_r_wrap">
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
                                                                if ($i <= $rating) {
                                                                    echo '<span class="star filled">&#9733;</span>'; // Filled star
                                                                } elseif ($i - 0.5 == $rating) {
                                                                    echo '<span class="star half">&#9733;</span>'; // Half-filled star
                                                                } else {
                                                                    echo '<span class="star">&#9734;</span>'; // Empty star
                                                                }
                                                            }
                                                            ?>
                                                    </div>
                                                    <div class="n_tag {{ $broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated ? 'regulated-tag' : 'non-regulated-tag' }}">
                                                    @if ($broker->accountOptions->first() && $broker->accountOptions->first()->is_regulated)
                                                        Regulated
                                                    @else
                                                        Non Regulated
                                                    @endif
                                                     </div>
                                                </div>
                                                
                                            </div>
                                             <!-- Tab Section -->
                                            <div class="ac_tab_wrapper">
                                                <ul class="nav nav-tabs accout_type_list_wrapper" id="featuredTab{{ $broker->id }}" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link active mx-1 aclist" id="featured-tab1-tab{{ $broker->id }}" data-bs-toggle="tab"
                                                            href="#featured-tab1{{ $broker->id }}" role="tab"
                                                            aria-controls="featured-tab1{{ $broker->id }}" aria-selected="true">Summary</a>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link mx-1 aclist" id="featured-tab2-tab{{ $broker->id }}" data-bs-toggle="tab"
                                                            href="#featured-tab2{{ $broker->id }}" role="tab"
                                                            aria-controls="featured-tab2{{ $broker->id }}" aria-selected="false">Review</a>
                                                    </li>
                                                    <li class="nav-item " role="presentation">
                                                        <a class="nav-link mx-1 aclist" id="featured-tab3-tab{{ $broker->id }}" data-bs-toggle="tab"
                                                            href="#featured-tab3{{ $broker->id }}" role="tab"
                                                            aria-controls="featured-tab3{{ $broker->id }}" aria-selected="false">Regulation</a>
                                                    </li>
                                            
                                                </ul>
                                            </div>
                                            <!-- Tab Content -->
                                            <div class="tab_c_wrapper">
                                                <div class="tab-content" id="featuredTabContent{{ $broker->id }}">
                                                    <div class="tab-pane fade show active" id="featured-tab1{{ $broker->id }}" role="tabpanel" aria-labelledby="featured-tab1-tab{{ $broker->id }}">
                                                        <div class="broker-info">
                                                            <div class="broker-title">{{ Str::limit($broker->title, 25) }}</div>
                                                            <div class="broker-details">
                                                                <div class="detail_item">
                                                                    <span class="label">Minimum Deposit:</span>
                                                                    <span class="value">${{ $broker->minimum_deposit }}</span>
                                                                </div>
                                                                <div class="detail_item">
                                                                    <span class="label">Spreads:</span>
                                                                    <span class="value">{{ $broker->spreads }}</span>
                                                                </div>
                                                                <div class="detail_item">
                                                                    <span class="label">Leverage:</span>
                                                                    <span class="value">{{ $broker->leverage }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="tab-pane fade" id="featured-tab2{{ $broker->id }}" role="tabpanel"
                                                        aria-labelledby="featured-tab2-tab{{ $broker->id }}">
                                                        <p class="ac_c_S">{{ Str::limit(strip_tags($broker->short_description), 200) }}</p>
                                                        <a class="ac_c_S_full" href="#">Read Full Review</a>
                                                    </div>
                                                    <div class="tab-pane fade" id="featured-tab3{{ $broker->id }}" role="tabpanel"
                                                        aria-labelledby="featured-tab3-tab{{ $broker->id }}">
                                                        <div class="ac_b_list">{!! $broker->regulation !!}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Button Section -->
                                        <div class="b_btn_wrapper">
                                            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}">
                                                <button class="site_button w_t">
                                                    <i class="fas fa-external-link-alt"></i> Read Review
                                                </button>
                                            </a>
                                            <a href="{!! $broker->url !!}" target="_blank" rel="noopener noreferrer">
                                            <button class="site_button w_t">
                                                <i class="fas fa-external-link-alt"></i> Visit Site
                                            </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No brokers found for the selected account type.</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sidebar">
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
                                @foreach ($featured_brokers as $featured_broker)
                                    <a href="{{ route('broker_detail', ['slug' => $featured_broker->slug]) }}" class="broker-side-card-link">
                                        <li class="broker_side_card">
                                            <div class="b_c_c_content">
                                                @if ($featured_broker->logo)
                                                    <img src=" {{ asset($featured_broker->logo) }}" alt="{{ $featured_broker->name }} Broker Logo" />
                                                   
                                                @else
                                                    <p>No logo available.</p>
                                                @endif
                                                <span>{{ $featured_broker->name }} Review 2024</span>
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
<script>
    $(document).ready(function () {
    handleAdBannersForAllPages('.breadcrumb_wrapper_by_account', {
        offset: 100, // Adjust as needed
        fadeDuration: 400,
        slideDuration: 600,
    });
    });
</script>

@endsection