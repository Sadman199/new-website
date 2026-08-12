@php
    $activeSort = $activeSort ?? 'featured';
    $featuredOnly = $featuredOnly ?? false;
    $promotionsService = app(\App\Services\PromotionsIndexService::class);
@endphp

<div class="bpr-tabbar" id="bpr-toolbar">
    {{-- Mobile category select (hidden on desktop) --}}
    <div class="bpr-tabbar__mobile-row">
        <select id="bpr-mobile-tab-select" class="bpr-tabbar__select" aria-label="Category">
            @foreach($tabs as $tab)
                <option value="{{ $promotionsService->tabUrl($tab['slug'], $activeSort, $featuredOnly, null) }}"
                        @selected($activeTab === $tab['slug'])>
                    {{ $tab['name'] }} ({{ $tab['count'] }})
                </option>
            @endforeach
        </select>

        <div class="bpr-tabbar__mobile-controls">
            <select id="bpr-sort-select-mobile" class="bpr-tabbar__select" aria-label="Sort"
                    data-base-url="{{ $promotionsService->tabUrl($activeTab, null, $featuredOnly, null) }}">
                @foreach($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected($activeSort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Desktop tabs + controls in one bar --}}
    <nav class="bpr-tabbar__desktop" aria-label="Promotion categories" id="bpr-tabs">
        <div class="bpr-tabbar__tabs">
            @foreach($tabs as $tab)
                <a href="{{ $promotionsService->tabUrl($tab['slug'], $activeSort, $featuredOnly, null) }}"
                   class="bpr-tab {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
                   @if($activeTab === $tab['slug']) aria-current="page" @endif>
                    {{ $tab['name'] }}
                    <span class="bpr-tab__count">{{ $tab['count'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="bpr-tabbar__controls">
            <select id="bpr-sort-select" class="bpr-tabbar__sort" aria-label="Sort promotions"
                    data-base-url="{{ $promotionsService->tabUrl($activeTab, null, $featuredOnly, null) }}">
                @foreach($sortOptions as $value => $label)
                    <option value="{{ $value }}" @selected($activeSort === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <label class="bpr-tabbar__featured-label">
                <input type="checkbox"
                       id="bpr-featured-toggle"
                       class="bpr-tabbar__featured-check"
                       @checked($featuredOnly)
                       data-base-url="{{ $promotionsService->tabUrl($activeTab, $activeSort, false, null) }}"
                       data-featured-url="{{ $promotionsService->tabUrl($activeTab, $activeSort, true, null) }}">
                <span>Featured</span>
            </label>
        </div>
    </nav>
</div>
