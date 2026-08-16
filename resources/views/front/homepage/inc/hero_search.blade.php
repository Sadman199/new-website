@php
    $stats = $homeStats ?? [];
    $brokersTotal = (int) ($stats['total'] ?? $brokerCount ?? 0);
    $regulatedTotal = (int) ($stats['regulated'] ?? 0);
    $reviewsTotal = (int) ($stats['reviews'] ?? 0);
    $avgRating = \App\Support\BrokerRating::outOfFive($stats['avg_rating'] ?? 0) ?? 0;
    $propFirmsTotal = (int) ($stats['prop_firms'] ?? 0);
    $demoTotal = (int) ($stats['with_demo'] ?? 0);
    $ratingPct = max(8, (int) round(\App\Support\BrokerRating::percent($stats['avg_rating'] ?? 0)));
@endphp

<section class="bc-hero" id="hero">
    <div class="bc-hero__bg" aria-hidden="true">
        <span class="bc-hero__blob bc-hero__blob--orange"></span>
        <span class="bc-hero__blob bc-hero__blob--gold"></span>
        <span class="bc-hero__blob bc-hero__blob--silver"></span>
        <span class="bc-hero__shard bc-hero__shard--a"></span>
        <span class="bc-hero__shard bc-hero__shard--b"></span>
        <span class="bc-hero__shard bc-hero__shard--c"></span>
    </div>

    <div class="container bc-hero__container">
        <div class="bc-hero__grid">
            <div class="bc-hero__copy">
                <header class="bc-hero__intro">
                    <p class="bc-hero__eyebrow">
                        <span class="bc-hero__eyebrow-dot" aria-hidden="true"></span>
                        Independent broker research
                    </p>

                    <h1 class="bc-hero__title">
                        Compare the world&rsquo;s<br>
                        most trusted <span class="bc-hero__title-accent">brokers</span>
                    </h1>

                    <p class="bc-hero__lead">
                        Transparent data, real reviews and powerful tools — everything you need to choose from
                        {{ number_format($brokersTotal) }}+ brokers with confidence.
                    </p>
                </header>

                <div class="bc-hero__panel" id="bcHomeSearch">
                    <form class="bc-hero__form"
                          action="{{ route('find_my_broker') }}"
                          method="GET"
                          id="bcHomeSearchForm"
                          data-broker-action="{{ route('find_my_broker') }}"
                          data-prop-action="{{ route('prop_firms.index') }}">
                        <div class="bc-hero__search-shell">
                            <label class="bc-hero__search-field" for="bcHeroBrokerName">
                                <span class="bc-hero__search-icon" aria-hidden="true">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                                </span>
                                <input type="search"
                                       id="bcHeroBrokerName"
                                       name="q"
                                       class="bc-hero__search-input"
                                       placeholder="Search Your Broker"
                                       autocomplete="off">
                            </label>
                            <button type="submit" class="bc-hero__search-btn">Search</button>
                        </div>

                        <div class="bc-hero__seg" role="group" aria-label="Search category">
                            <button type="button" class="bc-hero__seg-btn is-active" data-bc-hero-seg="brokers">Forex Broker</button>
                            <button type="button" class="bc-hero__seg-btn" data-bc-hero-seg="props">Prop Firms</button>
                        </div>

                        @if(!empty($quickFilterLinks))
                            <div class="bc-hero__chips" data-bc-hero-broker-only>
                                <span class="bc-hero__chips-label">Popular:</span>
                                @foreach($quickFilterLinks as $chip)
                                    <a href="{{ $chip['url'] }}" class="bc-hero__chip">{{ $chip['label'] }}</a>
                                @endforeach
                            </div>
                        @endif

                        <button type="button"
                                class="bc-hero__filters-toggle"
                                id="bcHeroFiltersToggle"
                                data-bc-hero-broker-only
                                aria-expanded="false"
                                aria-controls="bcHeroFilters">
                            <span>Filter by regulation, cost &amp; leverage</span>
                            <svg class="bc-hero__filters-chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div class="bc-hero__filters" id="bcHeroFilters" data-bc-hero-broker-only hidden>
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

            <aside class="bc-hero__visual" aria-hidden="true">
                <div class="bc-hero-carousel">
                    <div class="bc-hero-carousel__tilt">
                        <div class="bc-hero-carousel__ring">
                            <div class="bc-hero-card bc-hero-card--lg" style="--a: 0deg;">
                                <p class="bc-hero-card__label">Brokers listed</p>
                                <p class="bc-hero-card__value">{{ number_format($brokersTotal) }}</p>
                                <svg class="bc-hero-card__chart" viewBox="0 0 200 64" fill="none" preserveAspectRatio="none">
                                    <path d="M4 52 C28 18, 48 58, 74 38 S120 8, 146 30 182 16, 196 24" stroke="#e8822a" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="bc-hero-card bc-hero-card--lg" style="--a: 90deg;">
                                <p class="bc-hero-card__label">Regulated brokers</p>
                                <p class="bc-hero-card__value">{{ number_format($regulatedTotal) }}</p>
                                <div class="bc-hero-card__bars">
                                    <span style="height:45%"></span>
                                    <span style="height:70%"></span>
                                    <span style="height:55%"></span>
                                    <span style="height:100%"></span>
                                </div>
                            </div>

                            <div class="bc-hero-card bc-hero-card--lg" style="--a: 180deg;">
                                <p class="bc-hero-card__label">Reviews published</p>
                                <p class="bc-hero-card__value">{{ number_format($reviewsTotal) }}</p>
                                <svg class="bc-hero-card__chart" viewBox="0 0 200 64" fill="none" preserveAspectRatio="none">
                                    <path d="M4 42 C30 56, 52 12, 84 28 S138 56, 162 26 188 38, 196 32" stroke="#f5a623" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="bc-hero-card bc-hero-card--lg" style="--a: 270deg;">
                                <p class="bc-hero-card__label">Avg. broker rating</p>
                                <p class="bc-hero-card__value">{{ number_format($avgRating, 1) }}/5</p>
                                <div class="bc-hero-card__bars">
                                    <span style="height:60%"></span>
                                    <span style="height:40%"></span>
                                    <span style="height:85%"></span>
                                    <span style="height:65%"></span>
                                </div>
                            </div>

                            <div class="bc-hero-card bc-hero-card--sm bc-hero-card--row" style="--a: 45deg;">
                                <div class="bc-hero-card__donut" style="--pct: {{ $ratingPct }}%;"></div>
                                <div class="bc-hero-card__lines">
                                    <span class="bc-hero-card__line bc-hero-card__line--accent"></span>
                                    <span class="bc-hero-card__line" style="width:75%"></span>
                                    <span class="bc-hero-card__line" style="width:50%"></span>
                                </div>
                            </div>

                            <div class="bc-hero-card bc-hero-card--sm" style="--a: 135deg;">
                                <svg class="bc-hero-card__chart bc-hero-card__chart--fill" viewBox="0 0 120 70" fill="none" preserveAspectRatio="none">
                                    <path d="M4 40 C20 20, 32 55, 50 38 S86 14, 100 34 112 28, 116 30" stroke="#ffffff" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                            </div>

                            <div class="bc-hero-card bc-hero-card--sm" style="--a: 225deg;">
                                <div class="bc-hero-card__lines">
                                    <span class="bc-hero-card__line bc-hero-card__line--accent"></span>
                                    <span class="bc-hero-card__line" style="width:83%"></span>
                                    <span class="bc-hero-card__line bc-hero-card__line--accent" style="width:66%"></span>
                                    <span class="bc-hero-card__line" style="width:50%"></span>
                                </div>
                            </div>

                            <div class="bc-hero-card bc-hero-card--sm" style="--a: 315deg;">
                                <div class="bc-hero-card__bars bc-hero-card__bars--fill">
                                    <span style="height:35%;background:rgba(255,255,255,.8)"></span>
                                    <span style="height:65%"></span>
                                    <span style="height:50%;background:rgba(255,255,255,.8)"></span>
                                    <span style="height:90%"></span>
                                    <span style="height:60%;background:rgba(255,255,255,.8)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
