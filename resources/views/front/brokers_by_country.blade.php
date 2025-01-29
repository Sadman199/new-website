@extends('front.layout.app')
@section('title', 'BrokersCourt | Discover Top Brokers by Country or Region')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_country">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                    <div class="hero-content">
                        <h2 class="b_c_h">Leading Forex Brokers in {{ ucfirst($country) }}</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Brokers in {{ ucfirst($country) }}
                                </li>
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
            <div class="col-md-9">
                <div class="f_dynamic_content">
                    @switch($country)
                        @case('usa')
                            <p>Find top-rated forex brokers in the US, regulated by NFA and CFTC for secure trading. US brokers offer competitive spreads, advanced platforms, and diverse account types, ensuring a reliable experience for both beginners and experts. Enjoy access to markets like forex, commodities, and cryptocurrencies, along with excellent customer support and educational resources.</p>
                            @break
                        @case('canada')
                            <p>Explore Canada’s best forex brokers regulated by IIROC. These brokers provide secure platforms, competitive spreads, and advanced trading tools tailored for all levels of traders. Canadian brokers prioritize transparency, trader protection, and offer excellent risk management tools for a reliable trading experience.</p>
                            @break
                        @case('uk')
                            <p>Discover FCA-regulated brokers in the UK, known for transparency and low fees. UK brokers offer advanced tools, diverse markets, and strong consumer protections for a secure trading environment. Traders can benefit from robust educational resources and demo accounts to hone their skills before live trading.</p>
                            @break
                        @case('australia')
                            <p>Trade with ASIC-regulated brokers in Australia offering competitive spreads, low fees, and advanced platforms. Australian brokers are reliable and cater to both beginners and experienced traders. With strong financial regulations and fast execution, Australia remains a top choice for traders worldwide.</p>
                            @break
                        @case('south_africa')
                            <p>South African brokers, regulated by FSCA, provide secure trading environments, competitive spreads, and strong local support. These brokers cater to all trader levels with diverse platforms and local payment methods. South Africa’s growing market and robust regulations make it a popular choice across the continent.</p>
                            @break
                        @case('asia')
                            <p>Trade with leading brokers in Asia offering low spreads, fast execution, and localized services. Asian brokers cater to traders in Japan, China, Singapore, and beyond with region-specific support and innovative mobile platforms. Benefit from dynamic markets and flexible trading options tailored to your needs.</p>
                            @break
                        @default
                            <p>Discover forex brokers around the world, tailored to your needs. Whether regulated locally or offering unique features, our selection ensures low fees, advanced tools, and diverse account types for every trader. Find the right broker with expert reviews and comparisons to trade with confidence.</p>
                    @endswitch
                </div>
                @if ($brokers->isEmpty())
                <p class="text-muted">No brokers found for {{ ucfirst($country) }}. Please check back later.</p>
                @else
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-8 col-xl-9">
                            <div class="row">
                                @foreach ($brokers as $broker)
                                <div class="col-xl-4 col-lg-6 col-md-6">
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
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-3 col-md-6">
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
                                        @foreach ($f_broker_country as $broker)
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
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    
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
                <div class="col-lg-4 col-xl-3">
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
</div>
@endsection