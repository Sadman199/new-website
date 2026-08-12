@php
    $snapshot = $snapshot ?? [];
    $variant = $variant ?? 'hero';
    $isScam = !empty($snapshot['is_scam']);
    $visitUrl = $snapshot['visit_url'] ?? null;
    $demoUrl = $snapshot['demo_url'] ?? null;
    $compareUrl = $snapshot['compare_url'] ?? route('broker.comparison');
    $saveId = $broker->id ?? null;
@endphp

<div class="br-ctas br-ctas--{{ $variant }}">
    @if($isScam)
        <a href="#compare" class="br-btn br-btn--primary">Safer alternatives</a>
        <a href="{{ route('scam_brokers') }}" class="br-btn br-btn--secondary">Scam broker list</a>
        @if($visitUrl)
            <a href="{{ $visitUrl }}"
               target="_blank"
               rel="noopener noreferrer sponsored"
               class="br-btn br-btn--ghost br-btn--warned">
                Visit anyway
            </a>
        @endif
    @else
        @if($visitUrl)
            <a href="{{ $visitUrl }}"
               target="_blank"
               rel="noopener noreferrer sponsored"
               class="br-btn br-btn--primary">
                Visit broker
            </a>
        @endif
        @if($demoUrl)
            <a href="{{ $demoUrl }}"
               target="_blank"
               rel="noopener noreferrer sponsored"
               class="br-btn br-btn--secondary">
                Try demo
            </a>
        @endif
        <a href="{{ $compareUrl }}" class="br-btn br-btn--ghost">Compare</a>
        @if($saveId)
            <button type="button"
                    class="br-btn br-btn--ghost br-save-btn"
                    data-br-save
                    data-broker-id="{{ $saveId }}"
                    aria-pressed="false">
                Save
            </button>
        @endif
    @endif
</div>

<p class="br-disclosure">
    @if($isScam)
        We strongly advise against depositing funds with this broker.
    @else
        We may earn a commission if you visit this broker. That does not affect our rating.
    @endif
    <span>Your capital is at risk. CFDs are complex instruments.</span>
</p>
