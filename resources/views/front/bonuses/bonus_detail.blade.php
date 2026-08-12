@extends('front.layout.app')

@section('title', $bonus->meta_title ?? $bonus->title)
@section('meta_description', $bonus->meta_description ?? Str::limit(strip_tags($bonus->description), 150))
@section('canonical', $bonus->detailUrl() ?: url()->current())
@section('og_image', $bonus->feature_image ? 'uploads/'.$bonus->feature_image : ($bonus->broker?->logo ?: ''))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/bonus-detail.css') }}?v=3">
@endpush

@section('main_content')
@php
    $hero = $detail['hero'];
    $broker = $detail['broker'];
@endphp
<div class="bbd-page">
    <header class="bbd-hero bbg-hero bbd-hero--{{ $hero['tone'] }}">
        <div class="bbd-wrap">
            <nav class="bbd-breadcrumb" aria-label="Breadcrumb">
                @foreach($detail['breadcrumb'] as $crumb)
                    @if($crumb['url'])
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        <span aria-hidden="true">/</span>
                    @else
                        <span>{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>

            <div class="bbd-hero__intro">
                <p class="bbd-hero__eyebrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/>
                    </svg>
                    {{ $hero['eyebrow'] }}
                </p>
                <h1 class="bbd-hero__title">{{ $hero['title'] }}</h1>
                <p class="bbd-hero__headline">{{ $hero['headline'] }}</p>
                @if($hero['subtitle'])
                    <p class="bbd-hero__subtitle">{{ $hero['subtitle'] }}</p>
                @endif
            </div>

            @include('front.brokers.partials.best_guide_hero_author', [
                'editorialTeam' => $detail['editorial_team'],
                'guidePage' => $detail['guide_meta'],
            ])

            <div class="bbd-hero__meta">
                <div class="bbd-hero__badges">
                    <span class="bbd-status bbd-status--{{ $hero['is_active'] ? 'active' : 'expired' }}">{{ $hero['status_label'] }}</span>
                    @if($hero['is_featured'])
                        <span class="bbd-status bbd-status--featured">Featured</span>
                    @endif
                </div>

                @if($detail['claim_url'] && $hero['is_active'])
                    <a href="{{ $detail['claim_url'] }}" class="bbd-btn bbd-btn--primary" target="_blank" rel="noopener noreferrer sponsored">
                        Claim this offer
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 0 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <div class="bbd-body">
        <div class="bbd-wrap">
            <div class="bbd-layout">
                <main class="bbd-main">
                    @if($hero['stats'])
                        <section class="bbd-panel bbd-panel--stats" aria-labelledby="bbdStatsTitle">
                            <h2 class="bbd-panel__title" id="bbdStatsTitle">Offer at a glance</h2>
                            <div class="bbd-stats-grid">
                                @foreach($hero['stats'] as $stat)
                                    <div class="bbd-stat-card {{ !empty($stat['highlight']) ? 'bbd-stat-card--highlight' : '' }} {{ !empty($stat['urgent']) ? 'bbd-stat-card--urgent' : '' }}">
                                        <span class="bbd-stat-card__label">{{ $stat['label'] }}</span>
                                        <span class="bbd-stat-card__value">{{ $stat['value'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($detail['highlights'])
                        <section class="bbd-panel" aria-labelledby="bbdHighlightsTitle">
                            <h2 class="bbd-panel__title" id="bbdHighlightsTitle">Key highlights</h2>
                            <ul class="bbd-highlights">
                                @foreach($detail['highlights'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @foreach($detail['sections'] as $section)
                        <section class="bbd-panel" aria-labelledby="bbdSection{{ $section['key'] }}">
                            <h2 class="bbd-panel__title" id="bbdSection{{ $section['key'] }}">{{ $section['title'] }}</h2>
                            <div class="bbd-panel__content {{ $section['html'] ? 'bbd-panel__content--html' : '' }}">
                                @if($section['html'])
                                    {!! $section['content'] !!}
                                @else
                                    <p>{!! nl2br(e(strip_tags($section['content']))) !!}</p>
                                @endif
                            </div>
                        </section>
                    @endforeach
                </main>

                <aside class="bbd-sidebar" aria-label="Offer sidebar">
                    @if($broker)
                        <div class="bbd-broker-card">
                            <div class="bbd-broker-card__head">
                                @if($broker['logo'])
                                    <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}" class="bbd-broker-card__logo">
                                @else
                                    <span class="bbd-broker-card__fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                                @endif
                                <div>
                                    <p class="bbd-broker-card__label">Broker</p>
                                    <h2 class="bbd-broker-card__name">{{ $broker['name'] }}</h2>
                                    @if($broker['rating'])
                                        <p class="bbd-broker-card__rating">{{ number_format($broker['rating'], 1) }} / 10 rating</p>
                                    @endif
                                </div>
                            </div>

                            <dl class="bbd-broker-card__facts">
                                @if($broker['country'])
                                    <div><dt>Headquarters</dt><dd>{{ $broker['country'] }}</dd></div>
                                @endif
                                @if($broker['min_deposit'])
                                    <div><dt>Min. deposit</dt><dd>{{ $broker['min_deposit'] }}</dd></div>
                                @endif
                                @if($broker['regulation'])
                                    <div><dt>Regulation</dt><dd>{{ implode(', ', $broker['regulation']) }}</dd></div>
                                @endif
                                @if($broker['platforms'])
                                    <div><dt>Platforms</dt><dd>{{ implode(', ', $broker['platforms']) }}</dd></div>
                                @endif
                                @if($broker['top_feature'])
                                    <div><dt>Top feature</dt><dd>{{ $broker['top_feature'] }}</dd></div>
                                @endif
                            </dl>

                            <a href="{{ $broker['review_url'] }}" class="bbd-btn bbd-btn--ghost bbd-btn--block">Read {{ $broker['name'] }} review</a>
                        </div>
                    @endif

                    @if($hero['image'])
                        <div class="bbd-sidebar-visual">
                            <img src="{{ $hero['image'] }}" alt="{{ $hero['title'] }}">
                        </div>
                    @endif

                    @if($detail['claim_url'] && $hero['is_active'])
                        <div class="bbd-claim-card">
                            <h2 class="bbd-claim-card__title">Ready to claim?</h2>
                            <p class="bbd-claim-card__text">Open the broker offer page to register, verify, and opt in according to the terms above.</p>
                            <a href="{{ $detail['claim_url'] }}" class="bbd-btn bbd-btn--primary bbd-btn--block" target="_blank" rel="noopener noreferrer sponsored">
                                Claim this offer
                            </a>
                            @if($detail['terms_url'])
                                <a href="{{ $detail['terms_url'] }}" class="bbd-claim-card__terms" target="_blank" rel="noopener noreferrer">Official terms & conditions</a>
                            @endif
                        </div>
                    @endif

                    <nav class="bbd-quick-links" aria-label="Quick links">
                        <p class="bbd-quick-links__title">Quick links</p>
                        @foreach($detail['quick_links'] as $link)
                            <a href="{{ $link['url'] }}" class="bbd-quick-link">
                                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                                <span>
                                    <strong>{{ $link['label'] }}</strong>
                                    <small>{{ $link['desc'] }}</small>
                                </span>
                            </a>
                        @endforeach
                    </nav>
                </aside>
            </div>

            @if($detail['related_broker'])
                <section class="bbd-related" aria-labelledby="bbdRelatedBrokerTitle">
                    <div class="bbd-related__head">
                        <h2 class="bbd-related__title" id="bbdRelatedBrokerTitle">More offers from {{ $broker['name'] ?? 'this broker' }}</h2>
                    </div>
                    <div class="bbd-related__grid">
                        @foreach($detail['related_broker'] as $promo)
                            @include('front.bonuses.partials.related_promo_card', ['promo' => $promo])
                        @endforeach
                    </div>
                </section>
            @endif

            @if($detail['related_category'])
                <section class="bbd-related" aria-labelledby="bbdRelatedCategoryTitle">
                    <div class="bbd-related__head">
                        <h2 class="bbd-related__title" id="bbdRelatedCategoryTitle">Similar {{ Str::lower($detail['category_label']) }}</h2>
                    </div>
                    <div class="bbd-related__grid">
                        @foreach($detail['related_category'] as $promo)
                            @include('front.bonuses.partials.related_promo_card', ['promo' => $promo])
                        @endforeach
                    </div>
                </section>
            @endif

            <p class="bbd-disclaimer">{{ $detail['disclaimer'] }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
@endpush
