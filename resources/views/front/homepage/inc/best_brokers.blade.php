<section class="bc-home-section bc-home-section--white">
    <div class="bc-home-container">
        <div class="bc-home-carousel-title">Best Brokers for Beginners</div>
        <div class="owl-carousel best-for-beginners-slider owl-theme mb-10">
            @foreach($bestForBeginners as $broker)
                <x-broker-card :broker="$broker" />
            @endforeach
        </div>

        <div class="bc-home-carousel-title">Low Spread Forex Brokers {{ date('Y') }}</div>
        <div class="owl-carousel low-spread-brokers-slider owl-theme mb-10">
            @foreach($spreadRankings as $broker)
                <x-broker-card :broker="$broker" />
            @endforeach
        </div>

        <div class="bc-home-carousel-title">Best Brokers with Bonuses</div>
        <div class="owl-carousel best-bonuses-slider owl-theme">
            @foreach($bestBonuses as $broker)
                <x-broker-card :broker="$broker" />
            @endforeach
        </div>

        <p class="bc-home-disclaimer">
            Brokers above are curated based on spreads, beginner-friendliness, bonuses, and trading features. Forex trading carries risk — always do your own due diligence.
        </p>
    </div>
</section>
