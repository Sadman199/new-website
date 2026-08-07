<section class="br-compare" id="compare">
    <div class="br-section br-section--compare">
        <div class="br-section__head br-section__head--center">
            <h2 class="br-section__title">Compare {{ $broker->name }}</h2>
            <p class="br-section__desc">See how this broker stacks up against alternatives</p>
        </div>
        <div class="br-section__body">
            <div class="br-compare-grid">
                @foreach($compare_brokers as $compare_broker)
                    @php $isRegulated = $compare_broker->isRegulated(); @endphp
                    <a href="{{ route('compare', [$broker->slug, $compare_broker->slug]) }}" class="br-compare-card">
                        <div class="br-compare-card__logo">
                            @if($compare_broker->logo)
                                <img src="{{ asset($compare_broker->logo) }}" alt="{{ $compare_broker->name }}" loading="lazy">
                            @else
                                <span class="br-compare-card__fallback">{{ substr($compare_broker->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="br-compare-card__name">{{ $compare_broker->name }}</h3>
                        <p class="br-compare-card__status">{{ $isRegulated ? 'Regulated' : 'Unregulated' }}</p>
                        <p class="br-compare-card__score">{{ number_format($compare_broker->rating, 1) }}</p>
                    </a>
                @endforeach
            </div>
            <div class="br-compare__footer">
                <a href="{{ route('broker.reviews.index') }}" class="br-btn br-btn--secondary br-btn--sm">Browse all brokers</a>
            </div>
        </div>
    </div>
</section>
