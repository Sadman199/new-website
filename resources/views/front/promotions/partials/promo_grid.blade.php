@if(collect($cards ?? [])->isNotEmpty())
    <div class="bpr-list" id="bpr-list">
        <div class="bpr-list__head" aria-hidden="true">
            <span>Broker</span>
            <span>Promotion</span>
            <span>Key terms</span>
            <span>Action</span>
        </div>

        <div class="bpr-list__rows" id="bpr-grid">
            @foreach($cards as $promo)
                @include('front.promotions.partials.promo_row', ['promo' => $promo])
            @endforeach
        </div>
    </div>

    @if($hasMore ?? false)
        <div class="bpr-load-more" id="bpr-load-more">
            <button type="button"
                    class="bc-btn bc-btn--outline bpr-load-more__btn"
                    id="bpr-load-more-btn"
                    data-offset="{{ $loadedCount ?? $cards->count() }}"
                    data-type="{{ $activeTab }}"
                    data-sort="{{ $activeSort ?? 'featured' }}"
                    data-featured="{{ !empty($featuredOnly) ? '1' : '0' }}"
                    data-search="{{ $search ?? '' }}"
                    data-broker="{{ $activeFilters['broker'] ?? '' }}"
                    data-category="{{ $activeFilters['category'] ?? '' }}"
                    data-status="{{ $activeFilters['status'] ?? '' }}"
                    data-max-min-deposit="{{ $activeFilters['max_min_deposit'] ?? '' }}"
                    data-endpoint="{{ route('promotions.load_more') }}">
                Load more promotions
            </button>
            <p class="bpr-load-more__meta">
                <span id="bpr-loaded-count">{{ $loadedCount ?? $cards->count() }}</span>
                of
                <span id="bpr-total-count-footer">{{ $totalCount ?? $cards->count() }}</span>
                loaded
            </p>
        </div>
    @endif
@endif
