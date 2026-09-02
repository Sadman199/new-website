@php
    $viewUrl = $promo['detail_url'] ?? $promo['url'] ?? null;
    $terms = collect([
        ['label' => 'Min. deposit', 'value' => $promo['min_deposit'] ?? null],
        ['label' => 'Max credit', 'value' => $promo['max_credit'] ?? null],
        ['label' => 'Eligible', 'value' => $promo['eligible_clients'] ?? null],
        ['label' => 'Wagering', 'value' => $promo['wagering_requirement'] ?? null],
        ['label' => 'Volume req.', 'value' => $promo['volume_requirement'] ?? null],
        ['label' => 'Expires', 'value' => $promo['expiry'] ?? null],
    ])->filter(fn ($item) => filled($item['value']))->values();
    $expandFacts = collect($promo['expand_facts'] ?? [])->values();
@endphp

<article class="bpr-offer {{ !empty($promo['is_featured']) ? 'is-featured' : '' }} {{ !empty($promo['is_urgent']) ? 'is-urgent' : '' }}">
    <div class="bpr-offer__broker">
        @if($viewUrl || !empty($promo['broker_review_url']))
            <a href="{{ $viewUrl ?: $promo['broker_review_url'] }}" class="bpr-offer__logo">
        @else
            <span class="bpr-offer__logo">
        @endif
            @if(!empty($promo['broker_logo']))
                <img src="{{ $promo['broker_logo'] }}" alt="" loading="lazy" decoding="async" width="52" height="52">
            @else
                <span class="bpr-offer__logo-fallback">{{ strtoupper(substr((string) ($promo['broker_name'] ?? $promo['title']), 0, 1)) }}</span>
            @endif
        @if($viewUrl || !empty($promo['broker_review_url']))
            </a>
        @else
            </span>
        @endif
        <div class="bpr-offer__broker-meta">
            @if(!empty($promo['broker_name']))
                <p class="bpr-offer__broker-name">{{ $promo['broker_name'] }}</p>
            @endif
            <div class="bpr-offer__broker-tags">
                @if(!empty($promo['is_featured']))
                    <span class="bpr-offer__featured">Featured</span>
                @endif
                <span class="bpr-offer__type">{{ $promo['type_short'] }}</span>
                @if(!empty($promo['bonus_category']))
                    <span class="bpr-offer__category">{{ $promo['bonus_category'] }}</span>
                @endif
                @if(!empty($promo['promotion_status_label']))
                    <span class="bpr-offer__status bpr-offer__status--{{ $promo['promotion_status'] ?? 'ongoing' }}">{{ $promo['promotion_status_label'] }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bpr-offer__promo">
        @if(!empty($promo['offer_highlight']))
            <p class="bpr-offer__value" aria-label="Offer value">{{ $promo['offer_highlight'] }}</p>
        @endif
        <h3 class="bpr-offer__title">
            @if($viewUrl)
                <a href="{{ $viewUrl }}">{{ $promo['title'] }}</a>
            @else
                {{ $promo['title'] }}
            @endif
        </h3>
        @if(empty($promo['offer_highlight']) && !empty($promo['offer']))
            <p class="bpr-offer__offer-line">{{ $promo['offer'] }}</p>
        @endif
        @if(!empty($promo['type_details']))
            <p class="bpr-offer__detail">{{ $promo['type_details'] }}</p>
        @elseif(!empty($promo['description']))
            <p class="bpr-offer__detail">{{ $promo['description'] }}</p>
        @endif
    </div>

    @if($terms->isNotEmpty())
        <dl class="bpr-offer__terms">
            @foreach($terms as $term)
                <div>
                    <dt>{{ $term['label'] }}</dt>
                    <dd>{{ $term['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    @endif

    <div class="bpr-offer__actions">
        @if($viewUrl)
            <a href="{{ $viewUrl }}" class="bc-btn bc-btn--outline bpr-offer__btn">View bonus</a>
        @endif
        @if(!empty($promo['affiliate_link']))
            <a href="{{ $promo['affiliate_link'] }}"
               class="bc-btn bc-btn--primary bpr-offer__btn"
               target="_blank"
               rel="noopener noreferrer nofollow">Claim bonus</a>
        @endif
        @if(!empty($promo['broker_review_url']))
            <a href="{{ $promo['broker_review_url'] }}" class="bpr-offer__review">Review</a>
        @endif
    </div>

    @if($expandFacts->isNotEmpty())
        <details class="bpr-offer__more">
            <summary class="bpr-offer__more-toggle">
                <span>More details</span>
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m6 8 4 4 4-4"/></svg>
            </summary>
            <dl class="bpr-offer__more-facts">
                @foreach($expandFacts as $fact)
                    <div>
                        <dt>{{ $fact['label'] }}</dt>
                        <dd>{{ $fact['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </details>
    @endif
</article>
