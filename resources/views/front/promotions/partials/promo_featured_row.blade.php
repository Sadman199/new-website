@if(($featuredCards ?? collect())->isNotEmpty())
    <section class="bpr-featured-row" aria-labelledby="bprFeaturedTitle">
        <div class="bpr-featured-row__head">
            <div>
                <p class="bpr-section__eyebrow">Editor's picks</p>
                <h2 class="bpr-featured-row__title" id="bprFeaturedTitle">Featured promotions</h2>
            </div>
            <div class="bpr-featured-row__actions">
                @if(!($featuredOnly ?? false))
                    <a href="{{ app(\App\Services\PromotionsIndexService::class)->tabUrl($activeTab, $activeSort ?? 'featured', true, null) }}"
                       class="bpr-featured-row__link">
                        View all featured
                    </a>
                @endif
                <div class="bpr-featured-row__nav" aria-hidden="false">
                    <button type="button" class="bpr-featured-row__btn" id="bpr-featured-prev" aria-label="Previous featured promotions">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" class="bpr-featured-row__btn" id="bpr-featured-next" aria-label="Next featured promotions">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="bpr-featured-row__viewport" id="bpr-featured-viewport">
            <div class="bpr-featured-row__track" id="bpr-featured-track">
                @foreach($featuredCards as $promo)
                    @include('front.promotions.partials.promo_featured_compact', ['promo' => $promo])
                @endforeach
            </div>
        </div>
    </section>
@endif
