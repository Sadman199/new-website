<!-- resources/views/components/bonus-card.blade.php -->
@props([
    'bonus',
    'categoryLabel',
    'categoryColor',
    'categoryIcon',
    'routeName',
    'hoverBorderColor',
    'hoverTextColor',
    'badgeBgColor',
    'badgeTextColor',
    'badgeBorderColor'
])

<div class="group bg-white rounded-lg border border-gray-200 hover:border-{{ $hoverBorderColor }}-300 transition-all duration-200 overflow-hidden shadow-xs hover:shadow-sm">
    <div class="flex items-center p-4">
        <div class="flex-shrink-0 h-16 w-16 bg-gray-100 overflow-hidden border border-gray-200 rounded-md">
            @if ($bonus->feature_image)
                <img src="{{ asset($bonus->feature_image) }}" alt="{{ $bonus->title }}" class="h-full w-full object-contain p-2" width="64" height="64" loading="lazy" decoding="async">
            @else
                <div class="h-full w-full flex items-center justify-center text-gray-400">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            @endif
        </div>

        <div class="ml-4 flex-1 min-w-0">
            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $bonus->bonus_category ?? $bonus->title }}</h4>
            <div class="mt-1">
                <span class="text-xs px-2 py-1 bg-{{ $badgeBgColor }}-100 text-{{ $badgeTextColor }}-800 border border-{{ $badgeBorderColor }}-300 rounded-md">{{ $categoryLabel }}</span>
            </div>
        </div>
    </div>
    <div class="px-4 pb-4">
        <h3 class="font-semibold text-gray-900 mb-2 text-sm line-clamp-2">
            <a href="{{ route($routeName, $bonus->slug) }}" class="hover:text-{{ $hoverTextColor }}-600 transition-colors">
                {{ $bonus->title }}
            </a>
        </h3>
        <div class="flex justify-between items-center mt-3">
            <span class="text-xs text-gray-500 flex items-center">
                <i class="far fa-clock mr-1"></i>
                {{ $bonus->created_at->diffForHumans() }}
            </span>
            <a href="{{ route($routeName, $bonus->slug) }}" 
               class="text-xs font-medium text-{{ $hoverTextColor }}-600 hover:text-{{ $hoverTextColor }}-800 flex items-center">
                Details <i class="fas fa-chevron-right ml-1 text-xs"></i>
            </a>
        </div>
    </div>
</div>