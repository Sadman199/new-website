@if(($popularNewsData ?? collect())->isNotEmpty() || ($recentNewsData ?? collect())->isNotEmpty())
@php
    $blogIndexService = app(\App\Services\BlogIndexService::class);

    $insightPosts = ($recentNewsData ?? collect())
        ->merge($popularNewsData ?? collect())
        ->unique('id')
        ->filter(fn ($post) => $post->rSubCategory)
        ->take(6)
        ->values();
@endphp
<section class="bc-insights" id="news-insights" aria-labelledby="bcInsightsTitle">
    <div class="bc-container bc-insights__container">
        <header class="bc-insights__head">
            <div class="bc-insights__intro">
                <p class="bc-insights__eyebrow">
                    <span class="bc-insights__eyebrow-icon" aria-hidden="true">
                        <i class="fas fa-newspaper"></i>
                    </span>
                    Editorial
                </p>
                <h2 id="bcInsightsTitle" class="bc-insights__title">News &amp; insights</h2>
                <p class="bc-insights__sub">Latest articles and popular guides from our editorial team.</p>
            </div>
            <a href="{{ route('blog') }}" class="bc-insights__cta">
                View blog
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </header>

        <div class="bc-insights__grid">
            @foreach($insightPosts as $index => $post)
                @php
                    $author = $blogIndexService->authorFor($post);
                    $wordCount = str_word_count(strip_tags($post->post_detail ?? ''));
                    $readMinutes = max(1, (int) ceil($wordCount / 200));
                @endphp
                @include('front.partials.insight_card', [
                    'index' => $index,
                    'url' => route('news_detail', [
                        'subcategory_slug' => $post->rSubCategory->slug,
                        'post_slug' => $post->slug,
                    ]),
                    'title' => $post->post_title,
                    'photo' => $post->post_photo ? asset('uploads/'.$post->post_photo) : null,
                    'category' => $post->rSubCategory->sub_category_name ?? 'Insights',
                    'date' => $post->created_at?->format('M j, Y'),
                    'dateIso' => $post->created_at?->toDateString(),
                    'readMinutes' => $readMinutes,
                    'authorName' => $author['name'],
                    'authorPhoto' => $author['photo'] ? asset('uploads/'.$author['photo']) : null,
                ])
            @endforeach
        </div>
    </div>
</section>
@endif
