<div class="bc-result-main">
    @foreach($comparison['sections'] as $section)
        <section id="bc-result-{{ $section['id'] }}" class="bc-result-section" data-result-section="{{ $section['id'] }}">
            <header class="bc-result-section__head">
                <h2 class="bc-result-section__title">{{ $section['label'] }}</h2>
            </header>

            <div class="bc-result-table-wrap">
                <table class="bc-result-table">
                    <thead>
                        <tr>
                            <th scope="col" class="bc-result-table__metric">Metric</th>
                            <th scope="col" class="bc-result-table__broker">
                                <div class="bc-result-table__broker-head">
                                    @if($comparison['broker1']['logo'])
                                        <img src="{{ $comparison['broker1']['logo'] }}" alt="">
                                    @endif
                                    <span>{{ $comparison['broker1']['name'] }}</span>
                                </div>
                            </th>
                            <th scope="col" class="bc-result-table__broker">
                                <div class="bc-result-table__broker-head">
                                    @if($comparison['broker2']['logo'])
                                        <img src="{{ $comparison['broker2']['logo'] }}" alt="">
                                    @endif
                                    <span>{{ $comparison['broker2']['name'] }}</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section['rows'] as $row)
                            <tr>
                                <th scope="row" class="bc-result-table__metric">{{ $row['label'] }}</th>
                                <td class="bc-result-table__value {{ $row['winner'] === 'broker1' ? 'is-best' : '' }} {{ $row['left'] === $row['right'] ? 'is-same' : '' }} {{ $row['left'] === 'Yes' ? 'is-yes' : ($row['left'] === 'No' ? 'is-no' : '') }}" data-broker="{{ $comparison['broker1']['name'] }}">
                                    {{ $row['left'] }}
                                    @if($row['winner'] === 'broker1')
                                        <span class="bc-result-table__win" aria-label="Best value"><i class="fas fa-check"></i></span>
                                    @endif
                                </td>
                                <td class="bc-result-table__value {{ $row['winner'] === 'broker2' ? 'is-best' : '' }} {{ $row['left'] === $row['right'] ? 'is-same' : '' }} {{ $row['right'] === 'Yes' ? 'is-yes' : ($row['right'] === 'No' ? 'is-no' : '') }}" data-broker="{{ $comparison['broker2']['name'] }}">
                                    {{ $row['right'] }}
                                    @if($row['winner'] === 'broker2')
                                        <span class="bc-result-table__win" aria-label="Best value"><i class="fas fa-check"></i></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endforeach
</div>
