<section class="bc-explore" id="explore-categories" aria-labelledby="bcExploreTitle">
    <div class="bc-container">
        <header class="bc-explore__head">
            <p class="bc-explore__eyebrow">
                <span class="bc-explore__eyebrow-icon" aria-hidden="true">
                    <i class="fas fa-compass"></i>
                </span>
                Browse categories
            </p>
            <h2 id="bcExploreTitle" class="bc-explore__title">Explore by category</h2>
            <p class="bc-explore__sub">Jump straight to broker lists filtered by regulation, platform, or trading style.</p>
        </header>

        <div class="bc-explore__grid">
            <a href="{{ route('brokers.best.index') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Best brokers {{ date('Y') }}</strong>
                    <span class="bc-explore-card__desc">Rankings by category</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('regulated_brokers') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Regulated brokers</strong>
                    <span class="bc-explore-card__desc">{{ $homeStats['regulated'] ?? 0 }} licensed platforms</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('brokers.best', ['slug' => 'high-leverage']) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-chart-line"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">High leverage</strong>
                    <span class="bc-explore-card__desc">Up to 1:2000+</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('brokers.best', ['slug' => 'mt5-brokers']) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-desktop"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">MetaTrader 5</strong>
                    <span class="bc-explore-card__desc">MT5-supported brokers</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('brokers.by.regulation', 'fca') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-landmark"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">FCA regulated</strong>
                    <span class="bc-explore-card__desc">UK-authorised brokers</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('find_my_broker', ['min_deposit' => 10]) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-wallet"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Low minimum deposit</strong>
                    <span class="bc-explore-card__desc">Start from $10 or less</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('promotions.tab', 'deposit-bonuses') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-gift"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Deposit bonuses</strong>
                    <span class="bc-explore-card__desc">Extra trading credit</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>

            <a href="{{ route('methodology') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-microscope"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Our methodology</strong>
                    <span class="bc-explore-card__desc">How we rate brokers</span>
                </span>
                <span class="bc-explore-card__arrow" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>
