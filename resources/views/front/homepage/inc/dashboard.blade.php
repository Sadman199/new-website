<div class="bv-find-brokers">
    <h2 class="bv-find-brokers__title">Find Brokers</h2>

    <div class="bv-dashboard">
        <div class="bv-dashboard__main">
            {{-- 1 Overall Ranking --}}
            <div class="bv-section">
                <div class="bv-section__head">
                    <span class="bv-section__num">1</span>
                    <span class="bv-section__title">Overall Ranking</span>
                    <a href="{{ route('broker.reviews.index') }}" class="bv-section__link">More ›</a>
                </div>
                <div class="bv-ranking-scroll">
                    @foreach($topRatedBrokers as $broker)
                        <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-rank-card">
                            <div class="bv-rank-card__logo">
                                @if($broker->logo)
                                    <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                                @else
                                    <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="bv-rank-card__name">{{ $broker->name }}</div>
                            <div class="bv-rank-card__score">{{ number_format($broker->rating, 1) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>

            @include('front.homepage.inc.key_strengths')
            @include('front.homepage.inc.assessment')
            @include('front.homepage.inc.popularity')

            {{-- Broker comparison table --}}
            <div class="bv-section">
                <div class="bv-section__head">
                    <span class="bv-section__num">5</span>
                    <span class="bv-section__title">Broker Rankings</span>
                    <a href="{{ route('all_brokers') }}" class="bv-section__link">View all ›</a>
                </div>
                <div class="bv-table-head">
                    <span></span>
                    <span>Broker</span>
                    <span>Regulator</span>
                    <span>Min Deposit</span>
                    <span>Leverage</span>
                    <span>Score</span>
                </div>
                @foreach($all_brokers as $index => $broker)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-table-row bv-table-row--full">
                        <span class="bv-table-row__rank">{{ $index + 1 }}</span>
                        <div class="bv-table-row__broker">
                            <span class="bv-table-row__name">{{ $broker->name }}</span>
                            <span class="bv-table-row__meta">{{ $broker->country ?: 'Global' }}</span>
                        </div>
                        <span class="bv-table-row__reg">{{ Str::limit(implode(', ', array_slice($broker->regulationList(), 0, 2)), 24) ?: '—' }}</span>
                        <span class="bv-table-row__deposit">${{ number_format((float) ($broker->minimum_deposit ?? 0), 0) }}</span>
                        <span class="bv-table-row__lev">{{ $broker->leverage ?: '—' }}</span>
                        <span class="bv-table-row__score">{{ number_format($broker->rating, 1) }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Promotions --}}
            <div class="bv-section">
                <div class="bv-section__head">
                    <span class="bv-section__num">6</span>
                    <span class="bv-section__title">Trading Promotions</span>
                </div>
                <div class="bv-promo-row">
                    <a href="{{ route('bonuses.type','deposit-bonuses') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">Deposit Bonus</div>
                        <div class="bv-promo-mini__sub">Up to 50% extra</div>
                    </a>
                    <a href="{{ route('bonuses.type','no-deposit-bonuses') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">No Deposit</div>
                        <div class="bv-promo-mini__sub">Free trading credit</div>
                    </a>
                    <a href="{{ route('bonuses.type','live-contests') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">Live Contest</div>
                        <div class="bv-promo-mini__sub">Prize pools</div>
                    </a>
                    <a href="{{ route('bonuses.type','cashback-rebates') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">Cashback</div>
                        <div class="bv-promo-mini__sub">Up to 15% weekly</div>
                    </a>
                    <a href="{{ route('broker.comparison') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">Compare Brokers</div>
                        <div class="bv-promo-mini__sub">Side-by-side up to 3</div>
                    </a>
                    <a href="{{ route('find_my_broker') }}" class="bv-promo-mini">
                        <div class="bv-promo-mini__title">Find My Broker</div>
                        <div class="bv-promo-mini__sub">15+ filter criteria</div>
                    </a>
                </div>
            </div>
        </div>

        <aside class="bv-sidebar">
            <div class="bv-section">
                <div class="bv-section__head">
                    <span class="bv-section__title">Top Ranking</span>
                </div>
                <ul class="bv-top-list">
                    @foreach($all_brokers->take(10) as $index => $broker)
                        <li>
                            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-top-item">
                                <span class="bv-top-item__rank bv-top-item__rank--{{ min($index + 1, 3) }}">{{ $index + 1 }}</span>
                                <span class="bv-top-item__logo">
                                    @if($broker->logo)
                                        <img src="{{ asset($broker->logo) }}" alt="">
                                    @else
                                        <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                    @endif
                                </span>
                                <span class="bv-top-item__info">
                                    <span class="bv-top-item__name">{{ $broker->name }}</span>
                                </span>
                                <span class="bv-top-item__score">{{ number_format($broker->rating, 1) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bv-section">
                <div class="bv-section__head">
                    <span class="bv-section__title">Regulated Brokers</span>
                    <a href="{{ route('regulated_brokers') }}" class="bv-section__link">All ›</a>
                </div>
                <ul class="bv-reg-list">
                    @foreach($regulatedBrokers->take(8) as $broker)
                        <li>
                            <a href="{{ route('broker_detail', $broker->slug) }}" class="bv-reg-item">
                                <span class="bv-reg-item__name">{{ $broker->name }}</span>
                                <span class="bv-reg-item__score">{{ number_format($broker->rating, 1) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</div>
