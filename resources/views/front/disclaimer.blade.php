@extends('front.layout.app')
@section('title', 'Disclaimer | BrokersCourt')
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_disclaimer">
        <div class="container">
            <div class="row">
                <div class="hero-content">
                    <div class="col-md-8">
                        <h2 class="b_c_h">Disclaimer: Transparency & Responsibility</h2>
                        <p class="h_s_dec">Brokers Court provides information for general purposes only and does not offer financial advice. Verify details with brokers before making decisions.</p>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_data->disclaimer_title }}
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
                <h2 class="section_title">Brokers Court: Informational Purposes Only</h2>
                <p class="t_c_text">At Brokers Court, we aim to provide accurate and up-to-date information about brokers and financial services. However, the content on our website is for informational purposes only and should not be considered financial or investment advice.</p>

                <h2 class="section_title">No Financial Advice</h2>
                <p class="t_c_text">The reviews, promotions, and broker details presented here are based on publicly available data and our own analysis. Always consult a professional financial advisor or conduct thorough research before making any investment or trading decisions.</p>

                <h2 class="section_title">Accuracy of Information</h2>
                <p class="t_c_text">While we strive for accuracy, broker policies, terms, and conditions may change over time. We recommend confirming details directly with the broker's official website.</p>

                <h2 class="section_title">Affiliate Partnerships</h2>
                <p class="t_c_text">Brokers Court may earn affiliate commissions from some brokers listed on our platform. This does not affect our reviews or recommendations, which are based on honest and unbiased assessments.</p>

                <h2 class="section_title">Risk Warning</h2>
                <p class="t_c_text">Trading forex, CFDs, or other financial instruments carries significant risks and may not be suitable for all investors. Please ensure you fully understand the risks involved before trading.</p>
            </div>
        </div>
    </div>
</div>
@endsection