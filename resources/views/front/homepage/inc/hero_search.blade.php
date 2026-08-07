<section class="bc-hero">
    <div class="bc-hero__bg" aria-hidden="true"></div>

    <div class="bc-container bc-hero__container">
        <div class="bc-hero__stack">
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
                            <div class="bc-finder__field" data-bc-dropdown>
                                <span class="bc-finder__select-label">Regulation</span>
                                <input type="hidden" name="regulation" value="" data-bc-dropdown-input disabled>
                                <button type="button" class="bc-finder__dropdown-trigger" data-bc-dropdown-trigger aria-expanded="false" aria-haspopup="listbox" disabled>
                                    <span class="bc-finder__dropdown-value">Any regulator</span>
                                    <svg class="bc-finder__dropdown-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="bc-finder__dropdown-menu" data-bc-dropdown-menu role="listbox">
                                    <button type="button" class="bc-finder__dropdown-option is-selected" data-bc-dropdown-option data-value="" role="option">Any regulator</button>
                                    @foreach($searchCatalogs['regulation'] as $value => $label)
                                        @if($value !== '')
                                            <button type="button" class="bc-finder__dropdown-option" data-bc-dropdown-option data-value="{{ $value }}" role="option">{{ $label }}</button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="bc-finder__field" data-bc-dropdown>
                                <span class="bc-finder__select-label">Trading cost</span>
                                <input type="hidden" name="spread" value="" data-bc-dropdown-input disabled>
                                <button type="button" class="bc-finder__dropdown-trigger" data-bc-dropdown-trigger aria-expanded="false" aria-haspopup="listbox" disabled>
                                    <span class="bc-finder__dropdown-value">{{ $searchCatalogs['spread'][''] ?? 'Any spread' }}</span>
                                    <svg class="bc-finder__dropdown-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="bc-finder__dropdown-menu" data-bc-dropdown-menu role="listbox">
                                    @foreach($searchCatalogs['spread'] as $value => $label)
                                        <button type="button" class="bc-finder__dropdown-option {{ $value === '' ? 'is-selected' : '' }}" data-bc-dropdown-option data-value="{{ $value }}" role="option">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bc-finder__field" data-bc-dropdown>
                                <span class="bc-finder__select-label">Leverage</span>
                                <input type="hidden" name="leverage" value="" data-bc-dropdown-input disabled>
                                <button type="button" class="bc-finder__dropdown-trigger" data-bc-dropdown-trigger aria-expanded="false" aria-haspopup="listbox" disabled>
                                    <span class="bc-finder__dropdown-value">{{ $searchCatalogs['leverage'][''] ?? 'Any leverage' }}</span>
                                    <svg class="bc-finder__dropdown-chevron" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="bc-finder__dropdown-menu" data-bc-dropdown-menu role="listbox">
                                    @foreach($searchCatalogs['leverage'] as $value => $label)
                                        <button type="button" class="bc-finder__dropdown-option {{ $value === '' ? 'is-selected' : '' }}" data-bc-dropdown-option data-value="{{ $value }}" role="option">{{ $label }}</button>
                                    @endforeach
                                </div>
                            </div>

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
    </div>
</section>
