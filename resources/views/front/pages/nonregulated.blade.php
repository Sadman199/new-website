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
    <h1>Non-Regulated Brokers</h1>
    <div class="row">
        @foreach($nonRegulatedBrokers as $broker)
            <div class="col-md-4">
                <div class="broker-card tr_broker_card">
                    <img alt="{{ $broker->name }} logo" src="{{ asset('storage/' . $broker->logo) }}" />
                    <div class="broker-info">
                        <div class="b_h_info">
                            <div class="text-container">
                                <span>Trade with</span>
                                <a href="">{{ $broker->name }}</a>
                            </div>
                            <a href="{{ $broker->url }}">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="description-wrapper">
                            <p class="short-description">
                                {{ Str::limit(strip_tags($broker->short_description), 35) }}
                            </p>
                            <i class="info-icon fas fa-info-circle"></i>
                            <span class="full-description">
                                {{ strip_tags($broker->short_description) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


</div>
@endsection