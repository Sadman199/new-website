@if(isset($popularNewsData) && $popularNewsData->count() > 0)
<section class="bc-home-news">
    <div class="bc-home-container bc-home-news__inner">
        <span class="bc-home-news__label">Latest</span>
        <div class="bc-home-news__track">
            @foreach($popularNewsData as $item)
                @if($item->rSubCategory)
                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}" class="bc-home-news__item">
                        {{ $item->post_title }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
