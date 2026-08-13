@extends('front.layout.app')
@section('title', 'BrokersCourt | Terms and Conditions for Using Our Platform')
@section('canonical', route('terms'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/legal-page.css') }}?v=1">
@endpush

@section('main_content')
<div class="legal-page">
    <div class="legal-page__glow" aria-hidden="true"></div>
    <div class="container legal-page__inner">
        <header class="legal-page__head">
            <h1 class="legal-page__title">Brokers Court – Terms of Service</h1>
            <p class="legal-page__meta">Last Updated: May 19, 2025</p>
        </header>

        <article class="legal-card mb-4">
            <div class="legal-card__body">
                <i class="fas fa-file-alt legal-card__icon legal-card__icon--blue" aria-hidden="true"></i>
                <div>
                    <h2 class="legal-card__title legal-card__title--blue">Introduction</h2>
                    <p class="legal-card__text">Welcome to Brokers Court, your premier destination for comprehensive forex broker reviews and comparisons. By accessing or using our website, you agree to comply with and be bound by these Terms of Service. Please read them carefully before using our services.</p>
                </div>
            </div>
        </article>

        <div class="row g-4">
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-shield-alt legal-card__icon legal-card__icon--cyan" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--cyan">1. Acceptance of Terms</h2>
                            <p class="legal-card__text">Your access to and use of Brokers Court is conditioned on your acceptance of and compliance with these Terms. These Terms apply to all visitors, users, and others who access or use the service.</p>
                            <p class="legal-card__text">By accessing or using Brokers Court, you agree to be bound by these Terms. If you disagree with any part of the terms, you may not access the service.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-file-contract legal-card__icon legal-card__icon--emerald" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--emerald">2. Broker Information &amp; Financial Disclaimer</h2>
                            <p class="legal-card__text">All broker reviews, ratings, and comparisons provided on Brokers Court are for informational purposes only. We do not provide financial advice, nor do we guarantee the accuracy or completeness of any information on our site.</p>
                            <p class="legal-card__text">Forex trading involves substantial risk of loss and is not suitable for all investors. Past performance is not indicative of future results. You alone assume the sole responsibility of evaluating the merits and risks associated with the use of any information provided before making any decisions.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-user-shield legal-card__icon legal-card__icon--purple" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--purple">3. User Responsibilities</h2>
                            <ul class="legal-card__list">
                                <li>You must be at least 18 years old to use this service</li>
                                <li>You agree not to use Brokers Court for any unlawful purpose</li>
                                <li>You are responsible for maintaining the confidentiality of any account information</li>
                                <li>You agree not to disrupt or interfere with the security or accessibility of our site</li>
                                <li>You will not engage in data mining or similar data gathering activities</li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-shield-virus legal-card__icon legal-card__icon--amber" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--amber">4. Intellectual Property</h2>
                            <p class="legal-card__text">The Brokers Court name, logo, and all related names, logos, product and service names, designs, and slogans are trademarks of Brokers Court or its affiliates or licensors. You must not use such marks without our prior written permission.</p>
                            <p class="legal-card__text">All content on this site, including text, graphics, logos, and images, is our property or the property of our content suppliers and protected by international copyright laws.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-exclamation-triangle legal-card__icon legal-card__icon--red" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--red">5. Limitation of Liability</h2>
                            <p class="legal-card__text">In no event shall Brokers Court, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your access to or use of or inability to access or use the service.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12">
                <article class="legal-card">
                    <div class="legal-card__body">
                        <i class="fas fa-edit legal-card__icon legal-card__icon--green" aria-hidden="true"></i>
                        <div>
                            <h2 class="legal-card__title legal-card__title--green">6. Changes to Terms</h2>
                            <p class="legal-card__text">We reserve the right, at our sole discretion, to modify or replace these Terms at any time. We will provide notice of any changes by posting the new Terms on this page and updating the "Last Updated" date.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection
