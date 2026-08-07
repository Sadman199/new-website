<aside class="bbg-sidebar br-toc-sidebar" aria-label="Page sections">
    <div class="bbg-sidebar__inner">
        <p class="bbg-sidebar__label">On this page</p>
        <nav class="bbg-toc" aria-label="Table of contents">
            <ul class="bbg-toc__list">
                @foreach($reviewToc as $item)
                    <li>
                        <a href="#{{ $item['id'] }}" class="bbg-toc__link">{{ $item['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
