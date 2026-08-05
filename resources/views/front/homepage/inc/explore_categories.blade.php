<section class="bc-section">
    <div class="bc-container">
        <div class="bc-section__head">
            <div>
                <h2 class="bc-section__title">Explore by category</h2>
                <p class="bc-section__sub">Jump straight to broker lists filtered by regulation, platform, or trading style.</p>
            </div>
        </div>

        <div class="bc-explore-grid">
            <a href="{{ route('brokers.best.index') }}" class="bc-explore-card">
                <i class="fas fa-trophy"></i>
                <strong>Best brokers {{ date('Y') }}</strong>
                <span>Rankings by category</span>
            </a>
            <a href="{{ route('regulated_brokers') }}" class="bc-explore-card">
                <i class="fas fa-shield-alt"></i>
                <strong>Regulated brokers</strong>
                <span>{{ $homeStats['regulated'] ?? 0 }} licensed platforms</span>
            </a>
            <a href="{{ route('brokers.high.leverage') }}" class="bc-explore-card">
                <i class="fas fa-chart-line"></i>
                <strong>High leverage</strong>
                <span>Up to 1:2000+</span>
            </a>
            <a href="{{ route('brokers.by.platform', 'mt5') }}" class="bc-explore-card">
                <i class="fas fa-desktop"></i>
                <strong>MetaTrader 5</strong>
                <span>MT5-supported brokers</span>
            </a>
            <a href="{{ route('brokers.by.regulation', 'fca') }}" class="bc-explore-card">
                <i class="fas fa-landmark"></i>
                <strong>FCA regulated</strong>
                <span>UK-authorised brokers</span>
            </a>
            <a href="{{ route('find_my_broker', ['min_deposit' => 10]) }}" class="bc-explore-card">
                <i class="fas fa-wallet"></i>
                <strong>Low minimum deposit</strong>
                <span>Start from $10 or less</span>
            </a>
            <a href="{{ route('forex_deposit_bonus') }}" class="bc-explore-card">
                <i class="fas fa-gift"></i>
                <strong>Deposit bonuses</strong>
                <span>Extra trading credit</span>
            </a>
            <a href="{{ route('methodology') }}" class="bc-explore-card">
                <i class="fas fa-microscope"></i>
                <strong>Our methodology</strong>
                <span>How we rate brokers</span>
            </a>
        </div>
    </div>
</section>
