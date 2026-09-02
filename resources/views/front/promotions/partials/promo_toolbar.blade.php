@php
    $promotionsService = app(\App\Services\PromotionsIndexService::class);
    $activeSort = $activeSort ?? 'featured';
    $featuredOnly = $featuredOnly ?? false;
    $activeFilters = $activeFilters ?? [];
    $filterOptions = $filterOptions ?? [];
    $formAction = $promotionsService->tabUrl($activeTab, $activeSort, $featuredOnly, null, $activeFilters);
    $hasActiveFilters = filled($search ?? null)
        || $featuredOnly
        || collect($activeFilters)->filter()->isNotEmpty();
@endphp

@php
    $activeTabMeta = collect($tabs)->firstWhere('slug', $activeTab);
@endphp

<div class="bpr-filters" id="bpr-toolbar">
    <nav class="bpr-filters__types" aria-label="Promotion types">
        @foreach($tabs as $tab)
            <a href="{{ $promotionsService->tabUrl($tab['slug'], $activeSort, $featuredOnly, $search ?? null, $activeFilters) }}"
               class="bpr-filters__pill {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
               @if($activeTab === $tab['slug']) aria-current="page" @endif>
                <span>{{ $tab['name'] }}</span>
                <em>{{ $tab['count'] }}</em>
            </a>
        @endforeach
    </nav>

    @if(!empty($activeTabMeta['description']))
        <p class="bpr-filters__tab-desc">{{ $activeTabMeta['description'] }}</p>
    @endif

    <form class="bpr-filters__bar" method="get" action="{{ $formAction }}" id="bpr-filter-form">
        <div class="bpr-filters__search">
            <label class="bpr-sr-only" for="bpr-search-input">Search promotions</label>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <input type="search"
                   id="bpr-search-input"
                   name="q"
                   value="{{ $search ?? '' }}"
                   placeholder="Search broker or promotion"
                   maxlength="80"
                   autocomplete="off">
        </div>

        @if(!empty($filterOptions['brokers']))
            <label class="bpr-filters__control">
                <span class="bpr-sr-only">Broker</span>
                <select name="broker">
                    <option value="">All brokers</option>
                    @foreach($filterOptions['brokers'] as $broker)
                        <option value="{{ $broker['id'] }}" @selected(($activeFilters['broker'] ?? null) == $broker['id'])>{{ $broker['name'] }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        @if(!empty($filterOptions['categories']))
            <label class="bpr-filters__control">
                <span class="bpr-sr-only">Category</span>
                <select name="category">
                    <option value="">All categories</option>
                    @foreach($filterOptions['categories'] as $category)
                        <option value="{{ $category }}" @selected(($activeFilters['category'] ?? null) === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        @if(!empty($filterOptions['statuses']))
            <label class="bpr-filters__control">
                <span class="bpr-sr-only">Status</span>
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach($filterOptions['statuses'] as $status)
                        <option value="{{ $status }}" @selected(($activeFilters['status'] ?? null) === $status)>{{ ucfirst(str_replace('-', ' ', $status)) }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        @if(!empty($filterOptions['min_deposits']))
            <label class="bpr-filters__control">
                <span class="bpr-sr-only">Minimum deposit</span>
                <select name="max_min_deposit">
                    <option value="">Any min. deposit</option>
                    @foreach($filterOptions['min_deposits'] as $option)
                        <option value="{{ $option['value'] }}" @selected(($activeFilters['max_min_deposit'] ?? null) === $option['value'])>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
        @endif

        <label class="bpr-filters__control bpr-filters__control--sort">
            <span class="bpr-sr-only">Sort</span>
            <select name="sort" id="bpr-sort-select">
                @foreach($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected($activeSort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label class="bpr-filters__featured">
            <input type="checkbox" name="featured" value="1" @checked($featuredOnly)>
            <span>Featured only</span>
        </label>

        <button type="submit" class="bc-btn bc-btn--primary bpr-filters__apply">Apply</button>

        @if($hasActiveFilters)
            <a href="{{ $promotionsService->tabUrl($activeTab) }}" class="bpr-filters__reset">Reset</a>
        @endif
    </form>

    <p class="bpr-filters__meta">
        Showing <strong id="bpr-showing-count">{{ $loadedCount }}</strong>
        of <strong id="bpr-total-count">{{ $totalCount }}</strong>
        {{ \Illuminate\Support\Str::plural('promotion', $totalCount) }}
    </p>
</div>
