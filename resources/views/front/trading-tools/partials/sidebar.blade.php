<aside class="tt-sidebar" aria-label="All trading tools">
    <p class="tt-sidebar__title">All calculators</p>
    <nav class="tt-sidebar__nav">
        @foreach($tools as $item)
            <a href="{{ route('trading.tools.show', ['slug' => $item->route_slug]) }}"
               class="tt-sidebar__link {{ $item->slug === $activeSlug ? 'is-active' : '' }}">
                <i class="{{ $item->icon }}" aria-hidden="true"></i>
                <span>{{ $item->page_title ?? $item->name }}</span>
            </a>
        @endforeach
    </nav>
    <a href="{{ route('trading.tools') }}" class="tt-sidebar__back">
        ← All trading tools
    </a>
</aside>
