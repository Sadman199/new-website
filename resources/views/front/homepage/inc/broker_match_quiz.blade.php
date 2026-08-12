@php
    $matchSteps = $matchQuizConfig['steps'] ?? [];
    $matchOptions = $matchQuizConfig['options'] ?? [];
    $brokerTotal = $homeStats['total'] ?? $brokerCount ?? 0;
@endphp

<section class="bc-match" id="bcMatchQuiz" aria-labelledby="bcMatchTitle"
         data-endpoint="{{ route('broker_match.recommend') }}"
         data-csrf="{{ csrf_token() }}"
         data-broker-count="{{ $brokerTotal }}"
         data-config='@json(['steps' => $matchSteps, 'options' => $matchOptions])'>
    <div class="bc-match__glow" aria-hidden="true"></div>
    <div class="bc-container">
        <div class="bc-match__shell">
            <aside class="bc-match__aside">
                <p class="bc-match__eyebrow">
                    <span class="bc-match__pulse"></span>
                    BrokerMatch
                </p>
                <h2 class="bc-match__title" id="bcMatchTitle">Find your ideal broker</h2>
                <p class="bc-match__lead">Seven quick questions scored against <strong>{{ number_format($brokerTotal) }}+ brokers</strong>. Results open as a filtered broker list — visit, review, save, or compare.</p>

                <div class="bc-match__profile" id="bcMatchProfile">
                    <h3 class="bc-match__profile-title" id="bcMatchProfileTitle">Your profile</h3>
                    <ul class="bc-match__profile-tags" id="bcMatchProfileTags"></ul>
                </div>
            </aside>

            <div class="bc-match__engine">
                <div class="bc-match__engine-head">
                    <div class="bc-match__saved-banner is-hidden" id="bcMatchSavedBanner">
                        <p>You have saved match results from a previous session.</p>
                        <button type="button" class="bc-match__saved-btn" id="bcMatchLoadSaved">View matching brokers</button>
                        <button type="button" class="bc-match__saved-dismiss" id="bcMatchDismissSaved" aria-label="Dismiss">×</button>
                    </div>
                    <div class="bc-match__progress">
                        <div class="bc-match__progress-track">
                            <div class="bc-match__progress-fill" id="bcMatchProgressFill"></div>
                        </div>
                        <span class="bc-match__progress-text" id="bcMatchProgressLabel">1 / 7</span>
                    </div>
                </div>

                <div class="bc-match__stage" id="bcMatchStage">
                    <div class="bc-match__wizard" id="bcMatchWizard"></div>

                    <div class="bc-match__results is-hidden" id="bcMatchResults" aria-live="polite">
                        <header class="bc-match__results-header">
                            <p class="bc-match__results-kicker" id="bcMatchResultsKicker">Analysis complete</p>
                            <h3 class="bc-match__results-title" id="bcMatchResultsTitle">Your best matches</h3>
                            <p class="bc-match__results-summary" id="bcMatchResultsSummary"></p>
                            <p class="bc-match__results-meta" id="bcMatchResultsMeta"></p>
                        </header>
                        <div class="bc-match__results-list" id="bcMatchResultsGrid"></div>
                        <footer class="bc-match__results-footer">
                            <a href="#" class="bc-match__cta bc-match__cta--primary" id="bcMatchSeeAll">Explore all matching brokers</a>
                            <a href="#" class="bc-match__cta bc-match__cta--ghost is-hidden" id="bcMatchCompareTop" target="_blank" rel="noopener">Compare top 2</a>
                            <button type="button" class="bc-match__cta bc-match__cta--ghost" id="bcMatchCopyLink">Copy filtered search link</button>
                            <a href="{{ route('broker.scam_checker') }}" class="bc-match__cta bc-match__cta--ghost">Run scam check</a>
                            <button type="button" class="bc-match__cta bc-match__cta--text" id="bcMatchRestart">Retake quiz</button>
                        </footer>
                    </div>

                    <div class="bc-match__loading is-hidden" id="bcMatchLoading" aria-live="polite">
                        <div class="bc-match__loading-rings" aria-hidden="true">
                            <span></span><span></span><span></span>
                        </div>
                        <p class="bc-match__loading-title" id="bcMatchLoadingTitle">Analysing broker database…</p>
                        <p class="bc-match__loading-sub" id="bcMatchLoadingSub">Scoring regulation, costs &amp; platform fit</p>
                    </div>
                </div>

                <div class="bc-match__nav" id="bcMatchNav">
                    <button type="button" class="bc-match__nav-btn bc-match__nav-btn--back" id="bcMatchBack" disabled>
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
                        Back
                    </button>
                    <button type="button" class="bc-match__nav-btn bc-match__nav-btn--skip is-hidden" id="bcMatchSkip">Skip</button>
                    <button type="button" class="bc-match__nav-btn bc-match__nav-btn--next" id="bcMatchNext" disabled>
                        Continue
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
