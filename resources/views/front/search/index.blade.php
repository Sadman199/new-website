@extends('front.layout.app')

@section('title', $query !== '' ? 'Search results for "'.$query.'" | BrokersCourt' : 'Search | BrokersCourt')
@section('meta_description', $query !== ''
    ? 'Search results for "'.$query.'" across broker reviews, articles, prop firms, promotions, and more on BrokersCourt.'
    : 'Search BrokersCourt for broker reviews, articles, prop firms, promotions, trading tools, and guides.')
@section('robots', 'noindex, follow')
@section('canonical', route('search'))

@push('head')
    <link rel="stylesheet" href="{{ asset('css/site-search.css') }}?v=2">
@endpush

@section('main_content')
@php
    $buildSearchUrl = function (array $overrides = []) use ($query, $type, $sort) {
        return route('search', array_merge(
            array_filter([
                'q' => $query !== '' ? $query : null,
                'type' => ($type ?? 'all') !== 'all' ? $type : null,
                'sort' => ($sort ?? 'relevance') !== 'relevance' ? $sort : null,
            ]),
            $overrides
        ));
    };

    $highlight = function (?string $text) use ($query) {
        $text = (string) $text;

        if ($query === '' || $text === '') {
            return e($text);
        }

        return preg_replace(
            '/(' . preg_quote($query, '/') . ')/iu',
            '<mark class="ssr-mark">$1</mark>',
            e($text)
        );
    };
@endphp

