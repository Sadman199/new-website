@php
    $sectionNavItems = $sectionNavItems ?? ($guidePage['toc'] ?? []);
@endphp

@if(! empty($sectionNavItems))
<nav class="bbg-toc" aria-label="On this page" data-bbg-section-nav>
    <div class="bbg-toc__scroll" data-bbg-nav-scroll>
        @foreach($sectionNavItems as $item)
            <a href="#{{ $item['id'] }}" class="bbg-toc__link" data-bbg-section-link>{{ $item['label'] }}</a>
        @endforeach
    </div>
</nav>
@endif
