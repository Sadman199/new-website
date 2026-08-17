@php
    $left = $battle['broker1'];
    $right = $battle['broker2'];
    $rounds = $battle['rounds'] ?? [];
@endphp

<section class="bc-battle-summary" aria-labelledby="bcBattleSummaryTitle">
    <div class="bc-battle-section-head">
        <p class="bc-battle-section-head__eyebrow">Battle summary</p>
        <h2 class="bc-battle-section-head__title" id="bcBattleSummaryTitle">All categories at a glance</h2>
    </div>

    <div class="bc-battle-summary__panel" role="table" aria-label="Battle summary">
        <div class="bc-battle-summary__row bc-battle-summary__row--head" role="row">
            <span role="columnheader">Category</span>
            <span role="columnheader">{{ $left['name'] }}</span>
            <span role="columnheader">Result</span>
            <span role="columnheader">{{ $right['name'] }}</span>
        </div>

        @foreach($rounds as $round)
            <div class="bc-battle-summary__row bc-battle-summary__row--{{ $round['outcome'] }}" role="row">
                <span class="bc-battle-summary__label" role="cell">{{ $round['label'] }}</span>
                <span class="bc-battle-summary__value {{ $round['outcome'] === 'broker1' ? 'is-winner' : '' }}" role="cell" data-broker="{{ $left['name'] }}">
                    {{ $round['left'] }}
                    @if($round['outcome'] === 'broker1')
                        <i class="fas fa-trophy" aria-hidden="true"></i>
                    @endif
                </span>
                <span class="bc-battle-summary__result" role="cell">
                    @if($round['outcome'] === 'broker1')
                        <span class="bc-battle-badge bc-battle-badge--win">{{ $left['name'] }}</span>
                    @elseif($round['outcome'] === 'broker2')
                        <span class="bc-battle-badge bc-battle-badge--win">{{ $right['name'] }}</span>
                    @elseif($round['outcome'] === 'tie')
                        <span class="bc-battle-badge bc-battle-badge--tie">Tie</span>
                    @else
                        <span class="bc-battle-badge bc-battle-badge--muted">Not enough data</span>
                    @endif
                </span>
                <span class="bc-battle-summary__value {{ $round['outcome'] === 'broker2' ? 'is-winner' : '' }}" role="cell" data-broker="{{ $right['name'] }}">
                    {{ $round['right'] }}
                    @if($round['outcome'] === 'broker2')
                        <i class="fas fa-trophy" aria-hidden="true"></i>
                    @endif
                </span>
            </div>
        @endforeach
    </div>
</section>
