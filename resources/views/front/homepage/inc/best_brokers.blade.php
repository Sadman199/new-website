 <section class="py-6">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <!-- Best Brokers for Beginners -->
        <x-broker-carousel 
            title="Best Brokers for Beginners" 
            :brokers="$bestForBeginners" 
            carouselClass="best-for-beginners-slider" 
        />

        <!-- Low Spread Forex Brokers for 2025 -->
        <x-broker-carousel 
            title="Low Spread Forex Brokers for 2025" 
            :brokers="$spreadRankings" 
            carouselClass="low-spread-brokers-slider" 
        />

        <!-- Best Brokers with Bonuses -->
        <x-broker-carousel 
            title="Best Brokers with Bonuses" 
            :brokers="$bestBonuses" 
            carouselClass="best-bonuses-slider" 
        />

        <p class="text-xs text-gray-500 max-w-3xl mx-auto mt-6 leading-relaxed text-center">
            * The brokers above are curated based on performance factors like spreads, beginner-friendliness, bonuses, and trading features. While we recommend reputable and regulated brokers, forex trading carries risks. Always do your own due diligence before investing.
        </p>
    </div>
</section>

