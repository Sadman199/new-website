@php
    $gradient = $gradient ?? \App\Services\BlogIndexService::insightGradient($index ?? 0);
    $category = $category ?? 'Insights';
    $authorName = $authorName ?? 'BrokersCourt Editorial';
@endphp

<article class="bc-insight-card">
    <a href="{{ $url }}" class="bc-insight-card__link">
        <div class="bc-insight-card__media" style="--bc-insight-gradient: {{ $gradient }}">
            @if(!empty($photo))
                <img src="{{ $photo }}" alt="" loading="lazy">
            @endif
            <span class="bc-insight-card__badge">{{ $category }}</span>
        </div>

        <div class="bc-insight-card__body">
            <div class="bc-insight-card__meta">
                <time datetime="{{ $dateIso }}">{{ $date }}</time>
                <span class="bc-insight-card__dot" aria-hidden="true"></span>
                <span>{{ $readMinutes }} min read</span>
            </div>

            <h3 class="bc-insight-card__title">{{ $title }}</h3>

            <div class="bc-insight-card__author">
                <span class="bc-insight-card__avatar" aria-hidden="true">
                    @if(!empty($authorPhoto))
                        <img src="{{ $authorPhoto }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($authorName, 0, 1)) }}</span>
                    @endif
                </span>
                <span class="bc-insight-card__author-name">{{ $authorName }}</span>
            </div>
        </div>
    </a>
</article>
