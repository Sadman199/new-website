@php
    $highlight = (!$broker['is_award_winner'] && !empty($broker['top_feature']))
        ? $broker['top_feature']
        : ($broker['review_count'] > 0
            ? number_format($broker['review_count']) . ' user ' . \Illuminate\Support\Str::plural('review', $broker['review_count'])
            : ($broker['regulation_summary'] ?? $broker['short_description'] ?? null));
    $performance = array_slice($broker['performance'] ?? [], 0, 2);
@endphp

<article class="fmb-card {{ !empty($broker['is_featured']) ? 'is-featured' : '' }} {{ !empty($broker['is_best_match']) ? 'is-best-match' : '' }} {{ !empty($broker['is_match']) ? 'is-match' : '' }}" data-fmb-card data-broker-id="{{ $broker['id'] }}" data-broker-slug="{{ $broker['slug'] }}">
    <label class="fmb-card__compare">
        <input type="checkbox" class="fmb-card__compare-input" data-fmb-compare value="{{ $broker['slug'] }}">
        Compare
    </label>
    <div class="fmb-card__brand">
        <a href="{{ $broker['review_url'] }}" class="fmb-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}" loading="lazy" decoding="async">
            @else
                <span class="fmb-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>
        <span class="fmb-card__rank">#{{ $rank ?? '—' }}</span>
    </div>

    <div class="fmb-card__main">
        <div class="fmb-card__head">
            <a href="{{ $broker['review_url'] }}" class="fmb-card__name">{{ $broker['name'] }}</a>
            @if($broker['rating'] !== null)
                <span class="fmb-card__rating">
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ number_format($broker['rating'], 1) }}/5
                </span>
            @endif
            @if(!empty($broker['is_best_match']))
                <span class="fmb-card__badge fmb-card__badge--match">Best match</span>
            @elseif(!empty($broker['is_match']))
                <span class="fmb-card__badge fmb-card__badge--match">Quiz match</span>
            @elseif(!empty($broker['is_featured']))
                <span class="fmb-card__badge">Featured</span>
            @endif
        </div>

        @if($highlight)
            <p class="fmb-card__tagline">{{ $highlight }}</p>
        @endif

        <div class="fmb-card__metrics">
            <div class="fmb-card__metric">
                <span class="fmb-card__metric-label">Min. deposit</span>
                <span class="fmb-card__metric-value">{{ $broker['minimum_deposit'] ?? '—' }}</span>
            </div>
            <div class="fmb-card__metric">
                <span class="fmb-card__metric-label">Leverage</span>
                <span class="fmb-card__metric-value">{{ $broker['leverage'] ?? '—' }}</span>
            </div>
            <div class="fmb-card__metric">
                <span class="fmb-card__metric-label">Spreads</span>
                <span class="fmb-card__metric-value">{{ $broker['spreads'] ?? '—' }}</span>
            </div>
            <div class="fmb-card__metric">
                <span class="fmb-card__metric-label">Platforms</span>
                <span class="fmb-card__metric-value">{{ $broker['platforms'] ?? '—' }}</span>
            </div>
        </div>

        @if($performance !== [])
            <div class="fmb-card__performance" aria-label="Performance metrics">
                @foreach($performance as $metric)
                    <div class="fmb-card__perf">
                        <div class="fmb-card__perf-head">
                            <span class="fmb-card__perf-label">{{ $metric['label'] }}</span>
                            <span class="fmb-card__perf-value">{{ $metric['display'] }}</span>
                        </div>
                        <div class="fmb-card__perf-bar" aria-hidden="true">
                            <div class="fmb-card__perf-fill" style="width: {{ $metric['percent'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="fmb-card__actions">
        @if($broker['visit_url'])
            <a href="{{ $broker['visit_url'] }}"
               class="fmb-btn fmb-btn--primary"
               target="_blank"
               rel="noopener noreferrer nofollow">
                Visit broker
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
        <a href="{{ $broker['review_url'] }}" class="fmb-btn fmb-btn--ghost">Read review</a>
        <button type="button" class="fmb-btn fmb-btn--save" data-fmb-save aria-pressed="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
            </svg>
            Save
        </button>
        <p class="fmb-card__disclaimer">Your capital is at risk.</p>
    </div>
</article>
