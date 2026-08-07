<li class="awd-card awd-card--{{ $award['color'] }}"
    data-awi-card
    data-awi-name="{{ $award['name'] }}"
    style="--awd-card-delay: {{ ($index ?? 0) * 60 }}ms">
    <a href="{{ $award['url'] }}" class="awd-card__link">
        <div class="awd-card__head">
            <span class="awd-card__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172"/>
                </svg>
            </span>
            <div class="awd-card__identity">
                <h3 class="awd-card__name">{{ $award['name'] }}</h3>
                <span class="awd-card__count">
                    {{ $award['broker_count'] }} {{ \Illuminate\Support\Str::plural('broker', $award['broker_count']) }}
                </span>
            </div>
            <span class="awd-card__arrow" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </span>
        </div>

        <p class="awd-card__desc">{{ $award['description'] }}</p>

        @if(!empty($award['broker_logos']))
            <div class="awd-card__logos" aria-label="Top brokers in this category">
                @foreach($award['broker_logos'] as $broker)
                    <span class="awd-card__logo" title="{{ $broker['name'] }}">
                        @if($broker['logo'])
                            <img src="{{ $broker['logo'] }}" alt="{{ $broker['name'] }}">
                        @else
                            <span class="awd-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                        @endif
                    </span>
                @endforeach
            </div>
        @endif

        @if(!empty($award['top_broker']))
            <span class="awd-card__leader">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7L12 16.8 5.7 21l2.3-7-6-4.6h7.6L12 2z"/></svg>
                Leading: {{ $award['top_broker'] }}
            </span>
        @endif
    </a>
</li>
