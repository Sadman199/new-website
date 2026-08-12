@php
    $slug = $preferredCountry['slug'] ?? 'global';
    $brokers = $countryBrokers ?? collect();
    $total = $countryBrokersCount ?? 0;
    $brokersUrl = $countryBrokersUrl ?? null;
@endphp

@if($brokers->isNotEmpty())
    <section class="bc-country-strip" aria-labelledby="bcCountryStripTitle">
        <div class="bc-container bc-country-strip__container">
            <div class="bc-country-strip__head">
                <div class="bc-country-strip__intro">
                    <span class="bc-country-strip__flag" aria-hidden="true">
                        @include('front.layout.partial.country-flag', ['country' => $preferredCountry, 'width' => 24])
                    </span>
                    <div>
                        <h2 id="bcCountryStripTitle" class="bc-country-strip__title">
                            Top brokers in {{ $preferredCountry['name'] }}
                        </h2>
                        <p class="bc-country-strip__sub">
                            {{ $total }} broker{{ $total === 1 ? '' : 's' }} headquartered in {{ $preferredCountry['name'] }}
                        </p>
                    </div>
                </div>
                <div class="bc-country-strip__actions">
                    @if($brokersUrl)
                        <a href="{{ $brokersUrl }}" class="bc-country-strip__link">View full list</a>
                    @endif
                    <button type="button" class="bc-country-strip__change" id="countryStripChangeBtn" aria-label="Change country">
                        Change country
                    </button>
                </div>
            </div>

            <div class="bc-country-strip__grid">
                @foreach($brokers->take(6) as $broker)
                    @include('front.layout.partial.country-broker-mini', ['broker' => $broker, 'rank' => null, 'showHeadquarters' => true])
                @endforeach
            </div>
        </div>
    </section>
@endif
