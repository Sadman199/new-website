@extends('front.layout.app')

@section('title', 'Broker Alternatives ' . date('Y') . ' | BrokersCourt')
@section('meta_description', 'Compare live alternatives to popular forex brokers. See regulation, costs, platforms, and account terms from the BrokersCourt catalog before you switch.')
@section('canonical', route('broker.alternatives.index'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-alternatives.css') }}?v=4">
@endpush

@section('main_content')
<div class="bal-page">
    <header class="bal-hero">
        <div class="container">
            <nav class="bal-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Broker alternatives</span>
            </nav>
            <p class="bal-hero__eyebrow">Independent research</p>
            <h1 class="bal-hero__title">Broker <span>alternatives</span></h1>
            <p class="bal-hero__lead">Not sure a broker is the right fit? These pages compare live BrokersCourt data — regulation, deposits, spreads, platforms, and account types — against similar brokers in our catalog.</p>
            <ul class="bal-hero__pills">
                <li>Live catalog data</li>
                <li>Side-by-side facts</li>
                <li>Links to full reviews</li>
            </ul>
        </div>
    </header>

    <section class="bal-section" aria-labelledby="bal-index-title">
        <div class="container">
            <header class="bal-section__head">
                <h2 id="bal-index-title">Brokers with an alternatives page</h2>
                <p>Each listing is enabled in the catalog. Open a page to see curated or automatically scored alternatives from the same database.</p>
            </header>

            @if($pages->isEmpty())
                <div class="bal-empty">
                    <p>No broker alternative pages are published yet. Browse independent reviews or build your own comparison in the meantime.</p>
                    <div class="bal-empty__actions">
                        <a href="{{ route('broker.reviews.index') }}" class="bc-btn bc-btn--primary">Browse broker reviews</a>
                        <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--ghost">Compare brokers</a>
                    </div>
                </div>
            @else
                <div class="bal-source-grid">
                    @foreach($pages as $page)
                        @php $broker = $page->broker; @endphp
                        <a href="{{ route('broker.alternatives.show', ['slug' => $broker->slug]) }}" class="bal-source-card">
                            <span class="bal-source-card__logo">
                                @if($broker->logo)
                                    <img src="{{ asset($broker->logo) }}" alt="" loading="lazy" decoding="async">
                                @else
                                    <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                @endif
                            </span>
                            <span class="bal-source-card__body">
                                <strong>{{ $broker->name }}</strong>
                                <span>Best alternatives</span>
                            </span>
                            <span class="bal-source-card__cta">View alternatives</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
