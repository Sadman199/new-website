@extends('front.layout.app')
@section('title', 'Our Methodology | BrokersCourt')
@section('meta_description', 'Learn how BrokersCourt evaluates forex brokers using transparent research, data analysis, and expert validation.')
@section('canonical', route('methodology'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/legal-page.css') }}?v=1">
@endpush

@section('main_content')
<div class="legal-page">
    <div class="legal-page__glow" aria-hidden="true"></div>
    <div class="container legal-page__inner">
        <header class="legal-page__head">
            <h1 class="legal-page__title">Our Methodology</h1>
            <p class="legal-page__meta">Transparent, unbiased broker evaluation you can trust.</p>
        </header>

        <div class="row g-4">
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <h2 class="legal-card__title legal-card__title--gold">Data Collection</h2>
                    <p class="legal-card__text">We gather broker data across regulation, fees, platforms, account types, support quality, and real trader feedback.</p>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <h2 class="legal-card__title legal-card__title--gold">Hands-On Testing</h2>
                    <p class="legal-card__text">Our team tests account opening, execution, deposits, withdrawals, and platform usability wherever possible.</p>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <h2 class="legal-card__title legal-card__title--gold">Scoring Framework</h2>
                    <p class="legal-card__text">Brokers are scored using weighted criteria including safety, costs, trading conditions, tools, and customer experience.</p>
                </article>
            </div>
            <div class="col-12 col-md-6">
                <article class="legal-card">
                    <h2 class="legal-card__title legal-card__title--gold">Expert Validation</h2>
                    <p class="legal-card__text">Findings are reviewed by industry experts before publication to ensure accuracy and fairness.</p>
                </article>
            </div>
        </div>

        <div class="legal-page__cta">
            <a href="{{ route('awards.index') }}" class="legal-cta">View Broker Awards</a>
        </div>
    </div>
</div>
@endsection
