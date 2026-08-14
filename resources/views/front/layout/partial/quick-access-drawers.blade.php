<button type="button"
        class="bc-tools-trigger"
        id="bcToolsTrigger"
        aria-label="Open tools and resources panel"
        aria-controls="bcToolsSheet"
        aria-expanded="false">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
    </svg>
</button>

<div id="bcToolsSheet" class="bc-tools-sheet" aria-hidden="true">
    <div class="bc-tools-sheet__backdrop" data-bc-tools-close tabindex="-1" aria-hidden="true"></div>

    <aside class="bc-tools-sheet__panel"
           role="dialog"
           aria-labelledby="bcToolsSheetTitle"
           aria-modal="false">
        <header class="bc-tools-sheet__head">
            <div>
                <p class="bc-tools-sheet__eyebrow">
                    <i class="fas fa-bolt" aria-hidden="true"></i>
                    Quick tools
                </p>
                <h2 id="bcToolsSheetTitle" class="bc-tools-sheet__title">Tools &amp; resources</h2>
                <p class="bc-tools-sheet__desc">Jump to calculators, promos, editorial guides, and broker search.</p>
            </div>
            <button type="button" class="bc-tools-sheet__close" data-bc-tools-close aria-label="Close panel">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </header>

        <div class="bc-tools-sheet__body">
            <form class="bc-tools-sheet__search" action="{{ route('search') }}" method="GET" role="search">
                <span class="bc-tools-sheet__search-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m20 20-3.5-3.5"/></svg>
                </span>
                <input type="search"
                       class="bc-tools-sheet__input"
                       name="q"
                       data-bc-tools-search
                       placeholder="Search brokers, articles, tools…"
                       autocomplete="off"
                       aria-label="Search the site"
                       minlength="2"
                       required>
                <div class="bc-tools-sheet__results" data-bc-tools-results hidden></div>
            </form>

            <p class="bc-tools-sheet__section-title">Useful tools</p>
            <div class="bc-tools-sheet__links">
                <a href="{{ route('trading.tools') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon"><i class="fas fa-calculator" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Trading tools</strong>
                        <small>Calculators and utilities</small>
                    </span>
                </a>
                <a href="{{ route('promotions.index') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon"><i class="fas fa-gift" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Broker promotions</strong>
                        <small>Bonuses and offers</small>
                    </span>
                </a>
                <a href="{{ route('broker.scam_checker') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon bc-tools-sheet__link-icon--warn"><i class="fas fa-shield-alt" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Scam checker</strong>
                        <small>Verify any broker instantly</small>
                    </span>
                </a>
                <a href="{{ route('scam_brokers') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon bc-tools-sheet__link-icon--warn"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Scam broker list</strong>
                        <small>Verified warnings list</small>
                    </span>
                </a>
                <a href="{{ route('awards.index') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon"><i class="fas fa-award" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Broker awards</strong>
                        <small>Top-rated by category</small>
                    </span>
                </a>
                <a href="{{ route('blog') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon"><i class="fas fa-newspaper" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Blog &amp; news</strong>
                        <small>Market insights and guides</small>
                    </span>
                </a>
                <a href="{{ route('methodology') }}" class="bc-tools-sheet__link">
                    <span class="bc-tools-sheet__link-icon"><i class="fas fa-microscope" aria-hidden="true"></i></span>
                    <span class="bc-tools-sheet__link-text">
                        <strong>Our methodology</strong>
                        <small>How we rate brokers</small>
                    </span>
                </a>
            </div>

        </div>

        <footer class="bc-tools-sheet__foot">
            <a href="{{ route('broker.comparison') }}" class="bc-tools-sheet__cta">
                Compare brokers now
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </footer>
    </aside>
</div>
