@if ($paginator->hasPages())
    <nav class="bc-pagination-nav" role="navigation" aria-label="Pagination">
        <div class="bc-pagination-nav__meta">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </div>

        <ul class="bc-pagination-nav__list">
            @if ($paginator->onFirstPage())
                <li class="is-disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="bc-pagination-nav__link bc-pagination-nav__link--arrow" aria-hidden="true">‹</span>
                </li>
            @else
                <li>
                    <a class="bc-pagination-nav__link bc-pagination-nav__link--arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">‹</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="is-disabled" aria-disabled="true"><span class="bc-pagination-nav__link bc-pagination-nav__link--dots">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="is-active" aria-current="page"><span class="bc-pagination-nav__link">{{ $page }}</span></li>
                        @else
                            <li><a class="bc-pagination-nav__link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a class="bc-pagination-nav__link bc-pagination-nav__link--arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">›</a>
                </li>
            @else
                <li class="is-disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="bc-pagination-nav__link bc-pagination-nav__link--arrow" aria-hidden="true">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
