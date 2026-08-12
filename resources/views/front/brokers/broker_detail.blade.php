
@extends('front.layout.app')
@section('title', $broker->meta_title ?? $broker->title)
@section('meta_description', $broker->meta_description ?? Str::limit(strip_tags($broker->description), 150))
@section('canonical', route('broker_detail', ['slug' => \App\Http\Controllers\Front\BrokerController::reviewSlugFor($broker)]))
@section('og_image', $broker->ogShareImageUrl())
@section('og_image_width', (string) \App\Services\BrokerOgImageService::WIDTH)
@section('og_image_height', (string) \App\Services\BrokerOgImageService::HEIGHT)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=7">
    <link rel="stylesheet" href="{{ asset('css/broker-review.css') }}?v=17">
@endpush

@push('json_ld')
    <script type="application/ld+json">@json($reviewJsonLd)</script>
@endpush

@section('main_content')
@php
    $snapshot = $snapshot ?? \App\Support\BrokerReviewPresenter::decisionSnapshot($broker, $reviewStats ?? []);
@endphp
<div class="bbg-page br-page{{ $snapshot['is_scam'] ? ' br-page--scam' : '' }}">
    @include('front.brokers.partials.hero', [
        'broker' => $broker,
        'editorialTeam' => $editorialTeam ?? [],
        'reviewPageMeta' => $reviewPageMeta ?? ['updated_at' => ''],
        'publishedGuides' => $publishedGuides ?? collect(),
        'guideHubDescription' => $guideHubDescription ?? null,
        'snapshot' => $snapshot,
        'reviewStats' => $reviewStats ?? [],
    ])

    <div class="bbg-container">
        <div class="br-reading-layout">
            @include('front.brokers.partials.review_toc_sidebar', ['reviewToc' => $reviewToc ?? []])

            <div class="br-reading-layout__body">
                <div class="bbg-layout br-layout-v2">
                    <main class="bbg-main br-main">
                        <div class="bbg-mobile-toc" aria-label="Jump to section">
                            <label for="bbg-mobile-toc-select" class="bbg-sr-only">Jump to section</label>
                            <select id="bbg-mobile-toc-select" class="bbg-mobile-toc__select">
                                @foreach($reviewToc ?? [] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        @include('front.brokers.partials.score-breakdown', [
                            'broker' => $broker,
                            'scoreBreakdown' => $scoreBreakdown ?? ['has_scores' => false],
                        ])

                        @include('front.brokers.partials.key-stats', ['broker' => $broker])

                        @if(strip_tags($broker->description ?? ''))
                        <section class="br-section br-section--prose" id="review-body">
                            <div class="br-section__head">
                                <h2 class="br-section__title">Full Review</h2>
                                <p class="br-section__desc">In-depth analysis of {{ $broker->name }}</p>
                            </div>
                            <div class="br-section__body br-prose">
                                {!! $broker->description !!}
                            </div>
                        </section>
                        @endif

                        @include('front.brokers.partials.review-sections', ['broker' => $broker, 'account_options' => $account_options])
                        @unless($snapshot['is_scam'])
                            @include('front.brokers.partials.promotions', ['broker' => $broker])
                        @endunless
                    </main>

                    <aside class="br-sidebar" aria-label="Broker actions">
                        <div class="br-sidebar__inner{{ $snapshot['is_scam'] ? ' br-sidebar__inner--scam' : '' }}">
                            @if($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="br-sidebar__logo">
                            @endif
                            <div class="br-sidebar__info">
                                <p class="br-sidebar__name">{{ $broker->name }}</p>
                                <p class="br-sidebar__score-row">
                                    <span class="br-sidebar__score">{{ $snapshot['score'] }}</span>
                                    <span class="br-sidebar__score-label">Overall score</span>
                                </p>
                                @if(!empty($reviewPageMeta['updated_at']))
                                    <p class="br-sidebar__updated">
                                        <i class="far fa-clock" aria-hidden="true"></i>
                                        Updated {{ $reviewPageMeta['updated_at'] }}
                                    </p>
                                @endif
                            </div>
                            @if(!empty($scoreBreakdown['has_scores']))
                                <ul class="br-sidebar__scores" aria-label="Category scores">
                                    @foreach(array_slice($scoreBreakdown['items'], 0, 4) as $item)
                                        <li>
                                            <span>{{ $item['label'] }}</span>
                                            <strong>{{ $item['display'] }}</strong>
                                        </li>
                                    @endforeach
                                </ul>
                                <a href="#score-breakdown" class="br-sidebar__scores-link">Full breakdown</a>
                            @endif
                            @include('front.brokers.partials.decision_ctas', [
                                'broker' => $broker,
                                'snapshot' => $snapshot,
                                'variant' => 'sidebar',
                            ])
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <div class="br-full-section br-full-section--faqs">
        <div class="bbg-container">
            @include('front.brokers.partials.faqs', ['faqs' => $faqs])
        </div>
    </div>

    <div class="br-full-section br-full-section--reviews">
        <div class="bbg-container">
            @include('front.brokers.partials.reviews', [
                'broker' => $broker,
                'approved_reviews' => $approved_reviews,
                'reviewStats' => $reviewStats ?? ['count' => 0, 'average' => 0],
                'userReview' => $userReview ?? null,
            ])
        </div>
    </div>

    <div class="br-full-section br-full-section--compare">
        <div class="bbg-container">
            @include('front.brokers.partials.compare', [
                'broker' => $broker,
                'compare_brokers' => $compare_brokers,
                'snapshot' => $snapshot,
            ])
        </div>
    </div>

    @include('front.brokers.partials.mobile_cta_bar', [
        'broker' => $broker,
        'snapshot' => $snapshot,
    ])
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
    <script src="{{ asset('js/broker-review.js') }}?v=8" defer></script>
@endpush
