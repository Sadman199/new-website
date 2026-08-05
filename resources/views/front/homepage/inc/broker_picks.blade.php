<section class="bc-section" id="top-brokers">
    <div class="bc-container">
        <div class="bc-section__head">
            <div>
                <h2 class="bc-section__title">Top broker picks</h2>
                <p class="bc-section__sub">Curated from our latest ratings — updated dynamically from live broker data.</p>
            </div>
            <a href="{{ route('broker.reviews.index') }}" class="bc-link">All reviews <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="bc-picks">
            <div class="bc-picks__tabs" role="tablist">
                <button type="button" class="bc-picks__tab is-active" data-bc-pick="top">Highest rated</button>
                <button type="button" class="bc-picks__tab" data-bc-pick="beginners">For beginners</button>
                <button type="button" class="bc-picks__tab" data-bc-pick="spread">Lowest spreads</button>
                <button type="button" class="bc-picks__tab" data-bc-pick="leverage">High leverage</button>
                <button type="button" class="bc-picks__tab" data-bc-pick="bonuses">Best bonuses</button>
            </div>

            <div class="bc-picks__panel is-active" data-bc-pick-panel="top">
                <div class="bc-tile-grid">
                    @foreach($topRatedBrokers as $i => $broker)
                        @include('front.homepage.inc.broker_tile', ['broker' => $broker, 'rank' => $i + 1])
                    @endforeach
                </div>
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="beginners">
                <div class="bc-tile-grid">
                    @forelse($bestForBeginners as $i => $broker)
                        @include('front.homepage.inc.broker_tile', ['broker' => $broker, 'rank' => $i + 1])
                    @empty
                        <p class="bc-empty">No beginner-friendly brokers in database yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="spread">
                <div class="bc-tile-grid">
                    @foreach($spreadRankings->take(6) as $i => $broker)
                        @include('front.homepage.inc.broker_tile', ['broker' => $broker, 'rank' => $i + 1])
                    @endforeach
                </div>
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="leverage">
                <div class="bc-tile-grid">
                    @foreach($best_leverage_brokers->take(6) as $i => $broker)
                        @include('front.homepage.inc.broker_tile', ['broker' => $broker, 'rank' => $i + 1])
                    @endforeach
                </div>
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="bonuses">
                <div class="bc-tile-grid">
                    @forelse($bestBonuses as $i => $broker)
                        @include('front.homepage.inc.broker_tile', ['broker' => $broker, 'rank' => $i + 1])
                    @empty
                        <p class="bc-empty">No bonus-eligible brokers listed yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
