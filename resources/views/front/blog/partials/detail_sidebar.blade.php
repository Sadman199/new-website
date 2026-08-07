{{-- Recommended brokers --}}
<div class="bpd-sidebar__panel">
    <div class="bpd-sidebar__head">
        <h3 class="bpd-sidebar__title">
            <svg class="bpd-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            Recommended brokers
        </h3>
        <a href="{{ route('broker.reviews.index') }}" class="bpd-sidebar__link">View all</a>
    </div>

    @if($recommendedBrokers->isNotEmpty())
        <ul class="bpd-broker-list">
            @foreach($recommendedBrokers as $index => $broker)
                <li>
                    <a href="{{ $broker['review_url'] }}" class="bpd-broker-item">
                        <span class="bpd-broker-item__rank">{{ $index + 1 }}</span>
                        <span class="bpd-broker-item__logo">
                            @if($broker['logo'])
                                <img src="{{ $broker['logo'] }}" alt="" loading="lazy">
                            @else
                                <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                            @endif
                        </span>
                        <span class="bpd-broker-item__body">
                            <span class="bpd-broker-item__name">{{ $broker['name'] }}</span>
                            @if($broker['rating'] !== null)
                                <span class="bpd-broker-item__rating">
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($broker['rating'], 1) }}
                                </span>
                            @endif
                        </span>
                        <svg class="bpd-broker-item__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="bpd-sidebar__empty">No brokers available right now.</p>
    @endif
</div>

{{-- Latest deposit bonuses --}}
<div class="bpd-sidebar__panel bpd-sidebar__panel--accent">
    <div class="bpd-sidebar__head">
        <h3 class="bpd-sidebar__title">
            <svg class="bpd-sidebar__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/></svg>
            Latest deposit bonuses
        </h3>
        <a href="{{ route('promotions.index') }}" class="bpd-sidebar__link">All promos</a>
    </div>

    @if($depositBonuses->isNotEmpty())
        <ul class="bpd-bonus-list">
            @foreach($depositBonuses as $bonus)
                <li>
                    <a href="{{ $bonus->cardUrl() }}" class="bpd-bonus-item">
                        <span class="bpd-bonus-item__offer">{{ $bonus->headlineOffer() }}</span>
                        <span class="bpd-bonus-item__title">{{ $bonus->title }}</span>
                        @if($bonus->brokerDisplayName())
                            <span class="bpd-bonus-item__broker">{{ $bonus->brokerDisplayName() }}</span>
                        @endif
                        @if($bonus->minDepositLabel())
                            <span class="bpd-bonus-item__meta">{{ $bonus->minDepositLabel() }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="bpd-sidebar__empty">No active deposit bonuses at the moment.</p>
    @endif
</div>
