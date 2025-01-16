@extends('front.layout.app')

@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_comparison">
        <div class="container">
            <div class="row">
                <div class="hero-content">
                    <div class="col-md-12">
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
                            <td>Logo</td>
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
                            <td>URL</td>
                            <td><a href="{{ $broker1->url }}" target="_blank">{{ $broker1->url }}</a></td>
                            <td><a href="{{ $broker2->url }}" target="_blank">{{ $broker2->url }}</a></td>
                        </tr>
                        <!-- Trading Information -->
                        <tr>
                            <td colspan="4" class="heading-tage">Trading Information</td>
                        </tr>
                        <tr>
                            <td>Trading Platforms</td>
                            <td>{{ strip_tags($broker1->platforms) }}</td>
                            <td>{{ strip_tags($broker2->platforms) }}</td>
                        </tr>
                        <tr>
                            <td>Minimum Deposit</td>
                            <td>${{ number_format($broker1->minimum_deposit, 2) }}</td>
                            <td>${{ number_format($broker2->minimum_deposit, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Spreads</td>
                            <td>{{ $broker1->spreads }}</td>
                            <td>{{ $broker2->spreads }}</td>
                        </tr>
                        <tr>
                            <td>Leverage</td>
                            <td>{{ $broker1->leverage }}</td>
                            <td>{{ $broker2->leverage }}</td>
                        </tr>
                        <!-- Customer Support -->
                        <tr>
                            <td colspan="4" class="heading-tage">Customer Support</td>
                        </tr>
                        <tr>
                            <td>Customer Support</td>
                            <td>{{ strip_tags($broker1->customer_support) }}</td>
                            <td>{{ strip_tags($broker2->customer_support) }}</td>
                        </tr>
                        <tr>
                            <td>Languages Supported</td>
                            <td>{{ strip_tags($broker1->languages) }}</td>
                            <td>{{ strip_tags($broker2->languages) }}</td>
                        </tr>
                        <!-- Payment Methods -->
                        <tr>
                            <td colspan="4" class="heading-tage">Payment Methods</td>
                        </tr>
                        <tr>
                            <td>Withdrawal Methods</td>
                            <td>{{ strip_tags($broker1->withdrawal_method) }}</td>
                            <td>{{ strip_tags($broker2->withdrawal_method) }}</td>
                        </tr>
                        <tr>
                            <td>Deposit Methods</td>
                            <td>{{ strip_tags($broker1->deposit_methods) }}</td>
                            <td>{{ strip_tags($broker2->deposit_methods) }}</td>
                        </tr>
                        <!-- Account Types & Mobile -->
                        <tr>
                            <td colspan="4" class="heading-tage">Account Types & Mobile</td>
                        </tr>
                        <tr>
                            <td>Account Types</td>
                            <td>{{ $broker1->account_types }}</td>
                            <td>{{ $broker2->account_types }}</td>
                        </tr>
                        <tr>
                            <td>Mobile Trading</td>
                            <td>{{ $broker1->mobile_trading ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->mobile_trading ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <!-- Additional Trading Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Additional Trading Features</td>
                        </tr>
                        <tr>
                            <td>Social Trading</td>
                            <td>{{ $broker1->social_trading ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->social_trading ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td>Research Tools</td>
                            <td>{{ $broker1->research_tools ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->research_tools ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td>Economic Calendar</td>
                            <td>{{ $broker1->economic_calendar ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->economic_calendar ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <!-- Ratings & Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Ratings & Features</td>
                        </tr>
                        <tr>
                            <td>Rating</td>
                            <td>{{ $broker1->rating }}/5</td>
                            <td>{{ $broker2->rating }}/5</td>
                        </tr>
                        <tr>
                            <td>Key Features</td>
                            <td>{{ strip_tags($broker1->top_feature) }}</td>
                            <td>{{ strip_tags($broker2->top_feature) }}</td>
                        </tr>
                        <!-- Location & Web Features -->
                        <tr>
                            <td colspan="4" class="heading-tage">Location & Web Features</td>
                        </tr>
                        <tr>
                            <td>Country</td>
                            <td>{{ strip_tags($broker1->country) }}</td>
                            <td>{{ strip_tags($broker2->country) }}</td>
                        </tr>
                        <tr>
                            <td>Web Trading</td>
                            <td>{{ strip_tags($broker1->web_trader) }}</td>
                            <td>{{ strip_tags($broker2->web_trader) }}</td>
                        </tr>
                        <!-- Additional Services -->
                        <tr>
                            <td colspan="4" class="heading-tage">Additional Services</td>
                        </tr>
                        <tr>
                            <td>Account Manager</td>
                            <td>{{ $broker1->account_managers == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->account_managers == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td>VPS Hosting</td>
                            <td>{{ $broker1->vps_hosting == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->vps_hosting == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                        <tr>
                            <td>Segregation Of Funds</td>
                            <td>{{ $broker1->segregation_of_funds == 1 ? 'Available' : 'Not Available' }}</td>
                            <td>{{ $broker2->segregation_of_funds == 1 ? 'Available' : 'Not Available' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>
@endsection