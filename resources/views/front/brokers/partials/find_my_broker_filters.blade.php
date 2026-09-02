@php
    $idPrefix = $idPrefix ?? 'desk';
    $filters = $filters ?? [];
    $catalogs = $catalogs ?? [];

    $countActive = fn (array $keys) => array_sum(array_map(
        fn ($key) => in_array($key, ['account_type', 'regulation', 'platform', 'markets', 'payment', 'features', 'country'], true)
            ? count($filters[$key] ?? [])
            : (($filters[$key] ?? '') !== '' ? 1 : 0),
        $keys
    ));

    $groupCounts = [
        'basics' => $countActive(['min_deposit', 'rating']),
        'regulation' => $countActive(['regulation']),
        'costs' => $countActive(['account_type', 'leverage', 'spread', 'commission']),
        'platforms' => $countActive(['platform', 'features']),
        'markets' => $countActive(['markets', 'payment']),
        'availability' => $countActive(['deposit_bonus', 'country']),
    ];
@endphp

<form class="fmb-filter-form" data-fmb-form autocomplete="off">
    <div class="fmb-filters__search-wrap">
        <svg class="fmb-filters__search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="search"
               id="{{ $idPrefix }}-q"
               name="q"
               value="{{ $filters['q'] ?? '' }}"
               placeholder="Search broker name…"
               class="fmb-filters__search"
               data-fmb-input>
    </div>

    <details class="fmb-filter-group" open>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M7 12h10M10 18h4"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Basics</span>
            @if($groupCounts['basics'])
                <span class="fmb-filter-group__count">{{ $groupCounts['basics'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <select name="min_deposit" class="fmb-filter-group__select" data-fmb-input aria-label="Minimum deposit">
                @foreach($catalogs['min_deposit'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['min_deposit'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="rating" class="fmb-filter-group__select" data-fmb-input aria-label="Minimum rating">
                @foreach($catalogs['rating'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['rating'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['regulation'])) open @endif>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Regulation</span>
            @if($groupCounts['regulation'])
                <span class="fmb-filter-group__count">{{ $groupCounts['regulation'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <div class="fmb-check-grid">
                @foreach($catalogs['regulation'] as $value => $label)
                    @if($value !== '')
                        <label class="fmb-check">
                            <input type="checkbox" name="regulation" value="{{ $value }}" @checked(in_array($value, $filters['regulation'] ?? [], true)) data-fmb-input>
                            <span>{{ $label }}</span>
                        </label>
                    @endif
                @endforeach
            </div>
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['account_type']) || !empty($filters['leverage']) || !empty($filters['spread']) || !empty($filters['commission'])) open @endif>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.66 0-3 .9-3 2s1.34 2 3 2 3 .9 3 2-1.34 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/><circle cx="12" cy="12" r="9"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Trading costs</span>
            @if($groupCounts['costs'])
                <span class="fmb-filter-group__count">{{ $groupCounts['costs'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <p class="fmb-filter-group__label">Account type</p>
            <div class="fmb-check-grid">
                @foreach($catalogs['account_type'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="account_type" value="{{ $value }}" @checked(in_array($value, $filters['account_type'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <select name="leverage" class="fmb-filter-group__select" data-fmb-input aria-label="Maximum leverage">
                @foreach($catalogs['leverage'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['leverage'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="spread" class="fmb-filter-group__select" data-fmb-input aria-label="Spread">
                @foreach($catalogs['spread'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['spread'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="commission" class="fmb-filter-group__select" data-fmb-input aria-label="Commission">
                @foreach($catalogs['commission'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['commission'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['platform']) || !empty($filters['features'])) open @endif>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v11H4zM9 20h6M9 16v4"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Platforms &amp; features</span>
            @if($groupCounts['platforms'])
                <span class="fmb-filter-group__count">{{ $groupCounts['platforms'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <div class="fmb-check-grid">
                @foreach($catalogs['platform'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="platform" value="{{ $value }}" @checked(in_array($value, $filters['platform'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
                @foreach($catalogs['features'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="features" value="{{ $value }}" @checked(in_array($value, $filters['features'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['markets']) || !empty($filters['payment'])) open @endif>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3c2.5 2.5 3.5 6 3.5 9s-1 6.5-3.5 9c-2.5-2.5-3.5-6-3.5-9s1-6.5 3.5-9z"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Markets &amp; payments</span>
            @if($groupCounts['markets'])
                <span class="fmb-filter-group__count">{{ $groupCounts['markets'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <div class="fmb-check-grid">
                @foreach($catalogs['markets'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="markets" value="{{ $value }}" @checked(in_array($value, $filters['markets'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
                @foreach($catalogs['payment'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="payment" value="{{ $value }}" @checked(in_array($value, $filters['payment'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['country']) || !empty($filters['deposit_bonus'])) open @endif>
        <summary class="fmb-filter-group__summary">
            <span class="fmb-filter-group__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7h-9m9 5h-9m9 5h-9M4 4v16M4 4l3 3M4 4 1 7m3 13 3-3m-3 3-3-3"/></svg>
            </span>
            <span class="fmb-filter-group__label-text">Availability &amp; bonuses</span>
            @if($groupCounts['availability'])
                <span class="fmb-filter-group__count">{{ $groupCounts['availability'] }}</span>
            @endif
            <svg class="fmb-filter-group__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </summary>
        <div class="fmb-filter-group__body">
            <select name="deposit_bonus" class="fmb-filter-group__select" data-fmb-input aria-label="Deposit bonus">
                @foreach($catalogs['deposit_bonus'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['deposit_bonus'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
            <p class="fmb-filter-group__label">Country</p>
            <div class="fmb-check-grid fmb-check-grid--scroll">
                @foreach($catalogs['country'] as $value => $label)
                    <label class="fmb-check">
                        <input type="checkbox" name="country" value="{{ $value }}" @checked(in_array($value, $filters['country'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </details>

    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'highest_rated' }}" data-fmb-sort>
</form>
