@extends('front.layout.app')

@section('title', 'Compare Forex Brokers Side by Side | BrokersCourt')
@section('meta_description', 'Compare up to 3 forex brokers side by side. Review regulation, spreads, platforms, deposit methods, trust scores, and ratings from our live broker database.')
@section('canonical', route('broker.comparison'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/broker-compare.css') }}?v=17">
@endpush

@section('main_content')
<div class="bc-compare-page bc-compare-tool">
    <section class="bc-compare-hero">
        <div class="container">
            <nav class="bc-compare-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Compare brokers</span>
            </nav>

            <div class="bc-compare-hero__grid">
                <div class="bc-compare-hero__copy">
                    <p class="bc-compare-hero__eyebrow">
                        <span class="bc-compare-hero__eyebrow-dot" aria-hidden="true"></span>
                        Independent broker research
                    </p>
                    <h1 class="bc-compare-hero__title">
                        Compare forex brokers
                        <span class="bc-compare-hero__title-accent">side by side</span>
                    </h1>
                    <p class="bc-compare-hero__sub">Pick up to three brokers and stack regulation, costs, platforms, and trust metrics from live BrokersCourt data.</p>
                    <ul class="bc-compare-hero__pills">
                        <li>Up to 3 brokers</li>
                        <li>Live catalog data</li>
                        <li>Winners highlighted</li>
                    </ul>
                </div>
            </div>

            <div class="bc-compare-arena" id="compare-tool">
                <div class="bc-compare-arena__head">
                    <div>
                        <p class="bc-compare-arena__kicker">Comparison arena</p>
                        <h2 class="bc-compare-arena__title">Build your matchup</h2>
                    </div>
                    <div class="bc-compare-actions__buttons">
                        <a href="#" class="bc-compare-btn bc-compare-btn--primary bc-compare-hidden" id="bcComparePairLink">Open full comparison</a>
                        <a href="#" class="bc-compare-btn bc-compare-btn--ghost bc-compare-hidden" id="bcBattleModeLink">Battle mode</a>
                        <button type="button" class="bc-compare-btn bc-compare-btn--ghost" id="bcCompareClearBtn">Clear all</button>
                    </div>
                </div>

                <div class="bc-compare-pickers">
                    @for($i = 0; $i < 3; $i++)
                        @if($i > 0)
                            <div class="bc-compare-vs" aria-hidden="true">VS</div>
                        @endif
                        <div class="bc-compare-slot" data-compare-slot="{{ $i }}">
                            <div class="bc-compare-slot__inner">
                                <span class="bc-compare-slot__placeholder">
                                    <span class="bc-compare-slot__placeholder-icon" aria-hidden="true">+</span>
                                    <span class="bc-compare-slot__placeholder-text">{{ $i === 0 ? 'Add first broker' : ($i === 1 ? 'Add second broker' : 'Optional third') }}</span>
                                    <span class="bc-compare-slot__placeholder-hint">Search the catalog</span>
                                </span>
                                <div class="bc-compare-slot__selected bc-compare-hidden">
                                    <span class="bc-compare-slot__logo"></span>
                                    <div class="bc-compare-slot__meta">
                                        <span class="bc-compare-slot__name"></span>
                                        <span class="bc-compare-slot__sub"></span>
                                    </div>
                                    <button type="button" class="bc-compare-slot__clear" aria-label="Remove broker">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="bc-compare-slot__search">
                                <input type="text"
                                       class="bc-compare-slot__search-input"
                                       placeholder="Search by name…"
                                       autocomplete="off"
                                       aria-label="Search brokers">
                                <div class="bc-compare-slot__results"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                <p class="bc-compare-actions__hint" id="bcCompareHint">Pick at least two brokers to unlock the comparison matrix.</p>
            </div>
        </div>
    </section>

    <div class="container bc-compare-body">
        <div class="bc-compare-suggestions" id="bcCompareSuggestions">
            <div class="bc-compare-section-head bc-compare-section-head--row">
                <div>
                    <h3 class="bc-compare-section-head__title" id="bcCompareSuggestionsTitle">Suggested brokers</h3>
                    <p class="bc-compare-section-head__text">Top-rated names from our database — tap to drop into a slot.</p>
                </div>
            </div>
            <div class="bc-compare-suggestions__grid">
                @foreach($suggestedBrokers as $broker)
                    <button type="button"
                            class="bc-compare-suggestion"
                            data-suggest-slug="{{ $broker['slug'] }}">
                        <div class="bc-compare-suggestion__logo">
                            @if($broker['logo'])
                                <img src="{{ $broker['logo'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <span>{{ strtoupper(substr($broker['name'], 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="bc-compare-suggestion__body">
                            <span class="bc-compare-suggestion__name">{{ $broker['name'] }}</span>
                            <span class="bc-compare-suggestion__meta">
                                @if($broker['rating'] !== null)
                                    {{ number_format($broker['rating'], 1) }}/5
                                @endif
                                @if(($broker['regulatory_tier'] ?? '—') !== '—')
                                    · {{ $broker['regulatory_tier'] }}
                                @endif
                            </span>
                        </div>
                        <span class="bc-compare-suggestion__add" aria-hidden="true">+</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="bc-compare-shell" id="bcCompareShell">
            <div class="bc-compare-tabs" role="tablist" aria-label="Comparison categories">
                @foreach($tabGroups as $key => $group)
                    <button type="button"
                            class="bc-compare-tab {{ $loop->first ? 'is-active' : '' }}"
                            data-compare-tab="{{ $key }}"
                            role="tab"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $group['label'] }}
                    </button>
                @endforeach
            </div>

            <div class="bc-compare-winners bc-compare-hidden" id="bcCompareWinners" aria-live="polite"></div>
            <div class="bc-compare-profiles bc-compare-hidden" id="bcCompareProfiles"></div>

            <div class="bc-compare-toolbar bc-compare-hidden" id="bcCompareToolbar">
                <div class="bc-compare-toolbar__left">
                    <span class="bc-compare-toolbar__label" id="bcCompareSidebarHead">Overview</span>
                    <span class="bc-compare-toolbar__count" id="bcCompareDiffCount"></span>
                </div>
                <div class="bc-compare-toolbar__filters" role="group" aria-label="Matrix filters">
                    <button type="button" class="bc-compare-filter is-active" data-compare-filter="all">All metrics</button>
                    <button type="button" class="bc-compare-filter" data-compare-filter="diff">Differences only</button>
                </div>
            </div>

            <div class="bc-compare-main" id="bcCompareMain">
                <aside class="bc-compare-sidebar" aria-hidden="true">
                    <ul class="bc-compare-sidebar__rows" id="bcCompareSidebarRows"></ul>
                </aside>
                <div class="bc-compare-content">
                    <div id="bcCompareMatrixWrap" class="bc-compare-hidden"></div>
                </div>
            </div>
        </div>

        @if(! empty($popularComparisons))
            <section class="bc-compare-popular" aria-label="Popular comparisons">
                <div class="bc-compare-section-head">
                    <h2 class="bc-compare-section-head__title">Popular matchups</h2>
                    <p class="bc-compare-section-head__text">Jump straight into a ready-made head-to-head.</p>
                </div>
                <div class="bc-compare-popular__grid">
                    @foreach(array_slice($popularComparisons, 0, 4) as $pair)
                        <a href="{{ $pair['url'] }}" class="bc-compare-popular__card">
                            <div class="bc-compare-popular__pair">
                                <span class="bc-compare-popular__side">
                                    <span class="bc-compare-popular__logo">
                                        @if(! empty($pair['left']['logo']))
                                            <img src="{{ $pair['left']['logo'] }}" alt="" loading="lazy" decoding="async">
                                        @else
                                            <span>{{ Str::substr($pair['left']['name'] ?? 'B', 0, 1) }}</span>
                                        @endif
                                    </span>
                                    <span class="bc-compare-popular__name">{{ $pair['left']['name'] ?? explode(' vs ', $pair['label'])[0] }}</span>
                                    @if(! empty($pair['left']['rating']))
                                        <span class="bc-compare-popular__score">{{ $pair['left']['rating'] }}</span>
                                    @endif
                                </span>
                                <span class="bc-compare-popular__vs" aria-hidden="true">vs</span>
                                <span class="bc-compare-popular__side">
                                    <span class="bc-compare-popular__logo">
                                        @if(! empty($pair['right']['logo']))
                                            <img src="{{ $pair['right']['logo'] }}" alt="" loading="lazy" decoding="async">
                                        @else
                                            <span>{{ Str::substr($pair['right']['name'] ?? 'B', 0, 1) }}</span>
                                        @endif
                                    </span>
                                    <span class="bc-compare-popular__name">{{ $pair['right']['name'] ?? (explode(' vs ', $pair['label'])[1] ?? '') }}</span>
                                    @if(! empty($pair['right']['rating']))
                                        <span class="bc-compare-popular__score">{{ $pair['right']['rating'] }}</span>
                                    @endif
                                </span>
                            </div>
                            <span class="bc-compare-popular__cta">Open comparison</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="bc-compare-howto" aria-labelledby="compare-howto-title">
            <div class="bc-compare-section-head">
                <h2 class="bc-compare-section-head__title" id="compare-howto-title">How the comparison tool works</h2>
                <p class="bc-compare-section-head__text">A faster way to research brokers before you open an account.</p>
            </div>
            <ol class="bc-compare-howto__grid">
                <li>
                    <span class="bc-compare-howto__step">1</span>
                    <h3>Choose brokers</h3>
                    <p>Search the catalog or tap a suggestion. Add two for a pair, or a third for a wider scan.</p>
                </li>
                <li>
                    <span class="bc-compare-howto__step">2</span>
                    <h3>Switch categories</h3>
                    <p>Jump between safety, trading costs, platforms, payments, and service to focus on what matters.</p>
                </li>
                <li>
                    <span class="bc-compare-howto__step">3</span>
                    <h3>Spot winners fast</h3>
                    <p>Highlighted cells mark stronger values on comparable metrics. Use Differences only to cut noise.</p>
                </li>
                <li>
                    <span class="bc-compare-howto__step">4</span>
                    <h3>Go deeper</h3>
                    <p>Open the full comparison or battle mode for two brokers, or jump into each review.</p>
                </li>
            </ol>
        </section>

        @if(! empty($relatedGuides))
            <section class="bc-compare-guides" aria-labelledby="compare-guides-title">
                <div class="bc-compare-section-head">
                    <h2 class="bc-compare-section-head__title" id="compare-guides-title">Keep researching</h2>
                    <p class="bc-compare-section-head__text">Editorial shortlists ranked from the same live database.</p>
                </div>
                <div class="bc-compare-guides__grid">
                    @foreach($relatedGuides as $guide)
                        <a href="{{ $guide['url'] }}" class="bc-compare-guides__card">
                            <h3>{{ $guide['title'] }}</h3>
                            <p>{{ $guide['description'] }}</p>
                            <span>View guide</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(! empty($toolFaqs))
            <section class="bc-compare-faq" aria-labelledby="compare-faq-title">
                <div class="bc-compare-faq__intro">
                    <h2 class="bc-compare-section-head__title" id="compare-faq-title">Comparison FAQ</h2>
                    <p class="bc-compare-section-head__text">Short answers about how this tool ranks brokers and where the numbers come from.</p>
                </div>
                <div class="bc-compare-faq__list">
                    @foreach($toolFaqs as $index => $faq)
                        <details class="bc-compare-faq__item" @if($index === 0) open @endif>
                            <summary>{{ $faq['question'] }}</summary>
                            <p>{{ $faq['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        <p class="bc-compare-disclaimer">Everything on BrokersCourt is based on verified broker data and independent research. We may receive compensation from brokers we feature. Trading forex and CFDs involves significant risk.@if(! empty($catalogStats['updated_at'])) Data snapshot {{ $catalogStats['updated_at'] }}.@endif</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.BROKER_COMPARE = {
        brokers: @json($brokersPayload),
        tabGroups: @json($tabGroups),
        searchUrl: @json(route('broker.live.search')),
        pairBase: @json(url('/brokers/compare')),
        battleBase: @json(url('/broker-battle'))
    };
</script>
<script src="{{ asset('js/broker-compare.js') }}?v=13" defer></script>
@endpush
