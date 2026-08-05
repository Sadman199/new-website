@php
use App\Http\Controllers\Front\BrokerController;
use App\Support\BrokerTaxonomy;

$brokerCategories = BrokerTaxonomy::categories();

$brokerReviews = [
    'db-investing-review' => 'DB Investing Review',
    'exness-review' => 'Exness Review',
    'just-markets-review' => 'JustMarkets Review',
    'tickmill-review' => 'TickMill Review',
    'xm-review' => 'XM Review',
    'fbs-review' => 'FBS Review',
    'fp-markets-review' => 'FP Markets Review',
    'robo-forex-review' => 'RoboForex Review',
    'one-royal-review' => 'OneRoyal Review',
    'assetsfx-review' => 'AssetsFX Review',
];
@endphp

<style>
    /* BrokerChooser-inspired navigation */
    #navbar {
        z-index: 1050;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: visible;
    }
    .web_menu { overflow: visible; }
    .bc-site-logo {
        display: block;
        height: 40px;
        width: auto;
        max-width: 160px;
        object-fit: contain;
    }
    #mobileMenu { background: #ffffff; z-index: 1040; }
    #companyMenu { z-index: 1055; }
    #reviewsMegaMenu { z-index: 1045; }
    #navSearchResults, #navSearchResultsMobile { z-index: 1060; }

    /* Full-width mega dropdown — frosted glass */
    #brokersMegaMenu {
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        z-index: 1045;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(248, 250, 252, 0.88) 100%);
        backdrop-filter: blur(22px) saturate(180%);
        -webkit-backdrop-filter: blur(22px) saturate(180%);
        border-top: 1px solid rgba(255, 255, 255, 0.65);
        box-shadow: 0 24px 64px rgba(15, 23, 42, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: opacity 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.28s cubic-bezier(0.4, 0, 0.2, 1),
                    visibility 0.28s;
        pointer-events: none;
    }
    #brokersMegaMenu::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: -12px;
        height: 12px;
    }
    #brokersMegaMenu.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
    #brokersMegaMenu .bc-mega-inner {
        max-width: 80rem;
        margin: 0 auto;
        padding: 20px 24px 18px;
    }
    #brokersMegaMenu .bc-mega-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 0.85fr 0.9fr;
        gap: 14px;
    }
    #brokersMegaMenu .bc-glass-card {
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.85);
        border-radius: 16px;
        padding: 18px 16px;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.04), inset 0 1px 0 rgba(255, 255, 255, 1);
        min-width: 0;
    }
    #brokersMegaMenu .bc-mega-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }
    #brokersMegaMenu .bc-mega-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid rgba(191, 219, 254, 0.6);
        color: #2563eb;
        flex-shrink: 0;
    }
    #brokersMegaMenu .bc-mega-icon svg {
        width: 16px;
        height: 16px;
    }
    #brokersMegaMenu .bc-mega-title {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
    }
    #brokersMegaMenu .bc-chip-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }
    #brokersMegaMenu .bc-chip-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 11px;
        font-size: 12.5px;
        font-weight: 500;
        color: #334155;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    #brokersMegaMenu .bc-chip-link:hover {
        color: #1d4ed8;
        background: rgba(239, 246, 255, 0.95);
        border-color: #bfdbfe;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.12);
        transform: translateY(-1px);
    }
    #brokersMegaMenu .bc-link-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4px 10px;
    }
    #brokersMegaMenu .bc-link-list .bc-mega-link {
        padding: 6px 8px;
        margin: 0;
        font-size: 13px;
        border-radius: 8px;
        background: transparent;
    }
    #brokersMegaMenu .bc-link-list .bc-mega-link:hover {
        background: rgba(239, 246, 255, 0.7);
    }
    #brokersMegaMenu .bc-broker-row {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        padding: 9px 10px;
        margin: 0 0 6px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(241, 245, 249, 0.9);
    }
    #brokersMegaMenu .bc-broker-row:hover {
        background: rgba(255, 255, 255, 0.92);
        border-color: #dbeafe;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
        transform: translateY(-1px);
    }
    #brokersMegaMenu .bc-broker-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        font-size: 11px;
        font-weight: 700;
        color: #2563eb;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 7px;
        flex-shrink: 0;
    }
    #brokersMegaMenu .bc-mega-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 14px;
        padding: 14px 18px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }
    #brokersMegaMenu .bc-mega-bottom p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }
    #brokersMegaMenu .bc-mega-bottom .bc-btn-primary {
        border-radius: 999px;
        padding: 9px 18px;
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.3);
    }
    @media (max-width: 1023px) {
        #brokersMegaMenu { display: none !important; }
    }
    @media (max-width: 1279px) {
        #brokersMegaMenu .bc-mega-grid {
            grid-template-columns: 1fr 1fr;
        }
        #brokersMegaMenu .bc-glass-card:first-child {
            grid-column: 1 / -1;
        }
    }

    .bc-nav-link {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 6px 8px;
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        border-radius: 6px;
        transition: color 0.15s, background 0.15s;
        white-space: nowrap;
        text-decoration: none;
    }
    .bc-nav-link:hover,
    .bc-nav-link.bc-nav-active {
        color: #2563eb;
        background: #eff6ff;
    }
    .bc-nav-link-danger {
        color: #dc2626;
    }
    .bc-nav-link-danger:hover {
        color: #b91c1c !important;
        background: #fef2f2 !important;
    }
    .bc-nav-icon-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px;
        min-height: 40px;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 10px;
        color: #4b5563;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        cursor: pointer;
        transition: color 0.15s, background 0.15s;
        flex-shrink: 0;
        line-height: 0 !important;
        vertical-align: middle;
        position: relative;
    }
    .bc-nav-icon-btn svg {
        display: block !important;
        width: 20px !important;
        height: 20px !important;
        margin: 0 !important;
        padding: 0 !important;
        flex-shrink: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .bc-nav-icon-btn svg.hidden,
    .bc-nav-icon-btn svg.is-hidden {
        display: none !important;
    }
    .bc-nav-icon-btn:hover,
    .bc-nav-icon-btn.is-active {
        color: rgba(245, 158, 11, 1);
        background: rgba(245, 158, 11, 0.1) !important;
    }
    .bc-nav-actions {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end;
        gap: 8px;
        margin-left: auto;
        flex-shrink: 0;
        height: 40px;
        align-self: center;
    }
    .bc-search-wrap {
        position: relative;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center;
        height: 40px;
        width: 40px;
        flex-shrink: 0;
        align-self: center;
    }
    .bc-search-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: min(340px, calc(100vw - 2rem));
        padding: 12px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
        z-index: 1060;
    }
    .bc-nav-actions .bc-btn-primary {
        height: 40px !important;
        padding: 0 16px !important;
        display: none;
        align-items: center !important;
        justify-content: center;
        box-sizing: border-box;
        line-height: 1 !important;
        align-self: center;
    }
    @media (min-width: 1024px) {
        .bc-nav-actions .bc-btn-primary {
            display: inline-flex !important;
        }
        .bc-nav-actions #mobileMenuButton {
            display: none !important;
        }
    }
    .bc-mega-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .bc-mega-link {
        display: block;
        padding: 7px 10px;
        margin: 0 -10px;
        font-size: 14px;
        color: #374151;
        border-radius: 6px;
        transition: color 0.15s, background 0.15s;
        text-decoration: none;
    }
    .bc-mega-link:hover {
        color: #2563eb;
        background: #eff6ff;
    }
    .bc-mega-footer {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #f3f4f6;
        font-size: 14px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
    }
    .bc-mega-footer:hover { color: #1d4ed8; }
    .bc-btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        background: rgba(245, 158, 11, 1);
        border-radius: 8px;
        transition: background 0.15s;
        text-decoration: none;
        white-space: nowrap;
    }
    .bc-btn-primary:hover { background: rgba(217, 119, 6, 1); color: #fff; }
    .bc-search-input {
        width: 100%;
        height: 38px;
        padding: 0 14px 0 38px;
        font-size: 14px;
        color: #1f2937;
        background: #f3f4f6;
        border: 1px solid transparent;
        border-radius: 9999px;
        outline: none;
        transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
    }
    .bc-search-input:focus {
        background: #fff;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .bc-search-input::placeholder { color: #9ca3af; }
    .bc-broker-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        margin: 0 -10px;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.15s;
    }
    .bc-broker-row:hover { background: #f9fafb; }
    .bc-score {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        padding: 2px 8px;
        font-size: 12px;
        font-weight: 700;
        color: #047857;
        background: #ecfdf5;
        border-radius: 6px;
        flex-shrink: 0;
    }
</style>

<nav class="fixed top-0 inset-x-0" id="navbar">
    <div class="max-w-7xl mx-auto px-4 lg:px-6">
        <div class="flex items-center gap-2 h-16 min-w-0">

            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="https://www.brokerscourt.com/uploads/logo.png" alt="BrokersCourt" class="bc-site-logo">
            </a>

            {{-- Desktop nav --}}
            <div class="hidden lg:flex items-center gap-0.5 flex-1 min-w-0 justify-center">
                <a href="{{ route('home') }}" class="bc-nav-link">Home</a>

                {{-- Best brokers trigger --}}
                <div class="relative" id="brokersNavGroup">
                    <button type="button" id="brokersButton" class="bc-nav-link brokers-trigger" aria-expanded="false" aria-controls="brokersMegaMenu">
                        Best brokers
                        <svg class="w-4 h-4 chevron-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Broker reviews mega (hover) --}}
                <div class="relative" id="reviewsNavGroup">
                    <a href="{{ route('broker.reviews.index') }}" id="reviewsNavLink" class="bc-nav-link reviews-trigger">
                        Broker reviews
                        <svg class="w-4 h-4 reviews-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <div id="reviewsMegaMenu" class="hidden absolute left-0 top-full pt-2 w-80">
                        <div class="bg-white border border-gray-200 shadow-xl rounded-xl p-4">
                            <p class="bc-mega-title">Broker reviews</p>
                            @foreach($brokerReviews as $slug => $name)
                                <a href="{{ route('broker_detail', ['slug' => $slug]) }}" class="bc-mega-link">{{ $name }}</a>
                            @endforeach
                            <a href="{{ route('broker.reviews.index') }}" class="bc-mega-footer">All reviews →</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('broker.comparison') }}" class="bc-nav-link">Compare</a>
                <a href="{{ route('trading.tools') }}" class="bc-nav-link">Tools</a>
                <a href="{{ route('awards.index') }}" class="bc-nav-link">Awards</a>
                <a href="{{ route('blog') }}" class="bc-nav-link">Blog</a>
                <a href="{{ route('scam_brokers') }}" class="bc-nav-link bc-nav-link-danger">Scam brokers</a>

                <div class="relative" id="companyNavGroup">
                    <button type="button" id="companyButton" class="bc-nav-link company-trigger" aria-expanded="false">
                        About
                        <svg class="w-4 h-4 company-chevron transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="companyMenu" class="company-menu hidden absolute left-0 top-full pt-2 w-56">
                        <div class="bg-white border border-gray-200 shadow-xl rounded-xl py-2">
                            <a href="{{ route('about.us') }}" class="bc-mega-link mx-2">About us</a>
                            <a href="{{ route('methodology') }}" class="bc-mega-link mx-2">Our methodology</a>
                            <a href="{{ route('contact.us') }}" class="bc-mega-link mx-2">Contact us</a>
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
                    <span class="bc-country-nav-label" id="countryNavLabel">{{ $preferredCountry['name'] ?? 'Global' }}</span>
                </button>

                <div class="bc-search-wrap">
                    <button type="button" id="desktopSearchToggle" class="bc-nav-icon-btn" aria-label="Search brokers" aria-expanded="false">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <div id="desktopSearchPanel" class="bc-search-dropdown hidden">
                        <div class="relative" id="navSearchWrap">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="search" id="navBrokerSearch" class="bc-search-input" placeholder="Search brokers..." autocomplete="off">
                            <div id="navSearchResults" class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl hidden max-h-72 overflow-y-auto"></div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('find_my_broker') }}" class="hidden lg:inline-flex bc-btn-primary flex-shrink-0">Find my broker</a>

                @auth('web')
                    <div class="relative hidden lg:block" x-data="{ open: false }">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-full hover:bg-gray-100 transition" aria-label="Account menu">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 top-full mt-2 w-60 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-[1060]" style="display:none;">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800 truncate flex items-center gap-1">
                                    {{ auth()->user()->name }}
                                    @if(auth()->user()->is_verified)<i class="fas fa-check-circle text-blue-500 text-xs" title="Verified"></i>@endif
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-user-circle mr-2 text-gray-400"></i>My profile</a>
                            <a href="{{ route('user.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-pen mr-2 text-gray-400"></i>Edit profile</a>
                            <form action="{{ route('user.logout') }}" method="POST" class="border-t border-gray-100 mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fas fa-sign-out-alt mr-2"></i>Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('user.login') }}" class="hidden lg:inline-flex bc-nav-link font-semibold">Log in</a>
                @endauth

                <button type="button" id="mobileMenuButton" class="lg:hidden bc-nav-icon-btn" aria-label="Menu">
                    <svg id="menuIconOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="menuIconClose" class="is-hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

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
                                @if($broker->logo)<img src="{{ asset($broker->logo) }}" alt="" class="w-7 h-7 object-contain">@endif
                            </div>
                            <span class="flex-1 text-sm font-semibold text-gray-800 truncate">{{ $broker->name }}</span>
                            <span class="bc-score">★ {{ number_format($broker->rating, 1) }}</span>
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
                        @foreach($brokerCountries as $slug => $country)
                            @if($slug === 'global')
                                @continue
                            @endif
                            <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-chip-link">{{ $country['flag'] }} {{ $country['name'] }}</a>
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
                        @foreach(array_slice($brokerReviews, 0, 6, true) as $slug => $name)
                            <a href="{{ route('broker_detail', ['slug' => $slug]) }}" class="bc-mega-link">{{ $name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="bc-mega-bottom">
                <p>Independent comparisons — find your ideal broker in seconds.</p>
                <div class="flex items-center gap-4 flex-shrink-0">
                    <a href="{{ route('methodology') }}" class="bc-mega-footer" style="margin:0;padding:0;border:none;">Our methodology →</a>
                    <a href="{{ route('brokers.best.index') }}" class="bc-btn-primary">Explore all brokers</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="lg:hidden hidden border-t border-gray-200 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-1 max-h-[calc(100vh-4rem)] overflow-y-auto">
            <a href="{{ route('home') }}" class="bc-mega-link">Home</a>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn w-full flex items-center justify-between bc-mega-link font-semibold" data-target="mob-best-categories">
                    <span>Best brokers by category</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-best-categories" class="hidden pl-3 pb-2">
                    @foreach($brokerCategories as $slug => $name)
                        <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-mega-link text-sm">{{ $name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn w-full flex items-center justify-between bc-mega-link font-semibold" data-target="mob-best-countries">
                    <span>Best brokers by country</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-best-countries" class="hidden pl-3 pb-2">
                    @foreach($brokerCountries as $slug => $country)
                        @if($slug === 'global')
                            @continue
                        @endif
                        <a href="{{ route('brokers.best', ['slug' => $slug]) }}" class="bc-mega-link text-sm">{{ $country['flag'] }} {{ $country['name'] }}</a>
                    @endforeach
                    <a href="{{ route('brokers.best.index') }}" class="bc-mega-footer">All best brokers →</a>
                </div>
            </div>

            <div class="mobile-accordion">
                <button type="button" class="mobile-accordion-btn w-full flex items-center justify-between bc-mega-link font-semibold" data-target="mob-reviews">
                    <span>Broker reviews</span>
                    <svg class="w-4 h-4 accordion-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="mob-reviews" class="hidden pl-3 pb-2">
                    @foreach($brokerReviews as $slug => $name)
                        <a href="{{ route('broker_detail', ['slug' => $slug]) }}" class="bc-mega-link text-sm">{{ $name }}</a>
                    @endforeach
                    <a href="{{ route('broker.reviews.index') }}" class="bc-mega-footer">All reviews →</a>
                </div>
            </div>

            <a href="{{ route('broker.comparison') }}" class="bc-mega-link font-semibold">Compare brokers</a>
            <a href="{{ route('trading.tools') }}" class="bc-mega-link">Tools</a>
            <a href="{{ route('awards.index') }}" class="bc-mega-link">Awards</a>
            <a href="{{ route('blog') }}" class="bc-mega-link">Blog</a>
            <a href="{{ route('scam_brokers') }}" class="bc-mega-link" style="color:#dc2626;font-weight:600;">⚠ Scam brokers</a>
            <a href="{{ route('about.us') }}" class="bc-mega-link">About us</a>
            <a href="{{ route('methodology') }}" class="bc-mega-link">Our methodology</a>
            <a href="{{ route('contact.us') }}" class="bc-mega-link">Contact us</a>
            <a href="{{ route('find_my_broker') }}" class="bc-btn-primary w-full justify-center mt-3">Find my broker</a>

            <button type="button" class="bc-mega-link w-full text-left font-semibold flex items-center gap-2 mt-2" id="mobileCountrySelectorBtn">
                <span class="bc-country-nav-flag bc-country-nav-flag--sm">
                    @include('front.layout.partial.country-flag', ['country' => $preferredCountry, 'width' => 20, 'height' => 15])
                </span>
                <span>Country: {{ $preferredCountry['name'] ?? 'Global' }}</span>
            </button>

            <div class="border-t border-gray-200 mt-3 pt-3">
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
                    <a href="{{ route('user.profile') }}" class="bc-mega-link">My profile</a>
                    <form action="{{ route('user.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bc-mega-link w-full text-left text-red-600">Log out</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="bc-mega-link font-semibold">Log in</a>
                    <a href="{{ route('user.register') }}" class="bc-mega-link">Create account</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@include('front.layout.partial.country-drawer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const brokersBtn = document.getElementById('brokersButton');
    const brokersMenu = document.getElementById('brokersMegaMenu');
    const brokersGroup = document.getElementById('brokersNavGroup');
    const reviewsGroup = document.getElementById('reviewsNavGroup');
    const reviewsMenu = document.getElementById('reviewsMegaMenu');
    const reviewsLink = document.getElementById('reviewsNavLink');
    const companyBtn = document.getElementById('companyButton');
    const companyMenu = document.getElementById('companyMenu');
    const companyGroup = document.getElementById('companyNavGroup');
    const mobileBtn = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const desktopSearchToggle = document.getElementById('desktopSearchToggle');
    const desktopSearchPanel = document.getElementById('desktopSearchPanel');
    const menuIconOpen = document.getElementById('menuIconOpen');
    const menuIconClose = document.getElementById('menuIconClose');

    let brokersTimer, reviewsTimer, companyTimer;

    function setActive(el, on) {
        if (!el) return;
        el.classList.toggle('bc-nav-active', on);
        el.querySelector('.chevron-icon, .reviews-chevron, .company-chevron')?.classList.toggle('rotate-180', on);
    }

    function closeBrokersMenu() {
        brokersMenu?.classList.remove('is-open');
        brokersMenu?.setAttribute('aria-hidden', 'true');
        setActive(brokersBtn, false);
        brokersBtn?.setAttribute('aria-expanded', 'false');
    }

    function openBrokersMenu() {
        closeReviewsMenu();
        closeCompanyMenu();
        closeSearchPanel();
        closeCountryDrawer();
        brokersMenu?.classList.add('is-open');
        brokersMenu?.setAttribute('aria-hidden', 'false');
        setActive(brokersBtn, true);
        brokersBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeReviewsMenu() {
        reviewsMenu?.classList.add('hidden');
        setActive(reviewsLink, false);
    }

    function openReviewsMenu() {
        closeBrokersMenu();
        closeCompanyMenu();
        closeSearchPanel();
        closeCountryDrawer();
        reviewsMenu?.classList.remove('hidden');
        setActive(reviewsLink, true);
    }

    function closeCompanyMenu() {
        companyMenu?.classList.add('hidden');
        setActive(companyBtn, false);
        companyBtn?.setAttribute('aria-expanded', 'false');
    }

    function openCompanyMenu() {
        closeBrokersMenu();
        closeReviewsMenu();
        closeSearchPanel();
        closeCountryDrawer();
        companyMenu?.classList.remove('hidden');
        setActive(companyBtn, true);
        companyBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeMobileMenu() {
        mobileMenu?.classList.add('hidden');
        if (menuIconOpen) {
            menuIconOpen.classList.remove('is-hidden', 'hidden');
            menuIconOpen.style.display = 'block';
        }
        if (menuIconClose) {
            menuIconClose.classList.add('is-hidden', 'hidden');
            menuIconClose.style.display = 'none';
        }
        document.body.classList.remove('overflow-hidden');
    }

    function closeSearchResults() {
        document.getElementById('navSearchResults')?.classList.add('hidden');
    }

    function closeSearchPanel() {
        desktopSearchPanel?.classList.add('hidden');
        desktopSearchToggle?.classList.remove('is-active');
        desktopSearchToggle?.setAttribute('aria-expanded', 'false');
        closeSearchResults();
    }

    function closeCountryDrawer() {
        window.bcCountryDrawer?.close();
    }

    function openSearchPanel() {
        closeBrokersMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeMobileMenu();
        closeCountryDrawer();
        desktopSearchPanel?.classList.remove('hidden');
        desktopSearchToggle?.classList.add('is-active');
        desktopSearchToggle?.setAttribute('aria-expanded', 'true');
        setTimeout(function () {
            document.getElementById('navBrokerSearch')?.focus();
        }, 30);
    }

    function bindHover(group, openFn, closeFn, timerKey) {
        if (!group) return;
        group.addEventListener('mouseenter', function () {
            if (timerKey === 'brokers') clearTimeout(brokersTimer);
            if (timerKey === 'reviews') clearTimeout(reviewsTimer);
            if (timerKey === 'company') clearTimeout(companyTimer);
            openFn();
        });
        group.addEventListener('mouseleave', function () {
            if (timerKey === 'brokers') brokersTimer = setTimeout(closeFn, 200);
            if (timerKey === 'reviews') reviewsTimer = setTimeout(closeFn, 200);
            if (timerKey === 'company') companyTimer = setTimeout(closeFn, 200);
        });
    }

    bindHover(brokersGroup, openBrokersMenu, closeBrokersMenu, 'brokers');
    bindHover(brokersMenu, openBrokersMenu, closeBrokersMenu, 'brokers');
    bindHover(reviewsGroup, openReviewsMenu, closeReviewsMenu, 'reviews');
    bindHover(companyGroup, openCompanyMenu, closeCompanyMenu, 'company');

    brokersBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        brokersMenu?.classList.contains('is-open') ? closeBrokersMenu() : openBrokersMenu();
    });

    companyBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        companyMenu?.classList.contains('hidden') ? openCompanyMenu() : closeCompanyMenu();
    });

    reviewsLink?.addEventListener('click', function (e) {
        if (window.matchMedia('(min-width: 1024px)').matches && e.target.closest('.reviews-chevron, svg')) {
            e.preventDefault();
            reviewsMenu?.classList.contains('hidden') ? openReviewsMenu() : closeReviewsMenu();
        }
    });

    mobileBtn?.addEventListener('click', function () {
        const open = mobileMenu?.classList.contains('hidden');
        closeBrokersMenu(); closeReviewsMenu(); closeCompanyMenu(); closeSearchPanel(); closeCountryDrawer();
        if (open) {
            mobileMenu?.classList.remove('hidden');
            if (menuIconOpen) {
                menuIconOpen.classList.add('is-hidden', 'hidden');
                menuIconOpen.style.display = 'none';
            }
            if (menuIconClose) {
                menuIconClose.classList.remove('is-hidden', 'hidden');
                menuIconClose.style.display = 'block';
            }
            document.body.classList.add('overflow-hidden');
        } else {
            closeMobileMenu();
        }
    });

    desktopSearchToggle?.addEventListener('click', function (e) {
        e.stopPropagation();
        if (desktopSearchPanel?.classList.contains('hidden')) {
            openSearchPanel();
        } else {
            closeSearchPanel();
        }
    });

    document.getElementById('navBrokerSearch')?.addEventListener('focus', function () {
        closeBrokersMenu(); closeReviewsMenu(); closeCompanyMenu();
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#brokersNavGroup') && !e.target.closest('#brokersMegaMenu')) closeBrokersMenu();
        if (!e.target.closest('#reviewsNavGroup')) closeReviewsMenu();
        if (!e.target.closest('#companyNavGroup')) closeCompanyMenu();
        if (!e.target.closest('#navActions')) closeSearchPanel();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeBrokersMenu(); closeReviewsMenu(); closeCompanyMenu(); closeMobileMenu(); closeSearchPanel(); closeCountryDrawer();
        }
    });

    window.addEventListener('bc:country-drawer-open', function () {
        closeBrokersMenu(); closeReviewsMenu(); closeCompanyMenu(); closeSearchPanel(); closeMobileMenu();
    });

    document.getElementById('mobileCountrySelectorBtn')?.addEventListener('click', function () {
        closeMobileMenu();
        window.bcCountryDrawer?.open();
    });

    document.querySelectorAll('.mobile-accordion-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(btn.dataset.target);
            const chevron = btn.querySelector('.accordion-chevron');
            const isOpen = !target.classList.contains('hidden');
            document.querySelectorAll('.mobile-accordion [id^="mob-"]').forEach(function (el) {
                if (el.id !== btn.dataset.target) el.classList.add('hidden');
            });
            document.querySelectorAll('.accordion-chevron').forEach(function (c) {
                if (c !== chevron) c.classList.remove('rotate-180');
            });
            target.classList.toggle('hidden', isOpen);
            chevron?.classList.toggle('rotate-180', !isOpen);
        });
    });

    function initSearch(inputId, resultsId) {
        const input = document.getElementById(inputId);
        const resultBox = document.getElementById(resultsId);
        if (!input || !resultBox) return;
        let debounceTimer, selectedIndex = -1;

        function highlight(text, query) {
            const reg = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
            return text.replace(reg, '<strong>$1</strong>');
        }

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () {
                const query = input.value.trim();
                if (query.length < 2) { resultBox.classList.add('hidden'); return; }
                fetch('/broker-live-search?query=' + encodeURIComponent(query))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        resultBox.innerHTML = '';
                        selectedIndex = -1;
                        if (!data.length) {
                            resultBox.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No brokers found</div>';
                        } else {
                            data.forEach(function (broker) {
                                const a = document.createElement('a');
                                a.href = '/broker-reviews/' + broker.slug;
                                a.className = 'nav-search-item flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 transition';
                                a.innerHTML = '<img src="' + broker.logo_url + '" class="w-8 h-8 object-contain rounded border border-gray-100" alt=""><span class="text-sm text-gray-700">' + highlight(broker.name, query) + '</span>';
                                resultBox.appendChild(a);
                            });
                        }
                        resultBox.classList.remove('hidden');
                    });
            }, 280);
        });

        input.addEventListener('keydown', function (e) {
            const items = resultBox.querySelectorAll('.nav-search-item');
            if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, items.length - 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); }
            else if (e.key === 'Enter' && items[selectedIndex]) { e.preventDefault(); window.location.href = items[selectedIndex].href; return; }
            items.forEach(function (el, i) { el.classList.toggle('bg-blue-50', i === selectedIndex); });
        });
    }

    initSearch('navBrokerSearch', 'navSearchResults');
});
</script>
