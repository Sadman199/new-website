@php
    $idPrefix = $idPrefix ?? 'desk';
    $filters = $filters ?? [];
    $catalogs = $catalogs ?? [];
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
        <summary class="fmb-filter-group__summary">Basics</summary>
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
        <summary class="fmb-filter-group__summary">Regulation</summary>
        <div class="fmb-filter-group__body">
            @foreach($catalogs['regulation'] as $value => $label)
                @if($value !== '')
                    <label class="fmb-check">
                        <input type="checkbox" name="regulation" value="{{ $value }}" @checked(in_array($value, $filters['regulation'] ?? [], true)) data-fmb-input>
                        <span>{{ $label }}</span>
                    </label>
                @endif
            @endforeach
        </div>
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['account_type']) || !empty($filters['leverage']) || !empty($filters['spread']) || !empty($filters['commission'])) open @endif>
        <summary class="fmb-filter-group__summary">Trading costs</summary>
        <div class="fmb-filter-group__body">
            <p class="fmb-filter-group__label">Account type</p>
            @foreach($catalogs['account_type'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="account_type" value="{{ $value }}" @checked(in_array($value, $filters['account_type'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
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
        <summary class="fmb-filter-group__summary">Platforms & features</summary>
        <div class="fmb-filter-group__body">
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
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['markets']) || !empty($filters['payment'])) open @endif>
        <summary class="fmb-filter-group__summary">Markets & payments</summary>
        <div class="fmb-filter-group__body">
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
    </details>

    <details class="fmb-filter-group" @if(!empty($filters['country']) || !empty($filters['deposit_bonus'])) open @endif>
        <summary class="fmb-filter-group__summary">Availability & bonuses</summary>
        <div class="fmb-filter-group__body">
            <select name="deposit_bonus" class="fmb-filter-group__select" data-fmb-input aria-label="Deposit bonus">
                @foreach($catalogs['deposit_bonus'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['deposit_bonus'] ?? '') === (string) $value)>{{ $label }}</option>
                @endforeach
            </select>
            @foreach($catalogs['country'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="country" value="{{ $value }}" @checked(in_array($value, $filters['country'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </details>

    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'highest_rated' }}" data-fmb-sort>
</form>
