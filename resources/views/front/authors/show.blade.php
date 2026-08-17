@extends('front.layout.app')

@section('title', $author['name'] . ' | Editorial Team | BrokersCourt')
@section('meta_description', Str::limit($author['bio'] ?: 'Meet ' . $author['name'] . ' on the BrokersCourt editorial team.', 155))
@section('canonical', $author['profile_url'])

@push('page-styles')
    <link rel="stylesheet" href="{{ asset('css/authors-index.css') }}?v=7">
@endpush

@section('main_content')
<div class="aui-page">
    <header class="aui-hero aui-hero--profile">
        <div class="aui-wrap">
            <nav class="aui-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('authors') }}">Our team</a>
                <span aria-hidden="true">/</span>
                <span>{{ $author['name'] }}</span>
            </nav>

            <div class="aui-profile">
                <div class="aui-profile__photo">
                    <img src="{{ $author['photo'] }}" alt="{{ $author['name'] }}">
                </div>
                <div class="aui-profile__body">
                    <p class="aui-hero__eyebrow">{{ $author['primary_role'] ?? 'Contributor' }}</p>
                    <h1 class="aui-hero__title aui-hero__title--profile">{{ $author['name'] }}</h1>

                    @if($author['roles'] !== [])
                        <div class="aui-card__roles">
                            @foreach($author['roles'] as $role)
                                <span class="aui-role">{{ $role }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="aui-profile__stats">
                        <div class="aui-profile__stat">
                            <strong>{{ $author['contributions']['written'] }}</strong>
                            <span>Written</span>
                        </div>
                        <div class="aui-profile__stat">
                            <strong>{{ $author['contributions']['edited'] }}</strong>
                            <span>Edited</span>
                        </div>
                        <div class="aui-profile__stat">
                            <strong>{{ $author['contributions']['fact_checked'] }}</strong>
                            <span>Fact-checked</span>
                        </div>
                        @if(isset($author['broker_reviews_count']))
                            <div class="aui-profile__stat">
                                <strong>{{ $author['broker_reviews_count'] }}</strong>
                                <span>Broker reviews</span>
                            </div>
                        @endif
                    </div>

                    @if($author['social'] !== [])
                        <div class="aui-profile__social">
                            @foreach($author['social'] as $link)
                                <a href="{{ $link['url'] }}" class="aui-social" target="_blank" rel="noopener noreferrer" aria-label="{{ $author['name'] }} on {{ $link['platform'] }}">
                                    <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="aui-body">
        <div class="aui-wrap">
            @if($author['bio'])
                <section class="aui-bio-box" aria-labelledby="auiBioTitle">
                    <h2 class="aui-bio-box__title" id="auiBioTitle">About {{ $author['name'] }}</h2>
                    <div class="aui-bio-box__content">
                        <p>{{ $author['bio'] }}</p>
                    </div>
                </section>
            @endif

            <div class="aui-layout">
                <div class="aui-main">
                    @if($broker_reviews !== [])
                        <section class="aui-section" aria-labelledby="auiReviewsTitle">
                            <h2 class="aui-section__title" id="auiReviewsTitle">Broker reviews</h2>
                            <div class="aui-review-grid">
                                @foreach($broker_reviews as $review)
                                    <a href="{{ $review['review_url'] }}" class="aui-review-card">
                                        <span class="aui-review-card__logo">
                                            @if($review['logo'])
                                                <img src="{{ $review['logo'] }}" alt="">
                                            @else
                                                {{ strtoupper(substr($review['name'], 0, 1)) }}
                                            @endif
                                        </span>
                                        <span class="aui-review-card__body">
                                            <strong>{{ $review['name'] }}</strong>
                                            @if($review['rating'])
                                                <small>★ {{ $review['rating'] }}</small>
                                            @endif
                                            @if($review['roles'] !== [])
                                                <span class="aui-review-card__roles">{{ implode(' · ', $review['roles']) }}</span>
                                            @endif
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($articles !== [])
                        <section class="aui-section" aria-labelledby="auiArticlesTitle">
                            <h2 class="aui-section__title" id="auiArticlesTitle">Articles &amp; insights</h2>
                            <div class="aui-article-list">
                                @foreach($articles as $article)
                                    @if($article['url'])
                                        <a href="{{ $article['url'] }}" class="aui-article">
                                            @if($article['photo'])
                                                <span class="aui-article__thumb">
                                                    <img src="{{ $article['photo'] }}" alt="" loading="lazy">
                                                </span>
                                            @endif
                                            <span class="aui-article__body">
                                                @if($article['category'])
                                                    <span class="aui-article__cat">{{ $article['category'] }}</span>
                                                @endif
                                                <strong>{{ $article['title'] }}</strong>
                                                @if($article['excerpt'])
                                                    <span>{{ $article['excerpt'] }}</span>
                                                @endif
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($broker_reviews === [] && $articles === [])
                        <div class="aui-empty">
                            <p>No published work is linked to this profile yet.</p>
                        </div>
                    @endif
                </div>

                @if($team !== [])
                    <aside class="aui-aside" aria-label="More team members">
                        <p class="aui-aside__title">More from our team</p>
                        <div class="aui-aside__list">
                            @foreach($team as $member)
                                <a href="{{ $member['profile_url'] }}" class="aui-aside__member">
                                    <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" loading="lazy">
                                    <span>
                                        <strong>{{ $member['name'] }}</strong>
                                        <small>{{ implode(' · ', $member['roles']) ?: 'Contributor' }}</small>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('authors') }}" class="aui-aside__back">← All team members</a>
                    </aside>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
