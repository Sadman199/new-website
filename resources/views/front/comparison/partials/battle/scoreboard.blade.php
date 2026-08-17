@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $leftScore = $battle['scores']['broker1'] ?? ['display' => '—', 'value' => null];
    $rightScore = $battle['scores']['broker2'] ?? ['display' => '—', 'value' => null];
    $wins = $battle['wins'] ?? ['broker1' => 0, 'broker2' => 0];
    $winner = $battle['winner'] ?? null;
@endphp

<section class="bc-battle-scoreboard" aria-labelledby="bcBattleScoreTitle">
    <div class="container">
        <div class="bc-battle-scoreboard__panel">
            <p class="bc-battle-scoreboard__eyebrow">Overall battle score</p>
            <h2 class="bc-battle-scoreboard__title" id="bcBattleScoreTitle">Who leads this matchup?</h2>

            <div class="bc-battle-scoreboard__grid">
                <article class="bc-battle-score {{ ($winner['broker'] ?? '') === 'broker1' ? 'is-winner' : '' }}">
                    <span class="bc-battle-score__name">{{ $left['name'] }}</span>
                    <strong class="bc-battle-score__value">{{ $leftScore['display'] }}</strong>
                    <span class="bc-battle-score__meta">Evidence-based /10 score</span>
                </article>

                <div class="bc-battle-scoreboard__center">
                    @if($winner)
                        <p class="bc-battle-scoreboard__verdict">
                            <i class="fas fa-trophy" aria-hidden="true"></i>
                            <strong>{{ $winner['name'] }} Wins</strong>
                        </p>
                    @else
                        <p class="bc-battle-scoreboard__verdict bc-battle-scoreboard__verdict--tie">
                            <strong>No clear overall winner</strong>
                        </p>
                    @endif
                    <p class="bc-battle-scoreboard__tally">
                        <span>{{ $wins['broker1'] }} Wins</span>
                        <span aria-hidden="true">—</span>
                        <span>{{ $wins['broker2'] }} Wins</span>
                    </p>
                </div>

                <article class="bc-battle-score {{ ($winner['broker'] ?? '') === 'broker2' ? 'is-winner' : '' }}">
                    <span class="bc-battle-score__name">{{ $right['name'] }}</span>
                    <strong class="bc-battle-score__value">{{ $rightScore['display'] }}</strong>
                    <span class="bc-battle-score__meta">Evidence-based /10 score</span>
                </article>
            </div>
        </div>
    </div>
</section>
