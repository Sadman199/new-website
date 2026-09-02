@extends('admin.layout.app')
@include('admin.brokers._assets')

@section('dashboard_page', true)
@section('main_content_class', 'main-content--dashboard')

@section('heading', 'Broker Guide Topics')

@section('main_content')
<div class="ab-page">
    <div class="ab-wrap">
        <header class="ab-header">
            <div>
                <p class="ab-header__eyebrow">Guides</p>
                <h1 class="ab-header__title">Broker guide topics</h1>
                <p class="ab-header__sub">Topics shown on every broker review. New active topics create drafts for all brokers.</p>
            </div>
            <div class="ab-header__actions">
                <a href="{{ route('admin_broker_guide_topics_create') }}" class="ab-btn ab-btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    Add topic
                </a>
            </div>
        </header>

        <div class="ab-kpis">
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-book-open" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Topics</p>
                    <p class="ab-kpi__value">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-check" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Active</p>
                    <p class="ab-kpi__value">{{ number_format($stats['active']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-pause" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Inactive</p>
                    <p class="ab-kpi__value">{{ number_format($stats['inactive']) }}</p>
                </div>
            </div>
            <div class="ab-kpi">
                <span class="ab-kpi__icon"><i class="fas fa-clone" aria-hidden="true"></i></span>
                <div>
                    <p class="ab-kpi__label">Broker drafts</p>
                    <p class="ab-kpi__value">{{ number_format($stats['guides']) }}</p>
                </div>
            </div>
        </div>

        <div class="ab-stack">
            <div class="ab-panel">
                <div class="ab-section__head">
                    <h2>Review page hub</h2>
                    <p>Heading and intro shown on every broker review. Use <code>:broker</code> for the broker name.</p>
                </div>
                <form action="{{ route('admin_broker_guide_topics_hub') }}" method="POST" class="ab-hub">
                    @csrf
                    <div class="ab-field">
                        <label for="hub_title">Hub title</label>
                        <input id="hub_title" class="ab-input" type="text" name="hub_title" value="{{ old('hub_title', $hub['title']) }}" required>
                    </div>
                    <div class="ab-field ab-field--wide">
                        <label for="hub_description">Hub description</label>
                        <textarea id="hub_description" class="ab-input" name="hub_description" rows="2">{{ old('hub_description', $hub['description']) }}</textarea>
                    </div>
                    <div class="ab-header__actions">
                        <button type="submit" class="ab-btn ab-btn--primary">
                            <i class="fas fa-save" aria-hidden="true"></i>
                            Save hub
                        </button>
                    </div>
                </form>
            </div>

            <div class="ab-panel">
                <form method="GET" action="{{ route('admin_broker_guide_topics_index') }}" class="ab-filters">
                    <div class="ab-field">
                        <label for="ab-q">Search</label>
                        <input id="ab-q" class="ab-input" type="search" name="q" value="{{ $filters['q'] }}" placeholder="Title, slug, summary…">
                    </div>
                    <div class="ab-field">
                        <label for="ab-status">Status</label>
                        <select id="ab-status" class="ab-select" name="status">
                            <option value="">All topics</option>
                            <option value="active" @selected($filters['status'] === 'active')>Active</option>
                            <option value="inactive" @selected($filters['status'] === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div></div>
                    <div class="ab-header__actions">
                        <button type="submit" class="ab-btn ab-btn--primary">Filter</button>
                        @if($filters['q'] !== '' || $filters['status'] !== '')
                            <a href="{{ route('admin_broker_guide_topics_index') }}" class="ab-btn ab-btn--ghost">Reset</a>
                        @endif
                    </div>
                </form>

                @if($topics->isEmpty())
                    <div class="ab-empty">
                        <h3>No topics match these filters</h3>
                        <p>Add a topic to start generating broker guide drafts automatically.</p>
                        <a href="{{ route('admin_broker_guide_topics_create') }}" class="ab-btn ab-btn--primary">Add topic</a>
                    </div>
                @else
                    <div class="ab-cards">
                        @foreach($topics as $topic)
                            <article class="ab-card">
                                <div class="ab-card__body">
                                    <p class="ab-header__eyebrow">{{ $topic->slug }} · Order {{ $topic->sort_order }}</p>
                                    <p class="ab-broker__name">
                                        @if($topic->icon)
                                            <i class="{{ $topic->icon }}" aria-hidden="true"></i>
                                        @endif
                                        {{ $topic->title }}
                                    </p>
                                    <p class="ab-broker__meta">{{ $contextProfiles[$topic->context_profile ?? ''] ?? 'No context profile' }} · {{ $topic->guides_count ?? 0 }} brokers</p>
                                    <div class="ab-pills">
                                        @if($topic->is_active)
                                            <span class="ab-pill ab-pill--ok">Active</span>
                                        @else
                                            <span class="ab-pill">Inactive</span>
                                        @endif
                                        @if($topic->requires_swap_free)
                                            <span class="ab-pill">Swap-free</span>
                                        @endif
                                    </div>
                                    @if($topic->default_summary)
                                        <p class="ab-header__sub">{{ \Illuminate\Support\Str::limit($topic->default_summary, 110) }}</p>
                                    @endif
                                </div>
                                <div class="ab-card__foot">
                                    <a href="{{ route('admin_broker_guide_topics_edit', $topic->id) }}" class="ab-btn ab-btn--primary ab-btn--sm">Edit</a>
                                    <form action="{{ route('admin_broker_guide_topics_destroy', $topic->id) }}" method="POST" data-ab-delete data-ab-name="{{ $topic->title }}" data-ab-warn="This topic and all broker guide content for it will be removed." data-ab-confirm="Delete topic">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ab-btn ab-btn--danger ab-btn--sm">Delete</button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
