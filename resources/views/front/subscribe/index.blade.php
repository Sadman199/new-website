@extends('front.layout.app')

@section('title', 'Subscribe to the BrokersCourt Briefing')
@section('meta_description', 'Get independent broker research, market insights, trading tools, and important industry updates from BrokersCourt.')
@section('canonical', route('subscribe.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/subscribe.css') }}?v=1">
@endpush

@section('main_content')
<div class="sub-page">
    <header class="sub-hero">
        <div class="container">
            <nav class="sub-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Subscribe</span>
            </nav>

            <div class="sub-layout">
                <div class="sub-copy">
                    <p class="sub-eyebrow"><i class="far fa-envelope" aria-hidden="true"></i> BrokersCourt briefing</p>
                    <h1>Useful broker intelligence, <span>delivered clearly.</span></h1>
                    <p class="sub-lead">Stay informed with independent broker research, practical trading resources, and the market updates that matter.</p>

                    <ul class="sub-benefits">
                        <li>
                            <span><i class="fas fa-balance-scale" aria-hidden="true"></i></span>
                            <div><strong>Independent research</strong><small>Broker analysis shaped by facts, not promotions.</small></div>
                        </li>
                        <li>
                            <span><i class="fas fa-chart-line" aria-hidden="true"></i></span>
                            <div><strong>Actionable insights</strong><small>Clear explainers, comparisons, and market context.</small></div>
                        </li>
                        <li>
                            <span><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                            <div><strong>Safety updates</strong><small>Regulatory changes and scam warnings worth knowing.</small></div>
                        </li>
                    </ul>
                </div>

                <div class="sub-card">
                    <div class="sub-card__icon" aria-hidden="true"><i class="far fa-paper-plane"></i></div>
                    <p class="sub-card__kicker">Free newsletter</p>
                    <h2>Join the briefing</h2>
                    <p>Enter your email and we’ll send a verification link to confirm your subscription.</p>

                    @if(session('success'))
                        <div class="sub-alert sub-alert--success" role="status">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="sub-alert sub-alert--error" role="alert">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('subscribe') }}" method="post" class="sub-form">
                        @csrf
                        <label for="subscriberEmail">Email address</label>
                        <div class="sub-form__field">
                            <i class="far fa-envelope" aria-hidden="true"></i>
                            <input
                                id="subscriberEmail"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                maxlength="254"
                                autocomplete="email"
                                placeholder="you@example.com"
                                @error('email') aria-invalid="true" aria-describedby="subscriberEmailError" @enderror
                            >
                        </div>
                        @error('email')
                            <p class="sub-form__error" id="subscriberEmailError">{{ $message }}</p>
                        @enderror
                        <button type="submit">Subscribe now <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
                    </form>

                    <p class="sub-privacy"><i class="fas fa-lock" aria-hidden="true"></i> No spam. Unsubscribe whenever you like.</p>
                    <p class="sub-legal">By subscribing, you agree to our <a href="{{ route('privacy') }}">Privacy Policy</a>.</p>
                </div>
            </div>
        </div>
    </header>
</div>
@endsection
