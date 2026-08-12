@php
    $p = $personalization ?? [];
    $user = $p['user'] ?? null;
@endphp

@if(!empty($p['isAuthenticated']) && $user)
<section class="bc-home-personal bc-home-personal--welcome" aria-label="Welcome back">
    <div class="bc-container">
        <div class="bc-home-personal__banner">
            <div>
                <p class="bc-home-personal__eyebrow">Welcome back, {{ $user->name }}</p>
                <h2 class="bc-home-personal__title">Pick up where you left off</h2>
                <p class="bc-home-personal__sub">Your saved brokers, country picks, and account shortcuts — tailored to you.</p>
            </div>
            <div class="bc-home-personal__actions">
                <a href="{{ route('user.profile') }}" class="bc-home-personal__btn">My profile</a>
                <a href="{{ route('find_my_broker') }}" class="bc-home-personal__btn bc-home-personal__btn--ghost">Find my broker</a>
            </div>
        </div>
    </div>
</section>
@endif

@if(!empty($p['showSavedStrip']))
<section class="bc-home-personal" aria-label="Your saved brokers">
    <div class="bc-container">
        <div class="bc-home-personal__head">
            <h2 class="bc-home-personal__section-title">Your saved brokers</h2>
            <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-saved" class="bc-home-personal__link">View all →</a>
        </div>
        <div class="bc-home-personal__cards">
            @foreach($p['savedBrokerCards'] as $broker)
                <a href="{{ $broker['review_url'] }}" class="bc-home-personal__card">
                    <span class="bc-home-personal__card-logo">
                        @if($broker['logo'])
                            <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                        @else
                            <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                        @endif
                    </span>
                    <span class="bc-home-personal__card-body">
                        <strong>{{ $broker['name'] }}</strong>
                        @if($broker['rating'] !== null)
                            <span>★ {{ number_format($broker['rating'], 1) }}</span>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($p['showCountryStrip']))
<section class="bc-home-personal bc-home-personal--country" aria-label="Brokers in your country">
    <div class="bc-container">
        <div class="bc-home-personal__head">
            <h2 class="bc-home-personal__section-title">
                Top brokers in {{ $p['preferredCountry']['flag'] ?? '🌍' }} {{ $p['preferredCountry']['name'] ?? 'your region' }}
            </h2>
            <a href="{{ $p['countryBrokersUrl'] ?? route('all_brokers') }}" class="bc-home-personal__link">See all →</a>
        </div>
        <div class="bc-home-personal__cards">
            @foreach($p['countryBrokerCards'] as $broker)
                <a href="{{ $broker['review_url'] }}" class="bc-home-personal__card">
                    <span class="bc-home-personal__card-logo">
                        @if($broker['logo'])
                            <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                        @else
                            <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                        @endif
                    </span>
                    <span class="bc-home-personal__card-body">
                        <strong>{{ $broker['name'] }}</strong>
                        @if($broker['rating'] !== null)
                            <span>★ {{ number_format($broker['rating'], 1) }}</span>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
