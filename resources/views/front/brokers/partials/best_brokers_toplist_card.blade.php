<li class="bbh-card-wrap {{ ($spotlight ?? false) ? 'bbh-card-wrap--spotlight' : '' }}"
    data-bbh-card
    data-bbh-spotlight="{{ ($spotlight ?? false) ? 'true' : 'false' }}"
    data-bbh-title="{{ $list['title'] }}"
    data-bbh-desc="{{ $list['description'] }}"
    data-bbh-filters="{{ implode(',', $list['filters'] ?? []) }}">
    <article class="bbh-card {{ ($spotlight ?? false) ? 'bbh-card--award' : '' }}">
        @if($spotlight ?? false)
            <div>
                <span class="bbh-card__eyebrow">BrokersCourt</span>
                <a href="{{ $list['url'] }}" class="bbh-card__title">{{ $list['title'] }}</a>
                <p class="bbh-card__desc">{{ $list['description'] }}</p>
            </div>
        @else
            <div>
                <a href="{{ $list['url'] }}" class="bbh-card__title">{{ $list['title'] }}</a>
                <p class="bbh-card__desc">{{ $list['description'] }}</p>

                @if(!empty($list['broker_logos']))
                    <div class="bbh-card__logos" aria-hidden="true">
                        @foreach($list['broker_logos'] as $broker)
                            <span class="bbh-card__logo">
                                @if($broker['logo'])
                                    <img src="{{ $broker['logo'] }}" alt="">
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
        @endif

        <div class="bbh-card__footer">
            <a href="{{ $list['url'] }}" class="bbh-card__cta" aria-label="{{ $ctaLabel ?? 'View toplist' }}">
                <span class="bbh-card__cta-bar"></span>
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
