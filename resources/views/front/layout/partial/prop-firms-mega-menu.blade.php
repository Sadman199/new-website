@php
    $pfNav = $propFirmNav ?? ['categories' => collect(), 'featured' => collect(), 'topRated' => collect(), 'attributes' => collect()];
    $pfFeatured = $pfNav['featured']->isNotEmpty() ? $pfNav['featured'] : $pfNav['topRated'];
@endphp

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
