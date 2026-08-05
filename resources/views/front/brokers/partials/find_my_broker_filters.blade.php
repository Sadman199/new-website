@php
    $idPrefix = $idPrefix ?? 'desk';
    $filters = $filters ?? [];
    $catalogs = $catalogs ?? [];
@endphp

<form class="fmb-filter-form space-y-5" data-fmb-form autocomplete="off">
    <div class="flex items-center justify-between gap-2">
        <h2 class="text-base font-bold text-gray-900">Filters</h2>
        <button type="button" class="fmb-reset text-sm font-semibold text-blue-600 hover:text-blue-800">Reset</button>
    </div>

    <div>
        <label class="fmb-group-title" for="{{ $idPrefix }}-q">Broker name</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" id="{{ $idPrefix }}-q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search brokers..." class="w-full h-10 pl-9 pr-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400" data-fmb-input>
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Minimum deposit</p>
        <select name="min_deposit" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['min_deposit'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['min_deposit'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="fmb-group-title">Account type</p>
        <div class="space-y-0.5">
            @foreach($catalogs['account_type'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="account_type" value="{{ $value }}" @checked(in_array($value, $filters['account_type'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Regulation</p>
        <div class="space-y-0.5">
            @foreach($catalogs['regulation'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="regulation" value="{{ $value }}" @checked(in_array($value, $filters['regulation'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Platform</p>
        <div class="space-y-0.5">
            @foreach($catalogs['platform'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="platform" value="{{ $value }}" @checked(in_array($value, $filters['platform'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Maximum leverage</p>
        <select name="leverage" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['leverage'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['leverage'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="fmb-group-title">Spread</p>
        <select name="spread" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['spread'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['spread'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="fmb-group-title">Commission</p>
        <select name="commission" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['commission'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['commission'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="fmb-group-title">Markets</p>
        <div class="space-y-0.5">
            @foreach($catalogs['markets'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="markets" value="{{ $value }}" @checked(in_array($value, $filters['markets'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Deposit & withdrawal</p>
        <div class="space-y-0.5">
            @foreach($catalogs['payment'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="payment" value="{{ $value }}" @checked(in_array($value, $filters['payment'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Features</p>
        <div class="space-y-0.5">
            @foreach($catalogs['features'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="features" value="{{ $value }}" @checked(in_array($value, $filters['features'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Deposit bonus</p>
        <select name="deposit_bonus" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['deposit_bonus'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['deposit_bonus'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="fmb-group-title">Country availability</p>
        <div class="space-y-0.5">
            @foreach($catalogs['country'] as $value => $label)
                <label class="fmb-check">
                    <input type="checkbox" name="country" value="{{ $value }}" @checked(in_array($value, $filters['country'] ?? [], true)) data-fmb-input>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="fmb-group-title">Rating</p>
        <select name="rating" class="w-full h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white/80 focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-input>
            @foreach($catalogs['rating'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['rating'] ?? '') === (string) $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Hidden sort synced from results toolbar --}}
    <input type="hidden" name="sort" value="{{ $filters['sort'] ?? 'highest_rated' }}" data-fmb-sort>
</form>
