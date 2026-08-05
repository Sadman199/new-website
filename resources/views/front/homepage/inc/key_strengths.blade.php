<div class="bv-section bv-section--strengths">
    <div class="bv-section__head">
        <span class="bv-section__num">2</span>
        <span class="bv-section__title">Key Strengths</span>
    </div>
    <div class="bv-strength-tabs" role="tablist">
        <button type="button" class="bv-strength-tab is-active" data-bv-strength-tab="regulatory">Regulatory Ranking</button>
        <button type="button" class="bv-strength-tab" data-bv-strength-tab="spread">Spread Ranking</button>
        <button type="button" class="bv-strength-tab" data-bv-strength-tab="leverage">Leverage Ranking</button>
        <button type="button" class="bv-strength-tab" data-bv-strength-tab="overall">Strength Ranking</button>
    </div>

    <div class="bv-strength-panel is-active" data-bv-strength-panel="regulatory">
        @foreach(($regulatoryRankings ?? collect())->take(6) as $broker)
            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-strength-row">
                <span class="bv-strength-row__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                    @else
                        {{ strtoupper(substr($broker->name, 0, 1)) }}
                    @endif
                </span>
                <span class="bv-strength-row__name">{{ $broker->name }}</span>
                <span class="bv-strength-row__score">{{ number_format($broker->rating, 1) }}</span>
            </a>
        @endforeach
    </div>

    <div class="bv-strength-panel" data-bv-strength-panel="spread">
        @foreach(($spreadRankings ?? collect())->take(6) as $broker)
            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-strength-row">
                <span class="bv-strength-row__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                    @else
                        {{ strtoupper(substr($broker->name, 0, 1)) }}
                    @endif
                </span>
                <span class="bv-strength-row__name">{{ $broker->name }}</span>
                <span class="bv-strength-row__score">{{ number_format($broker->rating, 1) }}</span>
            </a>
        @endforeach
    </div>

    <div class="bv-strength-panel" data-bv-strength-panel="leverage">
        @foreach(($best_leverage_brokers ?? collect())->take(6) as $broker)
            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-strength-row">
                <span class="bv-strength-row__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                    @else
                        {{ strtoupper(substr($broker->name, 0, 1)) }}
                    @endif
                </span>
                <span class="bv-strength-row__name">{{ $broker->name }}</span>
                <span class="bv-strength-row__meta">{{ $broker->leverage ?: '—' }}</span>
            </a>
        @endforeach
    </div>

    <div class="bv-strength-panel" data-bv-strength-panel="overall">
        @foreach($all_brokers->take(6) as $broker)
            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-strength-row">
                <span class="bv-strength-row__logo">
                    @if($broker->logo)
                        <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                    @else
                        {{ strtoupper(substr($broker->name, 0, 1)) }}
                    @endif
                </span>
                <span class="bv-strength-row__name">{{ $broker->name }}</span>
                <span class="bv-strength-row__score">{{ number_format($broker->rating, 1) }}</span>
            </a>
        @endforeach
    </div>
</div>
