@props(['broker', 'rank' => null])

<a href="{{ route('broker_detail', $broker->slug) }}" class="bc-tile">
    @if($rank)
        <span class="bc-tile__rank">{{ $rank }}</span>
    @endif
    <div class="bc-tile__logo">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="{{ $broker->name }}" loading="lazy">
        @else
            <span>{{ strtoupper(substr($broker->name, 0, 1)) }}</span>
        @endif
    </div>
    <div class="bc-tile__body">
        <h3 class="bc-tile__name">{{ $broker->name }}</h3>
        <div class="bc-tile__meta">
            @if($broker->country)
                <span>{{ $broker->country }}</span>
            @endif
            @if($broker->minimum_deposit !== null)
                <span>Min ${{ number_format((float) $broker->minimum_deposit, 0) }}</span>
            @endif
        </div>
        @if($broker->isRegulated())
            <span class="bc-tile__badge">Regulated</span>
        @endif
    </div>
    <div class="bc-tile__score">
        <strong>{{ number_format($broker->rating, 1) }}</strong>
        <span>/10</span>
    </div>
</a>
