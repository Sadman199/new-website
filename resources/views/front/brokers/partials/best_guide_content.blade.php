<section class="bbg-scoreboard" id="broker-scores" aria-label="Top broker scores">
    <div class="bbg-scoreboard__head">
        <div>
            <h2 class="bbg-scoreboard__title">Top broker scores</h2>
            <p class="bbg-scoreboard__meta">{{ count($guidePage['entries']) }} brokers ranked · {{ $guidePage['updated_at'] }}</p>
            @if(($guidePage['country_matches'] ?? 0) > 0)
                <p class="bbg-scoreboard__country">
                    <span class="bbg-country-flag" aria-hidden="true">{{ $guidePage['country']['flag'] }}</span>
                    {{ $guidePage['country_matches'] }} {{ Str::plural('broker', $guidePage['country_matches']) }} on this list available in {{ $guidePage['country']['name'] }}
                </p>
            @endif
        </div>
        <div class="bbg-scoreboard__nav">
            <button type="button" class="bbg-scoreboard__btn" data-bbg-carousel-prev aria-label="Previous brokers">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="bbg-scoreboard__btn" data-bbg-carousel-next aria-label="Next brokers">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="bbg-scoreboard__track" data-bbg-carousel-track>
        @foreach($guidePage['entries'] as $entry)
            <a href="#broker-{{ $entry['rank'] }}"
               class="bbg-score-card @if($entry['rank'] === 1) bbg-score-card--featured @endif">
                @if($entry['rank'] === 1)
                    <span class="bbg-score-card__badge">Top pick</span>
                @endif
                @if($entry['in_country'] ?? false)
                    <span class="bbg-score-card__country" title="Available in {{ $guidePage['country']['name'] }}">{{ $guidePage['country']['flag'] }}</span>
                @endif
                <span class="bbg-score-card__rank">#{{ $entry['rank'] }}</span>
                <div class="bbg-score-card__logo">
                    @if($entry['logo_url'])
                        <img src="{{ $entry['logo_url'] }}" alt="{{ $entry['broker']->name }}" loading="lazy" decoding="async">
                    @else
                        <span>{{ Str::substr($entry['broker']->name, 0, 1) }}</span>
                    @endif
                </div>
                <p class="bbg-score-card__name">{{ $entry['broker']->name }}</p>
                <p class="bbg-score-card__metric">{{ $entry['score_label'] }}</p>
                <p class="bbg-score-card__score">{{ number_format($entry['score'], 1) }}</p>
                <p class="bbg-score-card__hint">{{ $entry['one_liner'] ?: $entry['recommended_for'] }}</p>
            </a>
        @endforeach
    </div>
</section>

