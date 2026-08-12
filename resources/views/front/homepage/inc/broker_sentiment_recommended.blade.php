@if($sentimentRecommended->isNotEmpty())
    @php
        $t = $site_t ?? fn (string $key, ?string $default = null) => $default ?? $key;
        $countryName = $preferredCountry['name'] ?? 'your country';
    @endphp
    <section id="bcTrustRecommendedZone" class="bc-trust-zone bc-trust-zone--recommended" aria-labelledby="bcTrustRecTitle">
        <header class="bc-trust-zone__head">
            <div class="bc-trust-zone__title-wrap">
                <span class="bc-trust-zone__icon bc-trust-zone__icon--good" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </span>
                <div>
                    <h3 id="bcTrustRecTitle" class="bc-trust-zone__title">{{ $t('home.recommended_title') }}</h3>
                    <p class="bc-trust-zone__desc" id="bcTrustRecDesc">
                        @if(($preferredCountry['slug'] ?? 'global') !== 'global')
                            {{ str_replace('{country}', $countryName, $t('home.recommended_country')) }}
                        @else
                            {{ $t('home.recommended_global') }}
                        @endif
                    </p>
                </div>
            </div>
            <span class="bc-trust-zone__count" id="bcTrustRecCount">{{ $sentimentRecommended->count() }}</span>
        </header>
        <div class="bc-trust-card-grid" id="bcTrustRecGrid">
            @foreach($sentimentRecommended as $item)
                @include('front.homepage.inc.broker_trust_recommend_card', ['item' => $item])
            @endforeach
        </div>
    </section>
@endif
