@if(isset($popularNewsData) && $popularNewsData->count() > 0)
<section class="bv-news">
    <div class="bv-news__grid">
        {{-- Side news (left) --}}
        <div class="bv-news__side bv-news__side--left">
            @foreach($popularNewsData->take(2) as $item)
                @if($item->rSubCategory)
                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}" class="bv-news__card">
                        <div class="bv-news__card-img">
                            @if($item->post_photo)
                                <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="" loading="lazy" decoding="async">
                            @else
                                <i class="fas fa-newspaper"></i>
                            @endif
                        </div>
                        <div class="bv-news__card-body">
                            <div class="bv-news__card-title">{{ $item->post_title }}</div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

        {{-- Hero news --}}
        <div>
            @php $heroNews = $popularNewsData->first(); @endphp
            @if($heroNews && $heroNews->rSubCategory)
                <a href="{{ route('news_detail', ['subcategory_slug' => $heroNews->rSubCategory->slug, 'post_slug' => $heroNews->slug]) }}" class="bv-news__hero">
                    <div class="bv-news__hero-img">
                        @if($heroNews->post_photo)
                            <img src="{{ asset('uploads/'.$heroNews->post_photo) }}" alt="">
                        @endif
                        <div class="bv-news__hero-overlay">
                            <div class="bv-news__hero-title">{{ $heroNews->post_title }}</div>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        {{-- Side news 2 --}}
        <div class="bv-news__side">
            @foreach($popularNewsData->skip(2)->take(2) as $item)
                @if($item->rSubCategory)
                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}" class="bv-news__card">
                        <div class="bv-news__card-img">
                            @if($item->post_photo)
                                <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="" loading="lazy" decoding="async">
                            @else
                                <i class="fas fa-newspaper"></i>
                            @endif
                        </div>
                        <div class="bv-news__card-body">
                            <div class="bv-news__card-title">{{ $item->post_title }}</div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
