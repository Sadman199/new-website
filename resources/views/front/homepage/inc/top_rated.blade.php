<section class="bc-home-section">
    <div class="container">
        <div class="bc-home-section__head">
            <div>
                <h2 class="bc-home-section__title">Top Rated Brokers</h2>
                <p class="bc-home-section__sub">Highest-scoring brokers based on our editorial ratings</p>
            </div>
            <a href="{{ route('broker.reviews.index') }}" class="bc-home-section__link">
                View all reviews <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($topRatedBrokers->isNotEmpty())
            <div class="bc-home-brokers-grid">
                @foreach($topRatedBrokers as $broker)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="bc-home-broker-card">
                        <div class="bc-home-broker-card__logo">
                            @if($broker->logo)
                                <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                            @else
                                <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <span class="bc-home-broker-card__name">{{ $broker->name }}</span>
                        <span class="bc-home-broker-card__score">{{ number_format($broker->rating, 1) }}</span>
                        @if($broker->isRegulated())
                            <span class="bc-home-broker-card__tag">Regulated</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
