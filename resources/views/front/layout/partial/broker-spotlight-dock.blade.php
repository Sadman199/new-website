@if(!empty($spotlightBrokers) && $spotlightBrokers->isNotEmpty())
@php
    $spotlightPayload = $spotlightBrokers
        ->map(fn ($broker) => \App\Support\BrokerFeaturePresenter::toSpotlightPayload($broker))
        ->values();
@endphp

<div class="bc-spotlight-dock"
     id="bcSpotlightDock"
     data-brokers='@json($spotlightPayload)'
     aria-live="polite">
    <div class="bc-spotlight-dock__shell">
        <button type="button"
                class="bc-spotlight-dock__peek"
                id="bcSpotlightPeek"
                aria-expanded="false"
                aria-controls="bcSpotlightPanel">
            <span class="bc-spotlight-dock__peek-logo" data-spotlight-logo></span>
            <span class="bc-spotlight-dock__peek-body">
                <strong data-spotlight-name></strong>
                <small data-spotlight-meta></small>
            </span>
            <span class="bc-spotlight-dock__peek-rating" data-spotlight-rating hidden></span>
            <span class="bc-spotlight-dock__peek-chevron" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                </svg>
            </span>
        </button>

        <div class="bc-spotlight-dock__panel" id="bcSpotlightPanel" hidden>
            <div class="bc-spotlight-dock__panel-inner">
                <header class="bc-spotlight-dock__head">
                    <div class="bc-spotlight-dock__brand">
                        <span class="bc-spotlight-dock__logo" data-spotlight-logo></span>
                        <div>
                            <p class="bc-spotlight-dock__eyebrow">Broker spotlight</p>
                            <h2 class="bc-spotlight-dock__name" data-spotlight-name></h2>
                            <p class="bc-spotlight-dock__tagline" data-spotlight-tagline hidden></p>
                        </div>
                    </div>
                    <div class="bc-spotlight-dock__head-actions">
                        <span class="bc-spotlight-dock__score" data-spotlight-rating hidden></span>
                        <button type="button" class="bc-spotlight-dock__collapse" data-spotlight-collapse aria-label="Collapse panel">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </header>

                <div class="bc-spotlight-dock__features" data-spotlight-features></div>

                <footer class="bc-spotlight-dock__foot">
                    <div class="bc-spotlight-dock__dots" data-spotlight-dots role="tablist" aria-label="Browse brokers"></div>
                    <div class="bc-spotlight-dock__actions">
                        <button type="button" class="bc-spotlight-dock__nav" data-spotlight-prev aria-label="Previous broker">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>
                        <a href="#" class="bc-spotlight-dock__btn bc-spotlight-dock__btn--ghost" data-spotlight-review>Review</a>
                        <a href="#" class="bc-spotlight-dock__btn bc-spotlight-dock__btn--primary" data-spotlight-visit target="_blank" rel="noopener noreferrer nofollow">Visit</a>
                        <button type="button" class="bc-spotlight-dock__nav" data-spotlight-next aria-label="Next broker">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
@endif
