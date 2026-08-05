<section class="bc-hero">
    <div class="bc-hero__bg" aria-hidden="true"></div>

    <div class="bc-container bc-hero__stack">
        {{-- Hero content (top) --}}
        <header class="bc-hero__head">
            <p class="bc-hero__eyebrow">
                <span class="bc-hero__eyebrow-dot"></span>
                Independent broker research
            </p>

            <h1 class="bc-hero__title">Find the right forex broker for you</h1>

            <p class="bc-hero__lead">
                Compare <strong>{{ $homeStats['total'] ?? $brokerCount }}+ brokers</strong> by regulation, trading costs, and safety — backed by expert reviews and transparent ratings.
            </p>

            @if(!empty($preferredCountry) && ($preferredCountry['slug'] ?? 'global') !== 'global')
                <p class="bc-hero__location">
                    <span class="bc-hero__location-flag" aria-hidden="true">
                        @include('front.layout.partial.country-flag', ['country' => $preferredCountry, 'width' => 20, 'height' => 15])
                    </span>
                    Showing recommendations for <strong>{{ $preferredCountry['name'] }}</strong>
                </p>
            @endif

            <div class="bc-hero__metrics">
                <div class="bc-hero__metric">
                    <span class="bc-hero__metric-label">Brokers reviewed</span>
                    <strong class="bc-hero__metric-value">{{ $homeStats['total'] ?? $brokerCount }}</strong>
                </div>
                <div class="bc-hero__metric">
                    <span class="bc-hero__metric-label">Regulated</span>
                    <strong class="bc-hero__metric-value">{{ $homeStats['regulated'] ?? 0 }}</strong>
                </div>
                <div class="bc-hero__metric">
                    <span class="bc-hero__metric-label">Demo accounts</span>
                    <strong class="bc-hero__metric-value">{{ $homeStats['with_demo'] ?? 0 }}</strong>
                </div>
                <div class="bc-hero__metric">
                    <span class="bc-hero__metric-label">Avg. score</span>
                    <strong class="bc-hero__metric-value">{{ $homeStats['avg_rating'] ?? '—' }}</strong>
                </div>
            </div>
        </header>

        {{-- Search (below hero content) --}}
        <div class="bc-finder" id="bcHomeSearch">
            <form class="bc-finder__form" action="{{ route('find_my_broker') }}" method="GET" id="bcHomeSearchForm">
                <div class="bc-finder__modes" role="tablist" aria-label="Search mode">
                    <button type="button" class="bc-finder__mode is-active" data-bc-tab="name" role="tab" aria-selected="true">
                        Search by name
                    </button>
                    <button type="button" class="bc-finder__mode" data-bc-tab="filter" role="tab" aria-selected="false">
                        Filter by criteria
                    </button>
                </div>

                <div class="bc-finder__panel" data-bc-panel="name">
                    <label class="bc-finder__search" for="bcHeroBrokerName">
                        <span class="bc-finder__search-icon" aria-hidden="true">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="search"
                               id="bcHeroBrokerName"
                               name="q"
                               class="bc-finder__input"
                               placeholder="Search broker name, e.g. Exness, IC Markets…"
                               autocomplete="off">
                        <button type="submit" class="bc-finder__submit">Search</button>
                    </label>
                </div>

                <div class="bc-finder__panel is-hidden" data-bc-panel="filter">
                    <div class="bc-finder__filters">
                        <label class="bc-finder__select-wrap">
                            <span class="bc-finder__select-label">Regulation</span>
                            <select name="regulation" class="bc-finder__select">
                                <option value="">Any regulator</option>
                                @foreach($searchCatalogs['regulation'] as $value => $label)
                                    @if($value !== '')
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </label>
                        <label class="bc-finder__select-wrap">
                            <span class="bc-finder__select-label">Trading cost</span>
                            <select name="spread" class="bc-finder__select">
                                @foreach($searchCatalogs['spread'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="bc-finder__select-wrap">
                            <span class="bc-finder__select-label">Leverage</span>
                            <select name="leverage" class="bc-finder__select">
                                @foreach($searchCatalogs['leverage'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                        <button type="submit" class="bc-finder__submit bc-finder__submit--filter">Find brokers</button>
                    </div>
                </div>

                @if(!empty($quickFilterLinks))
                    <div class="bc-finder__quick">
                        <span class="bc-finder__quick-label">Popular:</span>
                        @foreach($quickFilterLinks as $chip)
                            <a href="{{ $chip['url'] }}" class="bc-finder__quick-link">{{ $chip['label'] }}</a>
                        @endforeach
                    </div>
                @endif
            </form>
        </div>
    </div>
</section>
