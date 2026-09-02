{{--
    Comparison matrix using the same facts as the listings, so the table
    always fills the page and never collapses to one or two sparse columns.
--}}
@php
    $compareFields = \App\Support\BestBrokerGuideMetrics::compareFields();
@endphp

@if($compareFields !== [] && ! empty($guidePage['entries']))
<section class="bgx-section" id="compare">
    <header class="bgx-section__head">
        <h2 class="bgx-h2">{{ $guidePage['guide']['compare_title'] }}</h2>
        <p class="bgx-prose">Support, spreads, commission, swap-free, and markets — the same facts as the listings.</p>
    </header>

    <div class="bgx-table-scroll">
        <table class="bgx-table bgx-table--compare">
            <thead>
                <tr>
                    <th scope="col" class="bgx-table__broker-col">Broker</th>
                    @foreach($compareFields as $field)
                        <th scope="col">{{ $field['label'] }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($guidePage['entries'] as $entry)
                    <tr>
                        <th scope="row" class="bgx-table__broker-col">
                            <a href="#broker-{{ $entry['rank'] }}" class="bgx-table__broker">
                                <span class="bgx-table__rank">{{ str_pad((string) $entry['rank'], 2, '0', STR_PAD_LEFT) }}</span>
                                @if($entry['logo_url'])
                                    <img src="{{ $entry['logo_url'] }}" alt="" loading="lazy" decoding="async">
                                @endif
                                <span class="bgx-table__broker-text">
                                    <strong>{{ $entry['name'] }}</strong>
                                </span>
                            </a>
                        </th>

                        @foreach($compareFields as $field)
                            @php
                                $fact = $entry['facts'][$field['key']] ?? ['known' => false];
                                $isBest = ($guidePage['best_in_class'][$field['key']]['entry_id'] ?? null) === $entry['id'];
                            @endphp
                            <td class="@if($isBest) is-best @endif @if(empty($fact['known'])) is-unknown @endif"
                                data-metric="{{ $field['key'] }}">
                                @if(! empty($fact['known']))
                                    @if(! empty($field['chips']) && ! empty($fact['items']))
                                        <span class="bgx-pills">
                                            @foreach(array_slice($fact['items'], 0, 3) as $item)
                                                <span class="bgx-pill">{{ $item }}</span>
                                            @endforeach
                                            @if(count($fact['items']) > 3)
                                                <span class="bgx-pill bgx-pill--more">+{{ count($fact['items']) - 3 }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="bgx-table__value">{{ $fact['value'] }}</span>
                                    @endif

                                    @if($isBest)
                                        <span class="bgx-best" title="Best on this list"><i class="fas fa-star" aria-hidden="true"></i> Best</span>
                                    @endif
                                @else
                                    <span class="bgx-table__unknown">Not disclosed</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif
