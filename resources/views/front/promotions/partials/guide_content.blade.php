@php
    $typeIcons = [
        'deposit-bonuses' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z',
        'no-deposit-bonuses' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z',
        'live-contests' => 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0',
        'cashback-rebates' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99',
        'crypto-bonuses' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
@endphp

<section class="bpr-guide" aria-label="Broker promotions guide">
    <div class="bpr-guide__layout">
        <aside class="bpr-guide__sidebar" aria-label="Table of contents">
            <div class="bpr-guide__sidebar-inner">
                <p class="bpr-guide__sidebar-label">On this page</p>
                <nav class="bpr-guide__toc" aria-label="Table of contents">
                    <ul class="bpr-guide__toc-list">
                        @foreach($guide['toc'] ?? [] as $item)
                            <li>
                                <a href="#{{ $item['id'] }}" class="bpr-guide__toc-link">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="bpr-guide__main">
            <label for="bpr-guide-toc-select" class="bpr-sr-only">Jump to section</label>
            <select id="bpr-guide-toc-select" class="bpr-guide__mobile-toc" aria-label="Jump to section">
                @foreach($guide['toc'] ?? [] as $item)
                    <option value="{{ $item['id'] }}">{{ $item['label'] }}</option>
                @endforeach
            </select>

            <article class="bpr-guide__section bpr-guide__section--intro" id="what-is-forex-promotion">
                <div class="bpr-guide__intro-band">
                    <p class="bpr-guide__intro-stat">
                        <strong>{{ number_format($stats['total_active'] ?? 0) }}</strong>
                        <span>active offers tracked</span>
                    </p>
                    <p class="bpr-guide__intro-stat">
                        <strong>{{ $guide['total_types_live'] ?? 0 }}</strong>
                        <span>live categories</span>
                    </p>
                </div>
                <h2 class="bpr-guide__title">What is a Forex Promotion?</h2>
                <p class="bpr-guide__text">
                    A forex promotion is a time-limited incentive from a broker — deposit match, no-deposit credit,
                    trading contest, or cashback rebate — designed to attract clients or reward activity. These offers
                    can reduce trading costs, but they are not risk-free: eligibility, turnover rules, and withdrawal
                    conditions vary by broker and jurisdiction.
                </p>
                <p class="bpr-guide__text">
                    BrokersCourt links every listing to broker profiles, regulation data, and dedicated offer pages
                    so you can compare terms before opting in.
                </p>
            </article>

            <article class="bpr-guide__section bpr-guide__section--types" id="types-of-forex-promotions">
                <header class="bpr-guide__section-head">
                    <h2 class="bpr-guide__title">Types of Forex Promotions</h2>
                    <p class="bpr-guide__lead">Five incentive formats we monitor — tap a category to filter live offers above.</p>
                </header>
                <div class="bpr-type-mosaic">
                    @foreach($guide['type_rows'] ?? [] as $row)
                        <a href="{{ $row['url'] }}"
                           class="bpr-type-mosaic__cell {{ ($row['count'] ?? 0) > 0 ? 'is-live' : 'is-empty' }}">
                            <span class="bpr-type-mosaic__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $typeIcons[$row['slug']] ?? $typeIcons['deposit-bonuses'] }}"/>
                                </svg>
                            </span>
                            <span class="bpr-type-mosaic__count">{{ $row['count'] }}</span>
                            <span class="bpr-type-mosaic__name">{{ $row['name'] }}</span>
                            <p class="bpr-type-mosaic__desc">{{ $row['description'] }}</p>
                            <span class="bpr-type-mosaic__cta">
                                {{ ($row['count'] ?? 0) > 0 ? 'Explore category' : 'No live offers' }}
                                @if(($row['count'] ?? 0) > 0)
                                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 0 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                                @endif
                            </span>
                        </a>
                    @endforeach
                </div>
            </article>

            <article class="bpr-guide__section bpr-guide__section--glance" id="promotion-types-at-a-glance">
                <header class="bpr-guide__section-head">
                    <h2 class="bpr-guide__title">Promotion Types at a Glance</h2>
                    <p class="bpr-guide__lead">Live category breakdown from our database — updated whenever you load this page.</p>
                </header>
                <div class="bpr-glance">
                    <div class="bpr-glance__summary">
                        <div class="bpr-glance__summary-item">
                            <span class="bpr-glance__summary-value">{{ number_format($stats['total_active'] ?? 0) }}</span>
                            <span class="bpr-glance__summary-label">Total active</span>
                        </div>
                        <div class="bpr-glance__summary-divider" aria-hidden="true"></div>
                        <div class="bpr-glance__summary-item">
                            <span class="bpr-glance__summary-value">{{ number_format($stats['featured'] ?? 0) }}</span>
                            <span class="bpr-glance__summary-label">Featured</span>
                        </div>
                        <div class="bpr-glance__summary-divider" aria-hidden="true"></div>
                        <div class="bpr-glance__summary-item">
                            <span class="bpr-glance__summary-value">{{ number_format($stats['ending_soon'] ?? 0) }}</span>
                            <span class="bpr-glance__summary-label">Ending soon</span>
                        </div>
                    </div>
                    <div class="bpr-glance__rail">
                        @foreach($guide['type_rows'] ?? [] as $row)
                            <div class="bpr-glance__stop {{ ($row['count'] ?? 0) > 0 ? 'is-live' : '' }}">
                                <div class="bpr-glance__stop-top">
                                    <span class="bpr-glance__stop-count">{{ $row['count'] }}</span>
                                    <span class="bpr-glance__stop-name">{{ $row['name'] }}</span>
                                </div>
                                @if($row['sample_broker'] && $row['sample_offer'])
                                    <p class="bpr-glance__stop-sample">{{ $row['sample_broker'] }} · {{ $row['sample_offer'] }}</p>
                                @else
                                    <p class="bpr-glance__stop-sample bpr-glance__stop-sample--muted">No sample offer yet</p>
                                @endif
                                @if(($row['count'] ?? 0) > 0)
                                    <a href="{{ $row['url'] }}" class="bpr-glance__stop-link">View all</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </article>

            <article class="bpr-guide__section" id="how-to-evaluate">
                <h2 class="bpr-guide__title">How to Evaluate Any Forex Promotion?</h2>
                <ol class="bpr-guide__steps">
                    <li><span>01</span> Confirm the broker is regulated in your country and listed on our review page.</li>
                    <li><span>02</span> Read official terms: minimum deposit, bonus cap, eligible instruments, and expiry.</li>
                    <li><span>03</span> Check turnover or volume requirements before withdrawing profits or bonus credit.</li>
                    <li><span>04</span> Compare the offer against normal spreads, commissions, and swap rates.</li>
                    <li><span>05</span> Verify whether the bonus applies to new accounts, existing clients, or specific regions.</li>
                    <li><span>06</span> Prefer transparent offers with published end dates over vague “limited time” wording.</li>
                </ol>
            </article>

            <article class="bpr-guide__section bpr-guide__section--warn" id="common-mistakes">
                <h2 class="bpr-guide__title">Common Mistakes With Forex Promotions</h2>
                <div class="bpr-guide__warn-grid">
                    <div class="bpr-guide__warn-item">
                        <strong>Chasing headline %</strong>
                        <p>Large match rates often hide strict withdrawal or volume rules.</p>
                    </div>
                    <div class="bpr-guide__warn-item">
                        <strong>Over-depositing</strong>
                        <p>Never fund more than your plan just to unlock a higher bonus tier.</p>
                    </div>
                    <div class="bpr-guide__warn-item">
                        <strong>Ignoring regions</strong>
                        <p>Many offers exclude US, UK, EU, or other jurisdictions entirely.</p>
                    </div>
                    <div class="bpr-guide__warn-item">
                        <strong>Treating credit as cash</strong>
                        <p>Bonus balances usually cannot be withdrawn directly.</p>
                    </div>
                    <div class="bpr-guide__warn-item">
                        <strong>Skipping safety checks</strong>
                        <p>A generous offer does not replace regulation and review research.</p>
                    </div>
                </div>
            </article>

            <article class="bpr-guide__section" id="regulation-and-promotions">
                <h2 class="bpr-guide__title">Regulation and Forex Promotions</h2>
                <div class="bpr-guide__reg-callout">
                    <p>
                        Regulators treat promotional incentives differently. A regulated license means supervision —
                        not that every offer suits your profile. Before claiming, cross-check regulators on our
                        <a href="{{ route('regulated_brokers') }}">regulated brokers</a> hub, read the full review,
                        and use the <a href="{{ route('broker.scam_checker') }}">scam checker</a> if anything looks off.
                    </p>
                </div>
            </article>

            <article class="bpr-guide__section bpr-guide__section--live" id="current-promotions">
                <header class="bpr-guide__section-head">
                    <h2 class="bpr-guide__title">Current Promotions Available on BrokersCourt</h2>
                    <p class="bpr-guide__lead">Live database snapshot — scroll each lane to browse offers by category.</p>
                </header>

                @forelse($guide['current_by_type'] ?? [] as $group)
                    <section class="bpr-live-lane" aria-label="{{ $group['name'] }}">
                        <div class="bpr-live-lane__header">
                            <div class="bpr-live-lane__title-wrap">
                                <h3 class="bpr-live-lane__title">{{ $group['name'] }}</h3>
                                <span class="bpr-live-lane__pulse">{{ $group['count'] }} live</span>
                            </div>
                            <a href="{{ $group['url'] }}" class="bpr-live-lane__all">See all</a>
                        </div>
                        <div class="bpr-live-lane__track" tabindex="0">
                            @foreach($group['promos'] as $promo)
                                <a href="{{ $promo['url'] }}" class="bpr-live-chip {{ !empty($promo['is_featured']) ? 'is-featured' : '' }}">
                                    @if($promo['broker_logo'])
                                        <span class="bpr-live-chip__logo">
                                            <img src="{{ $promo['broker_logo'] }}" alt="">
                                        </span>
                                    @endif
                                    <span class="bpr-live-chip__offer">{{ $promo['offer'] }}</span>
                                    <span class="bpr-live-chip__title">{{ Str::limit($promo['title'], 48) }}</span>
                                    <span class="bpr-live-chip__meta">
                                        {{ $promo['broker_name'] }}
                                        @if($promo['expiry'])
                                            · {{ $promo['expiry'] }}
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <p class="bpr-guide__text">No active promotions are listed right now. Check back soon or browse our broker reviews.</p>
                @endforelse
            </article>

            <article class="bpr-guide__section bpr-guide__section--faq" id="faqs">
                <h2 class="bpr-guide__title">FAQs</h2>
                <div class="bpr-faq">
                    @foreach($guide['faqs'] ?? [] as $index => $faq)
                        <div class="bpr-faq__item">
                            <button type="button"
                                    class="bpr-faq__question"
                                    aria-expanded="false"
                                    aria-controls="bpr-faq-answer-{{ $index }}"
                                    id="bpr-faq-question-{{ $index }}">
                                <span>{{ $faq['question'] }}</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                                </svg>
                            </button>
                            <div class="bpr-faq__answer"
                                 id="bpr-faq-answer-{{ $index }}"
                                 role="region"
                                 aria-labelledby="bpr-faq-question-{{ $index }}"
                                 hidden>
                                <p>{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
</section>
