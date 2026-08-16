@if(!empty($popularComparisons))
<section class="bri-compare-hub bri-discovery-section" aria-labelledby="briCompareTitle">
    <div class="container">
        <div class="bri-compare-hub__inner">
            <div class="bri-compare-hub__copy">
                <p class="bri-section-head__eyebrow">Decide with confidence</p>
                <h2 id="briCompareTitle">Popular broker comparisons</h2>
                <p>Open a live side-by-side breakdown of regulation, fees, platforms, deposits, and editorial scores.</p>
                <a href="{{ route('broker.comparison') }}">Build your own comparison</a>
            </div>
            <div class="bri-compare-hub__links">
                @foreach(array_slice($popularComparisons, 0, 6) as $pair)
                    <a href="{{ $pair['url'] }}">
                        <span>{{ $pair['label'] }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
