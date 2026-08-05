@foreach($account_options as $accountOption)
    @if($accountOption->exclusive_offers)
    <section class="bc-section" id="accounttypes">
        <div class="bc-section__head">
            <h2 class="bc-section__title">{{ $accountOption->account_type }} — Exclusive Offers</h2>
        </div>
        <div class="bc-section__body overflow-x-auto exclusive-offers">
            {!! html_entity_decode($accountOption->exclusive_offers) !!}
        </div>
    </section>
    @endif

    <section class="bc-section" id="featuresconditions">
        <div class="bc-section__head">
            <h2 class="bc-section__title">Trading Conditions</h2>
            <p class="bc-section__desc">{{ $accountOption->account_type }} account features</p>
        </div>
        <div class="bc-section__body">
            <div class="bc-data-grid bc-data-grid--3">
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="bc-data-row__label">Max Leverage</div>
                        <div class="bc-data-row__value">{{ $accountOption->leverage_label ?? '—' }}</div>
                        @if($accountOption->margin_call_level || $accountOption->stop_out_level)
                            <div class="text-xs text-gray-500 mt-1">Margin {{ $accountOption->margin_call_level ?? '—' }}% · Stop out {{ $accountOption->stop_out_level ?? '—' }}%</div>
                        @endif
                    </div>
                </div>
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <div class="bc-data-row__label">Risk & Protection</div>
                        <div class="bc-data-row__value text-sm">
                            @if($broker->negative_balance_protection)
                                Negative balance protection<br>
                            @endif
                            @if($broker->investor_protection)
                                Investor protection<br>
                            @endif
                            @if($accountOption->hedging_allowed)
                                Hedging allowed<br>
                            @endif
                            @if($accountOption->max_open_positions)
                                Max {{ $accountOption->max_open_positions }} positions
                            @endif
                            @if(! $broker->negative_balance_protection && ! $broker->investor_protection && ! $accountOption->hedging_allowed)
                                See safety section
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bc-data-row">
                    <div class="bc-data-row__icon"><i class="fas fa-info-circle"></i></div>
                    <div>
                        <div class="bc-data-row__label">Special Conditions</div>
                        <div class="bc-data-row__value">{{ $accountOption->special_conditions ? strip_tags($accountOption->special_conditions) : 'Standard conditions apply' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endforeach
