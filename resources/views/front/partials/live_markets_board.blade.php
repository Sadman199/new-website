<div class="bc-markets__ticker" id="bcMarketsTicker" aria-label="Live forex ticker"></div>

<div class="bc-markets__board" id="bcMarketsApp">
    <nav class="bc-markets__tabs" aria-label="Market widget views">
        <button type="button"
                class="bc-markets__tab is-active"
                data-markets-tab="rates"
                aria-selected="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.172-.879-1.172-2.303 0-3.182s3.07-.879 4.242 0l.879.659"/>
            </svg>
            Currency rates
        </button>
        <button type="button"
                class="bc-markets__tab"
                data-markets-tab="heatmap"
                aria-selected="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
            </svg>
            Forex heatmap
        </button>
        <button type="button"
                class="bc-markets__tab"
                data-markets-tab="calendar"
                aria-selected="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            Economic calendar
        </button>
    </nav>

    <div class="bc-markets__panels">
        <div class="bc-markets__panel" data-markets-panel="rates">
            <div class="bc-markets__widget-wrap">
                <div class="bc-markets__widget" data-widget="rates"></div>
            </div>
        </div>
        <div class="bc-markets__panel is-hidden" data-markets-panel="heatmap">
            <div class="bc-markets__widget-wrap">
                <div class="bc-markets__widget" data-widget="heatmap"></div>
            </div>
        </div>
        <div class="bc-markets__panel is-hidden" data-markets-panel="calendar">
            <div class="bc-markets__widget-wrap">
                <div class="bc-markets__widget" data-widget="calendar"></div>
            </div>
        </div>
    </div>
</div>

<p class="bc-markets__credit">
    Market data provided by
    <a href="https://www.tradingview.com/" target="_blank" rel="noopener noreferrer">TradingView</a>.
    Rates and events are indicative — verify with your broker before trading.
</p>
