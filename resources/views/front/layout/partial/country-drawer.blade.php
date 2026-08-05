{{-- Country residence selector — BrokerChooser-style left slide panel --}}
<style>
    .bc-country-drawer {
        position: fixed;
        inset: 0;
        z-index: 1200;
        pointer-events: none;
        visibility: hidden;
    }
    .bc-country-drawer.is-open {
        pointer-events: auto;
        visibility: visible;
    }
    .bc-country-drawer-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        opacity: 0;
        transition: opacity 0.32s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .bc-country-drawer.is-open .bc-country-drawer-backdrop {
        opacity: 1;
    }
    .bc-country-drawer-panel {
        position: absolute;
        top: 0;
        left: 0;
        height: 100vh;
        height: 100dvh;
        width: min(92vw, 400px);
        max-width: 100%;
        background: #ffffff;
        box-shadow: 8px 0 40px rgba(15, 23, 42, 0.12);
        display: flex;
        flex-direction: column;
        transform: translateX(-100%);
        transition: transform 0.34s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    .bc-country-drawer.is-open .bc-country-drawer-panel {
        transform: translateX(0);
    }
    .bc-country-drawer-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 20px 16px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .bc-country-drawer-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 6px;
        line-height: 1.3;
    }
    .bc-country-drawer-desc {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    .bc-country-drawer-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: none;
        background: #f8fafc;
        border-radius: 10px;
        color: #64748b;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s, color 0.15s;
    }
    .bc-country-drawer-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .bc-country-drawer-list {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 8px 12px;
        -webkit-overflow-scrolling: touch;
    }
    .bc-country-option {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 4px;
        border: 1px solid transparent;
        border-radius: 12px;
        background: transparent;
        text-align: left;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
    }
    .bc-country-option:hover {
        background: #f8fafc;
    }
    .bc-country-option.is-selected {
        background: #eff6ff;
        border-color: #bfdbfe;
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.08);
    }
    .bc-country-option-flag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 24px;
        flex-shrink: 0;
        border-radius: 4px;
        overflow: hidden;
        background: #f8fafc;
        box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.08);
    }
    .bc-country-option-flag .bc-flag-img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .bc-country-option-flag .bc-flag-globe {
        width: 20px;
        height: 20px;
        color: #64748b;
    }
    .bc-country-option-name {
        flex: 1;
        font-size: 0.9375rem;
        font-weight: 500;
        color: #1e293b;
    }
    .bc-country-option-check {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: border-color 0.15s, background 0.15s;
    }
    .bc-country-option.is-selected .bc-country-option-check {
        border-color: #2563eb;
        background: #2563eb;
    }
    .bc-country-option.is-selected .bc-country-option-check::after {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #fff;
    }
    .bc-country-drawer-footer {
        padding: 16px 20px max(20px, env(safe-area-inset-bottom));
        border-top: 1px solid #f1f5f9;
        flex-shrink: 0;
        background: #fff;
    }
    .bc-country-confirm-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 48px;
        padding: 0 20px;
        border: none;
        border-radius: 12px;
        background: #2563eb;
        color: #fff;
        font-size: 0.9375rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s, transform 0.1s;
    }
    .bc-country-confirm-btn:hover {
        background: #1d4ed8;
    }
    .bc-country-confirm-btn:active {
        transform: scale(0.98);
    }
    .bc-country-confirm-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .bc-country-nav-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px;
        min-width: 40px !important;
        width: auto !important;
        padding: 0 10px !important;
        height: 40px !important;
        line-height: 1 !important;
    }
    .bc-country-nav-flag {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 18px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 3px;
        background: #f8fafc;
        box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.1);
    }
    .bc-country-nav-flag--sm {
        width: 22px;
        height: 16px;
    }
    .bc-country-nav-flag .bc-flag-img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .bc-country-nav-flag .bc-flag-globe {
        width: 18px;
        height: 18px;
        color: #475569;
    }
    .bc-country-nav-label {
        display: none;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        max-width: 88px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    @media (min-width: 1280px) {
        .bc-country-nav-label {
            display: inline;
        }
        .bc-country-nav-btn {
            padding: 0 10px !important;
        }
    }
    .bc-country-toast {
        position: fixed;
        top: 5.5rem;
        left: 50%;
        transform: translateX(-50%) translateY(-12px);
        z-index: 1300;
        max-width: min(92vw, 420px);
        padding: 0.875rem 1.125rem;
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12);
        color: #065f46;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.45;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.28s ease, transform 0.28s ease;
    }
    .bc-country-toast.is-visible {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
        pointer-events: auto;
    }
</style>

@if(session('country_updated'))
    <div id="countrySuccessToast" class="bc-country-toast is-visible" role="status" aria-live="polite">
        Country updated! You'll now see brokers and recommendations tailored to your location.
    </div>
@endif

