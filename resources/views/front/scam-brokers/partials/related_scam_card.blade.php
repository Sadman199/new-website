<li class="sbd-related-card">
    <a href="{{ $broker['detail_url'] }}" class="sbd-related-card__link">
        <span class="sbd-related-card__logo" aria-hidden="true">
            @if($broker['logo'])
                <img src="{{ $broker['logo'] }}" alt="">
            @else
                <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
            @endif
        </span>
        <span class="sbd-related-card__body">
            <span class="sbd-related-card__name">{{ $broker['name'] }}</span>
            <span class="sbd-related-card__meta">{{ $broker['reported_label'] }}</span>
            @if(!empty($broker['warning_tags'][0]) && isset($warningFilters[$broker['warning_tags'][0]]))
                <span class="sbd-related-card__tag">{{ $warningFilters[$broker['warning_tags'][0]] }}</span>
            @endif
        </span>
        <svg class="sbd-related-card__arrow" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 0 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
        </svg>
    </a>
</li>
