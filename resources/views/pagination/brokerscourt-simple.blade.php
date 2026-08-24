@if ($paginator->hasPages())
    <nav class="bc-pagination-nav bc-pagination-nav--simple" role="navigation" aria-label="Pagination">
        <div class="bc-pagination-nav__meta">
            Page {{ $paginator->currentPage() }}
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