@if($guidePage['winner'])
    <section class="bbg-spotlight" id="top-pick">
        <div class="bbg-spotlight__badge">Top pick</div>
        <div class="bbg-spotlight__body">
            <div class="bbg-spotlight__brand">
                @if($guidePage['winner']['logo_url'])
                    <img src="{{ $guidePage['winner']['logo_url'] }}" alt="{{ $guidePage['winner']['broker']->name }}" loading="lazy">
                @endif
                <div>
                    <h2 class="bbg-spotlight__title">{{ $guidePage['guide']['spotlight_title'] ?? 'Our top pick' }}</h2>
                    <p class="bbg-spotlight__name">{{ $guidePage['winner']['broker']->name }}</p>
                </div>
            </div>
            <ul class="bbg-spotlight__points">
                @foreach($guidePage['winner']['pros'] as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
            <div class="bbg-spotlight__actions">
                <a href="{{ $guidePage['winner']['visit_url'] }}" class="bbg-btn bbg-btn--primary" target="_blank" rel="noopener nofollow">Visit {{ $guidePage['winner']['broker']->name }}</a>
                <a href="{{ $guidePage['winner']['review_url'] }}" class="bbg-btn bbg-btn--ghost">{{ $guidePage['winner']['broker']->name }} review</a>
            </div>
        </div>
    </section>
@endif

@foreach($guidePage['guide']['sections'] as $section)
    <section class="bbg-section" id="{{ $section['id'] }}">
        <h2 class="bbg-section__title">{{ $section['title'] }}</h2>
        <p class="bbg-section__text">{{ $section['description'] }}</p>

        @if(! empty($section['table']) && isset($guidePage['tables'][$section['table']]))
            @php $table = $guidePage['tables'][$section['table']]; @endphp
            <p class="bbg-table-caption">{{ $section['caption'] ?? '' }}</p>
            <div class="bbg-table-wrap" data-bbg-table>
                <table class="bbg-table">
                    <thead>
                        <tr>
                            <th scope="col">Metric</th>
                            @foreach($guidePage['entries'] as $entry)
                                <th scope="col">
                                    <a href="{{ $entry['review_url'] }}" class="bbg-table-broker">
                                        @if($entry['logo_url'])
                                            <img src="{{ $entry['logo_url'] }}" alt="" loading="lazy" decoding="async">
                                        @endif
                                        <span>{{ $entry['broker']->name }}</span>
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($table['rows'] as $row)
                            <tr>
                                <th scope="row">{{ $row['label'] }}</th>
                                @foreach($guidePage['entries'] as $entry)
                                    <td>{{ $row['cells'][$entry['broker']->id] ?? '—' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endforeach

@foreach($guidePage['entries'] as $entry)
    <article class="bbg-broker-card" id="broker-{{ $entry['rank'] }}">
        <div class="bbg-broker-card__head">
            <div class="bbg-broker-card__rank">#{{ $entry['rank'] }}</div>
            <div class="bbg-broker-card__brand">
                @if($entry['logo_url'])
                    <img src="{{ $entry['logo_url'] }}" alt="" loading="lazy" decoding="async">
                @endif
                <div>
                    <h2 class="bbg-broker-card__title">
                        {{ $entry['broker']->name }}
                        @if($entry['in_country'] ?? false)
                            <span class="bbg-country-pill">{{ $guidePage['country']['flag'] }} Available in {{ $guidePage['country']['name'] }}</span>
                        @endif
                    </h2>
                    <p class="bbg-broker-card__score">{{ $entry['score_label'] }}: {{ number_format($entry['score'], 1) }}/5</p>
                </div>
            </div>
        </div>

        <p class="bbg-broker-card__recommended">Recommended for: {{ $entry['recommended_for'] }}</p>

        @if($entry['broker']->short_description)
            <p class="bbg-broker-card__summary">{{ $entry['broker']->short_description }}</p>
        @endif

        <ul class="bbg-broker-card__facts">
            <li><span>Spreads</span><strong>{{ $entry['metrics']['spreads'] }}</strong></li>
            <li><span>Min. deposit</span><strong>{{ $entry['metrics']['minimum_deposit'] }}</strong></li>
            <li><span>Leverage</span><strong>{{ $entry['metrics']['leverage'] }}</strong></li>
            <li><span>Regulation</span><strong>{{ $entry['metrics']['regulatory_tier'] }}</strong></li>
        </ul>

        <div class="bbg-broker-card__actions">
            <a href="{{ $entry['visit_url'] }}" class="bbg-btn bbg-btn--primary" target="_blank" rel="noopener nofollow">Visit {{ $entry['broker']->name }}</a>
            <a href="{{ $entry['review_url'] }}" class="bbg-btn bbg-btn--ghost">{{ $entry['broker']->name }} review for {{ date('Y') }}</a>
        </div>
    </article>
@endforeach

<section class="bbg-cta" id="find-match">
    <h2 class="bbg-section__title">{{ $guidePage['guide']['cta_title'] ?? 'Need help finding the right broker for you?' }}</h2>
    <p class="bbg-section__text">Answer a few questions about your experience, preferred platform, and budget — we will match you with regulated brokers that fit your profile.</p>
    <a href="{{ route('find_my_broker') }}" class="bbg-btn bbg-btn--primary">Get my best match</a>
</section>
