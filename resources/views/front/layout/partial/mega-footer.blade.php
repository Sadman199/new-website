<footer class="mf-footer" aria-labelledby="mfFooterTitle">
    <h2 id="mfFooterTitle" class="sr-only">Site footer</h2>

    <section class="mf-intro" aria-label="Newsletter signup">
        <div class="container">
            <form action="{{ route('subscribe') }}" method="post" class="mf-newsletter">
                @csrf
                <div class="mf-newsletter__head">
                    <span class="mf-newsletter__icon" aria-hidden="true"><i class="far fa-envelope"></i></span>
                    <div>
                        <strong>Join the BrokersCourt briefing</strong>
                        <span>Curated insights delivered to your inbox.</span>
                    </div>
                </div>

                <div class="mf-newsletter__action">
                    <label for="mfNewsletterEmail" class="sr-only">Email address</label>
                    <div class="mf-newsletter__row">
                        <input
                            type="email"
                            id="mfNewsletterEmail"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            maxlength="254"
                            placeholder="Enter your email address"
                            class="mf-newsletter__input"
                            autocomplete="email"
                            aria-describedby="mfNewsletterNote"
                        >
                        <button type="submit" class="mf-newsletter__btn">
                            Subscribe <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p id="mfNewsletterNote" class="mf-newsletter__note">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        Free insights. No spam. Unsubscribe anytime.
                    </p>
                </div>
            </form>
        </div>
    </section>

    {{-- Quick-action CTA band --}}
    <section class="mf-cta" aria-label="Quick actions">
        <div class="container">
            <div class="row g-3">
            @foreach($footer['cta'] as $item)
                <div class="col-12 col-md-4">
                <a href="{{ $item['url'] }}" class="mf-cta__card {{ !empty($item['primary']) ? 'is-primary' : '' }}">
                    <span class="mf-cta__icon" aria-hidden="true"><i class="{{ $item['icon'] }}"></i></span>
                    <span class="mf-cta__text">
                        <strong>{{ $item['label'] }}</strong>
                        <span>{{ $item['description'] }}</span>
                    </span>
                    <span class="mf-cta__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                </a>
                </div>
            @endforeach
            </div>
        </div>
    </section>

    {{-- Mega link grid --}}
    <section class="mf-main">
        <div class="container">
            <div class="mf-mega">
                {{-- Brand panel --}}
                <div class="mf-panel mf-panel--brand">
                    <a href="{{ route('home') }}" class="mf-brand__logo-link">
                        <img src="{{ $footer['brand']['logo'] }}" alt="{{ $footer['brand']['name'] }}" class="mf-brand__logo" loading="lazy">
                    </a>
                    <p class="mf-brand__tagline">{{ $footer['brand']['tagline'] }}</p>

                    <ul class="mf-brand__contact">
                        <li>
                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                            <span>{{ $footer['contact']['address'] }}</span>
                        </li>
                        <li>
                            <i class="far fa-envelope" aria-hidden="true"></i>
                            <a href="mailto:{{ $footer['contact']['email'] }}">{{ $footer['contact']['email'] }}</a>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                            <a href="tel:{{ preg_replace('/\s+/', '', $footer['contact']['phone']) }}">{{ $footer['contact']['phone'] }}</a>
                        </li>
                    </ul>

                    @if($footer['social'] !== [])
                        <div class="mf-social" aria-label="Social media">
                            @foreach($footer['social'] as $item)
                                <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" class="mf-social__link" aria-label="{{ $item['name'] }}">
                                    <i class="{{ $item['icon'] }}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>

                {{-- Top Brokers --}}
                @if($footer['top_brokers']['links'] !== [])
                    <nav class="mf-panel mf-panel--links" aria-label="Top brokers">
                        <div class="mf-panel__head">
                            <span class="mf-panel__icon"><i class="fas fa-star"></i></span>
                            <h3 class="mf-panel__title">Top Brokers</h3>
                        </div>
                        <ul class="mf-links">
                            @foreach($footer['top_brokers']['links'] as $link)
                                <li>
                                    <a href="{{ $link['url'] }}" class="mf-links__item">
                                        <span>{{ $link['label'] }}</span>
                                        @if(!empty($link['meta']))
                                            <span class="mf-links__meta">{{ $link['meta'] }} ★</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @if($footer['top_brokers']['view_all'])
                            <a href="{{ $footer['top_brokers']['view_all']['url'] }}" class="mf-panel__more">{{ $footer['top_brokers']['view_all']['label'] }} →</a>
                        @endif
                    </nav>
                @endif

                {{-- Comparisons --}}
                <nav class="mf-panel mf-panel--links" aria-label="Comparisons">
                    <div class="mf-panel__head">
                        <span class="mf-panel__icon"><i class="fas fa-columns"></i></span>
                        <h3 class="mf-panel__title">Comparisons</h3>
                    </div>
                    <ul class="mf-links">
                        @foreach($footer['comparisons'] as $link)
                            <li><a href="{{ $link['url'] }}" class="mf-links__item"><span>{{ $link['label'] }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>

                {{-- Regions --}}
                <nav class="mf-panel mf-panel--links" aria-label="Regions">
                    <div class="mf-panel__head">
                        <span class="mf-panel__icon"><i class="fas fa-globe-americas"></i></span>
                        <h3 class="mf-panel__title">Regions</h3>
                    </div>
                    <ul class="mf-links">
                        @foreach($footer['regions'] as $link)
                            <li>
                                <a href="{{ $link['url'] }}" class="mf-links__item mf-links__item--flag">
                                    <span class="mf-links__flag">
                                        @include('front.layout.partial.country-flag', [
                                            'country' => ['code' => $link['code'] ?? null],
                                            'class' => 'mf-flag',
                                            'width' => 20,
                                            'height' => 15,
                                        ])
                                    </span>
                                    <span>{{ $link['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>

                {{-- Explore --}}
                <nav class="mf-panel mf-panel--links" aria-label="Explore BrokersCourt">
                    <div class="mf-panel__head">
                        <span class="mf-panel__icon"><i class="fas fa-compass"></i></span>
                        <h3 class="mf-panel__title">Explore</h3>
                    </div>
                    <ul class="mf-links">
                        @foreach($footer['for_users'] as $link)
                            <li><a href="{{ $link['url'] }}" class="mf-links__item"><span>{{ $link['label'] }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    {{-- Disclosures --}}
    <section class="mf-disclosures" aria-label="Legal disclosures">
        <div class="container">
            <div class="row g-4">
                <article class="col-12 col-lg-6 mf-disclosures__card">
                    <h4 class="mf-disclosures__title"><i class="fas fa-exclamation-triangle"></i> Risk disclaimer</h4>
                    <p>{{ $footer['disclaimer'] }}</p>
                </article>
                <article class="col-12 col-lg-6 mf-disclosures__card">
                    <h4 class="mf-disclosures__title"><i class="fas fa-handshake"></i> Affiliate disclosure</h4>
                    <p>{{ $footer['affiliate'] }}</p>
                </article>
            </div>
        </div>
    </section>

    {{-- Bottom bar --}}
    <div class="mf-bottom">
        <div class="container mf-bottom__inner">
            <p class="mf-bottom__copy">{{ \App\Support\SiteTheme::footerCopyright() ?? ('© ' . date('Y') . ' ' . $footer['brand']['name'] . '. All rights reserved.') }}</p>
            <nav class="mf-bottom__legal" aria-label="Legal">
                @foreach($footer['legal'] as $link)
                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </div>
</footer>
