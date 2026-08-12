<section class="bc-markets" id="live-markets" aria-labelledby="bcMarketsTitle">
    <div class="bc-container bc-markets__container">
        <header class="bc-markets__head">
            <div class="bc-markets__intro">
                <p class="bc-markets__eyebrow">
                    <span class="bc-markets__live-dot" aria-hidden="true"></span>
                    Live market data
                </p>
                <h2 id="bcMarketsTitle" class="bc-markets__title">Live market widgets</h2>
                <p class="bc-markets__sub">
                    Real-time currency cross rates, forex heatmap, and economic calendar — powered by TradingView market data.
                </p>
            </div>
            <a href="{{ route('trading.tools') }}" class="bc-markets__cta">
                Open trading tools
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </header>

        @include('front.partials.live_markets_board')
    </div>
</section>
