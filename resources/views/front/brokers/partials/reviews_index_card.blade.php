@php
    $markets = implode(',', $broker['markets'] ?? []);
    $performance = $broker['performance'] ?? [];
    $highlight = (!$broker['is_award_winner'] && $broker['top_feature'])
        ? $broker['top_feature']
        : ($broker['review_count'] > 0
            ? number_format($broker['review_count']) . ' user ' . Str::plural('review', $broker['review_count'])
            : null);
@endphp
<li class="bri-card"
    data-bri-card
    data-bri-name="{{ $broker['name'] }}"
    data-bri-markets="{{ $markets }}">
    <div class="bri-card__body">
        <div class="bri-card__header">
            <a href="{{ $broker['review_url'] }}" class="bri-card__logo" aria-hidden="true" tabindex="-1">
                @if($broker['logo'])
                    <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}">
                @else
                    <span class="bri-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                @endif
            </a>
            <div class="bri-card__identity">
                <a href="{{ $broker['review_url'] }}" class="bri-card__name">{{ $broker['name'] }}</a>
                <div class="bri-card__badges">
                    @if($broker['rating'] !== null)
                        <span class="bri-card__rating">
                            <svg class="bri-card__star" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            {{ number_format($broker['rating'], 1) }}<span>/5</span>
                        </span>
                    @endif
                    @if($broker['is_award_winner'] && $broker['award_label'])
                        <a href="{{ route('awards.index') }}" class="bri-card__award">{{ $broker['award_label'] }}</a>
                    @endif
                </div>
            </div>
        </div>

        @if($highlight)
            <p class="bri-card__tagline">{{ $highlight }}</p>
        @endif

        @if($performance !== [])
            <div class="bri-metrics" aria-label="Broker performance metrics">
                @foreach(array_slice($performance, 0, 4) as $metric)
                    <div class="bri-metric">
                        <div class="bri-metric__head">
                            <span class="bri-metric__label">{{ $metric['label'] }}</span>
                            <span class="bri-metric__value">{{ $metric['display'] }}</span>
                        </div>
                        <div class="bri-metric__bar" aria-hidden="true">
                            <div class="bri-metric__fill" style="width: {{ $metric['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($broker['regulation_summary'])
            <p class="bri-card__regulation">{{ $broker['regulation_summary'] }}</p>
        @elseif($broker['short_description'])
            <p class="bri-card__regulation">{{ $broker['short_description'] }}</p>
        @endif
    </div>

    <div class="bri-card__footer">
        @if($broker['visit_url'])
            <a href="{{ $broker['visit_url'] }}"
               class="bri-btn bri-btn--primary"
               target="_blank"
               rel="noopener noreferrer nofollow">
                Visit broker
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
        <a href="{{ $broker['review_url'] }}" class="bri-btn bri-btn--ghost">Read review</a>
    </div>
</li>
