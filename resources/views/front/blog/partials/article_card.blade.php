@php
    $gradient = \App\Services\BlogIndexService::insightGradient($index ?? 0);
    $authorName = $post['author'] ?? '';
    $authorUrl = $post['author_url'] ?? null;
    $kicker = $post['category'] ?? ($post['subcategory']['name'] ?? 'Insights');
@endphp

<article class="bli-card">
    <a href="{{ $post['url'] }}" class="bli-card__media" style="--bli-card-gradient: {{ $gradient }}" tabindex="-1" aria-hidden="true">
        @if(!empty($post['photo']))
            <img src="{{ $post['photo'] }}" alt="" loading="lazy" decoding="async" width="480" height="270">
        @endif
    </a>

    <div class="bli-card__body">
        <p class="bli-card__cat">{{ $kicker }}</p>
        <h2 class="bli-card__title">
            <a href="{{ $post['url'] }}">{{ $post['title'] }}</a>
        </h2>
        <p class="bli-card__meta">
            @if($authorName !== '')
                @if(!empty($authorUrl))
                    <a href="{{ $authorUrl }}" class="bli-card__author">{{ $authorName }}</a>
                @else
                    <span class="bli-card__author">{{ $authorName }}</span>
                @endif
                <span class="bli-dot" aria-hidden="true"></span>
            @endif
            @if(!empty($post['date_rel']))
                <time datetime="{{ $post['date_iso'] }}">{{ $post['date_rel'] }}</time>
            @elseif(!empty($post['date']))
                <time datetime="{{ $post['date_iso'] }}">{{ $post['date'] }}</time>
            @endif
        </p>
    </div>
</article>
