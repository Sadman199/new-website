@php
    $posts = $posts ?? collect();
    $activeTab = $activeTab ?? 'all';
    $activeTabName = $activeTabName ?? 'All';
    $isFiltered = $activeTab !== 'all';
@endphp

@if(method_exists($posts, 'count') ? $posts->count() : count($posts))
    <div class="bli-grid">
        @foreach($posts as $index => $post)
            @include('front.blog.partials.article_card', [
                'post' => $post,
                'index' => $index + (method_exists($posts, 'firstItem') ? (int) $posts->firstItem() : 1),
            ])
        @endforeach
    </div>

    @if(method_exists($posts, 'hasPages') && $posts->hasPages())
        <div class="bli-pagination">
            {{ $posts->links('pagination.brokerscourt') }}
        </div>
    @endif
@else
    <div class="bli-empty">
        <p>
            @if($isFiltered)
                No articles in {{ $activeTabName }} yet.
            @else
                No articles have been published yet.
            @endif
        </p>
        @if($isFiltered)
            <a href="{{ route('blog') }}" class="bli-btn bli-btn--ghost" data-blog-tab="all">View all articles</a>
        @endif
    </div>
@endif
