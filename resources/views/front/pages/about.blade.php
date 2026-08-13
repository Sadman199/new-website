@extends('front.layout.app')

@section('title', 'About Us | BrokersCourt')
@section('meta_description', 'Learn about BrokersCourt — our mission to provide independent, unbiased forex broker reviews and help traders make informed decisions.')
@section('canonical', route('about'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/about-page.css') }}?v=1">
@endpush

@section('main_content')
<section class="abt-page">
    <div class="container abt-wrap">
        <header class="abt-hero">
            <h1 class="abt-hero__title">About Us</h1>
            <p class="abt-hero__meta">Last updated: May 19, 2025</p>
        </header>

        <div class="abt-card">
            <div class="abt-card__head">
                <svg xmlns="http://www.w3.org/2000/svg" class="abt-card__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h2 class="abt-card__title">Our Mission</h2>
            </div>
            <p class="abt-card__text">
                At BrokersCourt, we're dedicated to empowering traders worldwide by providing clear, unbiased, and comprehensive reviews of forex brokers. Our mission is to equip traders—whether beginners or professionals—with trusted insights, helping them navigate the complexities of the forex market with confidence.
            </p>
        </div>

        <div class="abt-card">
            <div class="abt-card__head">
                <svg xmlns="http://www.w3.org/2000/svg" class="abt-card__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="abt-card__title">Who We Are</h2>
            </div>
            <p class="abt-card__text">
                BrokersCourt was founded by a passionate team of forex traders, financial analysts, and market strategists who understand the importance of reliable information in making smart trading decisions. We believe transparency and accuracy are key to helping traders succeed.
            </p>
            <p class="abt-card__text">
                Our experts combine years of industry experience with deep market knowledge to provide detailed evaluations of brokers, focusing on their reliability, trading platforms, fees, and customer service. We are committed to maintaining an independent voice in the forex community.
            </p>
        </div>

        <div class="abt-card">
            <div class="abt-card__head">
                <svg xmlns="http://www.w3.org/2000/svg" class="abt-card__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h2 class="abt-card__title">What We Do</h2>
            </div>
            <p class="abt-card__text abt-card__text--lead">
                We rigorously evaluate forex brokers against a wide range of criteria to ensure our users have access to trustworthy and up-to-date information. Our analysis covers:
            </p>
            <div class="abt-grid">
                <div class="abt-grid__item">
                    <h3 class="abt-grid__title">Regulation &amp; Security</h3>
                    <p class="abt-grid__text">We verify brokers’ licensing and compliance with financial authorities, alongside advanced security protocols to safeguard client funds and data privacy.</p>
                </div>
                <div class="abt-grid__item">
                    <h3 class="abt-grid__title">Trading Conditions</h3>
                    <p class="abt-grid__text">Detailed scrutiny of spreads, commissions, leverage options, slippage, and execution speeds to find the best trading environment.</p>
                </div>
                <div class="abt-grid__item">
                    <h3 class="abt-grid__title">Platforms &amp; Tools</h3>
                    <p class="abt-grid__text">Assessment of user-friendly trading platforms, charting capabilities, mobile apps, and the availability of automated trading tools.</p>
                </div>
                <div class="abt-grid__item">
                    <h3 class="abt-grid__title">Customer Support</h3>
                    <p class="abt-grid__text">We test broker support responsiveness, multilingual service availability, and helpfulness across multiple contact methods.</p>
                </div>
            </div>
        </div>

        <div class="abt-card">
            <div class="abt-card__head">
                <svg xmlns="http://www.w3.org/2000/svg" class="abt-card__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <h2 class="abt-card__title">Why Choose BrokersCourt</h2>
            </div>
            <ul class="abt-list">
                <li class="abt-list__item">
                    <h3 class="abt-list__title">Unbiased Reviews</h3>
                    <p class="abt-list__text">We uphold total independence and never accept payments that could influence our ratings, ensuring our reviews remain honest and trustworthy.</p>
                </li>
                <li class="abt-list__item">
                    <h3 class="abt-list__title">Comprehensive Analysis</h3>
                    <p class="abt-list__text">Our 50+ point evaluation framework covers every essential aspect of trading with a broker, from fees to platform usability and customer service quality.</p>
                </li>
                <li class="abt-list__item">
                    <h3 class="abt-list__title">Real Trading Tests</h3>
                    <p class="abt-list__text">We conduct live account testing under actual market conditions to verify broker claims, execution quality, and service reliability.</p>
                </li>
                <li class="abt-list__item">
                    <h3 class="abt-list__title">Updated Regularly</h3>
                    <p class="abt-list__text">The forex market is dynamic, so we continuously review and update our content to reflect the latest broker changes and market trends.</p>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection
