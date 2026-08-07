@if($cards->isNotEmpty())
    <div class="bpr-grid" id="bpr-grid">
        @foreach($cards as $promo)
            @include('front.promotions.partials.promo_card', ['promo' => $promo])
        @endforeach
    </div>

    @if($hasMore ?? false)
        <div class="bpr-load-more" id="bpr-load-more">
            <button type="button"
                    class="bpr-btn bpr-btn--ghost bpr-load-more__btn"
                    id="bpr-load-more-btn"
                    data-offset="{{ $loadedCount ?? $cards->count() }}"
                    data-type="{{ $activeTab }}"
                    data-endpoint="{{ route('promotions.load_more') }}">
                Load more promotions
            </button>
            <p class="bpr-load-more__meta">
                Showing <span id="bpr-loaded-count">{{ $loadedCount ?? $cards->count() }}</span>
                of <span id="bpr-total-count">{{ $totalCount ?? $cards->count() }}</span>
            </p>
        </div>
    @endif
@endif
