@php
    $activeHub = $activeHub ?? null; // checker | list | report
@endphp
<nav class="bc-safety-hub" aria-label="Broker safety tools">
    <p class="bc-safety-hub__label">Safety hub</p>
    <div class="bc-safety-hub__links">
        <a href="{{ route('broker.scam_checker') }}"
           class="bc-safety-hub__link{{ $activeHub === 'checker' ? ' is-active' : '' }}">
            Scam checker
        </a>
        <a href="{{ route('scam_brokers') }}"
           class="bc-safety-hub__link{{ $activeHub === 'list' ? ' is-active' : '' }}">
            Flagged brokers
        </a>
        <a href="{{ route('broker.scam_checker') }}"
           class="bc-safety-hub__link{{ $activeHub === 'report' ? ' is-active' : '' }}">
            Check &amp; report
        </a>
        @auth('web')
            <a href="{{ route('user.profile', ['tab' => 'safety']) }}"
               class="bc-safety-hub__link">
                My reports
            </a>
        @endauth
    </div>
</nav>
