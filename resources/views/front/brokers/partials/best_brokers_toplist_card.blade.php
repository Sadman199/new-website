<li class="bbh-card-wrap"
    data-bbh-card
    data-bbh-type="{{ $list['type'] ?? 'category' }}"
    data-bbh-title="{{ $list['title'] }}"
    data-bbh-slug="{{ $list['slug'] ?? '' }}"
    data-bbh-filters="{{ implode(',', $list['filters'] ?? []) }}">
    <article class="bbh-card">
        <div class="bbh-card__body">
            <div class="bbh-card__top">
                <span class="bbh-card__badge">{{ ($list['type'] ?? '') === 'country' ? 'Country' : 'Category' }}</span>
                @if(!empty($list['broker_count']))
                    <span class="bbh-card__count">{{ number_format($list['broker_count']) }} {{ \Illuminate\Support\Str::plural('broker', $list['broker_count']) }}</span>
                @endif
            </div>

            <a href="{{ $list['url'] }}" class="bbh-card__title">{{ $list['title'] }}</a>

            @if(!empty($list['description']))
                <p class="bbh-card__desc">{{ $list['description'] }}</p>
            @endif

            @if(!empty($list['broker_logos']))
                <div class="bbh-card__logos" aria-hidden="true">
                    @foreach($list['broker_logos'] as $broker)
                        <span class="bbh-card__logo">
                            @if($broker['logo'])
                                <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async" width="36" height="36">
                            @else
                                <span class="bbh-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                            @endif
                        </span>
                    @endforeach
                    <span class="bbh-card__logos-fade"></span>
                </div>
            @endif

            <span class="bbh-card__updated">{{ $list['updated_label'] }}</span>
        </div>

        <div class="bbh-card__footer">
            <a href="{{ $list['url'] }}" class="bbh-card__cta" aria-label="{{ $ctaLabel ?? 'View toplist' }}">
                <span class="bbh-card__cta-label">{{ $ctaLabel ?? 'View toplist' }}</span>
                <span class="bbh-card__cta-icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                    </svg>
                </span>
            </a>
        </div>
    </article>
</li>
