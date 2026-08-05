<li class="awi-card"
    data-awi-card
    data-awi-name="{{ $award['name'] }}">
    <div class="awi-card__head">
        <span class="awi-card__icon" aria-hidden="true">🏆</span>
        <div class="awi-card__identity">
            <h3 class="awi-card__name">{{ $award['name'] }}</h3>
            <span class="awi-card__count">
                {{ $award['broker_count'] }} {{ \Illuminate\Support\Str::plural('broker', $award['broker_count']) }}
            </span>
        </div>
    </div>

    <div class="awi-card__body">
        <p class="awi-card__desc">{{ $award['description'] }}</p>

        @if(!empty($award['broker_logos']))
            <div class="awi-card__logos" aria-hidden="true">
                @foreach($award['broker_logos'] as $broker)
                    <span class="awi-card__logo">
                        @if($broker['logo'])
                            <img src="{{ $broker['logo'] }}" alt="">
                        @else
                            <span class="awi-card__logo-fallback">{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                        @endif
                    </span>
                @endforeach
            </div>
        @endif

        <a href="{{ $award['url'] }}" class="awi-card__cta">
            Explore brokers
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
            </svg>
        </a>
    </div>
</li>
