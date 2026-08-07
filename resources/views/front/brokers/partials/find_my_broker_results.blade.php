@php
    $sort = $filters['sort'] ?? 'highest_rated';
@endphp

<div class="fmb-toolbar">
    <div>
        <p class="fmb-toolbar__label">Showing</p>
        <p class="fmb-toolbar__count">
            <span id="fmb-count">{{ number_format($total ?? $brokers->total()) }}</span>
            <span>brokers</span>
        </p>
    </div>
    <div class="fmb-toolbar__sort">
        <label for="fmb-sort" class="fmb-toolbar__sort-label">Sort by</label>
        <select id="fmb-sort" class="fmb-toolbar__sort-select" data-fmb-sort-select>
            @foreach($catalogs['sort'] as $value => $label)
                <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if(!empty($activeChips))
        <div class="fmb-chips" id="fmb-active-chips">
            @foreach($activeChips as $chip)
                <span class="fmb-chip" data-chip-key="{{ $chip['key'] }}" data-chip-value="{{ $chip['value'] }}">
                    {{ $chip['label'] }}
                    <button type="button" class="fmb-chip-remove" aria-label="Remove {{ $chip['label'] }}">&times;</button>
                </span>
            @endforeach
            <button type="button" class="fmb-chips__clear fmb-reset">Clear all</button>
        </div>
    @endif
</div>

@if($brokers->count())
    <div class="fmb-grid" id="fmb-grid">
        @foreach($brokers as $index => $broker)
            @include('front.brokers.partials.find_my_broker_card', [
                'broker' => $broker,
                'rank' => ($brokers->currentPage() - 1) * $brokers->perPage() + $index + 1,
            ])
        @endforeach
    </div>

    <div class="fmb-pagination">
        {{ $brokers->onEachSide(1)->links() }}
    </div>
@else
    <div class="fmb-empty">
        <div class="fmb-empty__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="fmb-empty__title">No brokers match these filters</h3>
        <p class="fmb-empty__text">Try removing some filters or pick a popular search above to explore more options.</p>
        <button type="button" class="fmb-btn fmb-btn--primary fmb-reset">Reset filters</button>
    </div>
@endif
