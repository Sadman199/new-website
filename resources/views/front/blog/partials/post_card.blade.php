@php
    $isTop = ($variant ?? 'grid') === 'top';
    $cardClass = $isTop ? 'bli-card bli-card--top' : 'bli-card bli-card--grid';
@endphp

<article class="{{ $cardClass }}" data-bli-post>
    <a href="{{ $post['url'] }}" class="bli-card__media" tabindex="-1" aria-hidden="true">
        <img src="{{ $post['photo'] }}"
             alt=""
             loading="lazy"
             width="400"
             height="{{ $isTop ? 220 : 200 }}">
    </a>
    <div class="bli-card__body">
        <div class="bli-card__meta">
            <span class="bli-badge" style="--bli-badge-color: {{ $post['subcategory']['color'] }}">
                {{ $post['subcategory']['name'] }}
            </span>
            <time datetime="{{ $post['date'] }}">{{ $post['date_short'] }}</time>
            <span class="bli-dot" aria-hidden="true"></span>
            <span>{{ $post['read_time'] }} min</span>
        </div>
        <h3 class="bli-card__title">
            <a href="{{ $post['url'] }}">{{ $post['title'] }}</a>
        </h3>
        <p class="bli-card__excerpt">{{ $post['excerpt'] }}</p>
        <div class="bli-card__footer">
            <span>{{ $post['author'] }}</span>
            @if(!$isTop && $post['views'] > 0)
                <span class="bli-card__views">{{ number_format($post['views']) }} reads</span>
            @endif
        </div>
    </div>
</article>
