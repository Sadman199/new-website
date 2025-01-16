@extends('front.layout.app')
@section('title', 'BrokersCourt | Terms and Conditions for Using Our Platform')
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_trams">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="hero-content">
                        <h2 class="b_c_h">{{ $page_data->terms_title }}</h2>

                        <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page"> {{ $page_data->terms_title }}</li>
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
                <h2 class="section_title">Introduction</h2>
                <p class="t_c_text">Welcome to brokerscourt.com. By accessing and using this website, you agree to comply with and be bound by the following terms and conditions. If you do not agree with any part of these terms, please refrain from using our site.</p>

                <h2 class="section_title">General Information</h2>
                <p class="t_c_text">BrokersCourt is a platform designed to provide reviews, ratings, and promotions for forex brokers. The information on this site is provided solely for informational purposes and does not constitute financial advice.</p>

                <h2 class="section_title">Use of the Website</h2>
                <p class="t_c_text">You may use brokerscourt.com for personal, non-commercial purposes only.
                You agree not to engage in activities that may harm the website, its users, or any third-party services.</p>

                <h2 class="section_title">Accuracy of Information</h2>
                <p class="t_c_text">We strive to provide accurate and up-to-date information on all brokers and promotions. However, we do not guarantee the completeness or accuracy of any information, and we are not responsible for any errors or omissions.</p>

                <h2 class="section_title">Affiliate Links and Promotions</h2>
                <p class="t_c_text">BrokersCourt may feature affiliate links to forex brokers. This means that we may earn a commission if you sign up or make a transaction through these links. However, our reviews and promotions are independent, and we provide them based on our research and analysis.</p>

                <h2 class="section_title">User Responsibility</h2>
                <p class="t_c_text">By using this website, you understand that you are solely responsible for any actions you take based on the information provided. BrokersCourt is not liable for any financial losses, damages, or other consequences resulting from the use of the information provided on this site.</p>

                <h2 class="section_title">Intellectual Property</h2>
                <p class="t_c_text">All content on brokerscourt.com, including but not limited to text, images, logos, and designs, is the property of BrokersCourt or its content providers. You may not reproduce, modify, or distribute any part of this content without our prior written permission.</p>

                <h2 class="section_title">Contact Information</h2>
                <p class="t_c_text">For any questions or concerns regarding these Terms and Conditions, please contact us at:</p>
                <ul>
                    <li>Email: support@brokerscourt.com</li>
                     <li>Phone: [Insert phone number]</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection