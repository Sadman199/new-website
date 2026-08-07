@foreach($cards as $promo)
    @include('front.promotions.partials.promo_card', ['promo' => $promo])
@endforeach

<span class="bpr-hidden"
      data-loaded-count="{{ $loadedCount }}"
      data-total-count="{{ $totalCount }}"
      data-next-offset="{{ $nextOffset }}"
      data-has-more="{{ ($hasMore ?? false) ? '1' : '0' }}"></span>
