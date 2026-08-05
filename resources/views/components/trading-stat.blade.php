<!-- resources/views/components/trading-stat.blade.php -->
@props(['title', 'value', 'subtitle'])

<div class="bg-gray-800/60 p-3 rounded-lg border border-gray-50 backdrop-blur-sm shadow-sm">
    <div class="text-white text-xs uppercase tracking-wider">{{ $title }}</div>
    <div class="text-white text-2xl font-bold mt-1">{{ $value }}</div>
    <div class="text-yellow-400 text-xs font-medium">{{ $subtitle }}</div>
</div>