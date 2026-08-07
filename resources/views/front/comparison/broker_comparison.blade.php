@extends('front.layout.app')

@section('title', 'Compare Forex Brokers Side by Side | BrokersCourt')
@section('meta_description', 'Compare up to 3 forex brokers side by side. Review regulation, spreads, platforms, deposit methods, and ratings to find the best broker for your trading style.')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/broker-compare.css') }}?v=4">
@endpush

@section('main_content')
<div class="bc-compare-page">
    <header class="bc-compare-hero">
        <div class="bc-compare-wrap">
            <nav class="bc-compare-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Compare brokers</span>
            </nav>
            <p class="bc-compare-hero__eyebrow">Independent broker research</p>
            <h1 class="bc-compare-hero__title">Brokers comparison tool</h1>
            <p class="bc-compare-hero__sub">Select up to 3 brokers to compare regulation, costs, platforms, and service quality side by side — powered by live database data.</p>
        </div>
    </header>

    <div class="bc-compare-wrap">
        @if(!empty($popularComparisons))
            <section class="bc-compare-popular" aria-label="Popular comparisons">
                <p class="bc-compare-popular__label">Popular comparisons</p>
                <div class="bc-compare-popular__grid">
                    @foreach(array_slice($popularComparisons, 0, 6) as $pair)
                        <a href="{{ $pair['url'] }}" class="bc-compare-popular__chip">{{ $pair['label'] }}</a>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="bc-compare-shell">
            <div class="bc-compare-tabs" role="tablist">
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

            <div class="bc-compare-pickers">
                @for($i = 0; $i < 3; $i++)
                    @if($i > 0)
                        <div class="bc-compare-vs" aria-hidden="true">VS</div>
                    @endif
                    <div class="bc-compare-slot" data-compare-slot="{{ $i }}">
                        <div class="bc-compare-slot__inner">
                            <span class="bc-compare-slot__placeholder">Add a broker</span>
                            <div class="bc-compare-slot__selected bc-compare-hidden">
                                <span class="bc-compare-slot__logo"></span>
                                <span class="bc-compare-slot__name"></span>
                                <button type="button" class="bc-compare-slot__clear" aria-label="Remove broker">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="bc-compare-slot__search">
                            <input type="text"
                                   class="bc-compare-slot__search-input"
                                   placeholder="Search brokers…"
                                   autocomplete="off"
                                   aria-label="Search brokers">
                            <div class="bc-compare-slot__results"></div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="bc-compare-actions">
                <p class="bc-compare-actions__hint">Pick at least 2 brokers to see the comparison table below.</p>
                <div class="bc-compare-actions__buttons">
                    <button type="button" class="bc-compare-btn bc-compare-btn--ghost" id="bcCompareClearBtn">
                        Clear all
                    </button>
                </div>
            </div>

            <div class="bc-compare-main">
                <aside class="bc-compare-sidebar">
                    <div class="bc-compare-sidebar__head" id="bcCompareSidebarHead">Overall</div>
                    <ul class="bc-compare-sidebar__rows" id="bcCompareSidebarRows"></ul>
                </aside>

                <div class="bc-compare-content">
                    <div id="bcCompareMatrixWrap" class="bc-compare-hidden"></div>

                    <div class="bc-compare-suggestions" id="bcCompareSuggestions">
                        <p class="bc-compare-suggestions__title">Suggested brokers</p>
                        <div class="bc-compare-suggestions__grid">
                            @foreach($suggestedBrokers as $broker)
                                <button type="button"
                                        class="bc-compare-suggestion"
                                        data-suggest-slug="{{ $broker->slug }}">
                                    <div class="bc-compare-suggestion__logo">
                                        @if($broker->logo)
                                            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}">
                                        @else
                                            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <span class="bc-compare-suggestion__name">{{ $broker->name }}</span>
                                    @if($broker->rating)
                                        <span class="bc-compare-suggestion__score">{{ number_format($broker->rating, 1) }}</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.BROKER_COMPARE = {
        brokers: @json($brokersPayload),
        tabGroups: @json($tabGroups),
        searchUrl: @json(route('broker.live.search'))
    };
</script>
<script src="{{ asset('js/broker-compare.js') }}?v=4"></script>
@endpush
