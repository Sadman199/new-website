
@extends('front.layout.app')
@section('title', $broker->meta_title ?? $broker->title)
@section('meta_description', $broker->meta_description ?? Str::limit(strip_tags($broker->description), 150))
@section('meta_keywords', $broker->meta_keyword)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/best-broker-guide.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('css/broker-review.css') }}?v=9">
@endpush

@section('main_content')
<div class="bbg-page br-page">
    @if($broker->is_scam)
    <div class="br-scam-banner">
        <div class="bbg-container">
            <p class="br-scam-banner__title">Scam / High-Risk Warning</p>
            <p class="br-scam-banner__text">
                {{ $broker->scam_reason ?: 'This broker has been flagged as high-risk. We strongly advise caution before depositing any funds.' }}
                @if($broker->scam_reported_date)
                    (Reported {{ \Carbon\Carbon::parse($broker->scam_reported_date)->format('M d, Y') }})
                @endif
            </p>
            <a href="{{ route('scam_brokers') }}" class="br-scam-banner__link">View scam broker list</a>
        </div>
    </div>
    @endif

    @include('front.brokers.partials.hero', [
        'broker' => $broker,
        'editorialTeam' => $editorialTeam ?? [],
        'reviewPageMeta' => $reviewPageMeta ?? ['updated_at' => ''],
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
                        @include('front.brokers.partials.promotions', ['broker' => $broker])
                    </main>

                    <aside class="br-sidebar" aria-label="Broker actions">
                        <div class="br-sidebar__inner">
                            @if($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" class="br-sidebar__logo">
                            @endif
                            <p class="br-sidebar__name">{{ $broker->name }}</p>
                            <p class="br-sidebar__score">{{ number_format((float) $broker->rating, 1) }}</p>
                            <p class="br-sidebar__score-label">Overall score</p>
                            <a href="{{ $broker->open_live ?: $broker->visit_site ?: $broker->url }}" target="_blank" rel="noopener noreferrer" class="br-btn br-btn--primary">
                                Visit broker
                            </a>
                            @if($broker->demo_link ?: $broker->open_demo)
                            <a href="{{ $broker->demo_link ?: $broker->open_demo }}" target="_blank" rel="noopener noreferrer" class="br-btn br-btn--secondary">
                                Try demo
                            </a>
                            @endif
                            <a href="#compare" class="br-sidebar__compare-link">Compare with others</a>
                            <p class="br-sidebar__risk">Your capital is at risk. CFDs are complex instruments.</p>
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
            @include('front.brokers.partials.reviews', ['broker' => $broker, 'approved_reviews' => $approved_reviews, 'reviewStats' => $reviewStats ?? ['count' => 0, 'average' => 0]])
        </div>
    </div>

    <div class="br-full-section br-full-section--compare">
        <div class="bbg-container">
            @include('front.brokers.partials.compare', ['broker' => $broker, 'compare_brokers' => $compare_brokers])
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/best-broker-guide.js') }}?v=6" defer></script>
    <script src="{{ asset('js/broker-review.js') }}?v=6" defer></script>
@endpush
