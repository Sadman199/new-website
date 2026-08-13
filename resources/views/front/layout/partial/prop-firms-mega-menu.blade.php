@php
    $pfNav = $propFirmNav ?? ['categories' => collect(), 'featured' => collect(), 'topRated' => collect(), 'attributes' => collect()];
    $pfFeatured = $pfNav['featured']->isNotEmpty() ? $pfNav['featured'] : $pfNav['topRated'];
@endphp

<style>
    #propFirmsMegaMenu {
        --nav-ocean: var(--bc-primary, #007AAD);
        --nav-off-white: var(--bc-white, #FFFBFC);
        --nav-midnight: var(--bc-dark, #0C1D32);
        --nav-menu-bg: var(--nav-off-white);
        --nav-menu-text: var(--nav-midnight);
        --nav-menu-muted: #64748b;
        --nav-menu-border: #e2e8f0;
        --nav-menu-surface: #f8fafc;
        --nav-menu-hover: #eef2f7;
        --nav-border: var(--nav-menu-border);
    }
    #propFirmsMegaMenu .pf-nav-inner {
        position: relative;
        max-width: 80rem;
        margin: 0 auto;
        padding: 20px 24px 18px;
    }
    #propFirmsMegaMenu .pf-nav-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr 0.95fr 0.95fr;
        gap: 14px;
    }
    #propFirmsMegaMenu .pf-nav-card {
        background: var(--nav-menu-bg);
        border: 1px solid var(--nav-menu-border);
        border-radius: 16px;
        padding: 18px 16px;
        min-width: 0;
        box-shadow: none;
    }
    #propFirmsMegaMenu .pf-nav-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }
    #propFirmsMegaMenu .pf-nav-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(0, 122, 173, 0.1);
        border: 1px solid rgba(0, 122, 173, 0.22);
        color: var(--nav-ocean);
        flex-shrink: 0;
    }
    #propFirmsMegaMenu .pf-nav-icon svg { width: 16px; height: 16px; }
    #propFirmsMegaMenu .pf-nav-title {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--nav-menu-muted);
    }
    #propFirmsMegaMenu .pf-nav-firm {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 10px;
        margin-bottom: 6px;
        border-radius: 12px;
        background: var(--nav-menu-surface);
        border: 1px solid var(--nav-menu-border);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    #propFirmsMegaMenu .pf-nav-firm:hover {
        background: var(--nav-menu-hover);
        border-color: #cbd5e1;
        transform: none;
    }
    #propFirmsMegaMenu .pf-nav-firm:hover .pf-nav-firm-name {
        color: var(--nav-menu-text);
    }
    #propFirmsMegaMenu .pf-nav-firm-logo {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid var(--nav-menu-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    #propFirmsMegaMenu .pf-nav-firm-logo img { width: 28px; height: 28px; object-fit: contain; }
    #propFirmsMegaMenu .pf-nav-firm-name {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
        color: var(--nav-menu-text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    #propFirmsMegaMenu .pf-nav-trust {
        font-size: 11px;
        font-weight: 700;
        color: #047857;
        background: #ecfdf5;
        padding: 2px 8px;
        border-radius: 6px;
        flex-shrink: 0;
    }
    #propFirmsMegaMenu .pf-nav-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }
    #propFirmsMegaMenu .pf-nav-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--nav-menu-text);
        background: var(--nav-menu-surface);
        border: 1px solid var(--nav-menu-border);
        border-radius: 999px;
        text-decoration: none;
        transition: all 0.18s ease;
        white-space: nowrap;
    }
    #propFirmsMegaMenu .pf-nav-chip:hover {
        color: var(--nav-menu-text);
        background: var(--nav-menu-hover);
        border-color: #cbd5e1;
    }
    #propFirmsMegaMenu .pf-nav-links {
        display: grid;
        gap: 4px;
    }
    #propFirmsMegaMenu .pf-nav-link {
        display: block;
        padding: 7px 10px;
        font-size: 13px;
        font-weight: 500;
        color: var(--nav-menu-text);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.15s;
    }
    #propFirmsMegaMenu .pf-nav-link:hover {
        color: var(--nav-menu-text);
        background: var(--nav-menu-hover);
    }
    #propFirmsMegaMenu .pf-nav-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 14px;
        padding: 14px 18px;
        border-radius: 14px;
        background: var(--nav-menu-surface);
        border: 1px solid var(--nav-menu-border);
    }
    #propFirmsMegaMenu .pf-nav-bottom p {
        margin: 0;
        font-size: 13px;
        color: var(--nav-menu-muted);
    }
    #propFirmsMegaMenu .pf-nav-btn {
        display: inline-flex;
        align-items: center;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        background: var(--nav-ocean);
        border-radius: 999px;
        text-decoration: none;
        box-shadow: 0 8px 24px rgba(0, 122, 173, 0.25);
        transition: transform 0.15s, background 0.15s;
        flex-shrink: 0;
    }
    #propFirmsMegaMenu .pf-nav-btn:hover {
        transform: translateY(-1px);
        background: #006694;
    }
    #propFirmsMegaMenu .pf-nav-footer-link {
        font-size: 13px;
        font-weight: 600;
        color: var(--nav-ocean);
        text-decoration: none;
    }
    #propFirmsMegaMenu .pf-nav-footer-link:hover { color: #006694; }
    @media (max-width: 1279px) {
        #propFirmsMegaMenu .pf-nav-grid {
            grid-template-columns: 1fr 1fr;
        }
        #propFirmsMegaMenu .pf-nav-card:first-child {
            grid-column: 1 / -1;
        }
    }
