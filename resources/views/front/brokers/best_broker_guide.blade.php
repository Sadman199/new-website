@extends('front.layout.app')

@php
    $pageTitle = $guidePage['guide']['meta_title'] ?? $guidePage['guide']['title'];
    $metaDescription = $guidePage['guide']['meta_description'] ?? '';
@endphp

@section('title', $pageTitle)
@section('meta_description', $metaDescription)
@section('canonical', route('brokers.best', ['slug' => $guidePage['slug'] ?? request()->route('slug')]))

{{--
    Early in <head> so the title never paints at the UA bold weight, then
    snaps to Nunito 700 after stylesheets and the webfont arrive.
--}}
@push('head')
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="https://fonts.gstatic.com/s/nunitosans/v19/pe0TMImSLYBIv1o4X1M8ce2xCx3yop4tQpF_MeTm0lfUVwoNnq4CLz0_upHZPYsZ51Q42pulobt1R-s.woff2">
    <style>
        h1.bgx-hero__title {
            font-family: "Nunito Sans", system-ui, -apple-system, "Segoe UI", sans-serif;
            font-weight: 700;
            letter-spacing: -0.035em;
            font-synthesis: none;
        }
    </style>
@endpush

@push('page-styles')
    {{-- Shared editorial byline + author popover styles --}}
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=13">
    <link rel="stylesheet" href="{{ asset('css/best-guide.css') }}?v=19">
@endpush

@section('main_content')
<div class="bgx">
    <header class="bgx-hero">
        <div class="bgx-shell">
            <nav class="bgx-crumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('brokers.best.index') }}">Best brokers</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>{{ $guidePage['guide']['breadcrumb'] ?? $guidePage['label'] }}</span>
            </nav>

            <h1 class="bgx-hero__title">{{ $guidePage['guide']['title'] }}</h1>

            <div class="bgx-hero__lead">{!! \App\Support\RichText::forDisplay($guidePage['guide']['lead']) !!}</div>

            @if(! $guidePage['is_empty'])
                <p class="bgx-hero__meta">
                    <span>Updated {{ $guidePage['updated_at'] }}</span>
                    <span>{{ $guidePage['match_count'] }} screened</span>
                    <span>{{ count($guidePage['entries']) }} shortlisted</span>
                    @if($guidePage['ranked_from_all'])
                        <span>Ranked across our full database</span>
                    @endif
                </p>

                @include('front.brokers.partials.best_guide_hero_author', [
                    'editorialTeam' => $guidePage['editorial_team'] ?? [],
                    'guidePage' => $guidePage,
                ])
            @elseif($guidePage['is_empty'])
                <p class="bgx-hero__note">We are refreshing broker matches for {{ $guidePage['label'] }}. Use the broker finder to compare regulated platforms.</p>
            @endif

        </div>
    </header>

    @if(! $guidePage['is_empty'])
        @include('front.brokers.partials.guide_rail', ['nav' => $guidePage['nav']])
    @endif

    <main class="bgx-body">
        <div class="bgx-shell">
            @if($guidePage['is_empty'])
                <section class="bgx-card bgx-empty">
                    <h2 class="bgx-h2">No matching brokers yet</h2>
                    <p class="bgx-prose">We could not find brokers that meet the criteria for this listing in our current database. Try our broker finder or browse every review we publish.</p>
                    <div class="bgx-actions">
                        <a href="{{ route('find_my_broker') }}" class="bgx-btn bgx-btn--primary">Find my broker</a>
                        <a href="{{ route('broker.reviews.index') }}" class="bgx-btn bgx-btn--ghost">All broker reviews</a>
                    </div>
                </section>
            @else
                @include('front.brokers.partials.guide_shortlist')
                @include('front.brokers.partials.guide_top_pick')
                @include('front.brokers.partials.guide_compare')
                @include('front.brokers.partials.guide_scoring')

                <section class="bgx-section" id="reviews">
                    <header class="bgx-section__head">
                        <h2 class="bgx-h2">{{ $guidePage['guide']['reviews_title'] }}</h2>
                        <p class="bgx-prose">Each broker is listed with the same facts. Unverified figures are marked as not disclosed.</p>
                    </header>

                    <div class="bgx-reviews">
                        @foreach($guidePage['entries'] as $entry)
                            @include('front.brokers.partials.guide_broker_card', ['entry' => $entry])
                        @endforeach
                    </div>
                </section>

                <section class="bgx-cta" id="find-match">
                    <div class="bgx-cta__body">
                        <h2 class="bgx-h2">{{ $guidePage['guide']['cta_title'] }}</h2>
                        <p class="bgx-prose">A few questions about experience, budget, and platform.</p>
                    </div>
                    <a href="{{ route('find_my_broker') }}" class="bgx-btn bgx-btn--primary bgx-btn--lg">
                        Get my best match <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </section>
            @endif

            @include('front.brokers.partials.guide_explainer')

            <p class="bgx-disclaimer">
                Everything on BrokersCourt is based on verified broker data and independent research. We may receive
                compensation from brokers we feature, which never affects how they are scored. Trading forex and CFDs
                carries significant risk of loss.
            </p>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/best-broker-guide.js') }}?v=8" defer></script>
<script src="{{ asset('js/best-guide.js') }}?v=4" defer></script>
@endpush
