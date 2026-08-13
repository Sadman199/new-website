@props([
    'item',
    'userData',
    'updatedDate',
])

@php
    $authorName = is_object($userData)
        ? ($userData->name ?? 'Editor')
        : (is_string($userData) && $userData !== '' ? $userData : ($item->author_name ?? 'Editor'));
@endphp

<article class="nc-card">
    <div class="nc-card__inner">
        <div class="nc-card__media">
            <img
                src="{{ asset('uploads/' . $item->post_photo) }}"
                alt="{{ $item->post_title }}"
                loading="lazy"
                decoding="async"
            >
        </div>
        <div class="nc-card__body">
            <span class="nc-card__badge">
                {{ optional($item->rSubCategory)->sub_category_name ?? 'General' }}
            </span>
            <h3 class="nc-card__title">
                @if($item->rSubCategory)
                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                        {{ Str::limit($item->post_title, 30) }}
                    </a>
                @else
                    <span>{{ Str::limit($item->post_title, 30) }}</span>
                @endif
            </h3>
            <div class="nc-card__meta">
                <span class="nc-card__meta-author">{{ $authorName }}</span>
                <span aria-hidden="true">•</span>
                <span>{{ $updatedDate }}</span>
            </div>
        </div>
    </div>
</article>