</style>

<div id="propFirmsMegaMenu" aria-labelledby="propFirmsButton" aria-hidden="true">
    <div class="pf-nav-inner">
        <div class="pf-nav-grid">
            <div class="pf-nav-card">
                <div class="pf-nav-head">
                    <span class="pf-nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </span>
                    <p class="pf-nav-title">Featured prop firms</p>
                </div>
                @foreach($pfFeatured->take(5) as $firm)
                    <a href="{{ route('prop_firms.show', $firm->slug) }}" class="pf-nav-firm">
                        <span class="pf-nav-firm-logo">
                            @if($firm->logo)<img src="{{ asset($firm->logo) }}" alt="" loading="lazy" decoding="async">@endif
                        </span>
                        <span class="pf-nav-firm-name">{{ $firm->name }}</span>
                        @if($firm->trust_score)<span class="pf-nav-trust">{{ number_format($firm->trust_score, 1) }}</span>@endif
                    </a>
                @endforeach
            </div>

            <div class="pf-nav-card">
                <div class="pf-nav-head">
                    <span class="pf-nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </span>
                    <p class="pf-nav-title">By category</p>
                </div>
                <div class="pf-nav-chips">
                    @foreach($pfNav['categories'] as $cat)
                        <a href="{{ route('prop_firms.category', $cat->slug) }}" class="pf-nav-chip">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="pf-nav-card">
                <div class="pf-nav-head">
                    <span class="pf-nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <p class="pf-nav-title">Program types</p>
                </div>
                <div class="pf-nav-chips">
                    <a href="{{ route('prop_firms.index', ['attribute' => 'instant-funding']) }}" class="pf-nav-chip">Instant Funding</a>
                    <a href="{{ route('prop_firms.index', ['attribute' => 'one-step']) }}" class="pf-nav-chip">One Step</a>
                    <a href="{{ route('prop_firms.index', ['attribute' => 'two-step']) }}" class="pf-nav-chip">Two Step</a>
                    <a href="{{ route('prop_firms.index', ['verified' => 1]) }}" class="pf-nav-chip">Verified</a>
                    <a href="{{ route('prop_firms.index', ['featured' => 1]) }}" class="pf-nav-chip">Featured</a>
                </div>
            </div>

            <div class="pf-nav-card">
                <div class="pf-nav-head">
                    <span class="pf-nav-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7c-2 0-3 1-3 3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11h6M9 15h4"/></svg>
                    </span>
                    <p class="pf-nav-title">Platforms & features</p>
                </div>
                <div class="pf-nav-links">
                    @foreach($pfNav['attributes']->take(6) as $attr)
                        <a href="{{ route('prop_firms.index', ['attribute' => $attr->slug]) }}" class="pf-nav-link">{{ $attr->name }}</a>
                    @endforeach
                    <a href="{{ route('prop_firms.index') }}" class="pf-nav-link" style="color:var(--nav-ocean);font-weight:600;">Browse all firms →</a>
                </div>
            </div>
        </div>

        <div class="pf-nav-bottom">
            <p>Compare funded account programs, drawdown rules, and profit splits — built for prop traders.</p>
            <div style="display:flex;align-items:center;gap:14px;flex-shrink:0;">
                <a href="{{ route('prop_firms.index', ['featured' => 1]) }}" class="pf-nav-footer-link">Top rated →</a>
                <a href="{{ route('prop_firms.index') }}" class="pf-nav-btn">Explore prop firms</a>
            </div>
        </div>
    </div>
</div>
