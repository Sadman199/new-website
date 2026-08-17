@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $rounds = $battle['rounds'] ?? [];
@endphp

<section class="bc-battle-rounds" aria-labelledby="bcBattleRoundsTitle">
    <div class="bc-battle-section-head">
        <p class="bc-battle-section-head__eyebrow">Category battle</p>
        <h2 class="bc-battle-section-head__title" id="bcBattleRoundsTitle">Round-by-round results</h2>
        <p class="bc-battle-section-head__sub">Winners are calculated from live BrokersCourt data. Missing or non-rankable fields show “Not enough data”.</p>
    </div>

    <div class="bc-battle-rounds__list">
        @forelse($rounds as $round)
            <article class="bc-battle-round bc-battle-round--{{ $round['outcome'] }}">
                <header class="bc-battle-round__head">
                    <h3 class="bc-battle-round__label">{{ $round['label'] }}</h3>
                    <span class="bc-battle-round__status">
                        @if($round['outcome'] === 'broker1')
                            {{ $left['name'] }} wins
                        @elseif($round['outcome'] === 'broker2')
                            {{ $right['name'] }} wins
                        @elseif($round['outcome'] === 'tie')
                            Tie
                        @else
                            Not enough data
                        @endif
                    </span>
                </header>

                <div class="bc-battle-round__grid">
                    <div class="bc-battle-round__side {{ $round['outcome'] === 'broker1' ? 'is-winner' : '' }}">
                        <span class="bc-battle-round__broker">{{ $left['name'] }}</span>
                        <strong class="bc-battle-round__value">{{ $round['left'] }}</strong>
                        @if($round['outcome'] === 'broker1')
                            <span class="bc-battle-round__trophy" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                        @endif
                    </div>

                    <div class="bc-battle-round__vs" aria-hidden="true">VS</div>

                    <div class="bc-battle-round__side {{ $round['outcome'] === 'broker2' ? 'is-winner' : '' }}">
                        <span class="bc-battle-round__broker">{{ $right['name'] }}</span>
                        <strong class="bc-battle-round__value">{{ $round['right'] }}</strong>
                        @if($round['outcome'] === 'broker2')
                            <span class="bc-battle-round__trophy" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="bc-battle-empty">
                <p>Not enough comparable data is available for this battle yet.</p>
            </div>
        @endforelse
    </div>
</section>
