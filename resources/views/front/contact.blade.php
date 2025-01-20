@extends('front.layout.app')
@section('title', 'BrokersCourt | Get in Touch with Us')
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_contact">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-7">
                    <div class="hero-content">
                        <h2 class="b_c_h">We're Here to Help!</h2>
                        <p class="h_s_dec">Have questions or need assistance? Reach out to us anytime, and our team will get back to you as soon as possible. We're here to make your experience seamless and stress-free</p>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $page_data->contact_title }}
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
                {!! $page_data->contact_detail !!}
            </div>
            <div class="col-lg-6 col-md-12">
              <form action="{{ route('contact_form_submit') }}" method="post" class="contact-form-wrapper">
                    @csrf
                    <div class="contact-form">
                        <div class="s_c_form">
                            <label for="name" class="s_c_f_lebel">{{ NAME }} :</label>
                            <input type="text" class="c_form" name="name" placeholder="Input Name">
                            <span class="text-danger error-text name_error"></span>
                        </div>
                        <div class="s_c_form">
                            <label for="email" class="s_c_f_lebel">{{ EMAIL_ADDRESS }} :</label>
                            <input type="text" class="c_form" name="email" placeholder="exmaple@gmail.com">
                            <span class="text-danger error-text email_error"></span>
                        </div>
                        <div class="s_c_form">
                            <label for="m" class="s_c_f_lebel">{{ MESSAGE }} :</label>
                            <textarea class="c_form" name="message" rows="3" placeholder="Input Message"></textarea>
                            <span class="text-danger error-text message_error"></span>
                        </div>
                        <div class="s_c_form">
                            <button type="submit" class="compare-card-link">{{ SEND_MESSAGE }}</button>
                        </div>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                    $('.c_form').on('focus', function() {
                        $(this).addClass('clicked');
                    });
                    $('.c_form').on('blur', function() {
                        $(this).removeClass('clicked');
                    });
                });
                </script>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="row">
                    <!-- Office Hours Box -->
                    <div class="col-md-6">
                        <div class="box position-relative">
                            <div class="icon-circle bg-primary  mx-auto">
                                <i class="fas fa-clock fa-2x text-white"></i>
                            </div>
                            <span class="box-title">Office Hours:</span>
                            <p class="b_f_t">9:00 AM - 7:00 PM</strong></p>
                            <p class="o_t">Monday to Friday</p>
                        </div>
                    </div>
                    <!-- Email and WhatsApp Box -->
                     <div class="col-md-6">
                        <div class="box position-relative">
                            <div class="icon-circle bg-primary mx-auto">
                                <i class="fas fa-envelope fa-2x text-white"></i>
                            </div>
                            <span class="box-title">Email Us</span>
                            <p class="b_f_t">Drop us an email anytime!</p>
                            <p><i class="fas fa-envelope text-primary"></i> info@gmail.com</p>
                        </div>
                     </div>
                

                    <!-- WhatsApp Box -->
                    <div class="col-md-6">
                        <div class="box position-relative">
                            <div class="icon-circle bg-success mx-auto">
                                <i class="fab fa-whatsapp fa-2x text-white"></i>
                            </div>
                            <span class="box-title">WhatsApp Us</span>
                            <p class="b_f_t">Reach us quickly on WhatsApp!</p>
                            <p><i class="fab fa-whatsapp text-success"></i> +123-456-7890</p>
                        </div>
                    </div>
                    <!-- YouTube Channel Box -->
                    <div class="col-md-6">
                        <div class="box position-relative">
                            <div class="icon-circle bg-danger  mx-auto">
                                <i class="fab fa-youtube fa-2x text-white"></i>
                            </div>
                            <span class="box-title">Follow Us</span>
                            <p class="b_f_t">Stay connected for updates</p>
                            <p>
                                <a href="https://youtube.com/channel" class="text-danger fw-bold" target="_blank">
                                    Visit our YouTube Channel
                                </a>
                            </p>
                        </div>
                    </div>
                </div>


                <div class='icon_coantainer'>
                    
                    <div class='c_icon facebook'>
                        <i class="fab fa-facebook-f fa-2x"></i>
                    </div>
                    <div class='c_icon twitter'>
                        <i class="fab fa-twitter fa-2x"></i>
                    </div>
            
                    <div class='c_icon instagram'>
                        <i class="fab fa-instagram fa-2x"></i>
                    </div>
                    <div class='c_icon linkedin'>
                        <i class="fab fa-linkedin fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection