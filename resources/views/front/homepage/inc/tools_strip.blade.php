<section class="bc-tools">
    <div class="bc-container">
        <div class="bc-tools__grid">
            <a href="{{ route('find_my_broker') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-search"></i></span>
                <span class="bc-tools__label">Find My Broker</span>
                <span class="bc-tools__desc">15+ filter criteria</span>
            </a>
            <a href="{{ route('broker.comparison') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-balance-scale"></i></span>
                <span class="bc-tools__label">Compare</span>
                <span class="bc-tools__desc">Up to 3 brokers</span>
            </a>
            <a href="{{ route('broker.reviews.index') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-star"></i></span>
                <span class="bc-tools__label">Reviews</span>
                <span class="bc-tools__desc">Expert analysis</span>
            </a>
            <a href="{{ route('brokers.best.index') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-trophy"></i></span>
                <span class="bc-tools__label">Best Brokers</span>
                <span class="bc-tools__desc">{{ date('Y') }} rankings</span>
            </a>
            <a href="{{ route('regulated_brokers') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-shield-alt"></i></span>
                <span class="bc-tools__label">Regulated</span>
                <span class="bc-tools__desc">FCA, ASIC, CySEC</span>
            </a>
            <a href="{{ route('trading.tools') }}" class="bc-tools__item">
                <span class="bc-tools__icon"><i class="fas fa-calculator"></i></span>
                <span class="bc-tools__label">Calculators</span>
                <span class="bc-tools__desc">Trading tools</span>
            </a>
        </div>
    </div>
</section>
