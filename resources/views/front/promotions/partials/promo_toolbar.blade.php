@php
    $activeSort = $activeSort ?? 'featured';
    $featuredOnly = $featuredOnly ?? false;
    $promotionsService = app(\App\Services\PromotionsIndexService::class);
@endphp

<div class="bpr-filters" id="bpr-toolbar">
    <div class="bpr-filters__bar">
        <nav class="bpr-filters__tabs" id="bpr-tabs" aria-label="Promotion categories">
            @foreach($tabs as $tab)
                <a href="{{ $promotionsService->tabUrl($tab['slug'], $activeSort, $featuredOnly, null) }}"
                   class="bpr-tab {{ $activeTab === $tab['slug'] ? 'is-active' : '' }}"
                   @if($activeTab === $tab['slug']) aria-current="page" @endif>
                    {{ $tab['name'] }}
                    <span class="bpr-tab__count">{{ $tab['count'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="bpr-filters__controls">
            <label class="bpr-filters__toggle">
                <input type="checkbox"
                       id="bpr-featured-toggle"
                       @checked($featuredOnly)
                       data-base-url="{{ $promotionsService->tabUrl($activeTab, $activeSort, false, null) }}"
                       data-featured-url="{{ $promotionsService->tabUrl($activeTab, $activeSort, true, null) }}">
                <span>Editor’s picks</span>
            </label>

            <label class="bpr-filters__sort">
                <span class="bpr-sr-only">Sort promotions</span>
                <select id="bpr-sort-select"
                        data-base-url="{{ $promotionsService->tabUrl($activeTab, null, $featuredOnly, null) }}">
                    @foreach($sortOptions as $value => $label)
                        <option value="{{ $value }}" @selected($activeSort === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>
</div>
