<section class="bc-explore" id="explore-categories" aria-labelledby="bcExploreTitle">
    <div class="container">
        <header class="bc-explore__head">
            <div class="bc-explore__intro">
                <p class="bc-explore__eyebrow">
                    <span class="bc-explore__eyebrow-icon" aria-hidden="true">
                        <i class="fas fa-compass"></i>
                    </span>
                    Browse categories
                </p>
                <h2 id="bcExploreTitle" class="bc-explore__title">Explore by category</h2>
                <p class="bc-explore__sub">Jump into curated broker lists by regulation, platform, cost, and trading style.</p>
            </div>
            <a href="{{ route('find_my_broker') }}" class="bc-explore__cta">
                Advanced search
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </header>

        <div class="bc-explore__grid">
            <a href="{{ route('brokers.best.index') }}" class="bc-explore-card bc-explore-card--featured">
                <span class="bc-explore-card__badge">Editor picks</span>
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-trophy"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Best brokers {{ date('Y') }}</strong>
                    <span class="bc-explore-card__desc">Independent rankings across fees, platforms, safety, and overall value.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">Browse all rankings</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('regulated_brokers') }}" class="bc-explore-card bc-explore-card--safety">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-shield-alt"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Regulated brokers</strong>
                    <span class="bc-explore-card__desc">Licensed platforms with stronger investor protection.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">{{ number_format((int) ($homeStats['regulated'] ?? 0)) }} licensed</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('brokers.best', ['slug' => 'high-leverage']) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-bolt"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">High leverage</strong>
                    <span class="bc-explore-card__desc">Brokers offering flexible leverage for active traders.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">Up to 1:2000+</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('brokers.best', ['slug' => 'mt5-brokers']) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-desktop"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">MetaTrader 5</strong>
                    <span class="bc-explore-card__desc">Compare brokers with full MT5 support.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">Platform guide</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('find_my_broker', ['min_deposit' => 10]) }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-wallet"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Low minimum deposit</strong>
                    <span class="bc-explore-card__desc">Brokers you can open with a small starting balance.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">From $10</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('promotions.tab', 'deposit-bonuses') }}" class="bc-explore-card">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-gift"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Deposit bonuses</strong>
                    <span class="bc-explore-card__desc">Current offers with extra trading credit.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">Live promotions</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>

            <a href="{{ route('methodology') }}" class="bc-explore-card bc-explore-card--method">
                <span class="bc-explore-card__icon" aria-hidden="true"><i class="fas fa-microscope"></i></span>
                <span class="bc-explore-card__body">
                    <strong class="bc-explore-card__title">Our methodology</strong>
                    <span class="bc-explore-card__desc">How BrokersCourt scores safety, costs, and platforms.</span>
                </span>
                <span class="bc-explore-card__footer">
                    <span class="bc-explore-card__meta">Transparency first</span>
                    <span class="bc-explore-card__arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </span>
                </span>
            </a>
        </div>
    </div>
</section>
