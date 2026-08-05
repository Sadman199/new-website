<section class="bc-section" id="account-structures">
    <div class="bc-section__head">
        <h2 class="bc-section__title">Account Structures</h2>
        <p class="bc-section__desc">Compare account types, spreads, and leverage</p>
    </div>
    <div class="bc-section__body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($account_options as $accountOption)
                <div class="bc-account-card">
                    <div class="bc-account-card__head">{{ $accountOption->account_type }}</div>
                    <div class="bc-account-card__body">
                        <div class="flex flex-wrap gap-1 mb-3">
                            <span class="bc-tag">{{ $accountOption->account_currency }}</span>
                            @if($accountOption->execution_model)
                                <span class="bc-tag">{{ $accountOption->execution_model }}</span>
                            @endif
                            @if($accountOption->swap_free)
                                <span class="bc-tag">Swap Free</span>
                            @endif
                            @if($accountOption->bonus_eligibility)
                                <span class="bc-tag">Bonus</span>
                            @endif
                        </div>
                        @if($accountOption->description)
                            <p class="text-sm text-gray-600 mb-3">{{ $accountOption->description }}</p>
                        @endif
                        <div class="bc-account-card__stat"><span>Min deposit</span><strong>{{ $accountOption->min_deposit !== null ? '$' . number_format((float) $accountOption->min_deposit, 0) : '—' }}</strong></div>
                        <div class="bc-account-card__stat"><span>Leverage</span><strong>{{ $accountOption->leverage_label ?? '—' }}</strong></div>
                        <div class="bc-account-card__stat"><span>Spread</span><strong>{{ $accountOption->spread_label ?? '—' }}</strong></div>
                        <div class="bc-account-card__stat"><span>Commission</span><strong>{{ $accountOption->commission_display ?? 'None' }}</strong></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm col-span-2">No account structures listed.</p>
            @endforelse
        </div>
    </div>
</section>
