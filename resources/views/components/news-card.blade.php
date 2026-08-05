@props([
    'item', 
    'userData', 
    'updatedDate', 
    'bgColor' => 'bg-gray-800',     // default dark background
    'borderColor' => 'border border-gray-700', // default border
    'textColor' => 'text-white',   // default text color
    'hoverTextColor' => 'hover:text-blue-400', // default hover text
    'badgeBg' => 'bg-gray-700',    // default badge background
    'badgeText' => 'text-gray-300', // default badge text
    'badgeBorder' => 'border border-gray-700' // default badge border
])

<div class="{{ $bgColor }} {{ $borderColor }} rounded-lg p-3 hover:bg-gray-750 transition-all duration-300">
    <div class="flex gap-3">
        <!-- Image -->
        <div class="flex-shrink-0 w-20">
            <img 
                src="{{ asset('uploads/' . $item->post_photo) }}" 
                alt="{{ $item->post_title }}"
                class="w-full h-full object-cover rounded"
            >
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="mb-1">
                <span class="inline-block px-2 py-0.5 text-xs {{ $badgeBg }} {{ $badgeText }} rounded border {{ $badgeBorder }}">
                    {{ optional($item->rSubCategory)->sub_category_name ?? 'General' }}
                </span>
            </div>
            <h3 class="mb-1 text-sm font-semibold {{ $textColor }} {{ $hoverTextColor }} transition-colors leading-tight line-clamp-2">
                @if($item->rSubCategory)
                    <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}">
                        {{ Str::limit($item->post_title, 30) }}
                    </a>
                @else
                    <span>{{ Str::limit($item->post_title, 30) }}</span>
                @endif
            </h3>
            <div class="flex items-center text-xs text-gray-400">
                <span class="truncate">{{ $userData->name }}</span>
                <span class="mx-1">•</span>
                <span>{{ $updatedDate }}</span>
            </div>
        </div>
    </div>
</div>
