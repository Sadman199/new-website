<section class="bc-trust-section" id="community-sentiment" aria-labelledby="bcTrustTitle">
    <div class="bc-container bc-trust-section__container">
        <header class="bc-trust-section__head">
            <div class="bc-trust-section__intro">
                <p class="bc-trust-section__eyebrow">
                    <span class="bc-trust-section__eyebrow-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </span>
                    Trust &amp; rankings
                </p>
                <h2 id="bcTrustTitle" class="bc-trust-section__title">Broker trust board</h2>
                <p class="bc-trust-section__sub">Editor picks, live popularity scores, and verified scam alerts in one place.</p>
            </div>
            <a href="{{ route('broker.reviews.index') }}" class="bc-trust-section__cta">
                Browse reviews
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </header>

        <div class="bc-trust-board">
            <div class="bc-trust-board__body">
                <div class="bc-trust-board__primary">
                    @if($sentimentRecommended->isNotEmpty())
                        <section class="bc-trust-zone bc-trust-zone--recommended" aria-labelledby="bcTrustRecTitle">
                            <header class="bc-trust-zone__head">
                                <div class="bc-trust-zone__title-wrap">
                                    <span class="bc-trust-zone__icon bc-trust-zone__icon--good" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </span>
                                    <div>
                                        <h3 id="bcTrustRecTitle" class="bc-trust-zone__title">Recommended</h3>
                                        <p class="bc-trust-zone__desc">Top Broker Rank · highest first</p>
                                    </div>
                                </div>
                                <span class="bc-trust-zone__count">{{ $sentimentRecommended->count() }}</span>
                            </header>
                            <div class="bc-trust-card-grid">
                                @foreach($sentimentRecommended as $item)
                                    @include('front.homepage.inc.broker_trust_recommend_card', ['item' => $item])
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($sentimentScam->isNotEmpty())
                        <section class="bc-trust-zone bc-trust-zone--caution" aria-labelledby="bcTrustScamTitle">
                            <header class="bc-trust-zone__head">
                                <div class="bc-trust-zone__title-wrap">
                                    <span class="bc-trust-zone__icon bc-trust-zone__icon--warn" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71a1.5 1.5 0 001.943-1.883l-3.68-6.375a1.5 1.5 0 00-2.598 0L12.75 17.25 8.532 9.623a1.5 1.5 0 00-2.598 0L2.254 16.126z"/></svg>
                                    </span>
                                    <div>
                                        <h3 id="bcTrustScamTitle" class="bc-trust-zone__title">Scam alerts</h3>
                                        <p class="bc-trust-zone__desc">Brokers flagged in our database — avoid these</p>
                                    </div>
                                </div>
                                <a href="{{ route('scam_brokers') }}" class="bc-trust-zone__link">All warnings</a>
                            </header>
                            <div class="bc-trust-card-grid bc-trust-card-grid--alert">
                                @foreach($sentimentScam as $item)
                                    @include('front.homepage.inc.broker_trust_scam_card', ['item' => $item])
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                @if($sentimentRanking->isNotEmpty())
                    <aside class="bc-trust-board__aside bc-trust-zone bc-trust-zone--ranking" aria-labelledby="bcTrustLeaderTitle">
                        <header class="bc-trust-zone__head">
                            <div class="bc-trust-zone__title-wrap">
                                <span class="bc-trust-zone__icon bc-trust-zone__icon--rank" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                                </span>
                                <div>
                                    <h3 id="bcTrustLeaderTitle" class="bc-trust-zone__title">Popularity ranking</h3>
                                    <p class="bc-trust-zone__desc">Rating, trust, reviews &amp; editor rank</p>
                                </div>
                            </div>
                        </header>
                        <ol class="bc-trust-leaderboard__list">
                            @foreach($sentimentRanking as $item)
                                @php $broker = $item['broker']; @endphp
                                <li>
                                    <a href="{{ $item['review_url'] }}" class="bc-trust-leader-row">
                                        <span @class([
                                            'bc-trust-leader-row__rank',
                                            'bc-trust-leader-row__rank--gold' => $item['rank'] === 1,
                                            'bc-trust-leader-row__rank--silver' => $item['rank'] === 2,
                                            'bc-trust-leader-row__rank--bronze' => $item['rank'] === 3,
                                        ])>{{ $item['rank'] }}</span>

                                        <div class="bc-trust-leader-row__logo">
                                            @if($broker->logo)
                                                <img src="{{ asset($broker->logo) }}" alt="">
                                            @else
                                                <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                            @endif
                                        </div>

                                        <div class="bc-trust-leader-row__body">
                                            <span class="bc-trust-leader-row__name">{{ $broker->name }}</span>
                                            <span class="bc-trust-leader-row__meta">
                                                @if($item['rating'] !== null)
                                                    {{ number_format($item['rating'], 1) }} rating
                                                @endif
                                                @if($item['trust_score'] !== null)
                                                    · {{ $item['trust_score'] }} trust
                                                @endif
                                                @if($item['review_count'] > 0)
                                                    · {{ $item['review_count'] }} {{ Str::plural('review', $item['review_count']) }}
                                                @endif
                                            </span>
                                            <span class="bc-trust-leader-row__meter" style="--score: {{ $item['popularity_score'] }}" aria-hidden="true">
                                                <span class="bc-trust-leader-row__meter-fill"></span>
                                            </span>
                                        </div>

                                        <span class="bc-trust-leader-row__score" aria-label="Popularity score {{ $item['popularity_score'] }}">
                                            <small>Score</small>
                                            {{ $item['popularity_score'] }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ol>
                    </aside>
                @endif
            </div>
        </div>
    </div>
</section>
