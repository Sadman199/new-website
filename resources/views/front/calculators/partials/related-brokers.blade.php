@if(($relatedBrokers ?? collect())->isNotEmpty())
    <section class="calc-brokers-block" aria-labelledby="calc-related-brokers-title">
        <div class="calc-related__head">
            <h2 class="calc-related__title" id="calc-related-brokers-title">Related brokers</h2>
            <p class="calc-related__lead">Profiles from the BrokersCourt database — figures are only shown when they are stored.</p>
        </div>
        <div class="calc-broker-grid">
            @foreach($relatedBrokers as $broker)
                @include('front.calculators.partials.broker-card', ['broker' => $broker])
            @endforeach
        </div>
    </section>
@endif
