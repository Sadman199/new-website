{{-- Country selector — left slide panel --}}
@php
    $preferredSlug = $preferredCountry['slug'] ?? 'global';
    $t = $site_t ?? fn (string $key, ?string $default = null) => $default ?? $key;

    $countryList = collect($brokerCountries ?? []);
    $globalEntry = $countryList->pull('global');
    $sortedCountries = $globalEntry
        ? collect(['global' => $globalEntry])->merge($countryList->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE))
        : $countryList->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
@endphp

@if(session('country_updated'))
    <div id="countrySuccessToast" class="bc-country-toast is-visible" role="status" aria-live="polite">
        {{ $t('toast.country_updated') }}
    </div>
@endif

<div id="countryDrawer"
     class="bc-country-drawer"
     data-recommended-url="{{ route('home.recommended_brokers') }}"
     aria-hidden="true"
     role="dialog"
     aria-labelledby="countryDrawerTitle"
     aria-modal="true">
    <div class="bc-country-drawer-backdrop" id="countryDrawerBackdrop" tabindex="-1"></div>
    <aside class="bc-country-drawer-panel">
        <div class="bc-country-drawer-hero">
            <div class="bc-country-drawer-hero__row">
                <div>
                    <p class="bc-country-drawer-eyebrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5a17.92 17.92 0 01-8.716-2.247m0 0A8.966 8.966 0 013 12c0-1.97.633-3.794 1.716-5.282"/></svg>
                        {{ $t('drawer.eyebrow') }}
                    </p>
                    <h2 id="countryDrawerTitle" class="bc-country-drawer-title">{{ $t('drawer.title') }}</h2>
                    <p class="bc-country-drawer-desc">{{ $t('drawer.desc') }}</p>
                </div>
                <button type="button" class="bc-country-drawer-close" id="countryDrawerClose" aria-label="{{ $t('drawer.close') }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="bc-country-drawer-body">
            <section class="bc-country-drawer-section" aria-labelledby="countryDrawerCountryTitle">
                <div class="bc-country-drawer-section__head">
                    <h3 id="countryDrawerCountryTitle" class="bc-country-drawer-section__title">{{ $t('drawer.country_title') }}</h3>
                    <span class="bc-country-drawer-section__hint">{{ $sortedCountries->count() }} {{ strtolower($t('drawer.country_title')) }}</span>
                </div>

                <div class="bc-country-drawer-search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="search"
                           id="countryDrawerSearch"
                           class="bc-country-drawer-search"
                           placeholder="{{ $t('drawer.country_search') }}"
                           autocomplete="off"
                           aria-label="{{ $t('drawer.country_search') }}">
                </div>

                <div class="bc-country-drawer-list" role="listbox" aria-label="{{ $t('drawer.country_title') }}">
                    @foreach($sortedCountries as $slug => $country)
                        @php
                            $brokerCount = (int) ($country['broker_count'] ?? 0);
                            $isSelected = $preferredSlug === $slug;
                        @endphp
                        <form action="{{ route('front_country') }}" method="POST" class="bc-country-option-form">
                            @csrf
                            <input type="hidden" name="country" value="{{ $slug }}">
                            <button type="submit"
                                    class="bc-country-option {{ $isSelected ? 'is-selected' : '' }}"
                                    data-country="{{ $slug }}"
                                    data-name="{{ $country['name'] }}"
                                    data-code="{{ $country['code'] ?? '' }}"
                                    data-shortcode="{{ $country['shortcode'] ?? \App\Support\BrokerTaxonomy::countryShortcode($slug, $country['code'] ?? null) }}"
                                    role="option"
                                    aria-selected="{{ $isSelected ? 'true' : 'false' }}">
                                <span class="bc-country-option-flag" aria-hidden="true">
                                    @include('front.layout.partial.country-flag', ['country' => array_merge($country, ['slug' => $slug]), 'width' => 32, 'height' => 24])
                                </span>
                                <span class="bc-country-option-body">
                                    <span class="bc-country-option-name">{{ $country['name'] }}</span>
                                    <span class="bc-country-option-meta">
                                        @if($slug === 'global')
                                            {{ $t('drawer.global_meta') }}
                                        @elseif($brokerCount > 0)
                                            {{ str_replace('{count}', (string) $brokerCount, $t('drawer.brokers_count')) }}
                                        @else
                                            {{ $t('drawer.no_brokers') }}
                                        @endif
                                    </span>
                                </span>
                                <span class="bc-country-option-check" aria-hidden="true"></span>
                            </button>
                        </form>
                    @endforeach
                </div>

                <p id="countryDrawerEmpty" class="bc-country-drawer-empty is-hidden" role="status">{{ $t('drawer.country_empty') }}</p>
            </section>
        </div>
    </aside>
</div>
