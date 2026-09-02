@props([
    'broker',
    'rank' => null,
    'variant' => 'listing',
    'save' => false,
    'context' => null,
    'as' => 'article',
])

@php
    $card = \App\Support\BrokerCardData::from($broker);
    $compact = $variant === 'compact';
    $tag = in_array($as, ['article', 'li', 'div'], true) ? $as : 'article';

    // Try the richest source first, but always fall back to something so every
    // card reserves the same amount of space for its secondary line.
    $highlight = null;
    if (! $card['is_award_winner'] && ! empty($card['top_feature'])) {
        $highlight = \App\Support\RichText::toPlainText($card['top_feature']);
    } elseif (! empty($card['short_description'])) {
        $highlight = \App\Support\RichText::toPlainText($card['short_description']);
    } elseif (! empty($card['regulation_summary'])) {
        $highlight = $card['regulation_summary'];
    } elseif ($card['review_count'] > 0) {
        $highlight = number_format($card['review_count']) . ' user ' . \Illuminate\Support\Str::plural('review', $card['review_count']);
    } else {
        $highlight = $card['is_regulated']
            ? 'Regulated broker — see full trading conditions in our independent review.'
            : 'See full trading conditions and safety details in our independent review.';
    }
    $highlight = \Illuminate\Support\Str::limit($highlight, 92, '…');

    // Always render the same 4 stat slots (with a placeholder for missing data)
    // so every card lines up to the same grid and height.
    $stats = [
        ['label' => 'Min. deposit', 'value' => $card['minimum_deposit'] ?: '—'],
        ['label' => 'Leverage', 'value' => $card['leverage'] ?: '—'],
        ['label' => 'Spreads', 'value' => $card['spreads'] ?: '—'],
        ['label' => 'Platforms', 'value' => $card['platforms'] ?: '—'],
    ];

    $performance = array_slice($card['performance'], 0, $compact ? 4 : 2);
    $badge = null;
    if ($card['is_best_match']) {
        $badge = ['Best match', 'broker-badge--match'];
    } elseif ($card['is_match']) {
        $badge = ['Quiz match', 'broker-badge--match'];
    } elseif ($card['is_featured']) {
        $badge = ['Featured', ''];
    } elseif ($card['is_award_winner'] && $card['award_label']) {
        $badge = [$card['award_label'], ''];
    }

    $classes = ['broker-card'];
    if ($compact) {
        $classes[] = 'broker-card--compact';
    }
    if ($card['is_featured']) {
        $classes[] = 'is-featured';
    }
    if ($card['is_best_match']) {
        $classes[] = 'is-best-match';
    }
    if ($card['is_match']) {
        $classes[] = 'is-match';
    }

    $dataAttrs = [];
    if ($context === 'fmb') {
        $dataAttrs['data-fmb-card'] = '1';
        $dataAttrs['data-broker-id'] = $card['id'];
        $dataAttrs['data-broker-slug'] = $card['slug'];
    } elseif ($context === 'bri') {
        $dataAttrs['data-bri-card'] = '1';
        $dataAttrs['data-bri-name'] = $card['name'];
        $dataAttrs['data-bri-markets'] = implode(',', $card['markets']);
    } elseif ($context === 'rbi') {
        $dataAttrs['data-rbi-card'] = '1';
        $dataAttrs['data-rbi-name'] = $card['name'];
        $dataAttrs['data-rbi-regulators'] = implode(',', $card['regulator_slugs']);
        $dataAttrs['data-rbi-tier'] = $card['regulatory_tier_key'];
    }
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => implode(' ', $classes)])->merge($dataAttrs) }}>
    <div class="broker-card__brand">
        <a href="{{ $card['review_url'] }}" class="broker-card__logo" aria-hidden="true" tabindex="-1">
            @if($card['logo'])
                <img src="{{ $card['logo'] }}" alt="{{ $card['name'] }}" loading="lazy" decoding="async">
            @else
                <span class="broker-card__logo-fallback">{{ strtoupper(substr($card['name'], 0, 1)) }}</span>
            @endif
        </a>
        @if($rank)
            <span class="broker-card__rank">#{{ $rank }}</span>
        @endif
    </div>

    <div class="broker-card__main">
        <div class="broker-card__head">
            <a href="{{ $card['review_url'] }}" class="broker-card__name">{{ $card['name'] }}</a>
            @if($card['rating'] !== null)
                <x-broker.rating :rating="$card['rating']" />
            @endif
            @if($badge)
                <span class="broker-badge {{ $badge[1] }}">{{ $badge[0] }}</span>
            @endif
        </div>

        <p class="broker-card__tagline" title="{{ $highlight }}">{{ $highlight }}</p>

        <div class="broker-card__stats" aria-label="Trading conditions">
            @foreach($stats as $stat)
                <div class="broker-card__stat">
                    <span class="broker-card__stat-label">{{ $stat['label'] }}</span>
                    <span class="broker-card__stat-value" title="{{ $stat['value'] }}">{{ $stat['value'] }}</span>
                </div>
            @endforeach
        </div>

        @if($performance !== [])
            <div class="broker-card__performance" aria-label="Performance metrics">
                @foreach($performance as $metric)
                    <div class="broker-card__perf">
                        <div class="broker-card__perf-head">
                            <span class="broker-card__perf-label">{{ $metric['label'] ?? '' }}</span>
                            <span class="broker-card__perf-value">{{ $metric['display'] ?? '' }}</span>
                        </div>
                        <div class="broker-card__perf-bar" aria-hidden="true">
                            <div class="broker-card__perf-fill" style="width: {{ $metric['percent'] ?? 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="broker-card__actions">
        @if($card['visit_url'])
            <a href="{{ $card['visit_url'] }}" class="bc-btn bc-btn--primary" target="_blank" rel="noopener noreferrer nofollow">
                Visit broker
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        @endif
        <a href="{{ $card['review_url'] }}" class="bc-btn bc-btn--ghost">Read review</a>
        @if($save)
            <button type="button" class="bc-btn bc-btn--save" data-fmb-save aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                Save
            </button>
        @endif
        <p class="broker-card__disclaimer">Your capital is at risk.</p>
    </div>
</{{ $tag }}>
