<section class="bc-result-cta" aria-label="Next steps">
    <div class="bc-result-cta__inner">
        <div class="bc-result-cta__copy">
            <h2 class="bc-result-cta__title">Still deciding between {{ $comparison['broker1']['name'] }} and {{ $comparison['broker2']['name'] }}?</h2>
            <p class="bc-result-cta__sub">Read our in-depth reviews or run a safety check before you open an account.</p>
        </div>
        <div class="compare-table__actions bc-result-cta__actions">
            <a href="{{ $comparison['broker1']['review_url'] }}" class="bc-compare-btn bc-compare-btn--ghost">{{ $comparison['broker1']['name'] }} review</a>
            <a href="{{ $comparison['broker2']['review_url'] }}" class="bc-compare-btn bc-compare-btn--primary">{{ $comparison['broker2']['name'] }} review</a>
        </div>
    </div>
</section>
