@if(($popularNewsData ?? collect())->isNotEmpty() || ($recentNewsData ?? collect())->isNotEmpty())
<section class="bc-section">
    <div class="bc-container">
        <div class="bc-section__head">
            <div>
                <h2 class="bc-section__title">News &amp; insights</h2>
                <p class="bc-section__sub">Latest articles and popular guides from our editorial team.</p>
            </div>
            <a href="{{ route('blog') }}" class="bc-link">View blog <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="bc-news">
            @php $featured = ($popularNewsData ?? collect())->first(); @endphp
            @if($featured && $featured->rSubCategory)
                <a href="{{ route('news_detail', ['subcategory_slug' => $featured->rSubCategory->slug, 'post_slug' => $featured->slug]) }}" class="bc-news__featured">
                    <div class="bc-news__img">
                        @if($featured->post_photo)
                            <img src="{{ asset('uploads/'.$featured->post_photo) }}" alt="" loading="lazy">
                        @endif
                    </div>
                    <div class="bc-news__featured-body">
                        <span class="bc-news__tag">{{ $featured->rSubCategory->sub_category_name ?? 'Insights' }}</span>
                        <h3>{{ $featured->post_title }}</h3>
                        @if($featured->post_short_description)
                            <p>{{ Str::limit(strip_tags($featured->post_short_description), 140) }}</p>
                        @endif
                    </div>
                </a>
            @endif

            <ul class="bc-news__list">
                @foreach(($recentNewsData ?? collect())->take(5) as $post)
                    @if($post->rSubCategory)
                        <li>
                            <a href="{{ route('news_detail', ['subcategory_slug' => $post->rSubCategory->slug, 'post_slug' => $post->slug]) }}">
                                <span class="bc-news__date">{{ $post->created_at?->format('M j, Y') }}</span>
                                <span class="bc-news__title">{{ $post->post_title }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</section>
@endif
