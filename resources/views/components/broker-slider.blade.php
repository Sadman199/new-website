@props([
    'brokers' => null,
    'title' => 'Top rated brokers',
    'eyebrow' => null,
    'lead' => null,
    'viewAllUrl' => null,
    'viewAllLabel' => 'View all broker reviews',
    'limit' => 10,
    'tone' => 'light',
    'sectionId' => null,
    'emptyMessage' => 'Broker ratings are being updated. Browse the full reviews directory in the meantime.',
])

@php
    use App\Http\Controllers\Front\BrokerController;
    use App\Support\BrokerRating;
    use Illuminate\Support\Str;

    $items = collect($brokers ?? [])->filter()->take((int) $limit)->values();
    $sliderId = 'bcs-'.Str::random(6);
    $headingId = $sliderId.'-title';
@endphp

@once
    @push('page-styles')
        <link rel="stylesheet" href="{{ asset('css/broker-slider.css') }}?v=2">
    @endpush
    @push('scripts')
        <script src="{{ asset('js/broker-slider.js') }}?v=1" defer></script>
    @endpush
@endonce

<section {{ $attributes->merge(['class' => 'bcs bcs--'.$tone]) }}
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
                <a href="{{ $viewAllUrl }}" class="bcs__all">{{ $viewAllLabel }}</a>
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
                @foreach($items as $broker)
                    @php
                        $reviewUrl = route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]);
                        $visitUrl = $broker->open_live ?: ($broker->visit_site ?: $broker->url);
                        $rating = BrokerRating::outOfFive($broker->rating);
                        $regulators = array_slice($broker->regulationList() ?: [], 0, 2);
                        $minDeposit = $broker->minimum_deposit !== null && $broker->minimum_deposit !== ''
                            ? '$'.number_format((float) $broker->minimum_deposit, 0)
                            : null;
                        $leverage = $broker->leverage ? Str::limit(strip_tags((string) $broker->leverage), 12, '') : null;
                        $spreads = $broker->spreads ? Str::limit(strip_tags((string) $broker->spreads), 12, '') : null;
                        $founded = $broker->year_founded ?: null;
                        $stats = array_filter([
                            'Min. deposit' => $minDeposit,
                            'Leverage' => $leverage,
                            'Spreads' => $spreads,
                            'Founded' => $spreads || $leverage ? null : $founded,
                        ]);
                    @endphp

                    <li class="bcs__slide">
                        <article class="bcs-card">
                            <div class="bcs-card__head">
                                <span class="bcs-card__logo">
                                    @if($broker->logo)
                                        <img src="{{ asset($broker->logo) }}"
                                             alt="{{ $broker->name }} logo"
                                             loading="lazy"
                                             decoding="async"
                                             width="48"
                                             height="48">
                                    @else
                                        <span class="bcs-card__initial" aria-hidden="true">{{ Str::upper(Str::substr($broker->name, 0, 1)) }}</span>
                                    @endif
                                </span>

                                <div class="bcs-card__identity">
                                    <h3 class="bcs-card__name">
                                        <a href="{{ $reviewUrl }}">{{ $broker->name }}</a>
                                    </h3>
                                    <p class="bcs-card__meta">
                                        @if($broker->isRegulated())
                                            <span class="bcs-card__tag">Regulated</span>
                                        @endif
                                        @if($regulators)
                                            <span>{{ implode(' · ', $regulators) }}</span>
                                        @elseif($broker->country)
                                            <span>{{ $broker->country }}</span>
                                        @endif
                                    </p>
                                </div>

                                @if($rating !== null)
                                    <span class="bcs-card__score" aria-label="Rated {{ number_format($rating, 1) }} out of 5">
                                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ number_format($rating, 1) }}
                                    </span>
                                @endif
                            </div>

                            @if($stats)
                                <dl class="bcs-card__stats">
                                    @foreach($stats as $label => $value)
                                        <div>
                                            <dt>{{ $label }}</dt>
                                            <dd>{{ $value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            @endif

                            <div class="bcs-card__actions">
                                <a href="{{ $reviewUrl }}" class="bcs-card__link">
                                    Read review
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 0 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                                @if($visitUrl)
                                    <a href="{{ $visitUrl }}" class="bcs-card__visit" target="_blank" rel="noopener noreferrer nofollow">Visit broker</a>
                                @endif
                            </div>
                        </article>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</section>
