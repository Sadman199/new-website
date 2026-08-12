<aside class="br-guide-page-sidebar">
    <div class="br-guide-page-sidebar__broker br-sidebar__inner">
        @if($broker->logo)
            <img src="{{ asset($broker->logo) }}" alt="" class="br-sidebar__logo">
        @endif
        <p class="br-sidebar__name">{{ $broker->name }}</p>
        @if((float) $broker->rating > 0)
            <p class="br-sidebar__score">{{ number_format((float) $broker->rating, 1) }}</p>
            <p class="br-sidebar__score-label">Overall score</p>
        @endif

        @if(!empty($context['live_link']))
            <a href="{{ $context['live_link'] }}" class="br-btn br-btn--primary" target="_blank" rel="noopener noreferrer">
                Visit broker
            </a>
        @endif
        @if(!empty($context['demo_link']) && !empty($context['demo_available']))
            <a href="{{ $context['demo_link'] }}" class="br-btn br-btn--secondary" target="_blank" rel="noopener noreferrer">
                Try demo
            </a>
        @endif
        <a href="{{ route('broker_detail', ['slug' => $reviewSlug]) }}" class="br-sidebar__compare-link">Read full review</a>
        <p class="br-sidebar__risk">Your capital is at risk. CFDs are complex instruments.</p>
    </div>

    @if($publishedGuides->isNotEmpty())
        <nav class="br-guide-page-sidebar__nav" aria-label="Broker guides">
            <p class="br-guide-page-sidebar__nav-title">Guides for {{ $broker->name }}</p>
            <ul class="br-guide-page-sidebar__list">
                @foreach($publishedGuides as $item)
                    <li>
                        <a href="{{ $guideService->publicUrl($item) }}" @class(['is-active' => $item->id === $guide->id])>
                            <span class="br-guide-page-sidebar__list-icon" aria-hidden="true">
                                @if($item->topic?->icon)
                                    <i class="{{ $item->topic->icon }}"></i>
                                @else
                                    <i class="fas fa-book-open"></i>
                                @endif
                            </span>
                            <span class="br-guide-page-sidebar__list-text">{{ $item->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    @endif
</aside>
