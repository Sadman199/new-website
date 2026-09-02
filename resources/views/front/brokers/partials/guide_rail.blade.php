{{--
    Sticky section rail. Replaces the old vertical sidebar TOC: the same navigation moved
    to the top of the page as a horizontal slider with an animated active indicator,
    scroll arrows, and edge fades. Scrollspy and indicator movement live in best-guide.js.
--}}
@php
    $railGroups = collect($nav)->groupBy('group');

    $railIcon = static function (string $name): string {
        $svg = [
            'fa-trophy' => '<path d="M8 5h8v2.4a4 4 0 0 1-8 0V5z"/><path d="M8 6.2H5.6A2.4 2.4 0 0 0 8 8.6M16 6.2h2.4A2.4 2.4 0 0 1 16 8.6"/><path d="M10 15.5h4M12 9.4v6.1M9 20h6"/>',
            'fa-crown' => '<path d="M4 17.5h16L18.6 8.5 14 12.2 12 6.5l-2 5.7-4.6-3.7L4 17.5z"/><path d="M6.5 20h11"/>',
            'fa-columns' => '<rect x="3.5" y="5" width="7" height="14" rx="1.4"/><rect x="13.5" y="5" width="7" height="14" rx="1.4"/>',
            'fa-sliders-h' => '<path d="M4 7.5h16M4 12h16M4 16.5h16"/><circle cx="9" cy="7.5" r="1.7" fill="currentColor"/><circle cx="15" cy="12" r="1.7" fill="currentColor"/><circle cx="8" cy="16.5" r="1.7" fill="currentColor"/>',
            'fa-book-open' => '<path d="M4.5 6.2A2 2 0 0 1 6.4 4.5H12v15.2H6.6A2.1 2.1 0 0 0 4.5 21.6V6.2z"/><path d="M19.5 6.2A2 2 0 0 0 17.6 4.5H12v15.2h5.4a2.1 2.1 0 0 1 2.1 1.9V6.2z"/>',
            'fa-magic' => '<path d="M12 3.5v3.4M12 17.1v3.4M5.4 6.2l2.4 2.4M16.2 15.4l2.4 2.4M3.5 12h3.4M17.1 12h3.4M5.4 17.8 7.8 15.4M16.2 8.6l2.4-2.4"/>',
            'fa-flask' => '<path d="M9 3.5h6M10.2 3.5v5.6L5.6 18a2 2 0 0 0 1.7 3h9.4a2 2 0 0 0 1.7-3l-4.6-8.9V3.5"/>',
        ][$name] ?? '';

        if ($svg === '') {
            return '';
        }

        return '<svg class="bgx-rail__ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$svg.'</svg>';
    };
@endphp

{{-- Sits in normal flow just above the sticky rail so the observer can tell when it pins. --}}
<div class="bgx-rail__sentinel" data-bgx-rail-sentinel aria-hidden="true"></div>

<div class="bgx-rail" data-bgx-rail>
    <div class="bgx-rail__bar">
        <div class="bgx-shell bgx-rail__shell">
            <button type="button" class="bgx-rail__arrow bgx-rail__arrow--prev" data-bgx-rail-prev aria-label="Scroll sections left" tabindex="-1">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>

            <div class="bgx-rail__viewport" data-bgx-rail-viewport>
                <nav class="bgx-rail__track" data-bgx-rail-track aria-label="Page sections">
                    <span class="bgx-rail__indicator" data-bgx-rail-indicator aria-hidden="true"></span>

                    @foreach($railGroups as $groupLabel => $items)
                        @if(! $loop->first)
                            <span class="bgx-rail__divider" aria-hidden="true"></span>
                        @endif

                        <span class="bgx-rail__group" aria-hidden="true">{{ $groupLabel }}</span>

                        @foreach($items as $item)
                            <a href="#{{ $item['id'] }}"
                               class="bgx-rail__chip @if(!empty($item['rank'])) bgx-rail__chip--broker @endif"
                               data-bgx-rail-chip
                               data-bgx-target="{{ $item['id'] }}">
                                @if(!empty($item['rank']))
                                    <span class="bgx-rail__rank">{{ $item['rank'] }}</span>
                                    @if(!empty($item['logo']))
                                        <img src="{{ $item['logo'] }}" alt="" loading="lazy" decoding="async">
                                    @endif
                                @elseif(!empty($item['icon']))
                                    {!! $railIcon($item['icon']) !!}
                                @endif

                                <span class="bgx-rail__label">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @endforeach
                </nav>
            </div>

            <button type="button" class="bgx-rail__arrow bgx-rail__arrow--next" data-bgx-rail-next aria-label="Scroll sections right">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>
