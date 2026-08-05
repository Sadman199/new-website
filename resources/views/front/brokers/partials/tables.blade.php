@php
    $firstAccount = $broker->accountOptions->first();
    $regulations = $broker->regulationList();
    $platformList = $broker->platformList();
    $accountTypesList = is_array($broker->account_types) && count($broker->account_types)
        ? $broker->account_types
        : $broker->accountOptions->pluck('account_type')->filter()->unique()->values()->all();
    $spreadsDisplay = $broker->spreads
        ?: optional($firstAccount)->spread_label
        ?: (optional($firstAccount)->spread_value ? optional($firstAccount)->spread_value . ' pips' : null);
    $commissionDisplay = $broker->commission
        ?: optional($firstAccount)->commission_display;
@endphp

<section class="bc-section" id="brokeroverview">
    <div class="bc-section__head">
        <h2 class="bc-section__title">{{ $broker->name }} Overview</h2>
        <p class="bc-section__desc">Fees, platforms, deposits, and trading conditions at a glance</p>
    </div>
    <div class="bc-section__body">
        <div class="bc-data-grid bc-data-grid--2">
            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <div class="bc-data-row__label">Regulation</div>
                    <div class="bc-data-row__value">
                        {{ $regulations ? implode(', ', $regulations) : ($broker->investor_protection ? 'Investor protection' : '—') }}
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-globe"></i></div>
                <div>
                    <div class="bc-data-row__label">Languages & Region</div>
                    <div class="bc-data-row__value">{{ strip_tags($broker->languages ?? '—') }}</div>
                    @if($broker->country)
                        <div class="bc-data-row__value text-gray-500 text-sm mt-0.5">{{ strip_tags($broker->country) }}</div>
                    @endif
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-dollar-sign"></i></div>
                <div>
                    <div class="bc-data-row__label">Pricing & Fees</div>
                    <div class="bc-data-row__value">{{ strip_tags($broker->pricing ?? '—') }}</div>
                    @if($commissionDisplay)
                        <div class="text-xs text-gray-500 mt-0.5">Commission: {{ strip_tags($commissionDisplay) }}</div>
                    @endif
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-chart-line"></i></div>
                <div>
                    <div class="bc-data-row__label">Spreads</div>
                    <div class="bc-data-row__value">{{ strip_tags($spreadsDisplay ?? '—') }}</div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-arrow-down"></i></div>
                <div>
                    <div class="bc-data-row__label">Deposit Methods</div>
                    <div class="bc-data-row__value">{{ strip_tags($broker->deposit_methods ?? '—') }}</div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-arrow-up"></i></div>
                <div>
                    <div class="bc-data-row__label">Withdrawal Methods</div>
                    <div class="bc-data-row__value">{{ strip_tags($broker->withdrawal_method ?? '—') }}</div>
                    @if($broker->withdrawal_fee)
                        <div class="text-xs text-gray-500 mt-0.5">Fee: {{ strip_tags($broker->withdrawal_fee) }}</div>
                    @endif
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-desktop"></i></div>
                <div>
                    <div class="bc-data-row__label">Trading Platforms</div>
                    <div class="bc-data-row__value">
                        {{ $platformList ? implode(', ', $platformList) : strip_tags(is_string($broker->platforms) ? $broker->platforms : '—') }}
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <div class="bc-data-row__label">Leverage</div>
                    <div class="bc-data-row__value">
                        {{ strip_tags($broker->leverage ?? optional($firstAccount)->leverage_label ?? '—') }}
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-wallet"></i></div>
                <div>
                    <div class="bc-data-row__label">Account Types</div>
                    <div class="bc-data-row__value">
                        {{ $accountTypesList ? implode(', ', $accountTypesList) : '—' }}
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-coins"></i></div>
                <div>
                    <div class="bc-data-row__label">Min. Deposit</div>
                    <div class="bc-data-row__value">
                        @if($broker->minimum_deposit)
                            ${{ number_format((float) $broker->minimum_deposit, 0) }}
                        @elseif($firstAccount && $firstAccount->min_deposit !== null)
                            ${{ number_format((float) $firstAccount->min_deposit, 0) }}
                        @else
                            —
                        @endif
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-headset"></i></div>
                <div>
                    <div class="bc-data-row__label">Customer Support</div>
                    <div class="bc-data-row__value">{{ strip_tags($broker->customer_support ?? '—') }}</div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-lock"></i></div>
                <div>
                    <div class="bc-data-row__label">Fund Security</div>
                    <div class="bc-data-row__value">
                        {{ $broker->segregation_of_funds ? 'Segregated client funds' : 'No segregation stated' }}
                        @if($broker->investor_protection)
                            · Investor protection
                        @endif
                    </div>
                </div>
            </div>

            <div class="bc-data-row">
                <div class="bc-data-row__icon"><i class="fas fa-server"></i></div>
                <div>
                    <div class="bc-data-row__label">VPS Hosting</div>
                    <div class="bc-data-row__value">{{ $broker->vps_hosting ? 'Available' : 'Not available' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
