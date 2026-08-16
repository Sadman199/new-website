@if(!empty($regions))
<section class="bri-regions bri-discovery-section" aria-labelledby="briRegionsTitle" data-bri-regions-slider>
    <div class="container">
        <header class="bri-section-head">
            <div>
                <p class="bri-section-head__eyebrow">Explore globally</p>
                <h2 id="briRegionsTitle">Browse Brokers by Region</h2>
                <p>See broker shortlists relevant to each market. Counts update automatically as broker coverage changes.</p>
            </div>
            <div class="bri-regions__actions">
                <div class="bri-regions__nav" aria-label="Region carousel controls">
                    <button type="button" class="bri-regions__nav-btn" data-bri-regions-prev aria-label="Show previous regions">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="bri-regions__nav-btn" data-bri-regions-next aria-label="Show next regions">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                    </button>
                </div>
                <button type="button" class="bri-section-head__action" onclick="window.bcCountryDrawer?.open()">
                    View country selector
                </button>
            </div>
        </header>

        <div class="bri-regions__slider">
            <div class="bri-regions__track"
                 data-bri-regions-track
                 role="region"
                 aria-label="Broker regions"
                 tabindex="0">
                @foreach($regions as $region)
                    <a href="{{ $region['url'] }}" class="bri-region-card">
                        <span class="bri-region-card__flag" aria-hidden="true">
                            @include('front.layout.partial.country-flag', [
                                'country' => [
                                    'slug' => $region['slug'],
                                    'name' => $region['name'],
                                    'flag' => $region['flag'],
                                    'code' => $region['code'],
                                ],
                                'width' => 36,
                                'height' => 26,
                            ])
                        </span>
                        <span class="bri-region-card__copy">
                            <strong>{{ $region['name'] }}</strong>
                            <small>{{ number_format($region['count']) }} {{ \Illuminate\Support\Str::plural('broker', $region['count']) }}</small>
                        </span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
