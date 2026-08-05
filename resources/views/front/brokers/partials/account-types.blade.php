@php
    use App\Support\BrokerReviewPresenter;
@endphp

@if($account_options->isNotEmpty())
<section class="br-section" id="account-types">
    <div class="br-section__head">
        <h2 class="br-section__title">Account Types</h2>
        <p class="br-section__desc">Compare spreads, leverage, and conditions across {{ $broker->name }} accounts</p>
    </div>
    <div class="br-section__body">
        <div class="br-account-grid">
            @foreach($account_options as $index => $accountOption)
                @php
                    $expandRows = BrokerReviewPresenter::accountExpandRows($accountOption);
                    $panelId = 'account-more-' . ($accountOption->slug ?: 'option-' . ($index + 1));
                @endphp
                <article class="br-account-card">
                    <div class="br-account-card__head">{{ $accountOption->account_type }}</div>
                    <div class="br-account-card__body">
                        <div class="br-account-card__tags">
                            @if($accountOption->account_currency)
                                <span class="br-tag">{{ $accountOption->account_currency }}</span>
                            @endif
                            @if($accountOption->execution_model)
                                <span class="br-tag">{{ $accountOption->execution_model }}</span>
                            @endif
                            @if($accountOption->swap_free)
                                <span class="br-tag">Swap-free</span>
                            @endif
                            @if($accountOption->bonus_eligibility)
                                <span class="br-tag">Bonus eligible</span>
                            @endif
                        </div>

                        @if(strip_tags($accountOption->description ?? ''))
                            <p class="br-account-card__intro">{{ Str::limit(strip_tags($accountOption->description), 160) }}</p>
                        @endif

                        <dl class="br-account-card__stats">
                            <div class="br-account-card__stat">
                                <dt>Min. deposit</dt>
                                <dd>{{ $accountOption->min_deposit !== null ? '$' . number_format((float) $accountOption->min_deposit, 0) : '—' }}</dd>
                            </div>
                            <div class="br-account-card__stat">
                                <dt>Leverage</dt>
                                <dd>{{ $accountOption->leverage_label ?? '—' }}</dd>
                            </div>
                            <div class="br-account-card__stat">
                                <dt>Spread</dt>
                                <dd>{{ $accountOption->spread_label ?? '—' }}</dd>
                            </div>
                            <div class="br-account-card__stat">
                                <dt>Commission</dt>
                                <dd>{{ $accountOption->commission_display ?? ($accountOption->commission ?: 'None') }}</dd>
                            </div>
                        </dl>

                        @if(!empty($expandRows))
                            <div class="br-account-card__more" id="{{ $panelId }}" hidden>
                                <dl class="br-dl br-dl--compact">
                                    @foreach($expandRows as $row)
                                        <div class="br-dl__row">
                                            <dt>{{ $row['label'] }}</dt>
                                            <dd>
                                                @if(!empty($row['html']))
                                                    {!! $row['value'] !!}
                                                @else
                                                    {{ $row['value'] }}
                                                @endif
                                            </dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                            <button type="button"
                                    class="br-read-more"
                                    data-br-target="{{ $panelId }}"
                                    aria-expanded="false">
                                <span class="br-read-more__show">Read more about {{ $accountOption->account_type }}</span>
                                <span class="br-read-more__hide" hidden>Show less</span>
                            </button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
