@php $winner = $guidePage['winner']; @endphp

@if($winner)
<section class="bgx-section bgx-section--pick" id="top-pick">
    <header class="bgx-section__head">
        <h2 class="bgx-h2">{{ $guidePage['guide']['spotlight_title'] }}</h2>
        <p class="bgx-prose">Our highest-ranked match for this list, using the same facts as every broker below.</p>
    </header>

    @include('front.brokers.partials.guide_broker_card', [
        'entry' => $winner,
        'listingId' => 'top-pick-broker',
        'featured' => true,
    ])
</section>
@endif
