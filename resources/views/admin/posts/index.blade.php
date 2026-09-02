@extends('admin.layout.app')
@include('admin.posts._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Blog')

@php
    $hasFilters = $filters['q'] !== ''
        || $filters['category_id'] !== ''
        || $filters['sub_category_id'] !== ''
        || $filters['author_id'] !== ''
        || $filters['broker_id'] !== ''
        || $filters['content_type'] !== ''
        || $filters['status'] !== ''
        || $filters['language_id'] !== ''
        || $filters['featured'] !== ''
        || $filters['from'] !== ''
        || $filters['to'] !== '';
@endphp

@section('main_content')
<div class="ab-page ab-page--blog">
    <div class="ab-wrap">
        @include('admin.posts._nav', ['active' => 'blogs'])
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Blog</p>
                <h1 class="ab-header__title">Blog</h1>
                <p class="ab-header__sub">Search, filter, and manage every article from one place.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_post_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add Blog
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-newspaper" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Total</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check-circle" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Published</p>
                    <p class="ab-kpi__value">{{ number_format($stats['published']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-clock" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Scheduled</p>
                    <p class="ab-kpi__value">{{ number_format($stats['scheduled']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-star" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Featured</p>
                    <p class="ab-kpi__value">{{ number_format($stats['featured']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-panel">
            <form method="GET" action="{{ route('admin_post_show') }}" class="ab-filters ab-filters--posts">
                <div class="ab-field">
                    <label for="ab-q">Search title</label>
                    <input id="ab-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title or slug">
                </div>
                <div class="ab-field">
                    <label for="ab-category">Category</label>
                    <select id="ab-category" class="ab-select" name="category_id">
                        <option value="">All</option>
                        @foreach($formOptions['categories'] as $category)
                            <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-sub">Subcategory</label>
                    <select id="ab-sub" class="ab-select" name="sub_category_id">
                        <option value="">All</option>
                        @foreach($formOptions['subCategories'] as $sub)
                            <option value="{{ $sub->id }}" @selected((string) $filters['sub_category_id'] === (string) $sub->id)>{{ $sub->sub_category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-author">Author</label>
                    <select id="ab-author" class="ab-select" name="author_id">
                        <option value="">All</option>
                        @foreach($formOptions['authors'] as $author)
                            <option value="{{ $author->id }}" @selected((string) $filters['author_id'] === (string) $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-broker">Broker</label>
                    <select id="ab-broker" class="ab-select" name="broker_id">
                        <option value="">All</option>
                        @foreach($formOptions['brokers'] as $broker)
                            <option value="{{ $broker->id }}" @selected((string) $filters['broker_id'] === (string) $broker->id)>{{ $broker->name }}@if(!empty($broker->is_scam)) (scam flagged)@endif</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-type">Type</label>
                    <select id="ab-type" class="ab-select" name="content_type">
                        <option value="">All</option>
                        @foreach($formOptions['contentTypes'] as $value => $label)
                            <option value="{{ $value }}" @selected($filters['content_type'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-status">Status</label>
                    <select id="ab-status" class="ab-select" name="status">
                        <option value="">All</option>
                        @foreach($formOptions['statuses'] as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-lang">Language</label>
                    <select id="ab-lang" class="ab-select" name="language_id">
                        <option value="">All</option>
                        @foreach($formOptions['languages'] as $language)
                            <option value="{{ $language->id }}" @selected((string) $filters['language_id'] === (string) $language->id)>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-featured">Featured</label>
                    <select id="ab-featured" class="ab-select" name="featured">
                        <option value="">All</option>
                        <option value="1" @selected($filters['featured'] === '1')>Featured only</option>
                    </select>
                </div>
                <div class="ab-field">
                    <label for="ab-from">From</label>
                    <input id="ab-from" class="ab-input" type="date" name="from" value="{{ $filters['from'] }}">
                </div>
                <div class="ab-field">
                    <label for="ab-to">To</label>
                    <input id="ab-to" class="ab-input" type="date" name="to" value="{{ $filters['to'] }}">
                </div>
                <div class="ab-header__actions">
                    <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                    @if($hasFilters)
                        <a href="{{ route('admin_post_show') }}" class="ab-btn ab-btn--ghost">Reset</a>
                    @endif
                </div>
            </form>

            @if($posts->isEmpty())
                <div class="ab-empty">
                    <h3>No blogs match these filters</h3>
                    <p>Try a different search, or write the first article.</p>
                    <a href="{{ route('admin_post_create') }}" class="ab-btn ab-btn--primary">Add Blog</a>
                </div>
            @else
                <div class="ab-table-wrap">
                    <table class="ab-table">
                        <thead>
                            <tr>
                                <th>Blog</th>
                                <th>Classification</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Dates</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $row)
                                @php
                                    $thumb = $row->photoUrl();
                                    $status = $row->status ?: 'published';
                                    $effective = $row->effectiveStatus();
                                    $statusClass = match ($status) {
                                        'published' => 'ab-pill--ok',
                                        'scheduled' => 'ab-pill--warn',
                                        'pending_review' => 'ab-pill--warn',
                                        'archived' => 'ab-pill--danger',
                                        default => '',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="ab-broker">
                                            <span class="ab-logo">
                                                @if($thumb)
                                                    <img src="{{ $thumb }}" alt="">
                                                @else
                                                    <span>{{ strtoupper(substr($row->post_title ?: 'P', 0, 1)) }}</span>
                                                @endif
                                            </span>
                                            <div>
                                                <p class="ab-broker__name">{{ \Illuminate\Support\Str::limit($row->post_title, 72) }}</p>
                                                <p class="ab-broker__meta">
                                                    {{ $row->contentTypeLabel() }}
                                                    @if($row->reading_time) · {{ $row->reading_time }} min @endif
                                                </p>
                                                <div class="ab-pills">
                                                    @foreach($row->brokers->take(3) as $broker)
                                                        <span class="ab-pill">{{ $broker->name }}</span>
                                                    @endforeach
                                                    @if($row->brokers->count() > 3)
                                                        <span class="ab-pill">+{{ $row->brokers->count() - 3 }}</span>
                                                    @endif
                                                    @if($row->is_featured || $row->featured_blog || $row->featured_homepage)
                                                        <span class="ab-pill ab-pill--warn">Featured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ optional(optional($row->rSubCategory)->rCategory)->category_name ?? '—' }}
                                        <div class="ab-broker__meta">{{ optional($row->rSubCategory)->sub_category_name ?? '—' }}</div>
                                    </td>
                                    <td>{{ $row->author_name }}</td>
                                    <td>
                                        <span class="ab-pill {{ $statusClass }}">{{ $row->statusLabel() }}</span>
                                        <div class="ab-broker__meta">{{ optional($row->rLanguage)->name }}</div>
                                    </td>
                                    <td>
                                        <div>{{ optional($row->publish_at ?: $row->created_at)->format('M j, Y') }}</div>
                                        <div class="ab-broker__meta">Updated {{ $row->updated_at?->format('M j, Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="ab-actions">
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_post_view', $row->id) }}">View</a>
                                            <a class="ab-btn ab-btn--ghost ab-btn--sm" href="{{ route('admin_post_edit', $row->id) }}">Edit</a>
                                            <form action="{{ route('admin_post_duplicate', $row->id) }}" method="POST">
                                                @csrf
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Duplicate</button>
                                            </form>
                                            @if($effective !== 'published')
                                                <form action="{{ route('admin_post_status', [$row->id, 'published']) }}" method="POST">
                                                    @csrf
                                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Publish</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin_post_status', [$row->id, 'draft']) }}" method="POST">
                                                    @csrf
                                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Unpublish</button>
                                                </form>
                                            @endif
                                            @if($status !== 'archived')
                                                <form action="{{ route('admin_post_status', [$row->id, 'archived']) }}" method="POST">
                                                    @csrf
                                                    <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Archive</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin_post_delete', $row->id) }}" method="POST" data-ab-delete data-ab-name="{{ $row->post_title }}" data-ab-warn="This cannot be undone." data-ab-confirm="Delete blog">
                                                @csrf
                                                @method('DELETE')
                                                <button class="ab-btn ab-btn--ghost ab-btn--sm" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="ab-pager">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
