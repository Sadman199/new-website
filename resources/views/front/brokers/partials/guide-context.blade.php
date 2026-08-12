@php
    $profile = $contextProfile ?? '';
@endphp

@if($profile === 'deposits_withdrawals' && (!empty($context['minimum_deposit']) || !empty($context['payment_methods']) || !empty($context['withdrawal_method'])))
    <aside class="br-guide-facts br-guide-facts--highlight" aria-label="Funding quick facts">
        <div class="br-guide-facts__head">
            <span class="br-guide-facts__icon" aria-hidden="true"><i class="fas fa-wallet"></i></span>
            <h2 class="br-guide-facts__title">Quick facts</h2>
        </div>
        <dl class="br-guide-facts__grid">
            @if(!empty($context['minimum_deposit']))
                <div>
                    <dt>Minimum deposit</dt>
                    <dd>${{ number_format((float) $context['minimum_deposit'], 0) }}</dd>
                </div>
            @endif
            @if(!empty($context['payment_methods']))
                <div>
                    <dt>Payment methods</dt>
                    <dd>{{ $context['payment_methods'] }}</dd>
                </div>
            @endif
            @if(!empty($context['withdrawal_method']))
                <div>
                    <dt>Withdrawals</dt>
                    <dd>{{ $context['withdrawal_method'] }}</dd>
                </div>
            @endif
            @if(!empty($context['withdrawal_fee']))
                <div>
                    <dt>Withdrawal fee</dt>
                    <dd>{{ $context['withdrawal_fee'] }}</dd>
                </div>
            @endif
        </dl>
    </aside>
@endif

@if($profile === 'account_types' && !empty($context['account_options']) && $context['account_options']->isNotEmpty())
    <aside class="br-guide-facts br-guide-facts--highlight" aria-label="Account types overview">
        <div class="br-guide-facts__head">
            <span class="br-guide-facts__icon" aria-hidden="true"><i class="fas fa-layer-group"></i></span>
            <h2 class="br-guide-facts__title">Available account types</h2>
        </div>
        <ul class="br-guide-account-list">
            @foreach($context['account_options'] as $option)
                <li>
                    <strong>{{ $option->account_type }}</strong>
                    @if($option->min_deposit !== null)
                        <span>Min. ${{ number_format((float) $option->min_deposit, 0) }}</span>
                    @endif
                    @if($option->swap_free)
                        <span class="br-tag">Swap-free</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </aside>
@endif

@if($profile === 'demo_cta' && !empty($context['demo_link']))
    <aside class="br-guide-facts br-guide-facts--cta" aria-label="Demo account link">
        <a href="{{ $context['demo_link'] }}" class="br-btn br-btn--primary" target="_blank" rel="noopener noreferrer">Open demo with broker</a>
    </aside>
@endif

@if($profile === 'live_cta' && !empty($context['live_link']))
    <aside class="br-guide-facts br-guide-facts--cta" aria-label="Live account link">
        <a href="{{ $context['live_link'] }}" class="br-btn br-btn--primary" target="_blank" rel="noopener noreferrer">Open live account</a>
    </aside>
@endif
