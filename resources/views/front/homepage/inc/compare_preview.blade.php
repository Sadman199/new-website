<section class="bc-section bc-section--alt">
    <div class="bc-container">
        <div class="bc-section__head">
            <div>
                <h2 class="bc-section__title">Broker comparison snapshot</h2>
                <p class="bc-section__sub">Side-by-side view of top-rated brokers — regulation, deposit, leverage, and platforms.</p>
            </div>
            <a href="{{ route('broker.comparison') }}" class="bc-btn bc-btn--outline">Open compare tool</a>
        </div>

        <div class="bc-table-wrap">
            <table class="bc-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Broker</th>
                        <th>Score</th>
                        <th>Regulation</th>
                        <th>Min deposit</th>
                        <th>Leverage</th>
                        <th>Platforms</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($all_brokers as $index => $broker)
                        <tr>
                            <td class="bc-table__rank">{{ $index + 1 }}</td>
                            <td class="bc-table__broker">
                                <a href="{{ route('broker_detail', $broker->slug) }}" class="bc-table__name">{{ $broker->name }}</a>
                                <span class="bc-table__country">{{ $broker->country ?: 'Global' }}</span>
                            </td>
                            <td>
                                <span class="bc-table__score">{{ number_format($broker->rating, 1) }}</span>
                            </td>
                            <td class="bc-table__reg">
                                {{ Str::limit(implode(', ', array_slice($broker->regulationList(), 0, 2)), 28) ?: '—' }}
                            </td>
                            <td>${{ number_format((float) ($broker->minimum_deposit ?? 0), 0) }}</td>
                            <td>{{ $broker->leverage ?: '—' }}</td>
                            <td class="bc-table__plat">
                                {{ Str::limit(implode(', ', array_slice($broker->platformList(), 0, 2)), 24) ?: '—' }}
                            </td>
                            <td>
                                <a href="{{ route('broker_detail', $broker->slug) }}" class="bc-table__link">Review</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