<div id="countryDrawer" class="bc-country-drawer" aria-hidden="true" role="dialog" aria-labelledby="countryDrawerTitle" aria-modal="true">
    <div class="bc-country-drawer-backdrop" id="countryDrawerBackdrop" tabindex="-1"></div>
    <aside class="bc-country-drawer-panel">
        <div class="bc-country-drawer-header">
            <div>
                <h2 id="countryDrawerTitle" class="bc-country-drawer-title">Select your country</h2>
                <p class="bc-country-drawer-desc">Select your country of residence to see available brokers and get personalized recommendations.</p>
            </div>
            <button type="button" class="bc-country-drawer-close" id="countryDrawerClose" aria-label="Close country selector">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="bc-country-drawer-list" role="listbox" aria-label="Countries">
            @foreach($brokerCountries as $slug => $country)
                <button type="button"
                        class="bc-country-option {{ ($preferredCountry['slug'] ?? 'global') === $slug ? 'is-selected' : '' }}"
                        data-country="{{ $slug }}"
                        data-name="{{ $country['name'] }}"
                        data-code="{{ $country['code'] ?? '' }}"
                        role="option"
                        aria-selected="{{ ($preferredCountry['slug'] ?? 'global') === $slug ? 'true' : 'false' }}">
                    <span class="bc-country-option-flag" aria-hidden="true">
                        @include('front.layout.partial.country-flag', ['country' => array_merge($country, ['slug' => $slug]), 'width' => 32, 'height' => 24])
                    </span>
                    <span class="bc-country-option-name">{{ $country['name'] }}</span>
                    <span class="bc-country-option-check" aria-hidden="true"></span>
                </button>
            @endforeach
        </div>

        <div class="bc-country-drawer-footer">
            <form id="countrySwitchForm" action="{{ route('front_country') }}" method="POST">
                @csrf
                <input type="hidden" name="country" id="countrySwitchInput" value="{{ $preferredCountry['slug'] ?? 'global' }}">
                <button type="submit" class="bc-country-confirm-btn" id="countryConfirmBtn">Confirm Changes</button>
            </form>
        </div>
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var drawer = document.getElementById('countryDrawer');
    var backdrop = document.getElementById('countryDrawerBackdrop');
    var closeBtn = document.getElementById('countryDrawerClose');
    var openBtn = document.getElementById('countrySelectorBtn');
    var form = document.getElementById('countrySwitchForm');
    var input = document.getElementById('countrySwitchInput');
    var confirmBtn = document.getElementById('countryConfirmBtn');
    var navFlag = document.getElementById('countryNavFlag');
    var navLabel = document.getElementById('countryNavLabel');
    var options = drawer ? drawer.querySelectorAll('.bc-country-option') : [];
    var savedSlug = input ? input.value : 'global';
    var pendingSlug = savedSlug;
    var toast = document.getElementById('countrySuccessToast');

    if (toast) {
        setTimeout(function () {
            toast.classList.remove('is-visible');
        }, 4500);
    }

    function setBodyScroll(lock) {
        document.body.classList.toggle('overflow-hidden', lock);
    }

    function nearestFlagWidth(width) {
        var sizes = [20, 40, 80, 160, 320, 640];
        var closest = 40;
        var smallest = Infinity;
        sizes.forEach(function (size) {
            var diff = Math.abs(size - width);
            if (diff < smallest) {
                smallest = diff;
                closest = size;
            }
        });
        return closest;
    }

    function flagMarkup(code, width) {
        width = nearestFlagWidth(width || 20);
        var height = Math.max(14, Math.round(width * 0.75));
        if (code) {
            var base = 'https://flagcdn.com/w' + width + '/' + code + '.png';
            var retina = 'https://flagcdn.com/w' + nearestFlagWidth(width * 2) + '/' + code + '.png';
            return '<img src="' + base + '" srcset="' + retina + ' 2x" alt="" class="bc-flag-img" width="' + width + '" height="' + height + '" loading="lazy" decoding="async" aria-hidden="true">';
        }
        return '<svg class="bc-flag-globe" width="' + width + '" height="' + width + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke-width="1.75"/><path stroke-width="1.75" d="M3 12h18M12 3c2.5 2.8 3.8 6.2 3.8 9s-1.3 6.2-3.8 9M12 3c-2.5 2.8-3.8 6.2-3.8 9s1.3 6.2 3.8 9"/></svg>';
    }

    function updateNavPreview(slug) {
        var match = drawer ? drawer.querySelector('.bc-country-option[data-country="' + slug + '"]') : null;
        if (!match) return;
        if (navFlag) {
            navFlag.innerHTML = flagMarkup(match.dataset.code || '', 20);
        }
        if (navLabel) navLabel.textContent = match.dataset.name || 'Global';
        if (openBtn) {
            openBtn.setAttribute('aria-label', 'Select country: ' + (match.dataset.name || 'Global'));
            openBtn.setAttribute('title', match.dataset.name || 'Global');
        }
    }

    function selectOption(slug) {
        pendingSlug = slug;
        options.forEach(function (btn) {
            var active = btn.dataset.country === slug;
            btn.classList.toggle('is-selected', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        if (input) {
            input.value = slug;
        }
    }

    function openDrawer() {
        if (!drawer) return;
        pendingSlug = savedSlug;
        selectOption(savedSlug);
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        setBodyScroll(true);
        openBtn?.classList.add('is-active');
        openBtn?.setAttribute('aria-expanded', 'true');
        window.dispatchEvent(new CustomEvent('bc:country-drawer-open'));
    }

    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        setBodyScroll(false);
        openBtn?.classList.remove('is-active');
        openBtn?.setAttribute('aria-expanded', 'false');
        selectOption(savedSlug);
    }

    openBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (drawer?.classList.contains('is-open')) {
            closeDrawer();
        } else {
            openDrawer();
        }
    });

    closeBtn?.addEventListener('click', closeDrawer);
    backdrop?.addEventListener('click', closeDrawer);

    options.forEach(function (btn) {
        btn.addEventListener('click', function () {
            selectOption(btn.dataset.country || 'global');
        });
    });

    form?.addEventListener('submit', function (e) {
        if (!input || !input.value) {
            e.preventDefault();
            return;
        }
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.textContent = 'Saving…';
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && drawer?.classList.contains('is-open')) {
            closeDrawer();
        }
    });

    updateNavPreview(savedSlug);

    window.bcCountryDrawer = { open: openDrawer, close: closeDrawer };
});
</script>
