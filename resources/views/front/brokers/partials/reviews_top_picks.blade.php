@if(($topRatedBrokers ?? collect())->isNotEmpty())
<section class="bc-picks-section bri-discovery-section bri-top-picks" aria-labelledby="briTopPicksTitle">
    <div class="container bc-picks-section__container">
        <header class="bc-picks-section__head">
            <div class="bc-picks-section__intro">
                <p class="bc-picks-section__eyebrow">
                    <span class="bc-picks-section__eyebrow-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z"/></svg>
                    </span>
                    Editor&rsquo;s shortlist · {{ date('F Y') }}
                </p>
                <h2 id="briTopPicksTitle" class="bc-picks-section__title">Top Broker Picks</h2>
                <p class="bc-picks-section__sub">Our highest-rated brokers, using the same live data and ranking cards shown on the BrokersCourt homepage.</p>
            </div>
            <a href="#all-broker-reviews" class="bc-picks-section__cta">
                Browse all reviews
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m0 0 6-6m-6 6-6-6"/></svg>
            </a>
        </header>

        <div class="bc-picks">
            @include('front.homepage.inc.broker_picks_panel', ['brokers' => $topRatedBrokers])
        </div>
    </div>
</section>
@endif
