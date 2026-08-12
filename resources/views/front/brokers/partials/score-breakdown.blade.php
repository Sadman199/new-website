@if(!empty($scoreBreakdown['has_scores']))
<section class="br-section br-section--scores" id="score-breakdown">
    <div class="br-section__head br-section__head--row">
        <div>
            <h2 class="br-section__title">Score Breakdown</h2>
            <p class="br-section__desc">
                How {{ $broker->name }} rates across our review categories (out of 10).
            </p>
        </div>
        <div class="br-score-summary">
            <div class="br-score-summary__ring" aria-label="Overall score {{ $scoreBreakdown['overall_display'] }} out of 10">
                <span class="br-score-summary__value">{{ $scoreBreakdown['overall_display'] }}</span>
                <span class="br-score-summary__label">Overall</span>
            </div>
            @if($scoreBreakdown['updated_at'])
                <p class="br-score-summary__updated">
                    <i class="far fa-clock" aria-hidden="true"></i>
                    Updated {{ $scoreBreakdown['updated_at'] }}
                </p>
            @endif
        </div>
    </div>

    <div class="br-section__body">
        <div class="br-score-breakdown">
            @foreach($scoreBreakdown['items'] as $item)
                <article class="br-score-row br-score-row--{{ $item['tier'] }}">
                    <div class="br-score-row__head">
                        <span class="br-score-row__icon" aria-hidden="true">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </span>
                        <div class="br-score-row__copy">
                            <h3 class="br-score-row__label">{{ $item['label'] }}</h3>
                            <span class="br-score-row__tier">{{ $item['tier_label'] }}</span>
                        </div>
                        <div class="br-score-row__value-wrap">
                            <span class="br-score-row__value">{{ $item['display'] }}</span>
                            <span class="br-score-row__max">/10</span>
                        </div>
                    </div>
                    <div class="br-score-row__bar" role="presentation">
                        <div class="br-score-row__bar-fill" style="width: {{ $item['percent'] }}%"></div>
                    </div>
                </article>
            @endforeach
        </div>

        @if(!empty($scoreBreakdown['strengths']) || !empty($scoreBreakdown['weaknesses']))
            <div class="br-score-insights">
                @if(!empty($scoreBreakdown['strengths']))
                    <div class="br-score-insights__col">
                        <h3 class="br-score-insights__title br-score-insights__title--up">
                            <i class="fas fa-arrow-up" aria-hidden="true"></i> Top strengths
                        </h3>
                        <ul class="br-score-insights__list">
                            @foreach($scoreBreakdown['strengths'] as $item)
                                <li>
                                    <strong>{{ $item['label'] }}</strong>
                                    <span>{{ $item['display'] }}/10</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($scoreBreakdown['weaknesses']))
                    <div class="br-score-insights__col">
                        <h3 class="br-score-insights__title br-score-insights__title--down">
                            <i class="fas fa-arrow-down" aria-hidden="true"></i> Areas to improve
                        </h3>
                        <ul class="br-score-insights__list">
                            @foreach($scoreBreakdown['weaknesses'] as $item)
                                <li>
                                    <strong>{{ $item['label'] }}</strong>
                                    <span>{{ $item['display'] }}/10</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <p class="br-score-footnote">
            Scores reflect our editorial methodology.
            <a href="{{ route('methodology') }}">Learn how we rate brokers</a>.
        </p>
    </div>
</section>
@endif
