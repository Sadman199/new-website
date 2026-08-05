<section class="bc-home-section bc-home-section--white">
    <div class="bc-home-container">
        <div class="bc-home-section__head">
            <div>
                <h2 class="bc-home-section__title">Explore Broker Categories</h2>
                <p class="bc-home-section__sub">Curated lists for every trading style and experience level</p>
            </div>
        </div>

        <div class="bc-home-categories" id="broker-tabs">
            <div class="bc-home-categories__sidebar">
                @include('front.brokers.partials.tabs')
            </div>
            <div class="bc-home-categories__content">
                @include('front.brokers.partials.tab_top_rated')
                @include('front.brokers.partials.tab_non_regulated')
                @include('front.brokers.partials.tab_top_month')
                @include('front.brokers.partials.tab_demo_available')
                @include('front.brokers.partials.tab_low_deposit')
            </div>
        </div>

        <p class="bc-home-disclaimer">
            Categorized listings help you compare and choose wisely. Always verify broker credentials and understand the risks of leveraged trading.
        </p>
    </div>
</section>
