@extends('front.layout.app')

@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_broker_detail">
        <div class="container">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-lg-7 col-md-12">
                   <div class="hero-content">
                   <h2 class="b_c_h"><span>{{ $broker->name }}</span> 2024 Review: Key Features & Insights</h2>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $broker->name }} Review</li>
                            </ol>
                        </nav>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content s_padding">
   <section class="broker_details_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="sticky-panel">
                        <div class="row">
                            <!-- Broker Logo and Rating Section -->
                            <div class="col-lg-3 col-md-6 border-right">
                                <div class="d-flex justify-content-start align-items-center h-100">
                                    <div class="b_d_image_box">
                                        @if ($broker->logo)
                                        <img src="{{ asset($broker->logo) }}" alt="Broker Logo"
                                            class="img-fluid">
                                        @else
                                        <p>No logo available.</p>
                                        @endif
                                        <div class="b_o_live_btn_wrapper">
                                            <a href="{!! $broker->open_live !!}" target="_blank" rel="noopener noreferrer">
                                                <button class="site_button b_o_live_btn">Open Live</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pros and Cons Section -->
                            <div class="col-lg-6 col-md-6 border-right">
                                <div class="">
                                    <div class="p_c_box">
                                        <span>Pros:</span>
                                        <ul class="all_p">
                                            @foreach(explode('</li>', $broker->pros) as $pro)
                                            @if(trim(strip_tags($pro)))
                                            <!-- Ensure non-empty strings are processed -->
                                            <li class="b_list">
                                                <i class="fa fa-check-circle" style="color: green; margin-right: 8px;"></i>
                                                {{ strip_tags($pro) }}
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        <span>Cons:</span>
                                        <ul>
                                            @foreach(explode('</li>', $broker->cons) as $con)
                                            @if(trim(strip_tags($con)))
                                            <li class="b_list">
                                                <i class="fa fa-times-circle" style="color: red; margin-right: 8px;"></i>
                                                {{ strip_tags($con) }}
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="">
                                    <div class="bottom_attributes">
                                        <div class="bt_c">
                                            <div class="regulation_h">
                                                <strong> Regulation :</strong>
                                                <span>{{ strip_tags($broker->regulation) }}</span>
                                            </div>
                                        </div>
                                        <div class="bt_c">
                                            <div class="regulation_h">
                                                <strong>Country :</strong>
                                                <span>{{ strip_tags($broker->country) }}</span>
                                            </div>
                                        </div>
                                        <div class="bt_c">
                                            <div class="regulation_h">
                                               <strong> Minimum Deposit :</strong>
                                               <span>${{ strip_tags($broker->minimum_deposit) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div class="broker_left_detail s_padding">
                        <div class="broker_s_d">
                            <h2>Trade Securely with {{ $broker->name }}: Forex, CFDs & Crypto</h2>
                            <p class="t_d"> {{ strip_tags($broker->short_description) }}</p>
                        </div>
                        <div class="review_table">
                            <!-- Broker Details (Basic Information) -->
                            <h3 class="r_v_h">{{ $broker->name }} Overview</h3>
                            <p class="t_d">This section provides key details about {{ $broker->name }}, including supported languages,
                                pricing, deposit and withdrawal methods, and regulatory compliance. These insights help
                                traders assess the broker's credibility, understand their offerings, and make informed
                                decisions. We also cover the broker's country of operation and regulated jurisdictions for
                                added transparency.</p>

                            <div class="custom-table-responsive">
                                <table class="comparison_table">
                                    <tbody>
                                        <tr>
                                            <th>Languages:</th>
                                            <td>{{ strip_tags($broker->languages) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pricing:</th>
                                            <td>{{ strip_tags($broker->pricing) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Deposit Methods:</th>
                                            <td>{{ strip_tags($broker->deposit_methods) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Withdrawal Methods:</th>
                                            <td>{{ strip_tags($broker->withdrawal_method) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{ strip_tags($broker->country) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Regulation:</th>
                                            <td>{{ strip_tags($broker->regulation) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Regulated Jurisdictions:</th>
                                            <td>{{ strip_tags($broker->regulated_jurisdictions) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Regulatory Licenses:</th>
                                            <td>{{ strip_tags($broker->regulatory_licenses) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <!-- Trading Features -->
                            <h3 class="r_v_h">{{ $broker->name }} Trading Capabilities</h3>
                            <p class="t_d">This section highlights key trading features of {{ $broker->name }}, including minimum
                                deposit, platforms, leverage, spreads, payment methods, and customer support. It also covers
                                advanced tools like charting and social trading, helping traders assess the services that
                                support their trading activities.</p>

                            <div class="custom-table-responsive">
                                <table class="comparison_table">
                                    <tbody>
                                        <tr>
                                            <th>Minimum Deposit:</th>
                                            <td>{{ strip_tags($broker->minimum_deposit) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Spreads:</th>
                                            <td>{{ strip_tags($broker->spreads) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Leverage:</th>
                                            <td>{{ strip_tags($broker->leverage) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Platforms:</th>
                                            <td>{{ strip_tags($broker->platforms) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Methods:</th>
                                            <td>{{ strip_tags($broker->payment_methods) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer Support:</th>
                                            <td>{{ strip_tags($broker->customer_support) }}</td>
                                        </tr>

                                        <tr>
                                            <th>Mobile Trading:</th>
                                            <td>{{ $broker->mobile_trading ? 'Available' : 'Not Available' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Social Trading:</th>
                                            <td>{{ $broker->social_trading ? 'Available' : 'Not Available' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Charting Tools:</th>
                                            <td>{{ strip_tags($broker->charting_tools) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Account Information & Miscellaneous -->
                            <h3 class="r_v_h">{{ $broker->name }} Account and Services</h3>
                            <p class="t_d">This section covers the types of accounts offered by {{ $broker->name }}, including live and
                                demo options, along with key services like educational resources, research tools, and mobile
                                trading, all designed to support traders' success.</p>

                            <div class="custom-table-responsive">
                                <table class="comparison_table">
                                    <tbody>
                                        <tr>
                                            <th>Account Types:</th>
                                            <td>{{ $broker->account_types ? (is_array($accountTypes = json_decode($broker->account_types)) ? implode(', ', $accountTypes) : $broker->account_types) : '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Capitalization:</th>
                                            <td>{{ strip_tags($broker->capitalization) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Insurance:</th>
                                            <td>{{ strip_tags($broker->insurance) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Segregation of Funds:</th>
                                            <td>{{ $broker->segregation_of_funds ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Web Trader:</th>
                                            <td>{{ strip_tags($broker->web_trader) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Account Managers:</th>
                                            <td>
                                                {{ $broker->account_managers == 1 ? 'Available' : 'Not Available' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Economic Calendar:</th>
                                            <td>
                                                {{ $broker->economic_calendar == 1 ? 'Available' : 'Not Available' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VPS Hosting:</th>
                                            <td>{{ $broker->vps_hosting ? 'Available' : 'Not Available' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Associated Countries:</th>
                                            <td>{{ implode(', ', array_map('strip_tags', $broker->associated_countries)) }}
                                            </td>
                                        </tr>


                                        <tr>
                                            <th>Featured Broker:</th>
                                            <td>{{ $broker->featured_broker ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Top Broker:</th>
                                            <td>{{ $broker->top_broker ? 'Yes' : 'No' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3 class="r_v_h"><span>{{ $broker->name }}</span> News and Analysis</h3>
                            <p class="t_d">{{ strip_tags($broker->news_and_analysis) }}</p>
                            <h3 class="r_v_h"><span>{{ $broker->name }}</span> Top Feature</h3>
                            <p class="t_d">{{ strip_tags($broker->top_feature) }}</p>
                            <h3 class="r_v_h"><span>{{ $broker->name }}</span> Research Tools</h3>
                            <p class="t_d">{{ strip_tags($broker->research_tools) }}</p>

                            <h3 class="r_v_h"><span>{{ $broker->name }}</span> Educational Resources</h3>
                            <p class="t_d">{{ strip_tags($broker->educational_resources) }}</p>
                        </div>

                        <div class="account_option_field">
                            <h3 class="r_v_h">Available Account Structures at {{ $broker->name }}</h3>
                            <div class="account_option_table">
                                <table class="eael-data-table">
                                    <thead>
                                        <tr>
                                            <th>Account Structure</th>
                                            <th>Account Currency</th>
                                            <th>Min Deposit</th>
                                            <th>Max Leverage</th>
                                            <th>Spread Type</th>
                                            <th>Spread Value</th>
                                            <th>Demo Available</th>
                                            <th>Features</th>
                                            <th>Swap Free</th>
                                            <th>Min Trade Size</th>
                                            <th>Max Trade Size</th>
                                            <th>Interest Rate</th>
                                            <th>access_to_pro_features</th>
                                            <th>exclusive_offers</th>
                                            <th>Account Management</th>
                                            <th>Trading Instruments</th>
                                            <th>Margin Call Level</th>
                                            <th>Risk Management Tools</th>
                                            <th>Bonus Eligibility</th>
                                            <th>Personalized Education</th>
                                            <th>Exclusive Webinars</th>
                                            <th>Maximum Daily Trade Volume</th>
                                            <th>Trading Hours</th>
                                            <th>Special Conditions</th>
                                            <th>Stop Out Level</th>
                                            <th>Max Open Positions</th>
                                            <th>Commission</th>
                                            <th>Interest Rate</th>
                                            <th>Regulated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($account_options as $accountOption)
                                            <tr>
                                                <td>{{ $accountOption->account_type }}</td>
                                                <td>{{ $accountOption->account_currency }}</td>
                                                <td>{{ $accountOption->min_deposit }}</td>
                                                <td>{{ $accountOption->max_leverage }}</td>
                                                <td>{{ $accountOption->spread_type }}</td>
                                                <td>{{ $accountOption->spread_value }}</td>
                                                <td>{{ $accountOption->is_demo_available ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->features }}</td>
                                                <td>{{ $accountOption->swap_free ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->min_trade_size }}</td>
                                                <td>{{ $accountOption->max_trade_size }}</td>
                                                <td>{{ $accountOption->interest_rate }}</td>
                                                <td>{{ $accountOption->access_to_pro_features ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->exclusive_offers ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->account_management }}</td>
                                                <td>{{ $accountOption->trading_instruments }}</td>
                                                <td>{{ $accountOption->margin_call_level }}</td>
                                                <td>{{ $accountOption->risk_management_tools }}</td>
                                                <td>{{ $accountOption->bonus_eligibility ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->personalized_education ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->exclusive_webinars ? 'Yes' : 'No' }}</td>
                                                <td>{{ $accountOption->maximum_daily_trade_volume }}</td>
                                                <td>{{ $accountOption->trading_hours }}</td>
                                                <td>{{ $accountOption->special_conditions }}</td>
                                                <td>{{ $accountOption->stop_out_level }}</td>
                                                <td>{{ $accountOption->max_open_positions }}</td>
                                                <td>{{ $accountOption->commission ?? 'N/A' }}</td>
                                                <td>{{ $accountOption->interest_rate ?? 'N/A' }}</td>
                                                <td>{{ $accountOption->is_regulated ? 'Yes' : 'No' }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="faq-section">
                            <h3 class="r_v_h">Frequently Asked Questions</h3>
                            @if($faqs->isNotEmpty())
                                <div class="accordion">
                                    @foreach($faqs as $faq)
                                        <div class="accordion__item">
                                            <div class="accordion__header" data-toggle="#faq{{ $faq->id }}">
                                                {{ $faq->faq_title }}
                                            </div>
                                            <div class="accordion__content" id="faq{{ $faq->id }}">
                                                <p class="f_content">{{ strip_tags($faq->faq_detail) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>No FAQs available for this broker.</p>
                            @endif
                        </div>
                           
                        <div class="conclusion_details">
                            <h3 class="r_v_h">Conclusion:</h3>
                            <p>
                                {{ $broker->name }} is a forex broker operating from {{ $broker->country }}{{ $broker->country == 'Cyprus' ? ' (Headquarters), operates globally' : '' }}. 
                                and offers various account types 
                                {{ $broker->account_types ? 'such as ' . (is_array($accountTypes = json_decode($broker->account_types)) ? implode(', ', $accountTypes) : $broker->account_types) : '' }}. 
                                The broker provides access to trading platforms 
                                {{ $broker->platforms ? 'like ' . (is_array($platforms = json_decode($broker->platforms)) ? implode(', ', $platforms) : strip_tags($broker->platforms)) : '' }} 
                                and supports payment methods 
                                {{ $broker->payment_methods ? 'including ' . (is_array($paymentMethods = json_decode($broker->payment_methods)) ? implode(', ', $paymentMethods) : strip_tags($broker->payment_methods)) : '' }}. 
                                With a minimum deposit of {{ $broker->minimum_deposit ?? 'an affordable amount' }}, 
                                traders can enjoy spreads starting from {{ $broker->spreads ?? 'competitive rates' }} 
                                and leverage of up to {{ $broker->leverage ?? 'industry-standard levels' }}.
                            </p>


                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12">
                    <div class="broker-sticky-container s_padding">
                        <div class="b_s_box">
                            @if ($broker->logo)
                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} Broker Logo" />
                            @else
                            <p>No logo available.</p>
                            @endif
                            <div class="b_s_box_content">
                                <div class="rating_wrapper_b_d">
                                    <div class="rating">
                                        <?php
                                        $rating = $broker->rating;
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                // Full star
                                                echo '<span class="star filled">&#9733;</span>';
                                            } elseif ($i - 0.5 == $rating) {
                                                // Half star
                                                echo '<span class="star half">&#9733;</span>';
                                            } else {
                                                // Empty star
                                                echo '<span class="star">&#9734;</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <span class="l_rating">Overall {{ $broker->rating }}</span>
                                </div>

                                <div class="b_s_b_bton_wrapper">
                                    <a href="{!! $broker->url !!}" target="_blank" rel="noopener noreferrer">
                                        <button class="site_button btn btn-primary w-100 mb-2">Visit Site</button>
                                    </a>
                                    <a href="{!! $broker->open_demo !!}" target="_blank" rel="noopener noreferrer">
                                        <button class="site_button btn btn-secondary w-100">Open Demo</button>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="side_b_r_wrapper">
                            <div class="latest_brokers">
                                <span class="l_b_h">Latest Brokers</span>
                                <ul>
                                @foreach ($brokers as $otherBroker)
                                <a href="{{ route('broker_detail', ['slug' => $otherBroker->slug]) }}"
                                        class="broker-side-card-link">
                                        <li class="broker_side_card">
                                            <div class="b_c_c_content">
                                                @if ($otherBroker->logo)
                                                <img src="{{ asset($otherBroker->logo) }}" alt="{{ $otherBroker->name }} Broker Logo" />
                                                @else
                                                <p>No logo available.</p>
                                                @endif
                                                <span>{{ $otherBroker->name }} Review 2024</span>
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
            <div class="row">
                <!-- Review Modal -->
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content b_model">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Write a Review for {{ $broker->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                         
                            <div class="modal-body">
                                @php
                                    // Clear session if timeout has passed
                                    if (session('review_timeout_' . $broker->id) && now()->greaterThan(session('review_timeout_' . $broker->id))) {
                                        session()->forget(['review_submitted_' . $broker->id, 'review_timeout_' . $broker->id]);
                                    }
                                @endphp

                                @if(session('review_submitted_' . $broker->id))
                                    <!-- Message for already submitted reviews for this specific broker -->
                                    <div class="alert alert-info">
                                        You have already submitted a review for this broker. Thank you!
                                    </div>
                                @else
                                    <!-- Review Form -->
                                    <form action="{{ route('reviews.store', $broker->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="broker_id" value="{{ $broker->id }}">

                                        <!-- Name Field -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="name" class="form-control b_control" placeholder="Your Name" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Email Field -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="email" name="email" class="form-control b_control" placeholder="Your Email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Country Field -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="country" class="form-control b_control" placeholder="Your Country" value="{{ old('country') }}" required>
                                                    @error('country')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Rating Field -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Rating:</label>
                                                    <div class="rating">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                                            <label for="star{{ $i }}">&#9733;</label>
                                                        @endfor
                                                    </div>
                                                    @error('rating')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Review Description -->
                                        <div class="form-group mb-3">
                                            <textarea name="description" class="form-control b_control" rows="4" placeholder="Your Review" required>{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="m_sbutton">
                                            <button type="submit" class="compare-card-link">Submit Review</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </section>


   <section class="testimonial_review">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="review_heading_wrapper">
                        <h2 class="r_b_h"><span class="b_s">{{ $broker->name }}</span> <strong class="rbh_highlight">Reviews</strong></h2>
                        <button id="openReviewForm" class="compare-card-link" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            <i class="fas fa-edit me-2"></i> Write a Review
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="testimonial-slider-wrapper">
                        
                        @if($approved_reviews->isEmpty())
                            <div class="alert alert-warning text-center">
                            No reviews available for this broker yet.
                            </div>
                        @else
                            <div class="owl-carousel testimonial-slider">
                            @foreach ($approved_reviews as $review)
                                <div class="testimonial-item text-center">
                                <!-- Avatar -->
                                <div class="testimonial-avatar text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" 
                                    style="background-color: #7f8c8d; width: 70px; height: 70px; font-size: 20px;">
                                    {{ strtoupper(substr($review->email, 0, 2)) }}
                                </div>

                                <!-- Review Meta -->
                                <h6 class="testimonial-name">{{ $review->name }}</h6>
                                <small class="t_date">{{ $review->formatted_date }}</small>

                                <!-- Star Rating -->
                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                    @endfor
                                </div>

                                <!-- Review Description -->
                                <p class="testimonial-text">"{{ $review->description }}"</p>
                                </div>
                            @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="compare-brokers-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="compare-title">
                        Compare <span class="broker-highlight">{{ $broker->name }}</span> with Other Brokers
                    </h2>
                    <p class="compare-description">Discover how {{ $broker->name }} stacks up against other top brokers. Click below to explore detailed comparisons.</p>
                </div>

                <div class="col-md-12">
                    <div class="compare-card-grid">
                        @foreach($compare_brokers as $compare_broker)
                            <div class="compare-card">
                                <h3 class="compare-card-title">{{ $compare_broker->name }}</h3>
                                <a class="compare-card-link" href="{{ route('compare', [$broker->slug, $compare_broker->slug]) }}">
                                    Compare Now
                                </a>
                            </div>
                        @endforeach
                    </div>
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
        $(document).ready(function () {
        handleAdBannersForAllPages('.breadcrumb_wrapper_by_broker_detail', {
            offset: 200, // Adjust as needed
            fadeDuration: 400,
            slideDuration: 600,
        });
        });
    </script>
</div>


@endsection