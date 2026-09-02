@extends('admin.layout.app')
@include('admin.partials._ab_assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')
@section('heading', 'View Author')

@section('main_content')
<div class="ab-page ab-page--hub">
    <div class="ab-wrap">
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    <img src="{{ $author->photoUrl() }}" alt="">
                </span>
                <div>
                    <p class="ab-header__eyebrow">Author overview</p>
                    <h1 class="ab-header__title">{{ $author->name }}</h1>
                    <p class="ab-header__sub">{{ $author->email }}</p>
                    <div class="ab-pills">
                        @if($author->can_write)<span class="ab-pill ab-pill--ok">Written</span>@endif
                        @if($author->can_edit)<span class="ab-pill">Edited</span>@endif
                        @if($author->can_fact_check)<span class="ab-pill ab-pill--warn">Fact-Checked</span>@endif
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                <a href="{{ $author->profileUrl() }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">Open live</a>
                <a href="{{ route('admin_author_edit', $author->id) }}" class="ab-btn ab-btn--primary">Edit</a>
                <a href="{{ route('admin_author_show') }}" class="ab-btn ab-btn--ghost">All authors</a>
            </div>
        </header>

        <dl class="ab-dl">
            <div>
                <dt>Email</dt>
                <dd>{{ $author->email }}</dd>
            </div>
            <div>
                <dt>Written posts</dt>
                <dd>{{ number_format($author->written_posts_count ?? 0) }}</dd>
            </div>
            <div>
                <dt>Edited posts</dt>
                <dd>{{ number_format($author->edited_posts_count ?? 0) }}</dd>
            </div>
            <div>
                <dt>Fact-checked posts</dt>
                <dd>{{ number_format($author->fact_checked_posts_count ?? 0) }}</dd>
            </div>
        </dl>

        @if($author->bio)
            <section class="ab-section">
                <div class="ab-section__head"><h2>Bio</h2></div>
                <div class="ab-section__body"><p class="mb-0">{{ $author->bio }}</p></div>
            </section>
        @endif

        @if($author->socialLinks())
            <section class="ab-section">
                <div class="ab-section__head"><h2>Social</h2></div>
                <div class="ab-section__body">
                    <div class="ab-pills">
                        @foreach($author->socialLinks() as $link)
                            <a class="ab-pill" href="{{ $link['url'] }}" target="_blank" rel="noopener">{{ $link['platform'] }}</a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <div class="ab-save">
            <a href="{{ route('admin_author_edit', $author->id) }}" class="ab-btn ab-btn--primary">Edit this author</a>
            <form action="{{ route('admin_author_delete', $author->id) }}" method="POST" data-ab-delete data-ab-name="{{ $author->name }}" data-ab-warn="Editorial credits on posts will be cleared." data-ab-confirm="Delete author">
                @csrf
                @method('DELETE')
                <button class="ab-btn ab-btn--danger" type="submit">Delete author</button>
            </form>
        </div>
    </div>
</div>
@endsection
