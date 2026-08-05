<nav id="bc-scroll-nav" class="br-nav">
    <div class="br-nav__inner">
        <button type="button" id="bc-nav-left" class="br-nav__scroll-btn br-nav__scroll-btn--left" aria-label="Scroll left">Previous</button>
        <div class="br-nav__scroll">
            <div class="br-nav__links">
                <a href="#gettingstarted" class="br-nav__link">Overview</a>
                <a href="#key-stats" class="br-nav__link">Pros & Cons</a>
                @if(strip_tags($broker->description ?? ''))
                <a href="#review-body" class="br-nav__link">Full Review</a>
                @endif
                <a href="#brokeroverview" class="br-nav__link">Key data</a>
                <a href="#fees" class="br-nav__link">Fees</a>
                <a href="#safety" class="br-nav__link">Safety</a>
                <a href="#deposits-withdrawals" class="br-nav__link">Deposits</a>
                <a href="#platforms" class="br-nav__link">Platforms</a>
                @if($account_options->isNotEmpty())
                <a href="#account-types" class="br-nav__link">Accounts</a>
                @endif
                @if($broker->forexBonuses && $broker->forexBonuses->isNotEmpty())
                <a href="#broker-promotions" class="br-nav__link">Promotions</a>
                @endif
                <a href="#faqs" class="br-nav__link">FAQs</a>
                <a href="#voices" class="br-nav__link">Comments</a>
                <a href="#compare" class="br-nav__link">Compare</a>
                <a href="#author-profile" class="br-nav__link">Author</a>
            </div>
        </div>
        <button type="button" id="bc-nav-right" class="br-nav__scroll-btn br-nav__scroll-btn--right" aria-label="Scroll right">Next</button>
    </div>
</nav>
