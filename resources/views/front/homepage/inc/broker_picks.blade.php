<section class="bc-picks-section" id="top-brokers" aria-labelledby="bcPicksTitle">
    @php $t = $site_t ?? fn (string $key, ?string $default = null) => $default ?? $key; @endphp
    <div class="container bc-picks-section__container">
        <header class="bc-picks-section__head">
            <div class="bc-picks-section__intro">
                <p class="bc-picks-section__eyebrow">
                    <span class="bc-picks-section__eyebrow-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6L12 2z"/></svg>
                    </span>
                    {{ $t('home.picks_eyebrow') }} · {{ date('F Y') }}
                </p>
                <h2 id="bcPicksTitle" class="bc-picks-section__title">{{ $t('home.picks_title') }}</h2>
                <p class="bc-picks-section__sub">{{ $t('home.picks_sub') }}</p>
            </div>
            <a href="{{ route('broker.reviews.index') }}" class="bc-picks-section__cta">
                {{ $t('home.picks_browse') }}
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </header>

        <div class="bc-picks" data-bc-picks>
            <div class="bc-picks__tabs" role="tablist" aria-label="Broker categories">
                <button type="button" class="bc-picks__tab is-active" role="tab" aria-selected="true" data-bc-pick="top">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    {{ $t('home.picks_tab_top') }}
                </button>
                <button type="button" class="bc-picks__tab" role="tab" aria-selected="false" data-bc-pick="beginners">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342"/></svg>
                    {{ $t('home.picks_tab_beginners') }}
                </button>
                <button type="button" class="bc-picks__tab" role="tab" aria-selected="false" data-bc-pick="spread">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    {{ $t('home.picks_tab_spread') }}
                </button>
                <button type="button" class="bc-picks__tab" role="tab" aria-selected="false" data-bc-pick="leverage">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    {{ $t('home.picks_tab_leverage') }}
                </button>
                <button type="button" class="bc-picks__tab" role="tab" aria-selected="false" data-bc-pick="bonuses">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                    {{ $t('home.picks_tab_bonuses') }}
                </button>
            </div>

            <div class="bc-picks__panel is-active" data-bc-pick-panel="top" role="tabpanel">
                @include('front.homepage.inc.broker_picks_panel', ['brokers' => $topRatedBrokers])
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="beginners" role="tabpanel" hidden>
                @include('front.homepage.inc.broker_picks_panel', ['brokers' => $bestForBeginners, 'empty' => 'No beginner-friendly brokers in our database yet.'])
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="spread" role="tabpanel" hidden>
                @include('front.homepage.inc.broker_picks_panel', ['brokers' => $spreadRankings])
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="leverage" role="tabpanel" hidden>
                @include('front.homepage.inc.broker_picks_panel', ['brokers' => $best_leverage_brokers])
            </div>
            <div class="bc-picks__panel" data-bc-pick-panel="bonuses" role="tabpanel" hidden>
                @include('front.homepage.inc.broker_picks_panel', ['brokers' => $bestBonuses, 'empty' => 'No bonus-eligible brokers listed yet.'])
            </div>
        </div>
    </div>
</section>
