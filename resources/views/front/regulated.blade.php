@extends('front.layout.app')
@section('main_content')
<div class="page-top">
    <div class="breadcrumb_wrapper_by_sub">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="hero-content">
                    <h2 class="b_c_h"></h2>

                    <nav class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ HOME }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    </li>
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
        <h1>Regulated Brokers</h1>
        <div class="row">
            @foreach($regulatedBrokers as $broker)
            <div class="col-md-4 mb-4">
                <div class="broker-layer">
                    <div class="broker-background" style="background-image: url('{{ asset($broker->logo ?? 'default-logo.png') }}');"></div>
                    <div class="broker-content">
                        <div class="broker-header">
                            <a href="{{ $broker->url }}"><h4>{{ $broker->name }}</h4></a>
                            <p class="broker-leverage"><strong>Leverage:</strong> {{ $broker->leverage }}</p>
                        </div>
                        <div class="broker-footer">
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
                            <a href="{{ route('broker_detail', ['slug' => $broker->slug]) }}" class="review-button">Read Review</a>
                        </div>
                    </div>
                </div>
            </div> 
            @endforeach
        </div>
    </div>
</div>
@endsection