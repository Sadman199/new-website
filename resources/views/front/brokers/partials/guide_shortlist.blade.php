{{-- Ranked shortlist as a horizontal slider. Each card jumps to the full review below. --}}
<section class="bgx-section" id="shortlist">
    <header class="bgx-section__head bgx-section__head--row">
        <div>
            <h2 class="bgx-h2">The shortlist</h2>
            <p class="bgx-prose">{{ count($guidePage['entries']) }} brokers · updated {{ $guidePage['updated_at'] }}</p>
        </div>

        <div class="bgx-slider__controls">
            <button type="button" class="bgx-round-btn" data-bgx-slider-prev="shortlist" aria-label="Previous brokers">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="bgx-round-btn" data-bgx-slider-next="shortlist" aria-label="Next brokers">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </header>

    <div class="bgx-slider" data-bgx-slider="shortlist">
        <div class="bgx-slider__track" data-bgx-slider-track>
            @foreach($guidePage['entries'] as $entry)
                <a href="#broker-{{ $entry['rank'] }}"
                   class="bgx-pick @if($entry['rank'] === 1) bgx-pick--winner @endif"
                   style="--bgx-delay: {{ $loop->index * 50 }}ms">
                    <div class="bgx-pick__top">
                        <span class="bgx-pick__rank">{{ $entry['rank'] }}</span>
                        @if($entry['rank'] === 1)
                            <span class="bgx-pick__crown"><i class="fas fa-crown" aria-hidden="true"></i> Top pick</span>
                        @elseif($entry['in_country'])
                            <span class="bgx-pick__flag" title="Available in {{ $guidePage['country']['name'] }}">
                                @include('front.layout.partial.country-flag', [
                                    'country' => $guidePage['country'],
                                    'width' => 22,
                                    'height' => 16,
                                ])
                            </span>
                        @endif
                    </div>

                    <div class="bgx-pick__logo">
                        @if($entry['logo_url'])
                            <img src="{{ $entry['logo_url'] }}" alt="{{ $entry['name'] }}" loading="lazy" decoding="async">
                        @else
                            <span>{{ $entry['initial'] }}</span>
                        @endif
                    </div>

                    <p class="bgx-pick__name">{{ $entry['name'] }}</p>

                    <div class="bgx-pick__score">
                        <span class="bgx-pick__score-value">{{ number_format($entry['fit']['score'], 1) }}</span>
                        <span class="bgx-pick__score-max">/10</span>
                    </div>
                    <p class="bgx-pick__grade">{{ $entry['fit']['grade'] }}</p>

                    <dl class="bgx-pick__facts">
                        @foreach(['min_deposit' => 'From', 'spread' => 'Spread'] as $key => $label)
                            @if($entry['facts'][$key]['known'])
                                <div>
                                    <dt>{{ $label }}</dt>
                                    <dd>{{ $entry['facts'][$key]['value'] }}</dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bgx-slider__dots" data-bgx-slider-dots="shortlist" aria-hidden="true"></div>
</section>
