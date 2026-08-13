<section class="bc-home-section">
    <div class="container">
        <div class="bc-home-section__head">
            <div>
                <h2 class="bc-home-section__title">Broker Rankings</h2>
                <p class="bc-home-section__sub">Latest top-rated brokers on BrokersCourt</p>
            </div>
            <a href="{{ route('find_my_broker') }}" class="bc-home-section__link">
                View all brokers <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($all_brokers->isNotEmpty())
            <div class="bc-home-rankings">
                @foreach($all_brokers as $index => $broker)
                    <a href="{{ route('broker_detail', $broker->slug) }}" class="bc-home-rankings__row">
                        <span class="bc-home-rankings__rank {{ $index < 3 ? 'bc-home-rankings__rank--top' : '' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="bc-home-rankings__info">
                            <div class="bc-home-rankings__name">{{ $broker->name }}</div>
                            <div class="bc-home-rankings__meta">
                                {{ $broker->country ?: 'Global' }}
                                @if($broker->minimum_deposit)
                                    · Min {{ '$' . number_format((float) $broker->minimum_deposit, 0) }}
                                @endif
                            </div>
                        </div>
                        @if($broker->isRegulated())
                            <span class="bc-home-rankings__badge">Regulated</span>
                        @else
                            <span></span>
                        @endif
                        <span class="bc-home-rankings__score">{{ number_format($broker->rating, 1) }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <x-no-brokers-found />
        @endif

        <p class="bc-home-disclaimer">
            Rankings are based on editorial ratings, regulation, trading conditions, and user feedback. Trading forex and CFDs carries significant risk.
        </p>
    </div>
</section>
