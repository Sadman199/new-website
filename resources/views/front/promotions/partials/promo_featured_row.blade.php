@if(collect($featuredCards ?? [])->isNotEmpty() && ($activeTab ?? 'all') === \App\Services\PromotionsIndexService::TAB_ALL && empty($featuredOnly) && empty($search) && empty(array_filter($activeFilters ?? [])))
    @php
        $promotionsService = app(\App\Services\PromotionsIndexService::class);
    @endphp
    <section class="bpr-featured" aria-label="Featured promotions">
        <div class="bpr-featured__head">
            <div>
                <p class="bpr-kicker">Featured</p>
                <h2 class="bpr-featured__title">Highlighted broker promotions</h2>
            </div>
            <a href="{{ $promotionsService->tabUrl($activeTab, $activeSort, true, null, $activeFilters ?? []) }}" class="bpr-featured__all">
                View all featured
            </a>
        </div>

        <div class="bpr-featured__stack">
            @foreach($featuredCards as $promo)
                @php
                    $viewUrl = $promo['detail_url'] ?? $promo['url'] ?? null;
                    $facts = collect([
                        ['label' => 'Min. deposit', 'value' => $promo['min_deposit'] ?? null],
                        ['label' => 'Max credit', 'value' => $promo['max_credit'] ?? null],
                        ['label' => 'Eligible', 'value' => $promo['eligible_clients'] ?? null],
                        ['label' => 'Expires', 'value' => $promo['expiry'] ?? null],
                    ])->filter(fn ($item) => filled($item['value']))->take(3)->values();
                @endphp
                <article class="bpr-featured__card is-featured">
                    <div class="bpr-featured__body">
                        <div class="bpr-featured__top">
                            <div class="bpr-featured__broker">
                                <span class="bpr-featured__logo">
                                    @if(!empty($promo['broker_logo']))
                                        <img src="{{ $promo['broker_logo'] }}" alt="" loading="lazy" decoding="async" width="44" height="44">
                                    @else
                                        <span>{{ strtoupper(substr((string) ($promo['broker_name'] ?? $promo['title']), 0, 1)) }}</span>
                                    @endif
                                </span>
                                <div>
                                    @if(!empty($promo['broker_name']))
                                        <p class="bpr-featured__broker-name">{{ $promo['broker_name'] }}</p>
                                    @endif
                                    <div class="bpr-featured__labels">
                                        <span class="bpr-offer__featured">Featured</span>
                                        <span class="bpr-featured__type">{{ $promo['type_short'] }}</span>
                                        @if(!empty($promo['promotion_status_label']))
                                            <span class="bpr-offer__status bpr-offer__status--{{ $promo['promotion_status'] ?? 'ongoing' }}">{{ $promo['promotion_status_label'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(!empty($promo['offer_highlight']))
                                <p class="bpr-featured__value">{{ $promo['offer_highlight'] }}</p>
                            @endif
                        </div>

                        <h3 class="bpr-featured__headline">
                            @if($viewUrl)
                                <a href="{{ $viewUrl }}">{{ $promo['title'] }}</a>
                            @else
                                {{ $promo['title'] }}
                            @endif
                        </h3>

                        @if($facts->isNotEmpty())
                            <dl class="bpr-featured__facts">
                                @foreach($facts as $fact)
                                    <div>
                                        <dt>{{ $fact['label'] }}</dt>
                                        <dd>{{ $fact['value'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif

                        <div class="bpr-featured__actions">
                            @if($viewUrl)
                                <a href="{{ $viewUrl }}" class="bc-btn bc-btn--outline">View bonus</a>
                            @endif
                            @if(!empty($promo['affiliate_link']))
                                <a href="{{ $promo['affiliate_link'] }}"
                                   class="bc-btn bc-btn--primary"
                                   target="_blank"
                                   rel="noopener noreferrer nofollow">Claim bonus</a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
