<!-- resources/views/components/bonus-tab.blade.php -->
@props([
    'id',
    'title',
    'icon',
    'iconColor',
    'bonuses',
    'categoryLabel',
    'categoryColor',
    'categoryIcon',
    'routeName',
    'hoverBorderColor',
    'hoverTextColor',
    'badgeBgColor',
    'badgeTextColor',
    'badgeBorderColor',
    'isActive' => false
])

<div id="{{ $id }}" class="tab-content {{ $isActive ? '' : 'hidden' }}">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas {{ $icon }} text-{{ $iconColor }}-500 mr-2"></i>
            {{ $title }}
        </h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($bonuses as $bonus)
            <x-bonus-card 
                :bonus="$bonus"
                category-label="{{ $categoryLabel }}"
                category-color="{{ $categoryColor }}"
                category-icon="{{ $categoryIcon }}"
                route-name="{{ $routeName }}"
                hover-border-color="{{ $hoverBorderColor }}"
                hover-text-color="{{ $hoverTextColor }}"
                badge-bg-color="{{ $badgeBgColor }}"
                badge-text-color="{{ $badgeTextColor }}"
                badge-border-color="{{ $badgeBorderColor }}"
            />
        @endforeach
    </div>
</div>