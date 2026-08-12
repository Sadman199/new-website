<section class="bc-hero">
    <div class="bc-hero__bg" aria-hidden="true"></div>

    <div class="bc-container bc-hero__container">
        <div class="bc-hero__inner">
            <header class="bc-hero__intro">
                <p class="bc-hero__eyebrow">
                    <span class="bc-hero__eyebrow-dot" aria-hidden="true"></span>
                    Independent broker research
                </p>

                <h1 class="bc-hero__title">Compare forex brokers with confidence</h1>

                <p class="bc-hero__lead">
                    Expert reviews, regulation checks, and side-by-side tools for
                    {{ number_format($homeStats['total'] ?? $brokerCount ?? 0) }}+ brokers — free and unbiased.
                </p>
            </header>

            <div class="bc-hero__panel" id="bcHomeSearch">
                <form class="bc-hero__form" action="{{ route('find_my_broker') }}" method="GET" id="bcHomeSearchForm">
                    <div class="bc-hero__search-row">
                        <label class="bc-hero__search-field" for="bcHeroBrokerName">
                            <span class="bc-hero__search-icon" aria-hidden="true">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input type="search"
                                   id="bcHeroBrokerName"
                                   name="q"
                                   class="bc-hero__search-input"
                                   placeholder="Search broker name, e.g. Exness, IC Markets…"
                                   autocomplete="off">
                        </label>
                        <button type="submit" class="bc-hero__search-btn">Search</button>
                    </div>

                    @if(!empty($quickFilterLinks))
                        <div class="bc-hero__chips">
                            <span class="bc-hero__chips-label">Popular:</span>
                            @foreach($quickFilterLinks as $chip)
                                <a href="{{ $chip['url'] }}" class="bc-hero__chip">{{ $chip['label'] }}</a>
                            @endforeach
                        </div>
                    @endif

                    <button type="button"
                            class="bc-hero__filters-toggle"
                            id="bcHeroFiltersToggle"
                            aria-expanded="false"
                            aria-controls="bcHeroFilters">
                        <span>Filter by regulation, cost &amp; leverage</span>
                        <svg class="bc-hero__filters-chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div class="bc-hero__filters" id="bcHeroFilters" hidden>
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

                            <button type="submit" class="bc-hero__search-btn bc-hero__search-btn--filter">Apply filters</button>
                        </div>
                    </div>
                </form>
            </div>

            <nav class="bc-hero__links" aria-label="Quick actions">
                <a href="{{ route('find_my_broker') }}" class="bc-hero__link">Browse all brokers</a>
                <span class="bc-hero__link-divider" aria-hidden="true">·</span>
                <a href="{{ route('broker.comparison') }}" class="bc-hero__link">Compare side by side</a>
                <span class="bc-hero__link-divider" aria-hidden="true">·</span>
                <a href="{{ route('find_my_broker') }}" class="bc-hero__link">Find my match</a>
            </nav>
        </div>
    </div>
</section>
