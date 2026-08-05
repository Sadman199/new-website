<div class="bv-section bv-section--popularity">
    <div class="bv-section__head">
        <span class="bv-section__num">4</span>
        <span class="bv-section__title">Popularity and Service</span>
    </div>
    <div class="bv-popularity">
        <div class="bv-popularity__col">
            <div class="bv-popularity__label bv-popularity__label--good">Recommend</div>
            <div class="bv-popularity__grid">
                @foreach($all_brokers->take(6) as $broker)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-pop-card bv-pop-card--good">
                        <div class="bv-pop-card__logo">
                            @if($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="">
                            @else
                                {{ strtoupper(substr($broker->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="bv-pop-card__name">{{ $broker->name }}</div>
                        <div class="bv-pop-card__pct">{{ number_format(min(100, $broker->rating * 10), 0) }}% Satisfied</div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="bv-popularity__col">
            <div class="bv-popularity__label bv-popularity__label--bad">Avoid</div>
            <div class="bv-popularity__grid">
                @foreach(($non_regulatedBrokers ?? collect())->take(6) as $broker)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-pop-card bv-pop-card--bad">
                        <div class="bv-pop-card__logo">
                            @if($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="">
                            @else
                                {{ strtoupper(substr($broker->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="bv-pop-card__name">{{ $broker->name }}</div>
                        <div class="bv-pop-card__pct">Low trust score</div>
                    </a>
                @endforeach
                @if(($non_regulatedBrokers ?? collect())->isEmpty())
                    @foreach($all_brokers->slice(6, 3) as $broker)
                        <div class="bv-pop-card bv-pop-card--bad bv-pop-card--muted">
                            <div class="bv-pop-card__name">{{ $broker->name }}</div>
                            <div class="bv-pop-card__pct">Review carefully</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
