@php
    $warnings = implode(',', $broker['warning_tags'] ?? []);
@endphp
<li class="sbi-card"
    data-sbi-card
    data-sbi-name="{{ $broker['name'] }}"
    data-sbi-warnings="{{ $warnings }}">
    <div class="sbi-card__head">
        <a href="{{ $broker['detail_url'] }}" class="sbi-card__logo" aria-hidden="true" tabindex="-1">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}">
            @else
                <span class="sbi-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </a>
        <div class="sbi-card__identity">
            <a href="{{ $broker['detail_url'] }}" class="sbi-card__name">{{ $broker['name'] }}</a>
            <span class="sbi-card__badge">Scam warning</span>
        </div>
    </div>

    <div class="sbi-card__body">
        <p class="sbi-card__reason">{{ $broker['scam_reason_excerpt'] }}</p>

        @if(!empty($broker['warning_tags']))
            <div class="sbi-card__tags">
                @foreach($broker['warning_tags'] as $tag)
                    @if(isset($warningFilters[$tag]))
                        <span class="sbi-card__tag">{{ $warningFilters[$tag] }}</span>
                    @endif
                @endforeach
            </div>
        @endif

        <div class="sbi-card__meta">
            <div class="sbi-card__meta-row">
                <span class="sbi-card__meta-label">Reported</span>
                <span class="sbi-card__meta-value">{{ $broker['reported_label'] }}</span>
            </div>
            @if($broker['country'])
                <div class="sbi-card__meta-row">
                    <span class="sbi-card__meta-label">Country</span>
                    <span class="sbi-card__meta-value">{{ $broker['country'] }}</span>
                </div>
            @endif
            <div class="sbi-card__meta-row">
                <span class="sbi-card__meta-label">Regulation</span>
                <span class="sbi-card__meta-value {{ $broker['is_regulated'] ? '' : 'sbi-card__meta-value--danger' }}">
                    {{ $broker['regulation_summary'] }}
                </span>
            </div>
        </div>

        <div class="sbi-card__actions">
            <a href="{{ $broker['detail_url'] }}" class="sbi-card__cta">
                Read warning
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</li>
