@php
    use App\Services\BrokerGuideService;
    $guideService = app(BrokerGuideService::class);
    $hubTitle = $guideHubTitle ?? ('Getting started with ' . $broker->name);
    $hubDescription = $guideHubDescription ?? 'Step-by-step guides for account types, demo and live setup, Islamic accounts, and funding.';
@endphp

@if($publishedGuides->isNotEmpty())
<section class="br-guides-band" id="broker-guides" aria-labelledby="broker-guides-title">
    <div class="bbg-container">
        <div class="br-section br-section--guides">
            <div class="br-section__head br-section__head--center">
                <p class="br-guides-band__eyebrow">Account guides</p>
                <h2 class="br-section__title" id="broker-guides-title">{{ $hubTitle }}</h2>
                @if($hubDescription)
                    <p class="br-section__desc">{{ $hubDescription }}</p>
                @endif
            </div>

            <div class="br-section__body">
                <div class="br-guide-grid">
                    @foreach($publishedGuides as $guide)
                        @php $topic = $guide->topic; @endphp
                        <a href="{{ $guideService->publicUrl($guide) }}" class="br-guide-card">
                            <span class="br-guide-card__icon" aria-hidden="true">
                                @if($topic?->icon)
                                    <i class="{{ $topic->icon }}"></i>
                                @else
                                    <i class="fas fa-book-open"></i>
                                @endif
                            </span>
                            <span class="br-guide-card__content">
                                <strong class="br-guide-card__title">{{ $guide->title }}</strong>
                                @if($guide->summary)
                                    <span class="br-guide-card__summary">{{ $guide->summary }}</span>
                                @endif
                            </span>
                            <span class="br-guide-card__arrow" aria-hidden="true">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
