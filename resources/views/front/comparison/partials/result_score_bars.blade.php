@if(!empty($comparison['score_bars']))
<section class="bc-result-scores" id="bc-result-scores" aria-label="Category score comparison">
    <div class="bc-compare-wrap">
        <header class="bc-result-scores__head">
            <h2 class="bc-result-scores__title">Category scores</h2>
            <p class="bc-result-scores__sub">Editorial scores out of 10 — higher is better.</p>
        </header>

        <div class="bc-result-scores__grid">
            @foreach($comparison['score_bars'] as $bar)
                <article class="bc-result-score-row">
                    <h3 class="bc-result-score-row__label">{{ $bar['label'] }}</h3>
                    <div class="bc-result-score-row__duel">
                        <div class="bc-result-score-row__side {{ $bar['winner'] === 'broker1' ? 'is-best' : '' }}">
                            <span class="bc-result-score-row__name">{{ $comparison['broker1']['name'] }}</span>
                            <div class="bc-result-score-row__bar" aria-hidden="true">
                                <i style="width: {{ $bar['left_pct'] }}%"></i>
                            </div>
                            <strong>{{ $bar['left'] !== null ? number_format($bar['left'], 1) : '—' }}</strong>
                        </div>
                        <div class="bc-result-score-row__side {{ $bar['winner'] === 'broker2' ? 'is-best' : '' }}">
                            <span class="bc-result-score-row__name">{{ $comparison['broker2']['name'] }}</span>
                            <div class="bc-result-score-row__bar" aria-hidden="true">
                                <i style="width: {{ $bar['right_pct'] }}%"></i>
                            </div>
                            <strong>{{ $bar['right'] !== null ? number_format($bar['right'], 1) : '—' }}</strong>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
