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
                        <h2 class="b_c_h">{{ $broker1->name }} vs {{ $broker2->name }}: A Detailed Comparison</h2>
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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <p class="section_dec">Compare {{ $broker1->name }} and {{ $broker2->name }} on key aspects like user experience, fees, assets, customer support, and unique features. Whether you're a beginner or experienced trader, this comparison will help you choose the best broker for your needs based on their strengths and offerings.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="comparison_table">
                    <thead>
                        <tr>
                            <th class="feature-col">Feature</th>
                            <th class="broker-col">{{ $broker1->name }}</th>
                            <th class="broker-col">{{ $broker2->name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- General Information -->
                        <tr>
                            <td class="t_bold">Logo</td>
                            <td>
                                <img src="{{ asset($broker1->logo) }}" alt="{{ $broker1->name }}"
                                    class="comparison_img">
                            </td>
                            <td>
                                <img src="{{ asset($broker2->logo) }}" alt="{{ $broker2->name }}"
                                    class="comparison_img">
                            </td>
                        </tr>
                        <tr>
                            <td class="t_bold">URL</td>
                            <td><a href="{{ $broker1->url }}" target="_blank">{{ $broker1->url }}</a></td>
                            <td><a href="{{ $broker2->url }}" target="_blank">{{ $broker2->url }}</a></td>
                        </tr>
                        <!-- Trading Information -->
                        <tr>
                            <td colspan="4" class="heading-tage">Trading Information</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Trading Platforms</td>
                            <td>{{ strip_tags($broker1->platforms) }}</td>
                            <td>{{ strip_tags($broker2->platforms) }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Minimum Deposit</td>
                            <td>${{ number_format($broker1->minimum_deposit, 2) }}</td>
                            <td>${{ number_format($broker2->minimum_deposit, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Spreads</td>
                            <td>{{ $broker1->spreads }}</td>
                            <td>{{ $broker2->spreads }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Leverage</td>
                            <td>{{ $broker1->leverage }}</td>
                            <td>{{ $broker2->leverage }}</td>
                        </tr>
                        <!-- Customer Support -->
                        <tr>
                            <td colspan="4" class="heading-tage">Customer Support</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Customer Support</td>
                            <td>{{ strip_tags($broker1->customer_support) }}</td>
                            <td>{{ strip_tags($broker2->customer_support) }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Languages Supported</td>
                            <td>{{ strip_tags($broker1->languages) }}</td>
                            <td>{{ strip_tags($broker2->languages) }}</td>
                        </tr>
                        <!-- Payment Methods -->
                        <tr>
                            <td colspan="4" class="heading-tage">Payment Methods</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Withdrawal Methods</td>
                            <td>{{ strip_tags($broker1->withdrawal_method) }}</td>
                            <td>{{ strip_tags($broker2->withdrawal_method) }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Deposit Methods</td>
                            <td>{{ strip_tags($broker1->deposit_methods) }}</td>
                            <td>{{ strip_tags($broker2->deposit_methods) }}</td>
                        </tr>
                        <!-- Account Types & Mobile -->
                        <tr>
                            <td colspan="4" class="heading-tage">Account Types & Mobile</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Account Types</td>
                            <td>{{ implode(', ', json_decode($broker1->account_types)) }}</td>
                            <td>{{ implode(', ', json_decode($broker2->account_types)) }}</td>

                        </tr>
                        <tr>
                            <td class="t_bold">Mobile Trading</td>
                            <td>{{ $broker1->mobile_trading ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->mobile_trading ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <!-- Additional Trading Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Additional Trading Features</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Social Trading</td>
                            <td>{{ $broker1->social_trading ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->social_trading ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Research Tools</td>
                            <td>{{ $broker1->research_tools ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->research_tools ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Economic Calendar</td>
                            <td>{{ $broker1->economic_calendar ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->economic_calendar ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <!-- Ratings & Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Ratings & Features</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Rating</td>
                            <td>{{ $broker1->rating }}/5</td>
                            <td>{{ $broker2->rating }}/5</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Key Features</td>
                            <td>{{ strip_tags($broker1->top_feature) }}</td>
                            <td>{{ strip_tags($broker2->top_feature) }}</td>
                        </tr>
                        <!-- Location & Web Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Location & Web Features</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Country</td>
                            <td>{{ strip_tags($broker1->country) }}</td>
                            <td>{{ strip_tags($broker2->country) }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Web Trading</td>
                            <td>{{ strip_tags($broker1->web_trader) }}</td>
                            <td>{{ strip_tags($broker2->web_trader) }}</td>
                        </tr>
                        <!-- Additional Services -->
                        <tr>
                            <td colspan="4" class="heading-tage">Additional Services</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Account Manager</td>
                            <td>{{ $broker1->account_managers == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->account_managers == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">VPS Hosting</td>
                            <td>{{ $broker1->vps_hosting == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->vps_hosting == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td class="t_bold">Segregation Of Funds</td>
                            <td>{{ $broker1->segregation_of_funds == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->segregation_of_funds == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                    </tbody>
                </table>
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
    <script>
        $(document).ready(function () {
        handleAdBannersForAllPages('.breadcrumb_wrapper_by_comparison', {
            offset: 200, // Adjust as needed
            fadeDuration: 400,
            slideDuration: 600,
        });
        });
    </script>
</div>

@endsection