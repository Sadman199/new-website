<section class="bv-hero">
    <div class="bv-hero__bg"></div>
    <div class="bv-hero__wrap">
        <form class="bv-search-card" action="{{ route('find_my_broker') }}" method="GET" id="bvHomeSearchForm">
            <div class="bv-search-tabs">
                <button type="button" class="bv-search-tab is-active" data-bv-search-tab="filter">Search by Filter</button>
                <button type="button" class="bv-search-tab" data-bv-search-tab="name">Search by Name</button>
                <div class="bv-search-tabs__right">
                    <a href="#" class="bv-search-advanced-link" id="bvAdvancedToggle">Advanced Search <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>

            <div id="bvSearchFilterPanel" class="bv-search-panel-filter">
                <div class="bv-search-bar">
                    <div class="bv-search-field" data-bv-dropdown>
                        <input type="hidden" name="regulation" value="">
                        <button type="button" class="bv-search-trigger is-placeholder" data-bv-dropdown-trigger data-placeholder="Regulated By">Regulated By <i class="fas fa-chevron-down"></i></button>
                        <div class="bv-search-dropdown">
                            <button type="button" class="bv-search-option is-selected" data-bv-dropdown-option data-value="">Any regulator</button>
                            @foreach($searchCatalogs['regulation'] as $value => $label)
                                <button type="button" class="bv-search-option" data-bv-dropdown-option data-value="{{ $value }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="bv-search-field" data-bv-dropdown>
                        <input type="hidden" name="spread" value="">
                        <button type="button" class="bv-search-trigger is-placeholder" data-bv-dropdown-trigger data-placeholder="Trading Cost">Trading Cost <i class="fas fa-chevron-down"></i></button>
                        <div class="bv-search-dropdown">
                            @foreach($searchCatalogs['spread'] as $value => $label)
                                <button type="button" class="bv-search-option {{ $value === '' ? 'is-selected' : '' }}" data-bv-dropdown-option data-value="{{ $value }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="bv-search-field" data-bv-dropdown>
                        <input type="hidden" name="leverage" value="">
                        <button type="button" class="bv-search-trigger is-placeholder" data-bv-dropdown-trigger data-placeholder="Leverage">Leverage <i class="fas fa-chevron-down"></i></button>
                        <div class="bv-search-dropdown">
                            @foreach($searchCatalogs['leverage'] as $value => $label)
                                <button type="button" class="bv-search-option {{ $value === '' ? 'is-selected' : '' }}" data-bv-dropdown-option data-value="{{ $value }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="bv-search-btn">Search</button>
                </div>

                <div class="bv-search-advanced" id="bvAdvancedPanel">
                    @php $advancedCatalogs = \App\Support\FindMyBrokerFilters::catalogs(); @endphp
                    <div class="bv-search-advanced__grid">
                        <div>
                            <label for="bv-min-deposit">Min Deposit</label>
                            <select id="bv-min-deposit" name="min_deposit">
                                @foreach($advancedCatalogs['min_deposit'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-platform">Platform</label>
                            <select id="bv-platform" name="platform">
                                <option value="">Any platform</option>
                                @foreach($advancedCatalogs['platform'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-markets">Markets</label>
                            <select id="bv-markets" name="markets">
                                <option value="">Any market</option>
                                @foreach($advancedCatalogs['markets'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-payment">Payment</label>
                            <select id="bv-payment" name="payment">
                                <option value="">Any method</option>
                                @foreach($advancedCatalogs['payment'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-commission">Commission</label>
                            <select id="bv-commission" name="commission">
                                @foreach($advancedCatalogs['commission'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-rating">Rating</label>
                            <select id="bv-rating" name="rating">
                                @foreach($advancedCatalogs['rating'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-country">Country</label>
                            <select id="bv-country" name="country">
                                <option value="">Any country</option>
                                @foreach($advancedCatalogs['country'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="bv-sort">Sort By</label>
                            <select id="bv-sort" name="sort">
                                @foreach($advancedCatalogs['sort'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bv-search-panel-name" id="bvSearchNamePanel">
                <div class="bv-search-bar bv-search-bar--name">
                    <div class="bv-search-field bv-search-field--grow">
                        <input type="search" name="q" class="bv-search-name-input" placeholder="Enter broker name…" autocomplete="off" disabled>
                    </div>
                    <button type="submit" class="bv-search-btn">Search</button>
                </div>
            </div>
        </form>
    </div>
</section>
