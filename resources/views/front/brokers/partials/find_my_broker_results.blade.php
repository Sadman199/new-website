@php
    $sort = $filters['sort'] ?? 'highest_rated';
@endphp

<div class="fmb-panel p-4 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-sm text-gray-500">Showing</p>
            <p class="text-lg font-bold text-gray-900">
                <span id="fmb-count">{{ number_format($total ?? $brokers->total()) }}</span>
                <span class="font-medium text-gray-600 text-base">brokers</span>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <label for="fmb-sort" class="text-sm text-gray-500 whitespace-nowrap">Sort by</label>
            <select id="fmb-sort" class="h-10 px-3 text-sm rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-200" data-fmb-sort-select>
                @foreach($catalogs['sort'] as $value => $label)
                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if(!empty($activeChips))
        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100" id="fmb-active-chips">
            @foreach($activeChips as $chip)
                <span class="fmb-chip" data-chip-key="{{ $chip['key'] }}" data-chip-value="{{ $chip['value'] }}">
                    {{ $chip['label'] }}
                    <button type="button" class="fmb-chip-remove" aria-label="Remove {{ $chip['label'] }}">&times;</button>
                </span>
            @endforeach
            <button type="button" class="fmb-reset text-xs font-semibold text-gray-500 hover:text-blue-600 underline ml-1">Clear all</button>
        </div>
    @endif
</div>

@if($brokers->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" id="fmb-grid">
        @foreach($brokers as $broker)
            <x-broker-row :broker="$broker" />
        @endforeach
    </div>

    <div class="mt-8 fmb-pagination">
        {{ $brokers->onEachSide(1)->links() }}
    </div>
@else
    <div class="fmb-panel p-10 text-center">
        <div class="mx-auto w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">No brokers match these filters</h3>
        <p class="text-sm text-gray-500 mb-5">Try removing some filters or reset to see all brokers.</p>
        <button type="button" class="fmb-reset inline-flex items-center px-5 py-2.5 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Reset filters</button>
    </div>
@endif
