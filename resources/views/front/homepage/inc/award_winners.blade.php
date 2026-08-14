@php
    $winners = collect($awardWinners ?? [])->take(6)->values();
    $awardYear = date('Y');
@endphp

@if($winners->isNotEmpty())
    <section class="bc-awards" id="award-winners" aria-labelledby="bcAwardsTitle">
        <div class="container">
            <header class="bc-awards__head">
                <div class="bc-awards__intro">
                    <p class="bc-awards__eyebrow">
                        <span class="bc-awards__eyebrow-icon" aria-hidden="true">
                            <i class="fas fa-trophy"></i>
                        </span>
                        BrokersCourt Awards {{ $awardYear }}
                    </p>
                    <h2 id="bcAwardsTitle" class="bc-awards__title">This year&rsquo;s award winners</h2>
                    <p class="bc-awards__sub">Category winners chosen by our editorial team on regulation, costs, platforms, and verified client feedback.</p>
                </div>
                <a href="{{ route('awards.index') }}" class="bc-awards__cta">
                    All award categories
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </a>
            </header>

            <div class="bc-awards__grid">
                @foreach($winners as $i => $winner)
                    <article @class(['bc-award-card', 'bc-award-card--featured' => $i === 0])>
                        <span class="bc-award-card__glow" aria-hidden="true"></span>

                        <header class="bc-award-card__head">
                            <span class="bc-award-card__medal" aria-hidden="true">
                                <i class="fas {{ $i === 0 ? 'fa-crown' : 'fa-medal' }}"></i>
                            </span>
                            <span class="bc-award-card__category">
                                <small>{{ $i === 0 ? 'Overall winner' : 'Winner' }}</small>
                                <strong>{{ $winner['award'] }}</strong>
                            </span>
                        </header>

                        @if(! empty($winner['description']))
                            <p class="bc-award-card__desc">
                                {{ $i === 0 ? $winner['description'] : \Illuminate\Support\Str::limit($winner['description'], 78) }}
                            </p>
                        @endif

                        <div class="bc-award-card__broker">
                            <span class="bc-award-card__logo">
                                @if($winner['broker_logo'])
                                    <img src="{{ $winner['broker_logo'] }}" alt="{{ $winner['broker_name'] }} logo" loading="lazy" decoding="async">
                                @else
                                    <span class="bc-award-card__logo-fallback">{{ strtoupper(substr($winner['broker_name'], 0, 1)) }}</span>
                                @endif
                            </span>
                            <span class="bc-award-card__identity">
                                <a href="{{ $winner['broker_url'] }}" class="bc-award-card__name">{{ $winner['broker_name'] }}</a>
                                <span class="bc-award-card__meta">
                                    @if($winner['broker_rating'] !== null)
                                        <span class="bc-award-card__score">
                                            <i class="fas fa-star" aria-hidden="true"></i>
                                            {{ number_format($winner['broker_rating'], 1) }}
                                        </span>
                                    @endif
                                    @if($winner['broker_regulated'])
                                        <span class="bc-award-card__pill">Regulated</span>
                                    @endif
                                </span>
                            </span>
                        </div>

                        <footer class="bc-award-card__foot">
                            <span class="bc-award-card__contenders">{{ number_format($winner['contenders']) }} brokers assessed</span>
                            <a href="{{ $winner['award_url'] }}" class="bc-award-card__link">
                                See category
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </a>
                        </footer>
                    </article>
                @endforeach

                <a href="{{ route('methodology') }}" class="bc-award-method">
                    <span class="bc-award-method__icon" aria-hidden="true"><i class="fas fa-microscope"></i></span>
                    <h3 class="bc-award-method__title">How we judge</h3>
                    <p class="bc-award-method__desc">Every category is scored against the same four pillars — no paid placements.</p>
                    <span class="bc-award-method__pillars">
                        <span>Regulation</span>
                        <span>Costs</span>
                        <span>Platforms</span>
                        <span>Client experience</span>
                    </span>
                    <span class="bc-award-method__link">
                        Read our methodology
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </a>
            </div>
        </div>
    </section>
@endif
