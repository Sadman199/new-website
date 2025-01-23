@extends('front.layout.app')
@section('title', 'Broker Comparison | Compare Forex Brokers and Find the Best Option')
@section('main_content')
<div id="loader-overlay">
    <div class="loader"></div>
</div>
<div class="page-top">
    <div class="breadcrumb_wrapper_by_comparison">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-lg-7 col-md-12">
                    <div class="hero-content">
                        <h2 class="b_c_h">Your Guide to Comparing Forex Brokers for Smarter Trading</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Comparison Broker
                            </ol>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content s_padding">

    <section class="Compare_broker">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <h2 class="section_title">Compare Brokers</h2>
                    <p class="t_d">
                        Use this tool to compare key features, trading conditions, and benefits of various brokers side-by-side. 
                        Make informed decisions by analyzing brokers based on your preferences.
                    </p>

                    <div class="info_section">
                            <h4 class="section_sub_title">How to Use:</h4>
                            <ul>
                                <li>Select two brokers from the dropdown menus below.</li>
                                <li>Click the "Compare" button to view a detailed comparison.</li>
                                <li>Analyze key metrics like spreads, leverage, trading platforms, and more.</li>
                            </ul>
                        </div>
                    <div class="compare_hub">
                        <form action="{{ route('brokers.getComparison') }}" method="POST" id="compareForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="compare-dropdown">
                                        <button type="button" class="compare-toggle" id="compare_broker1_toggle">
                                            -- Select Broker --
                                            <i class="fas fa-chevron-down toggle-icon"></i>
                                        </button>
                                        <ul class="compare-menu" id="compare_broker1_menu">
                                            @foreach($brokers as $broker)
                                            <li class="b_data_list" data-value="{{ $broker->slug }}">
                                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}"
                                                    class="c_logo">
                                                {{ $broker->name }}
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input type="hidden" name="broker1_id" id="compare_broker1" required>
                                        <span class="compare-error-message" id="compare_broker1_error">Please select a
                                            broker.</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="compare-dropdown">
                                        <button type="button" class="compare-toggle" id="compare_broker2_toggle">
                                            -- Select Broker --
                                            <i class="fas fa-chevron-down toggle-icon"></i>
                                        </button>
                                        <ul class="compare-menu" id="compare_broker2_menu">
                                            @foreach($brokers as $broker)
                                            <li class="b_data_list" data-value="{{ $broker->slug }}">
                                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}"
                                                    class="c_logo">
                                                {{ $broker->name }}
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input type="hidden" name="broker2_id" id="compare_broker2" required>
                                        <span class="compare-error-message" id="compare_broker2_error">Please select a
                                            broker.</span>
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
                    <div class="info_section">
                        <h4 class="section_sub_title">Why Compare Brokers?</h4>
                        <p class="t_d">
                            Comparing brokers helps you find the best options tailored to your trading needs. Whether you're seeking competitive spreads, low commissions, robust platforms, or excellent customer service, this tool helps you choose wisely.
                        </p>
                        <span class="key_c">Key factors to consider:</span>
                        <ul>
                            <li><strong>Regulation:</strong> Ensure brokers are regulated by trusted authorities.</li>
                            <li><strong>Costs:</strong> Compare spreads, commissions, and fees.</li>
                            <li><strong>Trading Tools:</strong> Check platform features like charting tools and mobile access.</li>
                            <li><strong>Support:</strong> Look for responsive customer service and helpful resources.</li>
                        </ul>
                        <p class="t_d">
                            Finding the right broker empowers you to trade confidently and efficiently. Start your comparison now to make an informed choice!
                        </p>

                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
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
    </section>

</div>

@endsection