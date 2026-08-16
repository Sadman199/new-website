@php
    $guideService = app(\App\Services\BrokerGuideService::class);
    $rating = \App\Support\BrokerRating::outOfFive($broker->rating) ?? 0;
    $guidePage = $guidePageMeta ?? ['updated_at' => '', 'updated_relative' => null];
    $topicIcon = $topic?->icon ?? 'fas fa-book-open';
@endphp

<div class="bbg-page br-page br-guide-page-v2">
    @if($broker->is_scam)
        <div class="br-scam-banner">
            <div class="bbg-container">
                <p class="br-scam-banner__title">Scam / High-Risk Warning</p>
                <p class="br-scam-banner__text">
                    {{ $broker->scam_reason ?: 'This broker has been flagged as high-risk. We strongly advise caution before depositing any funds.' }}
                </p>
            </div>
        </div>
    @endif

    <header class="bbg-hero br-hero br-guide-page-hero" id="guide-top">
        <div class="bbg-container">
            <nav class="bbg-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('broker.reviews.index') }}">Broker Reviews</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('broker_detail', ['slug' => $reviewSlug]) }}">{{ $broker->name }}</a>
                <span aria-hidden="true">/</span>
                <span>{{ $guide->title }}</span>
            </nav>

            <div class="br-guide-page-hero__topic">
                <span class="br-guide-page-hero__topic-icon" aria-hidden="true"><i class="{{ $topicIcon }}"></i></span>
                <span>Account guide</span>
            </div>

            <div class="br-hero__head br-guide-page-hero__head">
                <div class="br-hero__identity">
                    <div class="br-hero__logo">
                        @if($broker->logo)
                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" loading="eager">
                        @else
                            <span class="br-hero__logo-fallback">{{ strtoupper(substr($broker->name, 0, 2)) }}</span>
                        @endif
                    </div>

                    <div class="br-hero__intro">
                        <p class="br-guide-page-hero__broker-name">{{ $broker->name }}</p>
                        <h1 class="bbg-hero__title br-guide-page-hero__title">{{ $guide->title }}</h1>
                        @if($guide->summary)
                            <p class="br-hero__subtitle br-guide-page-hero__summary">{{ $guide->summary }}</p>
                        @endif

                        <div class="br-hero__badges">
                            @if($broker->is_scam)
                                <span class="br-badge br-badge--danger">High Risk</span>
                            @elseif($broker->isRegulated())
                                <span class="br-badge br-badge--safe bc-regulated-tag">Regulated</span>
                            @endif
                            @if($topic?->title)
                                <span class="br-badge br-badge--guide">{{ $topic->title }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($rating > 0)
                    <div class="br-hero__score-wrap">
                        <div class="br-hero__score-ring"
                             style="--br-score-pct: {{ \App\Support\BrokerRating::percent($broker->rating) }}%"
                             aria-label="Overall score {{ number_format($rating, 1) }} out of 5">
                            <span class="br-hero__score-value">{{ number_format($rating, 1) }}</span>
                            <span class="br-hero__score-label">Score</span>
                        </div>
                        @if($broker->trust_score && (int) $broker->trust_score <= 99)
                            <p class="br-hero__trust">Trust score {{ (int) $broker->trust_score }}/99</p>
                        @endif
                    </div>
                @endif
            </div>

            @include('front.brokers.partials.best_guide_hero_author', [
                'editorialTeam' => $editorialTeam ?? [],
                'guidePage' => $guidePage,
            ])

            <div class="br-hero__meta br-guide-page-hero__meta">
                @if(!empty($guidePage['updated_at']))
                    <div class="br-hero__meta-item br-hero__meta-item--updated">
                        <span>Guide updated</span>
                        <strong>{{ $guidePage['updated_at'] }}</strong>
                    </div>
                @endif
                @if(!empty($context['minimum_deposit']))
                    <div class="br-hero__meta-item">
                        <span>Min. deposit</span>
                        <strong>${{ number_format((float) $context['minimum_deposit'], 0) }}</strong>
                    </div>
                @endif
                @if($publishedGuides->count() > 0)
                    <div class="br-hero__meta-item">
                        <span>Guides</span>
                        <strong>{{ $publishedGuides->count() }} topics</strong>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="bbg-container br-guide-page-body">
        <div class="br-guide-page-layout">
            <aside class="br-guide-page-aside" aria-label="Guide navigation and broker actions">
                @include('front.brokers.partials.guide_page_sidebar', [
                    'broker' => $broker,
                    'guide' => $guide,
                    'context' => $context,
                    'publishedGuides' => $publishedGuides,
                    'reviewSlug' => $reviewSlug,
                    'guideService' => $guideService,
                ])
            </aside>

            <article class="br-guide-page-article">
                @include('front.brokers.partials.guide-context', [
                    'context' => $context,
                    'contextProfile' => $topic?->context_profile,
                ])

                <div class="br-guide-article-panel">
                    @if(strip_tags($guide->content ?? ''))
                        <div class="br-prose br-guide-content">
                            {!! $guide->content !!}
                        </div>
                    @else
                        <div class="br-guide-empty">
                            <p>Detailed guide content for this topic is coming soon.</p>
                            <a href="{{ route('broker_detail', ['slug' => $reviewSlug]) }}" class="br-btn br-btn--secondary">Back to {{ $broker->name }} review</a>
                        </div>
                    @endif
                </div>

                @include('front.brokers.partials.guide_page_author_panel', [
                    'editorialTeam' => $editorialTeam ?? [],
                    'guidePageMeta' => $guidePage,
                ])

                <footer class="br-guide-page-footer">
                    <div class="br-guide-page-footer__copy">
                        <p class="br-guide-page-footer__note">
                            Guides are independently researched. Always verify current terms on the broker's website.
                            <a href="{{ route('methodology') }}">How we review brokers</a>
                        </p>
                    </div>
                    <div class="br-guide-page-footer__actions">
                        @if(!empty($context['live_link']))
                            <a href="{{ $context['live_link'] }}" class="br-btn br-btn--primary" target="_blank" rel="noopener noreferrer">Visit {{ $broker->name }}</a>
                        @endif
                        @if(!empty($context['demo_link']) && !empty($context['demo_available']))
                            <a href="{{ $context['demo_link'] }}" class="br-btn br-btn--secondary" target="_blank" rel="noopener noreferrer">Try demo</a>
                        @endif
                        <a href="{{ route('broker_detail', ['slug' => $reviewSlug]) }}" class="br-btn br-btn--ghost">Full review</a>
                    </div>
                </footer>
            </article>
        </div>
    </div>
</div>
