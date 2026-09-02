@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Blog overview')

@php
    $thumb = $post->photoUrl();
    $status = $post->status ?: 'published';
@endphp

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'blogs'])
        <header class="ab-header">
            <div class="ab-identity">
                <span class="ab-logo ab-logo--lg">
                    @if($thumb)
                        <img src="{{ $thumb }}" alt="">
                    @else
                        <span>{{ strtoupper(substr($post->post_title ?: 'P', 0, 1)) }}</span>
                    @endif
                </span>
                <div>
                    <p class="ab-header__eyebrow">Blog overview</p>
                    <h1 class="ab-header__title">{{ $post->post_title }}</h1>
                    <p class="ab-header__sub">{{ $post->slug }} · {{ $post->contentTypeLabel() }}</p>
                    <div class="ab-pills">
                        <span class="ab-pill">{{ $post->statusLabel() }}</span>
                        @if($post->is_featured || $post->featured_blog || $post->featured_homepage)
                            <span class="ab-pill ab-pill--warn">Featured</span>
                        @endif
                        @if($post->is_breaking)
                            <span class="ab-pill ab-pill--danger">Breaking</span>
                        @endif
                        @if($post->reading_time)
                            <span class="ab-pill">{{ $post->reading_time }} min read</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ab-header__actions">
                @if($post->publicUrl() && $post->isPubliclyVisible())
                    <a href="{{ $post->publicUrl() }}" class="ab-btn ab-btn--ghost" target="_blank" rel="noopener">View on site</a>
                @endif
                <a href="{{ route('admin_post_edit', $post->id) }}" class="ab-btn ab-btn--primary">Edit</a>
                <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost">All blogs</a>
            </div>
        </header>

        @if($thumb)
            <figure class="ab-hero-media">
                <img src="{{ $thumb }}" alt="{{ $post->image_alt ?: $post->post_title }}">
                @if($post->image_caption)
                    <figcaption>{{ $post->image_caption }}</figcaption>
                @endif
            </figure>
        @endif

        <dl class="ab-dl">
            <div>
                <dt>Slug</dt>
                <dd>{{ $post->slug ?: '—' }}</dd>
            </div>
            <div>
                <dt>Category</dt>
                <dd>{{ optional(optional($post->rSubCategory)->rCategory)->category_name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Subcategory</dt>
                <dd>{{ optional($post->rSubCategory)->sub_category_name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Content type</dt>
                <dd>{{ $post->contentTypeLabel() }}</dd>
            </div>
            <div>
                <dt>Language</dt>
                <dd>{{ optional($post->rLanguage)->name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Author</dt>
                <dd>{{ $post->author_name }}</dd>
            </div>
            <div>
                <dt>Written by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'written')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Reviewed by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'edited')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Fact checked by</dt>
                <dd>{{ collect($credits)->firstWhere('role', 'fact_checked')['name'] ?? '—' }}</dd>
            </div>
            <div>
                <dt>Published</dt>
                <dd>{{ optional($post->publish_at ?: $post->created_at)->format('M j, Y H:i') }}</dd>
            </div>
            <div>
                <dt>Updated</dt>
                <dd>{{ $post->updated_at?->format('M j, Y H:i') }}</dd>
            </div>
            <div>
                <dt>Views</dt>
                <dd>{{ number_format((int) $post->visitors) }}</dd>
            </div>
        </dl>

        @if($post->excerpt)
            <section class="ab-section">
                <div class="ab-section__head">
                    <h2>Excerpt</h2>
                </div>
                <div class="ab-section__body">
                    <p>{{ $post->excerpt }}</p>
                </div>
            </section>
        @endif

        <section class="ab-section">
            <div class="ab-section__head">
                <h2>Content</h2>
            </div>
            <div class="ab-section__body rich-text">
                {!! $post->post_detail !!}
            </div>
        </section>

        <div class="row">
            <div class="col-md-6">
                <section class="ab-section">
                    <div class="ab-section__head"><h2>Tags</h2></div>
                    <div class="ab-section__body">
                        @forelse($post->tags as $tag)
                            <span class="ab-pill">{{ $tag->tag_name }}</span>
                        @empty
                            <p class="text-muted mb-0">No tags</p>
                        @endforelse
                    </div>
                </section>
            </div>
            <div class="col-md-6">
                <section class="ab-section">
                    <div class="ab-section__head"><h2>Related brokers</h2></div>
                    <div class="ab-section__body">
                        @forelse($post->brokers as $broker)
                            <span class="ab-pill">{{ $broker->name }}</span>
                        @empty
                            <p class="text-muted mb-0">None attached</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <section class="ab-section">
            <div class="ab-section__head"><h2>SEO</h2></div>
            <div class="ab-section__body">
                <dl class="ab-dl">
                    <div><dt>Meta title</dt><dd>{{ $post->meta_title ?: '—' }}</dd></div>
                    <div><dt>Focus keyword</dt><dd>{{ $post->focus_keyword ?: '—' }}</dd></div>
                    <div><dt>Canonical</dt><dd>{{ $post->canonical_url ?: 'auto' }}</dd></div>
                    <div><dt>Robots</dt><dd>{{ $post->robotsDirective() }}</dd></div>
                    <div><dt>Schema</dt><dd>{{ $post->schema_type ?: 'Article' }}</dd></div>
                    <div><dt>Keywords</dt><dd>{{ $post->meta_keywords ?: '—' }}</dd></div>
                </dl>
                @if($post->meta_description)
                    <p class="mt-3 mb-0">{{ $post->meta_description }}</p>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
