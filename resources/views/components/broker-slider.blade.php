@props([
    'brokers' => null,
    'title' => 'Top Rated Brokers',
    'eyebrow' => null,
    'lead' => null,
    'viewAllUrl' => null,
    'viewAllLabel' => 'View all broker reviews',
    'ctaLabel' => 'Read Review',
    'limit' => 10,
    'tone' => 'light',
    'sectionId' => null,
    'emptyMessage' => 'Broker ratings are being updated. Browse the full reviews directory in the meantime.',
    'compact' => false,
])

@php
    use App\Http\Controllers\Front\BrokerController;
    use App\Support\BrokerRating;
    use Illuminate\Support\Str;

    $items = collect($brokers ?? [])->filter()->take((int) $limit)->values();
    $sliderId = 'bcs-'.Str::random(6);
    $dotsId = $sliderId.'-dots';
    $headingId = $sliderId.'-title';
@endphp

@once
    @push('page-styles')
        <link rel="stylesheet" href="{{ asset('css/broker-slider.css') }}?v=6">
    @endpush
    @push('scripts')
        <script src="{{ asset('js/broker-slider.js') }}?v=3" defer></script>
    @endpush
@endonce

<section {{ $attributes->merge(['class' => 'bcs bcs--'.$tone.($compact ? ' bcs--compact' : '')]) }}
         @if($sectionId) id="{{ $sectionId }}" @endif
         aria-labelledby="{{ $headingId }}"
         data-broker-slider>
    <header class="bcs__head">
        <div class="bcs__intro">
            @if($eyebrow)
                <p class="bcs__eyebrow">{{ $eyebrow }}</p>
            @endif
            <h2 class="bcs__title" id="{{ $headingId }}">{{ $title }}</h2>
            @if($lead)
                <p class="bcs__lead">{{ $lead }}</p>
            @endif
        </div>

        <div class="bcs__tools">
            @if($viewAllUrl)
                <a href="{{ $viewAllUrl }}" class="bcs__all">
                    <span>{{ $viewAllLabel }}</span>
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5 13 10l-5.5 5.5"/>
                    </svg>
                </a>
            @endif

            @if($items->count() > 1)
                <div class="bcs__nav" data-bcs-nav>
                    <button type="button" class="bcs__btn" data-bcs-prev aria-controls="{{ $sliderId }}" aria-label="Scroll to previous brokers">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" class="bcs__btn" data-bcs-next aria-controls="{{ $sliderId }}" aria-label="Scroll to next brokers">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </header>

    @if($items->isEmpty())
        <p class="bcs__empty">{{ $emptyMessage }}</p>
    @else
        <div class="bcs__viewport">
            <ul class="bcs__track" id="{{ $sliderId }}" tabindex="0" role="list">
                @foreach($items as $index => $broker)
                    @php
                        $reviewUrl = route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);
                        $rating = BrokerRating::outOfFive($broker->rating);
                        $rank = $index + 1;
                    @endphp

                    <li class="bcs__slide">
                        <a href="{{ $reviewUrl }}" class="bcs-card{{ $rank === 1 ? ' bcs-card--lead' : '' }}" aria-label="{{ $broker->name }} review, rated {{ $rating !== null ? number_format($rating, 1).' out of 5' : 'unrated' }}">
                            <span class="bcs-card__rank" aria-hidden="true">{{ $rank }}</span>

                            <span class="bcs-card__logo">
                                @if($broker->logo)
                                    <img src="{{ asset($broker->logo) }}"
                                         alt=""
                                         loading="lazy"
                                         decoding="async"
                                         width="80"
                                         height="80">
                                @else
                                    <span class="bcs-card__initial">{{ Str::upper(Str::substr($broker->name, 0, 1)) }}</span>
                                @endif
                            </span>

                            <span class="bcs-card__name">{{ $broker->name }}</span>

                            @if($rating !== null)
                                <span class="bcs-card__rating" aria-hidden="true">
                                    <span class="bcs-card__stars">
                                        @for($star = 1; $star <= 5; $star++)
                                            @php
                                                $fill = max(0, min(1, $rating - ($star - 1)));
                                            @endphp
                                            <span class="bcs-card__star">
                                                <svg class="bcs-card__star-bg" viewBox="0 0 20 20" aria-hidden="true">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                <svg class="bcs-card__star-fg" viewBox="0 0 20 20" aria-hidden="true" style="clip-path: inset(0 {{ number_format((1 - $fill) * 100, 0, '.', '') }}% 0 0)">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </span>
                                        @endfor
                                    </span>
                                    <span class="bcs-card__score">{{ number_format($rating, 1) }}</span>
                                </span>
                            @endif

                            <span class="bcs-card__cta">
                                <span>{{ $ctaLabel }}</span>
                                <span class="bcs-card__cta-icon" aria-hidden="true">
                                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 10h6m0 0-2.5-2.5M13 10l-2.5 2.5"/>
                                    </svg>
                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bcs__dots" id="{{ $dotsId }}" data-bcs-dots role="tablist" aria-label="Broker slider pages"></div>
    @endif
</section>
