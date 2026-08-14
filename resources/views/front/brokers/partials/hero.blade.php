@php
    $isRegulated = $broker->isRegulated();
    $guidePage = $reviewPageMeta ?? ['updated_at' => '', 'updated_relative' => null];
    $snapshot = $snapshot ?? \App\Support\BrokerReviewPresenter::decisionSnapshot($broker, $reviewStats ?? []);
    $guides = $publishedGuides ?? collect();
@endphp

<header class="bbg-hero br-hero{{ $snapshot['is_scam'] ? ' br-hero--scam' : '' }}" id="gettingstarted">
    <div class="bbg-container">
        <nav class="bbg-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('broker.reviews.index') }}">Broker Reviews</a>
            <span aria-hidden="true">/</span>
            <span>{{ $broker->name }}</span>
        </nav>

        @if($snapshot['is_scam'])
            <div class="br-hero__alert" role="alert">
                <p class="br-hero__alert-title">High-risk / scam warning</p>
                <p class="br-hero__alert-text">
                    {{ $snapshot['scam_reason'] ?: 'This broker has been flagged as high-risk. Do not deposit funds until you have read the warning below.' }}
                    @if($snapshot['scam_reported'])
                        <span>Flagged {{ $snapshot['scam_reported'] }}.</span>
                    @endif
                </p>
                <a href="{{ route('methodology') }}" class="br-hero__alert-link">How we flag brokers</a>
            </div>
        @endif

        <p class="bbg-hero__eyebrow">{{ $snapshot['is_scam'] ? 'Safety warning' : 'Independent broker review' }}</p>

        <div class="br-hero__head">
            <div class="br-hero__identity">
                <div class="br-hero__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }} logo" loading="eager">
                    @else
                        <span class="br-hero__logo-fallback">{{ strtoupper(substr($broker->name, 0, 2)) }}</span>
                    @endif
                </div>

                <div class="br-hero__intro">
                    <h1 class="bbg-hero__title">{{ $broker->title ?: $broker->name . ' Review' }}</h1>

                    @if($broker->short_description)
                        <p class="br-hero__subtitle">{!! Str::limit(strip_tags($broker->short_description), 160) !!}</p>
                    @endif

                    <div class="br-hero__badges">
                        @if($snapshot['is_scam'])
                            <span class="br-badge br-badge--danger">High Risk</span>
                        @elseif($isRegulated)
                            <span class="br-badge br-badge--safe">Regulated</span>
                        @else
                            <span class="br-badge br-badge--warn">Unregulated</span>
                        @endif
                        @if($broker->featured_broker && ! $snapshot['is_scam'])
                            <span class="br-badge br-badge--featured">Featured</span>
                        @endif
                        @if($snapshot['review_count'] > 0)
                            <a href="#voices" class="br-hero__reviews-chip">
                                {{ number_format((float) $snapshot['review_average'], 1) }}/5
                                · {{ $snapshot['review_count'] }} {{ Str::plural('review', $snapshot['review_count']) }}
                            </a>
                        @else
                            <a href="#voices" class="br-hero__reviews-chip">Write a review</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="br-hero__score-wrap">
                <div class="br-hero__score-ring" aria-label="Overall score {{ $snapshot['score'] }} out of 10">
                    <span class="br-hero__score-value">{{ $snapshot['score'] }}</span>
                    <span class="br-hero__score-label">Score</span>
                </div>
                @if($snapshot['trust_score'])
                    <p class="br-hero__trust">Trust {{ $snapshot['trust_score'] }}/99</p>
                @endif
                <a href="{{ route('methodology') }}" class="br-hero__how">How we score</a>
            </div>
        </div>

        <div class="br-hero__cta-block">
            @include('front.brokers.partials.decision_ctas', [
                'broker' => $broker,
                'snapshot' => $snapshot,
                'variant' => 'hero',
            ])
        </div>

        <div class="br-hero__footer">
            @include('front.brokers.partials.country_context_hero', [
                'context' => 'review',
                'brokerName' => $broker->name,
            ])

            @include('front.brokers.partials.best_guide_hero_author', [
                'editorialTeam' => $editorialTeam ?? [],
                'guidePage' => $guidePage,
            ])

            @if($guides->isNotEmpty())
                <nav class="br-hero__guides" aria-label="Related guides">
                    <span class="br-hero__guides-label">Guides</span>
                    @foreach($guides->take(4) as $guide)
                        <a href="{{ app(\App\Services\BrokerGuideService::class)->publicUrl($guide) }}">{{ $guide->title }}</a>
                    @endforeach
                </nav>
            @endif
        </div>
    </div>
</header>
