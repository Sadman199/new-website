@extends('front.layout.app')

@section('title', $awardName . ' | Broker Awards ' . date('Y') . ' | BrokersCourt')
@section('meta_description', $awardDescription)
@section('canonical', route('awards.show', ['award' => $routeSlug]))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/awards-index.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/broker-reviews-index.css') }}?v=8">
@endpush

@section('main_content')
<div class="awd-page awd-page--show awd-page--{{ $awardColor }}">
    <header class="awd-hero awd-hero--compact">
        <div class="awd-hero__bg" aria-hidden="true"></div>

        <div class="awd-wrap">
            <nav class="awd-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('awards.index') }}">Broker awards</a>
                <span aria-hidden="true">/</span>
                <span>{{ $awardName }}</span>
            </nav>

            <div class="awd-hero__inner awd-hero__inner--show">
                <div class="awd-hero__copy">
                    <p class="awd-hero__eyebrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172"/>
                        </svg>
                        Award category
                    </p>
                    <h1 class="awd-hero__title">{{ $awardName }}</h1>
                    <p class="awd-hero__subtitle">{{ $awardDescription }}</p>
                </div>
            </div>

        </div>
    </header>

    <div class="awd-body">
        <div class="awd-wrap">
            <div class="awd-show">
                <div class="awd-show__head">
                    <h2 class="awd-show__heading">Award-winning brokers</h2>
                    <p class="awd-show__count">
                        Showing {{ $paginatedBrokers->firstItem() ?? 0 }}–{{ $paginatedBrokers->lastItem() ?? 0 }}
                        of {{ $paginatedBrokers->total() }}
                    </p>
                </div>

                @if($brokersPayload !== [])
                    <div class="awd-bri">
                        <ul class="bri-grid awd-show__grid">
                            @foreach($brokersPayload as $broker)
                                @include('front.brokers.partials.reviews_index_card', ['broker' => $broker])
                            @endforeach
                        </ul>
                    </div>

                    @if($paginatedBrokers->hasPages())
                        <nav class="awd-show__pagination" aria-label="Broker pagination">
                            {{ $paginatedBrokers->links() }}
                        </nav>
                    @endif
                @else
                    <div class="awd-empty">
                        <p>No brokers currently match this award category.</p>
                        <a href="{{ route('awards.index') }}" class="awd-show__back">Browse all awards</a>
                    </div>
                @endif

                @if($relatedAwards !== [])
                    <section class="awd-show__related" aria-labelledby="awdRelatedTitle">
                        <h2 class="awd-show__related-title" id="awdRelatedTitle">More award categories</h2>
                        <ul class="awd-grid awd-grid--related">
                            @foreach($relatedAwards as $index => $award)
                                @include('front.awards.partials.award_card', [
                                    'award' => $award,
                                    'index' => $index,
                                ])
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
