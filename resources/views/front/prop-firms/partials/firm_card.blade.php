@php $presenter = \App\Support\PropFirmPresenter::make($firm); @endphp
<a href="{{ route('prop_firms.show', $firm->slug) }}" class="pf-card pf-firm-card">
    <div class="pf-firm-card__head">
        <div class="pf-firm-card__logo">
            @if($firm->logo)
                <img src="{{ asset($firm->logo) }}" alt="{{ $firm->name }}" loading="lazy" decoding="async">
            @endif
        </div>
        <div class="pf-firm-card__meta">
            <h3 class="pf-firm-card__name">{{ $firm->name }}</h3>
            <p class="pf-firm-card__cat">{{ $firm->category?->name ?? 'Prop Firm' }}</p>
        </div>
        @if($firm->trust_score)
            <span class="pf-score">★ {{ number_format($firm->trust_score, 1) }}</span>
        @endif
    </div>

    <div class="pf-firm-card__badges">
        @if($firm->is_featured)<span class="pf-badge pf-badge--gold">Featured</span>@endif
        @if($firm->is_verified)<span class="pf-badge pf-badge--cyan">Verified</span>@endif
        @if($firm->scaling_available)<span class="pf-badge pf-badge--emerald">Scaling</span>@endif
    </div>

    <div class="pf-firm-card__stats">
        <div class="pf-firm-card__stat">
            <small>Max funding</small>
            <strong>{{ $firm->max_funding ?? '—' }}</strong>
        </div>
        <div class="pf-firm-card__stat">
            <small>Profit split</small>
            <strong>{{ $firm->profit_split ? Str::limit($firm->profit_split, 12) : '—' }}</strong>
        </div>
        <div class="pf-firm-card__stat">
            <small>Rating</small>
            <strong>{{ $firm->overall_rating ? number_format($firm->overall_rating, 1) : '—' }}</strong>
        </div>
    </div>

    <div class="pf-firm-card__cta">
        <span>View programs & rules</span>
        <span aria-hidden="true">→</span>
    </div>
</a>
