@extends('front.layout.app')

@section('title', 'Our Editorial Team | BrokersCourt')
@section('meta_description', 'Meet the BrokersCourt editorial team — writers, editors, and fact-checkers behind our broker reviews and financial news.')
@section('canonical', route('authors'))

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/authors-index.css') }}?v=7">
@endpush

@section('main_content')
<div class="aui-page">
    <header class="aui-hero">
        <div class="aui-wrap">
            <nav class="aui-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <span>Our team</span>
            </nav>

            <p class="aui-hero__eyebrow">Editorial team</p>
            <h1 class="aui-hero__title">{{ $page['title'] }}</h1>
            <p class="aui-hero__subtitle">{{ $page['subtitle'] }}</p>
        </div>
    </header>

    <div class="aui-body">
        <div class="aui-wrap">
            @if($authors === [])
                <div class="aui-empty">
                    <p>No authors have been published yet. Check back soon.</p>
                </div>
            @else
                <div class="aui-grid">
                    @foreach($authors as $author)
                        <a href="{{ $author['profile_url'] }}" class="aui-card">
                            <span class="aui-card__photo">
                                <img src="{{ $author['photo'] }}" alt="{{ $author['name'] }}" loading="lazy">
                            </span>
                            <span class="aui-card__body">
                                @if($author['roles'] !== [])
                                    <span class="aui-card__roles">
                                        @foreach($author['roles'] as $role)
                                            <span class="aui-role">{{ $role }}</span>
                                        @endforeach
                                    </span>
                                @endif
                                <span class="aui-card__name">{{ $author['name'] }}</span>
                                @if($author['bio'])
                                    <span class="aui-card__bio">{{ Str::limit($author['bio'], 220) }}</span>
                                @endif

                                <span class="aui-card__stats">
                                    <span class="aui-card__stat">
                                        <strong>{{ $author['contributions']['written'] }}</strong>
                                        <span>Written</span>
                                    </span>
                                    <span class="aui-card__stat">
                                        <strong>{{ $author['contributions']['edited'] }}</strong>
                                        <span>Edited</span>
                                    </span>
                                    <span class="aui-card__stat">
                                        <strong>{{ $author['contributions']['fact_checked'] }}</strong>
                                        <span>Fact-checked</span>
                                    </span>
                                </span>

                                <span class="aui-card__link">View profile →</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if(!empty($how_we_work))
                <section class="aui-work" aria-labelledby="auiWorkTitle">
                    <div class="aui-work__head">
                        <p class="aui-work__eyebrow">Editorial process</p>
                        <h2 class="aui-work__title" id="auiWorkTitle">How we work</h2>
                        <p class="aui-work__lead">Every broker review and article follows the same editorial process — research, write, verify, publish.</p>
                    </div>
                    <ol class="aui-work__steps">
                        @foreach($how_we_work as $step)
                            <li class="aui-work__step">
                                <span class="aui-work__step-marker">
                                    <span class="aui-work__step-icon" aria-hidden="true">
                                        <i class="{{ $step['icon'] ?? 'fas fa-check' }}"></i>
                                    </span>
                                    <span class="aui-work__step-num" aria-hidden="true">{{ $step['step'] }}</span>
                                </span>
                                <div class="aui-work__step-body">
                                    <h3 class="aui-work__step-title">{{ $step['title'] }}</h3>
                                    <p class="aui-work__step-text">{{ $step['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            <section class="aui-join" aria-labelledby="auiJoinTitle">
                <div class="aui-join__glow" aria-hidden="true"></div>
                <div class="aui-join__inner">
                    <p class="aui-join__eyebrow">Careers</p>
                    <h2 class="aui-join__title" id="auiJoinTitle">Join our team</h2>
                    <p class="aui-join__text">We're always interested in experienced financial writers, editors, and researchers who care about transparent broker coverage.</p>
                    <a href="{{ route('contact') }}" class="aui-join__btn">
                        Get in touch
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
