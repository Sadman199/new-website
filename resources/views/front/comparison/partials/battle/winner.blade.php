@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $winner = $battle['winner'] ?? null;
    $wins = $battle['wins'] ?? ['broker1' => 0, 'broker2' => 0];
@endphp

<section class="bc-battle-winner" aria-labelledby="bcBattleWinnerTitle">
    <div class="bc-battle-winner__panel">
        <p class="bc-battle-winner__eyebrow">Battle winner</p>

        @if($winner)
            <h2 class="bc-battle-winner__title" id="bcBattleWinnerTitle">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                {{ $winner['name'] }}
            </h2>
            <p class="bc-battle-winner__reason">{{ $winner['reason'] }}</p>
        @else
            <h2 class="bc-battle-winner__title" id="bcBattleWinnerTitle">No decisive winner</h2>
            <p class="bc-battle-winner__reason">
                Category wins are tied at {{ $wins['broker1'] }}–{{ $wins['broker2'] }}, and there is not enough evidence to declare an overall winner from the available data.
            </p>
        @endif

        <div class="bc-battle-winner__actions">
            <a href="{{ $left['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost">View {{ $left['name'] }}</a>
            <a href="{{ $right['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost">View {{ $right['name'] }}</a>
            <a href="{{ route('broker.comparison') }}" class="bc-compare-btn bc-compare-btn--primary">Compare again</a>
        </div>
    </div>
</section>
