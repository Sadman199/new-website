@php
    $toc = $guide['toc'] ?? [];
    $typeRows = $guide['type_rows'] ?? [];
@endphp

<section class="bpr-guide" aria-label="Broker promotions guide">
    <article class="bpr-guide__index" id="whats-inside">
        <div class="bpr-guide__index-inner">
            <div class="bpr-guide__index-head">
                <p class="bpr-guide__eyebrow">Promotions guide</p>
                <h2 class="bpr-guide__index-title">What’s Inside</h2>
                <p class="bpr-guide__index-lead">
                    Understand every promotion format, compare live terms side by side, and check broker safety
                    before you claim anything.
                </p>
            </div>

            <dl class="bpr-inside-stats">
                <div>
                    <dt>Active offers</dt>
                    <dd>{{ number_format($stats['total_active'] ?? 0) }}</dd>
                </div>
                <div>
                    <dt>Live categories</dt>
                    <dd>{{ $guide['total_types_live'] ?? 0 }}</dd>
                </div>
                <div>
                    <dt>Brokers listed</dt>
                    <dd>{{ number_format($stats['total_brokers'] ?? 0) }}</dd>
                </div>
            </dl>

            <ol class="bpr-inside-grid">
                @foreach($toc as $item)
                    <li>
                        <a href="#{{ $item['id'] }}" class="bpr-inside-link">
                            <span aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
    </article>

    <div class="bpr-guide__body">
        <article class="bpr-guide__section" id="what-is-forex-promotion">
            <h2 class="bpr-guide__title">What is a Forex Promotion?</h2>
            <p class="bpr-guide__text">
                A forex promotion is a time-limited incentive from a broker — a deposit match, no-deposit credit,
                trading contest, or cashback rebate — designed to attract new clients or reward existing activity.
                These offers can lower your trading costs, but they are never free money: eligibility, turnover rules,
                and withdrawal conditions differ by broker and jurisdiction.
            </p>
            <p class="bpr-guide__text">
                Every listing on this page is linked to a broker profile with regulation data and a dedicated offer
                page, so you can read the full terms before opting in.
            </p>
        </article>

        <article class="bpr-guide__section" id="types-of-forex-promotions">
            <header class="bpr-guide__section-head">
                <h2 class="bpr-guide__title">Types of Forex Promotions</h2>
                <p class="bpr-guide__lead">
                    Each format works differently. The table below is built from live listings, so counts, offers,
                    and terms change as brokers update their campaigns.
                </p>
            </header>

            <div class="bpr-typetable">
                <p class="bpr-typetable__caption" id="bprTypeTableCaption">Promotion Types at a Glance</p>
                <table class="bpr-typetable__table" aria-describedby="bprTypeTableCaption">
                    <thead>
                        <tr>
                            <th scope="col">Promotion type</th>
                            <th scope="col">Live offers</th>
                            <th scope="col">Typical offer</th>
                            <th scope="col">Min. deposit</th>
                            <th scope="col">Wagering / volume</th>
                            <th scope="col">Max credit</th>
                            <th scope="col">Example broker</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($typeRows as $row)
                            <tr class="{{ ($row['count'] ?? 0) > 0 ? 'is-live' : 'is-empty' }}">
                                <th scope="row" data-label="Promotion type">
                                    <a href="{{ $row['url'] }}">{{ $row['name'] }}</a>
                                    <span class="bpr-typetable__desc">{{ $row['description'] }}</span>
                                </th>
                                <td data-label="Live offers">
                                    <span class="bpr-typetable__count">{{ $row['count'] }}</span>
                                    @if(($row['ending_soon'] ?? 0) > 0)
                                        <span class="bpr-typetable__note">{{ $row['ending_soon'] }} ending soon</span>
                                    @endif
                                </td>
                                <td data-label="Typical offer">{{ $row['sample_offer'] ?: '—' }}</td>
                                <td data-label="Min. deposit">{{ $row['min_deposit'] }}</td>
                                <td data-label="Wagering / volume">{{ $row['requirement'] }}</td>
                                <td data-label="Max credit">{{ $row['max_credit'] }}</td>
                                <td data-label="Example broker">
                                    @if($row['sample_broker'] && $row['sample_url'])
                                        <a href="{{ $row['sample_url'] }}">{{ $row['sample_broker'] }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="bpr-guide__section" id="how-to-evaluate">
            <h2 class="bpr-guide__title">How to Evaluate Any Forex Promotion?</h2>
            <ol class="bpr-guide__steps">
                <li><span>01</span> Confirm the broker is regulated in your country and listed on our review pages.</li>
                <li><span>02</span> Read the official terms: minimum deposit, bonus cap, eligible instruments, and expiry.</li>
                <li><span>03</span> Check turnover or volume rules before counting on withdrawing profits or credit.</li>
                <li><span>04</span> Compare the offer against normal spreads, commissions, and swap rates.</li>
                <li><span>05</span> Verify whether it applies to new accounts, existing clients, or specific regions.</li>
                <li><span>06</span> Prefer offers with a published end date over vague “limited time” wording.</li>
            </ol>
        </article>

        <article class="bpr-guide__section" id="common-mistakes">
            <h2 class="bpr-guide__title">Common Mistakes With Forex Promotions</h2>
            <div class="bpr-guide__warn-grid">
                <div class="bpr-guide__warn-item">
                    <strong>Chasing the headline percentage</strong>
                    <p>Large match rates often hide strict withdrawal or volume rules.</p>
                </div>
                <div class="bpr-guide__warn-item">
                    <strong>Over-depositing</strong>
                    <p>Never fund more than your plan just to unlock a higher bonus tier.</p>
                </div>
                <div class="bpr-guide__warn-item">
                    <strong>Ignoring regional limits</strong>
                    <p>Many offers exclude the US, UK, EU, or other jurisdictions entirely.</p>
                </div>
                <div class="bpr-guide__warn-item">
                    <strong>Treating credit as cash</strong>
                    <p>Bonus balances usually cannot be withdrawn directly.</p>
                </div>
                <div class="bpr-guide__warn-item">
                    <strong>Skipping safety checks</strong>
                    <p>A generous offer never replaces regulation and review research.</p>
                </div>
                <div class="bpr-guide__warn-item">
                    <strong>Missing the expiry date</strong>
                    <p>Unused credit and contest entries are forfeited once a campaign closes.</p>
                </div>
            </div>
        </article>

        <article class="bpr-guide__section" id="regulation-and-promotions">
            <h2 class="bpr-guide__title">Regulation and Forex Promotions</h2>
            <div class="bpr-guide__reg-callout">
                <p>
                    Regulators treat promotional incentives differently, and some restrict them outright. A licence
                    means supervision — not that every offer suits your profile. Before claiming, cross-check the
                    regulator on our <a href="{{ route('regulated_brokers') }}">regulated brokers</a> hub, read the
                    full review, and run the <a href="{{ route('broker.scam_checker') }}">scam checker</a> if
                    anything looks off.
                </p>
            </div>
        </article>

        <article class="bpr-guide__section bpr-guide__section--faq" id="faqs">
            <h2 class="bpr-guide__title">Broker Promos FAQ</h2>
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
</section>
