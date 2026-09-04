@if(! empty($showBrokerCosts) && ($brokerCostCards ?? []) !== [])
    <section class="calc-costs" aria-labelledby="calc-costs-title">
        <div class="calc-related__head">
            <h2 class="calc-related__title" id="calc-costs-title">
                {{ ($toolKey ?? '') === 'cost' ? 'Compare trading costs across brokers' : 'Looking for a broker with competitive trading costs?' }}
            </h2>
            <p class="calc-related__lead">
                Spread, commission, minimum deposit, and platforms come from each broker’s BrokersCourt profile.
                Numeric spread estimates appear only when a pip figure can be parsed. Other missing values stay unavailable.
            </p>
        </div>
        <div class="calc-cost-table-wrap">
            <table class="calc-cost-table">
                <thead>
                    <tr>
                        <th>Broker</th>
                        <th>Rating</th>
                        <th>Spread</th>
                        <th>Commission</th>
                        <th>Min. deposit</th>
                        <th>Platforms</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brokerCostCards as $card)
                        <tr>
                            <td>
                                <a href="{{ $card['review_url'] }}" class="calc-cost-table__broker">
                                    @if($card['logo'])
                                        <img src="{{ $card['logo'] }}" alt="" width="28" height="28" loading="lazy">
                                    @endif
                                    <span>{{ $card['name'] }}</span>
                                </a>
                            </td>
                            <td>{{ $card['rating'] !== null ? number_format((float) $card['rating'], 1) : '—' }}</td>
                            <td>
                                {{ $card['spreads'] }}
                                @if(empty($card['spread_known']))
                                    <small class="calc-unavailable">Numeric spread unavailable</small>
                                @endif
                            </td>
                            <td>
                                @if($card['commission'])
                                    {{ $card['commission'] }}
                                @else
                                    <span class="calc-unavailable">Unavailable</span>
                                @endif
                            </td>
                            <td>{{ $card['minimum_deposit'] }}</td>
                            <td>{{ $card['platforms'] }}</td>
                            <td>
                                <a href="{{ $card['review_url'] }}" class="calc-cost-table__cta">Review</a>
                                <a href="{{ $card['compare_url'] }}" class="calc-cost-table__cta">Compare</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="calc-costs__mobile">
            @foreach($brokerCostCards as $card)
                <article class="calc-cost-card">
                    <a href="{{ $card['review_url'] }}" class="calc-cost-table__broker">
                        @if($card['logo'])
                            <img src="{{ $card['logo'] }}" alt="" width="28" height="28" loading="lazy">
                        @endif
                        <span>{{ $card['name'] }}</span>
                    </a>
                    <dl class="calc-cost-card__facts">
                        <div><dt>Rating</dt><dd>{{ $card['rating'] !== null ? number_format((float) $card['rating'], 1) : '—' }}</dd></div>
                        <div><dt>Spread</dt><dd>{{ $card['spreads'] }}</dd></div>
                        <div><dt>Commission</dt><dd>{{ $card['commission'] ?: 'Unavailable' }}</dd></div>
                        <div><dt>Min. deposit</dt><dd>{{ $card['minimum_deposit'] }}</dd></div>
                        <div><dt>Platforms</dt><dd>{{ $card['platforms'] }}</dd></div>
                    </dl>
                    <div class="calc-cost-card__actions">
                        <a href="{{ $card['review_url'] }}" class="bc-btn bc-btn--primary">Read review</a>
                        <a href="{{ $card['compare_url'] }}" class="bc-btn bc-btn--ghost">Compare</a>
                    </div>
                </article>
            @endforeach
        </div>
        <p class="calc-costs__links">
            <a href="{{ route('broker.comparison') }}">Compare brokers with competitive spreads</a>
            ·
            <a href="{{ route('broker.alternatives.index') }}">Browse broker alternatives</a>
        </p>
    </section>
@endif
