@extends('front.layout.app')

@section('title', 'Prop Firms — Compare Funded Trading Programs | BrokersCourt')
@section('meta_description', 'Discover and compare proprietary trading firms. Instant funding, one-step and two-step evaluations, profit splits, drawdown rules, and trust scores.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/prop-firms-design-system.css') }}?v=3">
    <link rel="stylesheet" href="{{ asset('css/prop-firms-index.css') }}?v=4">
@endpush

@section('main_content')
<div class="pf-root pf-index">
    <header class="pf-index__hero">
        <div class="pf-wrap">
            <nav class="pf-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Prop firms</span>
            </nav>

            <p class="pf-eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Funded trader hub
            </p>
            <h1 class="pf-index__title pf-display">Find your next <span>prop firm</span></h1>
            <p class="pf-index__lead">Compare evaluation programs, funding limits, profit splits, and platform features — curated for serious prop traders.</p>

            <div class="pf-stats-bar" aria-label="Prop firm statistics">
                <div class="pf-stats-bar__item">
                    <strong class="pf-stats-bar__value">{{ $stats['total'] }}</strong>
                    <span class="pf-stats-bar__label">Active firms</span>
                </div>
                <div class="pf-stats-bar__item">
                    <strong class="pf-stats-bar__value">{{ $stats['featured'] }}</strong>
                    <span class="pf-stats-bar__label">Featured</span>
                </div>
                <div class="pf-stats-bar__item">
                    <strong class="pf-stats-bar__value">{{ $stats['verified'] }}</strong>
                    <span class="pf-stats-bar__label">Verified</span>
                </div>
                <div class="pf-stats-bar__item">
                    <strong class="pf-stats-bar__value">{{ $stats['categories'] }}</strong>
                    <span class="pf-stats-bar__label">Categories</span>
                </div>
            </div>
        </div>
    </header>

    <div class="pf-wrap pf-index__layout">
        <aside class="pf-sidebar">
            <form method="GET" class="pf-card pf-filter-card">
                <h3>Filter firms</h3>
                @if($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory->slug }}">
                @endif
                <div class="pf-filter-group">
                    <label for="pf-q">Search</label>
                    <input type="search" id="pf-q" name="q" class="pf-input" value="{{ request('q') }}" placeholder="Firm name…">
                </div>
                <div class="pf-filter-group">
                    <label for="pf-attr">Feature</label>
                    <select id="pf-attr" name="attribute" class="pf-select">
                        <option value="">All features</option>
                        @foreach($attributes as $attr)
                            <option value="{{ $attr->slug }}" @selected(request('attribute') === $attr->slug)>{{ $attr->group ? $attr->group.' — ' : '' }}{{ $attr->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pf-filter-group">
                    <label for="pf-sort">Sort by</label>
                    <select id="pf-sort" name="sort" class="pf-select">
                        @foreach(['trust_score' => 'Trust score', 'overall_rating' => 'Overall rating', 'name' => 'Name', 'created_at' => 'Newest'] as $key => $label)
                            <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="pf-filter-check"><input type="checkbox" name="featured" value="1" @checked(request()->boolean('featured'))> Featured only</label>
                <label class="pf-filter-check"><input type="checkbox" name="verified" value="1" @checked(request()->boolean('verified'))> Verified only</label>
                <label class="pf-filter-check"><input type="checkbox" name="instant" value="1" @checked(request()->boolean('instant'))> Instant funding</label>
                <button type="submit" class="pf-btn pf-btn--gold" style="width:100%;margin-top:0.75rem;">Apply filters</button>
            </form>

            <div class="pf-card pf-filter-card">
                <h3>Categories</h3>
                <nav class="pf-cat-list">
                    <a href="{{ route('prop_firms.index', request()->except('page')) }}" class="pf-cat-link @if(!$activeCategory) is-active @endif">
                        All firms <span>{{ $stats['total'] }}</span>
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('prop_firms.category', $cat->slug) }}" class="pf-cat-link @if($activeCategory && $activeCategory->id === $cat->id) is-active @endif">
                            {{ $cat->name }} <span>{{ $cat->prop_firms_count }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            @if($featuredFirms->isNotEmpty())
            <div class="pf-card pf-spotlight">
                <h3 style="margin:0 0 0.75rem;font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--pf-dim);">Top picks</h3>
                @foreach($featuredFirms as $spot)
                    <a href="{{ route('prop_firms.show', $spot->slug) }}" class="pf-spotlight__item">
                        <span class="pf-spotlight__logo">@if($spot->logo)<img src="{{ asset($spot->logo) }}" alt="">@endif</span>
                        <span class="pf-spotlight__name">{{ $spot->name }}</span>
                        @if($spot->trust_score)<span class="pf-score" style="font-size:0.75rem;padding:0.15rem 0.45rem;">{{ number_format($spot->trust_score, 1) }}</span>@endif
                    </a>
                @endforeach
            </div>
            @endif
        </aside>

        <main>
            <div class="pf-toolbar">
                <p class="pf-toolbar__count">
                    @if($activeCategory)
                        Showing <strong>{{ $firms->total() }}</strong> in {{ $activeCategory->name }}
                    @else
                        Showing <strong>{{ $firms->total() }}</strong> prop firms
                    @endif
                </p>
            </div>

            @if($firms->isNotEmpty())
                <div class="pf-grid">
                    @foreach($firms as $firm)
                        @include('front.prop-firms.partials.firm_card', ['firm' => $firm])
                    @endforeach
                </div>
                <div class="pf-pagination">{{ $firms->links() }}</div>
            @else
                <div class="pf-card pf-empty">
                    <p>No prop firms match your filters. <a href="{{ route('prop_firms.index') }}" style="color:var(--pf-gold);">Clear filters</a></p>
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
