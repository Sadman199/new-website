@php
    $variant = $variant ?? 'grid';
    $gradient = \App\Services\BlogIndexService::insightGradient($index ?? 0);
    $authorName = $post['author'] ?? 'BrokersCourt Editorial';
@endphp

<article class="bli-card bli-card--{{ $variant }}">
    <a href="{{ $post['url'] }}"
       class="bli-card__media"
       style="--bli-card-gradient: {{ $gradient }}"
       tabindex="-1"
       aria-hidden="true">
        @if(!empty($post['photo']))
            <img src="{{ $post['photo'] }}" alt="" loading="lazy" decoding="async" width="480" height="270">
        @endif
        <span class="bli-card__badge">{{ $post['category'] }}</span>
    </a>

    <div class="bli-card__body">
        <p class="bli-card__meta">
            <time datetime="{{ $post['date_iso'] }}">{{ $post['date'] }}</time>
            <span class="bli-dot" aria-hidden="true"></span>
            <span>{{ $post['read_time'] }} min read</span>
        </p>

        <h3 class="bli-card__title">
            <a href="{{ $post['url'] }}">{{ $post['title'] }}</a>
        </h3>

        @if(!empty($post['excerpt']))
            <p class="bli-card__excerpt">{{ $post['excerpt'] }}</p>
        @endif

        <footer class="bli-card__footer">
            <span class="bli-author">
                <span class="bli-author__avatar" aria-hidden="true">
                    @if(!empty($post['author_photo']))
                        <img src="{{ $post['author_photo'] }}" alt="" loading="lazy" decoding="async">
                    @else
                        {{ strtoupper(substr($authorName, 0, 1)) }}
                    @endif
                </span>
                @if(!empty($post['author_url']))
                    <a href="{{ $post['author_url'] }}" class="bli-author__name">{{ $authorName }}</a>
                @else
                    <span class="bli-author__name">{{ $authorName }}</span>
                @endif
            </span>

            @if(($post['views'] ?? 0) > 0)
                <span class="bli-card__views">{{ number_format($post['views']) }} reads</span>
            @endif
        </footer>
    </div>
</article>
