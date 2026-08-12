@php
    $promotions = $homepagePromotions ?? $latestBonuses ?? collect();
    $featured = $featuredPromotion ?? null;
    $cards = isset($promotionCards) ? $promotionCards : $promotions->take(6);
    $categories = $promotionCategories ?? \App\Models\ForexBonus::homepageCategoryLinks();
@endphp

@if($promotions->isNotEmpty())
<section class="bc-section bc-promos" id="active-promotions">
    <div class="bc-container">
        <div class="bc-promos__head">
            <div class="bc-promos__intro">
                <p class="bc-promos__eyebrow">Bonuses &amp; offers</p>
                <h2 class="bc-section__title">Active promotions</h2>
                <p class="bc-section__sub">Live deposit bonuses, no-deposit offers, contests, and cashback deals from regulated brokers — updated from our promotions database.</p>
            </div>
            <a href="{{ route('promotions.index') }}" class="bc-promos__all-link">
                Browse all bonuses
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <nav class="bc-promos__categories" aria-label="Promotion categories">
            @foreach($categories as $category)
                <a href="{{ route($category['route'], $category['type']) }}" class="bc-promos__category">
                    {{ $category['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="bc-promos__layout">
            @if($featured)
                <article class="bc-promo-spotlight bc-promo-spotlight--{{ $featured->promoTypeTone() }}">
                    <a href="{{ $featured->cardUrl() }}" class="bc-promo-spotlight__link">
                        <div class="bc-promo-spotlight__media">
                            @if($featured->feature_image)
                                <img src="{{ asset($featured->feature_image) }}"
                                     alt=""
                                     class="bc-promo-spotlight__image"
                                     loading="lazy"
                                     decoding="async">
                            @else
                                <div class="bc-promo-spotlight__placeholder" aria-hidden="true">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/></svg>
                                </div>
                            @endif
                            @if($featured->is_featured)
                                <span class="bc-promo-spotlight__featured">Featured</span>
                            @endif
                        </div>

                        <div class="bc-promo-spotlight__body">
                            <div class="bc-promo-spotlight__meta">
                                <span class="bc-promo-badge bc-promo-badge--{{ $featured->promoTypeTone() }}">{{ $featured->promoTypeShort() }}</span>
                                @php $featuredBadge = $featured->expiryBadge(); @endphp
                                @if($featuredBadge && in_array($featuredBadge['tone'], ['urgent', 'soon'], true))
                                    @include('front.partials.expiry_badge', ['badge' => $featuredBadge])
                                @elseif($featured->promotion_status === 'limited-time')
                                    <span class="bc-promo-badge bc-promo-badge--urgent">Limited time</span>
                                @endif
                            </div>

                            <h3 class="bc-promo-spotlight__title">{{ $featured->title }}</h3>

                            @if($featured->brokerDisplayName())
                                <p class="bc-promo-spotlight__broker">{{ $featured->brokerDisplayName() }}</p>
                            @endif

                            <p class="bc-promo-spotlight__offer">{{ $featured->headlineOffer() }}</p>

                            <ul class="bc-promo-spotlight__facts">
                                @if($featured->minDepositLabel())
                                    <li>{{ $featured->minDepositLabel() }}</li>
                                @endif
                                @if($featured->expiryLabel())
                                    <li @class(['bc-expiry-fact--' . ($featured->expiryTone() ?? '') => $featured->expiryTone()])>{{ $featured->expiryLabel() }}</li>
                                @endif
                            </ul>

                            <span class="bc-promo-spotlight__cta">View promotion</span>
                        </div>
                    </a>
                </article>
            @endif

            <div class="bc-promos__grid">
                @foreach($cards as $bonus)
                    @php $cardBadge = $bonus->expiryBadge(); @endphp
                    <article class="bc-promo-card bc-promo-card--{{ $bonus->promoTypeTone() }}">
                        <a href="{{ $bonus->cardUrl() }}" class="bc-promo-card__link">
                            <div class="bc-promo-card__top">
                                <div class="bc-promo-card__thumb">
                                    @if($bonus->feature_image)
                                        <img src="{{ asset($bonus->feature_image) }}"
                                             alt=""
                                             loading="lazy"
                                             decoding="async">
                                    @else
                                        <span class="bc-promo-card__thumb-fallback" aria-hidden="true">
                                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="bc-promo-card__head">
                                    <span class="bc-promo-badge bc-promo-badge--{{ $bonus->promoTypeTone() }}">{{ $bonus->promoTypeShort() }}</span>
                                    @if($cardBadge && in_array($cardBadge['tone'], ['urgent', 'soon'], true))
                                        @include('front.partials.expiry_badge', ['badge' => $cardBadge])
                                    @endif
                                    <h3 class="bc-promo-card__title">{{ Str::limit($bonus->title, 72) }}</h3>
                                    @if($bonus->brokerDisplayName())
                                        <p class="bc-promo-card__broker">{{ $bonus->brokerDisplayName() }}</p>
                                    @endif
                                </div>
                            </div>

                            <p class="bc-promo-card__offer">{{ $bonus->headlineOffer() }}</p>

                            <div class="bc-promo-card__footer">
                                <div class="bc-promo-card__facts">
                                    @if($bonus->minDepositLabel())
                                        <span>{{ $bonus->minDepositLabel() }}</span>
                                    @endif
                                    @if($bonus->expiryLabel())
                                        <span @class(['bc-expiry-fact--' . ($bonus->expiryTone() ?? '') => $bonus->expiryTone()])>{{ $bonus->expiryLabel() }}</span>
                                    @endif
                                </div>
                                <span class="bc-promo-card__cta">Details</span>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
