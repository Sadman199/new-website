@extends('front.layout.app')

@section('title', 'Our Editorial Team | BrokersCourt')
@section('meta_description', 'Meet the BrokersCourt editorial team — writers, editors, and fact-checkers behind our broker reviews and financial news.')

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/authors-index.css') }}?v=1">
@endpush

@section('main_content')
<div class="aui-page">
    <div class="aui-wrap">
        <header class="aui-hero">
            <span class="aui-hero__badge">Editorial team</span>
            <h1 class="aui-hero__title">{{ $page['title'] }}</h1>
            <p class="aui-hero__subtitle">{{ $page['subtitle'] }}</p>
        </header>

        <div class="aui-stats">
            <div class="aui-stat">
                <span class="aui-stat__value">{{ number_format($stats['total_authors']) }}</span>
                <span class="aui-stat__label">Team members</span>
            </div>
            <div class="aui-stat">
                <span class="aui-stat__value">{{ number_format($stats['writers']) }}</span>
                <span class="aui-stat__label">Writers</span>
            </div>
            <div class="aui-stat">
                <span class="aui-stat__value">{{ number_format($stats['editors']) }}</span>
                <span class="aui-stat__label">Editors</span>
            </div>
            <div class="aui-stat">
                <span class="aui-stat__value">{{ number_format($stats['fact_checkers']) }}</span>
                <span class="aui-stat__label">Fact-checkers</span>
            </div>
        </div>

        @if($authors === [])
            <div class="aui-empty">
                <p>No authors have been published yet. Check back soon.</p>
            </div>
        @else
            <div class="aui-grid">
                @foreach($authors as $author)
                    <article class="aui-card">
                        <div class="aui-card__media">
                            <img src="{{ $author['photo'] }}" alt="{{ $author['name'] }}" loading="lazy">
                        </div>
                        <div class="aui-card__body">
                            @if($author['roles'] !== [])
                                <div class="aui-card__roles">
                                    @foreach($author['roles'] as $role)
                                        <span class="aui-role">{{ $role }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h2 class="aui-card__name">{{ $author['name'] }}</h2>

                            @if($author['bio'])
                                <p class="aui-card__bio">{{ $author['bio'] }}</p>
                            @endif

                            <dl class="aui-card__stats">
                                @if($author['contributions']['written'] > 0)
                                    <div class="aui-card__stat">
                                        <dt>Written</dt>
                                        <dd>{{ number_format($author['contributions']['written']) }}</dd>
                                    </div>
                                @endif
                                @if($author['contributions']['edited'] > 0)
                                    <div class="aui-card__stat">
                                        <dt>Edited</dt>
                                        <dd>{{ number_format($author['contributions']['edited']) }}</dd>
                                    </div>
                                @endif
                                @if($author['contributions']['fact_checked'] > 0)
                                    <div class="aui-card__stat">
                                        <dt>Fact-checked</dt>
                                        <dd>{{ number_format($author['contributions']['fact_checked']) }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
