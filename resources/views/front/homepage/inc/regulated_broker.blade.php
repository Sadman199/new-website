<section class="bc-home-section">
    <div class="container">
        <div class="bc-home-section__head">
            <div>
                <h2 class="bc-home-section__title">Trade With a Regulated Broker</h2>
                <p class="bc-home-section__sub">Brokers licensed by tier-1 and tier-2 regulators worldwide</p>
            </div>
            <a href="{{ route('regulated_brokers') }}" class="bc-home-section__link">
                See all regulated <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="bc-home-regulated-grid">
            @foreach($regulatedBrokers as $broker)
                @include('front.brokers.regulated_card.regulated_card')
            @endforeach
        </div>

        <p class="bc-home-disclaimer">
            Regulated brokers listed here comply with financial regulations, transparency standards, and investor protection requirements.
        </p>
    </div>
</section>
