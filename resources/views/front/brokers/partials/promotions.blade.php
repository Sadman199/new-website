@if($broker->forexBonuses && $broker->forexBonuses->isNotEmpty())
<section class="br-section" id="broker-promotions">
    <div class="br-section__head">
        <h2 class="br-section__title">Active Promotions</h2>
        <p class="br-section__desc">Current bonus offers from {{ $broker->name }}</p>
    </div>
    <div class="br-section__body br-promotions">
        @foreach($broker->forexBonuses as $index => $promo)
            <article class="br-promo">
                <div class="br-promo__summary">
                    <div>
                        <div class="br-promo__head">
                            <span class="br-promo__type">{{ $promo->promo_type }}</span>
                            @php $expiryBadge = $promo->expiryBadge(); @endphp
                            @if($expiryBadge)
                                @include('front.partials.expiry_badge', ['badge' => $expiryBadge])
                            @endif
                        </div>
                        <h3 class="br-promo__title">{{ $promo->title }}</h3>
                        @if($promo->bonus_amount || $promo->bonus_percentage)
                            <p class="br-promo__value">
                                @if($promo->bonus_amount)
                                    <strong>${{ number_format((float) $promo->bonus_amount, 0) }}</strong>
                                @endif
                                @if($promo->bonus_amount && $promo->bonus_percentage)
                                    ·
                                @endif
                                @if($promo->bonus_percentage)
                                    <strong>{{ rtrim(rtrim(number_format((float) $promo->bonus_percentage, 2), '0'), '.') }}%</strong> match
                                @endif
                            </p>
                        @endif
                        @if($promo->min_deposit)
                            <p class="br-promo__meta">Min deposit: ${{ number_format((float) $promo->min_deposit, 0) }}</p>
                        @endif
                    </div>
                    <div class="br-promo__actions">
                        @if($promo->detailUrl())
                            <a href="{{ $promo->detailUrl() }}" class="br-promo__link">Full details</a>
                        @endif
                        @if($promo->link)
                            <a href="{{ $promo->link }}" target="_blank" rel="noopener noreferrer" class="br-btn br-btn--primary br-btn--sm">Claim offer</a>
                        @endif
                    </div>
                </div>

                @php
                    $hasMore = $promo->description || $promo->details || $promo->how_to_participate || $promo->general_terms || $promo->eligibility_criteria || $promo->expiry_date;
                @endphp
                @if($hasMore)
                    <div class="br-promo__more" id="promo-more-{{ $index }}" hidden>
                        @if($promo->description)
                            <div class="br-promo__block">
                                <h4>Description</h4>
                                <div>{!! $promo->description !!}</div>
                            </div>
                        @endif
                        @if($promo->details)
                            <div class="br-promo__block">
                                <h4>Details</h4>
                                <div>{!! $promo->details !!}</div>
                            </div>
                        @endif
                        @if($promo->how_to_participate)
                            <div class="br-promo__block">
                                <h4>How to participate</h4>
                                <div>{!! $promo->how_to_participate !!}</div>
                            </div>
                        @endif
                        @if($promo->eligibility_criteria)
                            <div class="br-promo__block">
                                <h4>Eligibility</h4>
                                <div>{!! $promo->eligibility_criteria !!}</div>
                            </div>
                        @endif
                        @if($promo->general_terms)
                            <div class="br-promo__block">
                                <h4>Terms</h4>
                                <div>{!! $promo->general_terms !!}</div>
                            </div>
                        @endif
                        @if($promo->expiry_date)
                            <p class="br-promo__meta">Expires: {{ $promo->expiry_date->format('M j, Y') }}</p>
                        @endif
                    </div>
                    <button type="button"
                            class="br-read-more"
                            data-br-target="promo-more-{{ $index }}"
                            aria-expanded="false">
                        <span class="br-read-more__show">Read more about {{ $promo->title }}</span>
                        <span class="br-read-more__hide" hidden>Show less</span>
                    </button>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endif