<div class="ssr-page">
    <header class="ssr-hero">
        <div class="ssr-wrap">
            <nav class="ssr-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Search</span>
            </nav>

            <p class="ssr-hero__eyebrow">Site search</p>
            <h1 class="ssr-hero__title">
                @if($query !== '')
                    Results for <span class="ssr-hero__accent">“{{ $query }}”</span>
                @else
                    Search <span class="ssr-hero__accent">BrokersCourt</span>
                @endif
            </h1>
            <p class="ssr-hero__subtitle">
                Find broker reviews, articles, prop firms, promotions, tools, and guides across the site.
            </p>

            <form class="ssr-search-form" action="{{ route('search') }}" method="GET" role="search">
                <label class="sr-only" for="ssrSearchInput">Search the site</label>
                <input type="search"
                       id="ssrSearchInput"
                       name="q"
                       class="ssr-search-form__input"
                       value="{{ $query }}"
                       placeholder="Search brokers, articles, prop firms, tools…"
                       autocomplete="off"
                       minlength="2"
                       required>
                @if($type !== 'all')
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif
                @if($sort !== 'relevance')
                    <input type="hidden" name="sort" value="{{ $sort }}">
                @endif
                <button type="submit" class="ssr-search-form__btn">Search</button>
            </form>

            @if($query !== '')
                <p class="ssr-hero__meta">
                    @if($total > 0)
                        {{ number_format($total) }} {{ Str::plural('result', $total) }} found
                        @if($type !== 'all' && isset($filters[$type]))
                            in {{ $filters[$type]['label'] }}
                        @endif
                    @else
                        No results found — try a different keyword or browse our hubs below.
                    @endif
                </p>
            @endif
        </div>
    </header>

    <div class="ssr-wrap">
        @if($query !== '' && ($counts['all'] ?? 0) > 0)
            <div class="ssr-toolbar">
                <nav class="ssr-filters" aria-label="Filter results by type">
                    @foreach($filters as $filterKey => $filter)
                        @php
                            $count = $counts[$filterKey] ?? 0;
                            $isActive = $type === $filterKey;
                            $filterUrl = $buildSearchUrl([
                                'type' => $filterKey === 'all' ? null : $filterKey,
                            ]);
                        @endphp
                        <a href="{{ $filterUrl }}"
                           class="ssr-filter{{ $isActive ? ' is-active' : '' }}{{ $count === 0 && $filterKey !== 'all' ? ' is-empty' : '' }}">
                            {{ $filter['label'] }}
                            <span class="ssr-filter__count">{{ $count }}</span>
                        </a>
                    @endforeach
                </nav>

                <form class="ssr-sort" method="GET" action="{{ route('search') }}">
                    <input type="hidden" name="q" value="{{ $query }}">
                    @if($type !== 'all')
                        <input type="hidden" name="type" value="{{ $type }}">
                    @endif
                    <label class="sr-only" for="ssrSortSelect">Sort results</label>
                    <select id="ssrSortSelect" name="sort" class="ssr-sort__select" onchange="this.form.submit()">
                        @foreach($sortOptions as $sortKey => $sortLabel)
                            <option value="{{ $sortKey }}" @selected($sort === $sortKey)>Sort: {{ $sortLabel }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        @if($query === '')
            <section class="ssr-empty" aria-labelledby="ssrStartTitle">
                <h2 class="ssr-empty__title" id="ssrStartTitle">Popular starting points</h2>
                <div class="ssr-hub-grid">
                    <a href="{{ route('broker.reviews.index') }}" class="ssr-hub-card">Broker reviews</a>
                    <a href="{{ route('find_my_broker') }}" class="ssr-hub-card">Find my broker</a>
                    <a href="{{ route('prop_firms.index') }}" class="ssr-hub-card">Prop firms</a>
                    <a href="{{ route('promotions.index') }}" class="ssr-hub-card">Broker promos</a>
                    <a href="{{ route('blog') }}" class="ssr-hub-card">Blog &amp; insights</a>
                    <a href="{{ route('trading.tools') }}" class="ssr-hub-card">Trading tools</a>
                </div>
            </section>
        @elseif($total === 0)
            <section class="ssr-empty" aria-labelledby="ssrEmptyTitle">
                <h2 class="ssr-empty__title" id="ssrEmptyTitle">Nothing matched your search</h2>
                <p class="ssr-empty__text">
                    @if($type !== 'all')
                        No {{ strtolower($filters[$type]['label'] ?? 'results') }} matched “{{ $query }}”.
                        <a href="{{ $buildSearchUrl(['type' => null]) }}" class="ssr-inline-link">Search all content types</a>
                    @else
                        Try a broker name, article topic, prop firm, or tool keyword.
                    @endif
                </p>
                <div class="ssr-hub-grid">
                    <a href="{{ route('broker.reviews.index') }}" class="ssr-hub-card">Browse broker reviews</a>
                    <a href="{{ route('blog') }}" class="ssr-hub-card">Read latest articles</a>
                    <a href="{{ route('prop_firms.index') }}" class="ssr-hub-card">Explore prop firms</a>
                </div>
            </section>
        @else
            <div class="ssr-results">
                @foreach($groups as $group)
                    <section class="ssr-group" aria-labelledby="ssr-group-{{ $group['key'] }}">
                        @if($type === 'all')
                            <div class="ssr-group__head">
                                <h2 class="ssr-group__title" id="ssr-group-{{ $group['key'] }}">{{ $group['label'] }}</h2>
                                <span class="ssr-group__count">{{ count($group['items']) }}</span>
                            </div>
                        @endif

                        <ul class="ssr-list">
                            @foreach($group['items'] as $item)
                                <li>
                                    <a href="{{ $item['url'] }}" class="ssr-item">
                                        <span class="ssr-item__media" aria-hidden="true">
                                            @if(!empty($item['image']))
                                                <img src="{{ $item['image'] }}" alt="">
                                            @else
                                                <span class="ssr-item__placeholder">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        </span>
                                        <span class="ssr-item__body">
                                            <span class="ssr-item__type">{{ $item['type_label'] }}</span>
                                            <span class="ssr-item__title">{!! $highlight($item['title']) !!}</span>
                                            @if(!empty($item['excerpt']))
                                                <span class="ssr-item__excerpt">{!! $highlight(Str::limit($item['excerpt'], 140)) !!}</span>
                                            @endif
                                            @if(!empty($item['meta']))
                                                <span class="ssr-item__meta">{{ $item['meta'] }}</span>
                                            @endif
                                        </span>
                                        <span class="ssr-item__arrow" aria-hidden="true">→</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
