@php
use App\Http\Controllers\Front\BrokerController;
use App\Support\BrokerTaxonomy;

$brokerCategories = BrokerTaxonomy::categories();
$t = $site_t ?? fn (string $key, ?string $default = null) => $default ?? $key;
$countryShortcode = $preferredCountry['shortcode'] ?? BrokerTaxonomy::countryShortcode(
    $preferredCountry['slug'] ?? 'global',
    $preferredCountry['code'] ?? null
);

$popularReviewBrokers = ($popularReviewBrokers ?? collect())->take(10);
@endphp

    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}?v=1" data-bc-global>


<nav class="fixed top-0 inset-x-0" id="navbar">
    <div class="bc-nav-bar" id="bcNavBar">
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
        <div class="flex items-center gap-2 h-16 min-w-0" id="navBarRow">

            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="{{ \App\Support\SiteTheme::logoUrl() }}" alt="{{ \App\Support\SiteTheme::siteName() }}" class="bc-site-logo">
            </a>

            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-0.5 flex-1 min-w-0 justify-center bc-nav-desktop">
                <a href="{{ route('home') }}" class="bc-nav-link">Home</a>

                {{-- Best brokers trigger --}}
                <div class="relative" id="brokersNavGroup">
                    <button type="button" id="brokersButton" class="bc-nav-link brokers-trigger" aria-expanded="false" aria-controls="brokersMegaMenu">
                        Best brokers
                        <svg class="w-4 h-4 chevron-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Prop firms mega menu --}}
                <div class="relative" id="propFirmsNavGroup">
                    <button type="button" id="propFirmsButton" class="bc-nav-link @if(Request::is('prop-firms*')) bc-nav-active @endif" aria-expanded="false" aria-controls="propFirmsMegaMenu">
                        Prop firms
                        <svg class="w-4 h-4 pf-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Broker reviews mega (hover) --}}
                <div class="relative" id="reviewsNavGroup">
                    <a href="{{ route('broker.reviews.index') }}" id="reviewsNavLink" class="bc-nav-link reviews-trigger" data-bc-nav-warm>
                        Broker reviews
                        <svg class="w-4 h-4 reviews-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div id="reviewsMegaMenu" class="hidden absolute left-0 top-full pt-2">
                        <div class="bc-reviews-mega-panel">
                            <div class="bc-reviews-mega-grid">
                                <div class="bc-reviews-mega-col">
                                    <p class="bc-mega-title">Popular reviews</p>
                                    <div class="bc-link-list">
                                        @forelse($popularReviewBrokers as $broker)
                                            <a href="{{ route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]) }}" class="bc-mega-link">{{ $broker->name }} Review</a>
                                        @empty
                                            <a href="{{ route('broker.reviews.index') }}" class="bc-mega-link">Browse all reviews</a>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="bc-reviews-mega-col">
                                    <p class="bc-mega-title">By region</p>
                                    <div class="bc-chip-wrap">
                                        @foreach($listedRegions as $slug => $region)
                                            <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-chip-link">
                                                <span class="bc-chip-flag">
                                                    @include('front.layout.partial.country-flag', ['country' => array_merge($region, ['slug' => $slug]), 'width' => 18, 'height' => 13])
                                                </span>
                                                {{ $region['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('broker.reviews.index') }}" class="bc-mega-footer">All reviews →</a>
                        </div>
                    </div>
                </div>

                <div class="relative" id="toolsNavGroup">
                    <button type="button" id="toolsButton" class="bc-nav-link tools-trigger" aria-expanded="false" aria-controls="toolsMenu">
                        Tools
                        <svg class="w-4 h-4 tools-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="toolsMenu" class="bc-nav-dropdown hidden" aria-labelledby="toolsButton">
                        <div class="bc-nav-dropdown-panel">
                            <a href="{{ route('promotions.index') }}" class="bc-nav-dropdown-link">Broker Promos</a>
                            <a href="{{ route('prop_firms.index') }}" class="bc-nav-dropdown-link">Prop Firms</a>
                            <a href="{{ route('broker.comparison') }}" class="bc-nav-dropdown-link">Compare Brokers</a>
                            <a href="{{ route('broker.scam_checker') }}" class="bc-nav-dropdown-link bc-nav-dropdown-link--danger">Scam Checker</a>
                            <a href="{{ route('scam_brokers') }}" class="bc-nav-dropdown-link bc-nav-dropdown-link--danger">Scam broker list</a>
                            <a href="{{ route('trading.tools') }}" class="bc-nav-dropdown-link" data-bc-nav-warm>Trading Tools</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('awards.index') }}" class="bc-nav-link" data-bc-nav-warm>Awards</a>
                <a href="{{ route('blog') }}" class="bc-nav-link">Blog</a>

                <div class="relative" id="companyNavGroup">
                    <button type="button" id="companyButton" class="bc-nav-link company-trigger" aria-expanded="false">
                        About
                        <svg class="w-4 h-4 company-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="companyMenu" class="company-menu hidden absolute left-0 top-full w-56">
                        <div class="bg-white border border-gray-200 shadow-xl rounded-xl">
                            <a href="{{ route('about.us') }}" class="bc-mega-link">{{ $t('nav.about_us') }}</a>
                            <a href="{{ route('authors') }}" class="bc-mega-link">{{ $t('nav.our_team') }}</a>
                            <a href="{{ route('methodology') }}" class="bc-mega-link">{{ $t('nav.methodology') }}</a>
                            <a href="{{ route('contact') }}" class="bc-mega-link">{{ $t('nav.contact_us') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right actions: country, search, CTA --}}
            <div class="bc-nav-actions" id="navActions">
                <button type="button"
                        id="countrySelectorBtn"
                        class="bc-nav-icon-btn bc-country-nav-btn"
                        aria-label="Select country: {{ $preferredCountry['name'] ?? 'Global' }}"
                        aria-expanded="false"
                        title="{{ $preferredCountry['name'] ?? 'Global' }}">
                    <span class="bc-country-nav-flag" id="countryNavFlag">
                        @include('front.layout.partial.country-flag', ['country' => $preferredCountry, 'width' => 20, 'height' => 15])
                    </span>
                    <span class="bc-country-nav-label" id="countryNavLabel">{{ $countryShortcode }}</span>
                </button>

                <div class="bc-search-wrap hidden lg:flex" id="desktopSearchWrap">
                    <button type="button" id="desktopSearchToggle" class="bc-nav-icon-btn" aria-label="{{ $t('nav.search_brokers') }}" aria-expanded="false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <div id="desktopSearchPanel" class="bc-search-dropdown">
                        <form action="{{ route('search') }}" method="GET" class="bc-search-form" id="navSearchForm" role="search">
                            <div class="bc-search-field" id="navSearchWrap">
                                <input type="search"
                                       id="navBrokerSearch"
                                       name="q"
                                       class="bc-search-input"
                                       placeholder="{{ $t('nav.search_placeholder') }}"
                                       autocomplete="off"
                                       aria-label="{{ $t('nav.search_brokers') }}"
                                       minlength="2"
                                       required>
                            </div>
                        </form>
                    </div>
                </div>

                <a href="{{ route('find_my_broker') }}" class="hidden lg:inline-flex bc-btn-primary flex-shrink-0">{{ $t('nav.find_broker') }}</a>

                @auth('web')
                    @include('front.layout.partial.notification-bell')
                    <div class="relative hidden lg:block" id="bcAccountMenu">
                        <button type="button" id="bcAccountMenuBtn" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-gray-100 transition" aria-label="Account menu" aria-expanded="false" aria-controls="bcAccountMenuPanel">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200" width="32" height="32">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="bcAccountMenuPanel" class="absolute right-0 top-full mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-[1060]" hidden>
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate flex items-center gap-1">
                                    {{ auth()->user()->name }}
                                    @if(auth()->user()->is_verified)<i class="fas fa-check-circle text-blue-500 text-xs" title="Verified"></i>@endif
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user-circle mr-2 text-gray-400"></i>My profile</a>
                            <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-saved" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-bookmark mr-2 text-gray-400"></i>Saved brokers</a>
                            <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-notifications" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-bell mr-2 text-gray-400"></i>Notifications</a>
                            <a href="{{ route('user.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-pen mr-2 text-gray-400"></i>Edit profile</a>
                            <form action="{{ route('user.logout') }}" method="POST" class="border-t border-gray-100 mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt mr-2"></i>Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="hidden lg:inline-flex bc-nav-icon-btn" aria-label="Log in" title="Log in">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth

                <button type="button" id="mobileMenuButton" class="lg:hidden bc-nav-icon-btn" aria-label="Menu">
                    <svg id="menuIconOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="menuIconClose" class="is-hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile search bar --}}
        <div class="bc-mobile-search-row lg:hidden" id="mobileSearchRow">
            <form action="{{ route('search') }}" method="GET" class="bc-search-form" id="navSearchFormMobile" role="search">
                <div class="bc-search-field">
                    <input type="search"
                           id="navBrokerSearchMobile"
                           name="q"
                           class="bc-search-input"
                           placeholder="{{ $t('nav.search_placeholder') }}"
                           autocomplete="off"
                           aria-label="{{ $t('nav.search_brokers') }}"
                           minlength="2"
                           required>
                </div>
            </form>
        </div>
    </div>
    </div>

    @include('front.layout.partial.prop-firms-mega-menu')

    {{-- Best brokers full-width mega menu --}}
    <div id="brokersMegaMenu" aria-labelledby="brokersButton" aria-hidden="true">
        <div class="bc-mega-inner">
            <div class="bc-mega-grid">
                <div class="bc-glass-card">
                    <div class="bc-mega-head">
                        <span class="bc-mega-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </span>
                        <p class="bc-mega-title">Top rated brokers</p>
                    </div>
                    @foreach($topRatedBrokers->take(5) as $index => $broker)
                        <a href="{{ route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]) }}" class="bc-broker-row">
                            <span class="bc-broker-rank">{{ $index + 1 }}</span>
                            <div class="w-9 h-9 rounded-xl border border-white bg-white flex items-center justify-center overflow-hidden flex-shrink-0 shadow-sm">
                                @if($broker->logo)<img src="{{ asset($broker->logo) }}" alt="" class="w-7 h-7 object-contain" loading="lazy" decoding="async" width="28" height="28">@endif
                            </div>
                            <span class="flex-1 text-sm font-semibold text-gray-800 truncate">{{ $broker->name }}</span>
                            <span class="bc-score">? {{ number_format($broker->rating, 1) }}</span>
                        </a>
                    @endforeach
                </div>

                <div class="bc-glass-card">
                    <div class="bc-mega-head">
                        <span class="bc-mega-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        </span>
                        <p class="bc-mega-title">By category</p>
                    </div>
                    <div class="bc-chip-wrap">
                        @foreach($brokerCategories as $slug => $name)
                            <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-chip-link">{{ $name }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="bc-glass-card">
                    <div class="bc-mega-head">
                        <span class="bc-mega-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <p class="bc-mega-title">By country</p>
                    </div>
                    <div class="bc-chip-wrap">
                        @foreach($listedCountries as $slug => $country)
                            <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-chip-link">
                                <span class="bc-chip-flag">
                                    @include('front.layout.partial.country-flag', ['country' => array_merge($country, ['slug' => $slug]), 'width' => 18, 'height' => 13])
                                </span>
                                {{ $country['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bc-glass-card">
                    <div class="bc-mega-head">
                        <span class="bc-mega-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <p class="bc-mega-title">Popular reviews</p>
                    </div>
                    <div class="bc-link-list">
                        @forelse($popularReviewBrokers->take(6) as $broker)
                            <a href="{{ route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]) }}" class="bc-mega-link">{{ $broker->name }} Review</a>
                        @empty
                            <a href="{{ route('broker.reviews.index') }}" class="bc-mega-link">Browse all reviews</a>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="bc-mega-bottom">
                <p>Independent comparisons ? find your ideal broker in seconds.</p>
                <div class="flex items-center gap-4 flex-shrink-0">
                    <a href="{{ route('methodology') }}" class="bc-mega-footer" style="margin:0;padding:0;border:none;">Our methodology ?</a>
                    <a href="{{ route('brokers.best.index') }}" class="bc-btn-primary">Explore all brokers</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="lg:hidden hidden border-t border-gray-200 shadow-lg">
        <div class="bc-mobile-nav-inner">
            <a href="{{ route('home') }}" class="bc-mobile-nav-link">Home</a>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn font-semibold" data-target="mob-prop-firms">
                    <span>Prop firms</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-prop-firms" class="hidden bc-mobile-subpanel">
                    <a href="{{ route('prop_firms.index') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child font-semibold">All prop firms</a>
                    @foreach(($propFirmNav['categories'] ?? collect()) as $cat)
                        <a href="{{ route('prop_firms.category', $cat->slug) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">{{ $cat->name }}</a>
                    @endforeach
                    <a href="{{ route('prop_firms.index', ['attribute' => 'instant-funding']) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Instant funding</a>
                    <a href="{{ route('prop_firms.index', ['featured' => 1]) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Featured firms</a>
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn font-semibold" data-target="mob-best-categories">
                    <span>Best brokers by category</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-best-categories" class="hidden bc-mobile-subpanel">
                    @foreach($brokerCategories as $slug => $name)
                        <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">{{ $name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn font-semibold" data-target="mob-best-countries">
                    <span>Best brokers by country</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-best-countries" class="hidden bc-mobile-subpanel">
                    @foreach($listedCountries as $slug => $country)
                        <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">{{ $country['flag'] }} {{ $country['name'] }}</a>
                    @endforeach
                    <a href="{{ route('brokers.best.index') }}" class="bc-mobile-footer-link">All best brokers →</a>
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn font-semibold" data-target="mob-reviews">
                    <span>Broker reviews</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-reviews" class="hidden bc-mobile-subpanel">
                    <div class="bc-mobile-reviews-grid">
                        <div>
                            <p class="bc-mobile-subtitle">Popular reviews</p>
                            @forelse($popularReviewBrokers as $broker)
                                <a href="{{ route('broker_detail', ['slug' => BrokerController::reviewSlugFor($broker)]) }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">{{ $broker->name }} Review</a>
                            @empty
                                <a href="{{ route('broker.reviews.index') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Browse all reviews</a>
                            @endforelse
                        </div>
                        <div>
                            <p class="bc-mobile-subtitle">By region</p>
                            @foreach($listedRegions as $slug => $region)
                                <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-mobile-region-link">
                                    <span class="bc-chip-flag">
                                        @include('front.layout.partial.country-flag', ['country' => array_merge($region, ['slug' => $slug]), 'width' => 18, 'height' => 13])
                                    </span>
                                    {{ $region['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('broker.reviews.index') }}" class="bc-mobile-footer-link">All reviews →</a>
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn font-semibold" data-target="mob-tools">
                    <span>Tools</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-tools" class="hidden bc-mobile-subpanel">
                    <a href="{{ route('promotions.index') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Broker Promos</a>
                    <a href="{{ route('broker.comparison') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Compare Brokers</a>
                    <a href="{{ route('broker.scam_checker') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child" style="color:#f87171;">Scam Checker</a>
                    <a href="{{ route('scam_brokers') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child" style="color:#f87171;">Scam broker list</a>
                    <a href="{{ route('trading.tools') }}" class="bc-mobile-nav-link bc-mobile-nav-link--child">Trading Tools</a>
                </div>
            </div>

            <div class="bc-mobile-divider"></div>

            <a href="{{ route('awards.index') }}" class="bc-mobile-nav-link">Awards</a>
            <a href="{{ route('blog') }}" class="bc-mobile-nav-link">Blog</a>
            <a href="{{ route('about.us') }}" class="bc-mobile-nav-link">{{ $t('nav.about_us') }}</a>
            <a href="{{ route('authors') }}" class="bc-mobile-nav-link">Our team</a>
            <a href="{{ route('methodology') }}" class="bc-mobile-nav-link">Our methodology</a>
            <a href="{{ route('contact') }}" class="bc-mobile-nav-link">Contact us</a>

            <a href="{{ route('find_my_broker') }}" class="bc-btn-primary bc-mobile-cta">{{ $t('nav.find_broker') }}</a>

            <button type="button" class="bc-mobile-country-btn" id="mobileCountrySelectorBtn">
                <span class="bc-country-nav-flag bc-country-nav-flag--sm">
                    @include('front.layout.partial.country-flag', ['country' => $preferredCountry, 'width' => 20, 'height' => 15])
                </span>
                <span>Country: {{ $countryShortcode }}</span>
            </button>

            <div class="bc-mobile-auth">
                @auth('web')
                    <div class="flex items-center gap-3 px-1 pb-2">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-200">
                        <div>
                            <p class="text-sm font-semibold text-gray-800 flex items-center gap-1">
                                {{ auth()->user()->name }}
                                @if(auth()->user()->is_verified)<i class="fas fa-check-circle text-blue-500 text-xs"></i>@endif
                            </p>
                            <p class="text-xs text-gray-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('user.profile') }}" class="bc-mobile-nav-link">My profile</a>
                    <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-saved" class="bc-mobile-nav-link">Saved brokers</a>
                    <a href="{{ route('user.profile', ['tab' => 'overview']) }}#ua-notifications" class="bc-mobile-nav-link">Notifications</a>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bc-mobile-nav-link w-full text-left text-red-600">Log out</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="bc-mobile-nav-link font-semibold flex items-center gap-2" aria-label="Log in">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Log in
                    </a>
                    <a href="{{ route('user.register') }}" class="bc-mobile-nav-link">Create account</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@include('front.layout.partial.country-drawer')

<script src="{{ asset('js/navbar.js') }}?v=3" defer></script>
