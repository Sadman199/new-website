
@extends('front.layout.app')
@section('title', $broker->meta_title ?? $broker->title)
@section('meta_description', $broker->meta_description ?? Str::limit(strip_tags($broker->description), 150))
@section('meta_keywords', $broker->meta_keyword)

@push('head')
    <link rel="stylesheet" href="{{ asset('css/broker-review.css') }}?v=3">
@endpush

@section('main_content')
<div class="br-page">
    @if($broker->is_scam)
    <div class="br-scam-banner">
        <div class="br-container">
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

    @include('front.brokers.partials.hero', ['broker' => $broker, 'editorialCredits' => $editorialCredits ?? []])
    @include('front.brokers.partials.navigation', ['broker' => $broker, 'account_options' => $account_options])

    <div class="br-container br-layout">
        <main class="br-main">
            @include('front.brokers.partials.key-stats', ['broker' => $broker])

            @if(strip_tags($broker->description ?? ''))
            <section class="br-section" id="review-body">
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
            @include('front.brokers.partials.faqs', ['faqs' => $faqs])
        </main>

        <aside class="br-sidebar">
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
            <p class="br-sidebar__risk">Your capital is at risk. CFDs are complex instruments.</p>
        </aside>
    </div>

    @include('front.brokers.partials.reviews', ['broker' => $broker, 'approved_reviews' => $approved_reviews, 'reviewStats' => $reviewStats ?? ['count' => 0, 'average' => 0]])
    @include('front.brokers.partials.compare', ['broker' => $broker, 'compare_brokers' => $compare_brokers])
    @include('front.brokers.partials.author-profile', ['editorialTeam' => $editorialTeam ?? [], 'editorialCredits' => $editorialCredits ?? []])
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/broker-review.js') }}?v=3" defer></script>
@endpush
