{{--
    Weighting model for this list, plus how the top five score on each pillar.
    Bars animate in via IntersectionObserver; widths come from inline custom properties.
--}}
@php
    $scoringEntries = array_slice($guidePage['entries'], 0, 5);
@endphp

<section class="bgx-section" id="scoring">
    <header class="bgx-section__head">
        <h2 class="bgx-h2">{{ $guidePage['guide']['scoring_title'] }}</h2>
        <p class="bgx-prose">Weights are unique to this list. Missing data is dropped, not scored as zero.</p>
    </header>

    <div class="bgx-scoring">
        <div class="bgx-mix" aria-hidden="true">
            @foreach($guidePage['pillars'] as $pillar)
                <span class="bgx-mix__seg" style="width: {{ max(round($pillar['weight'] * 100), 1) }}%; --bgx-i: {{ $loop->index }}"></span>
            @endforeach
        </div>

        <ol class="bgx-mix-legend">
            @foreach($guidePage['pillars'] as $pillar)
                <li class="bgx-mix-legend__item" style="--bgx-i: {{ $loop->index }}">
                    <span class="bgx-mix-legend__pct">{{ round($pillar['weight'] * 100) }}%</span>
                    <span class="bgx-mix-legend__label">{{ $pillar['label'] }}</span>
                    <p class="bgx-mix-legend__desc">{{ $pillar['description'] }}</p>
                </li>
            @endforeach
        </ol>

        <div class="bgx-matrix">
            <h3 class="bgx-h3">How the shortlist scores</h3>

            <div class="bgx-table-scroll">
                <table class="bgx-table bgx-table--matrix">
                    <thead>
                        <tr>
                            <th scope="col" class="bgx-table__broker-col">Broker</th>
                            @foreach($guidePage['pillars'] as $pillar)
                                <th scope="col">{{ $pillar['label'] }}</th>
                            @endforeach
                            <th scope="col">Overall</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scoringEntries as $entry)
                            <tr>
                                <th scope="row" class="bgx-table__broker-col">
                                    <a href="#broker-{{ $entry['rank'] }}" class="bgx-table__broker">
                                        <span class="bgx-table__rank">{{ $entry['rank'] }}</span>
                                        <span class="bgx-table__broker-text"><strong>{{ $entry['name'] }}</strong></span>
                                    </a>
                                </th>

                                @foreach($entry['fit']['breakdown'] as $pillar)
                                    <td>
                                        @if($pillar['known'])
                                            <span class="bgx-meter" style="--bgx-fill: {{ round($pillar['score'] * 10) }}%">
                                                <span class="bgx-meter__bar" data-bgx-bar><span></span></span>
                                                <span class="bgx-meter__value">{{ number_format($pillar['score'], 1) }}</span>
                                            </span>
                                        @else
                                            <span class="bgx-table__unknown">n/a</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td><span class="bgx-overall">{{ number_format($entry['fit']['score'], 1) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
