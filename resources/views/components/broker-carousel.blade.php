<!-- resources/views/components/broker-carousel.blade.php -->
@props(['title', 'brokers', 'carouselClass'])

<div class="flex flex-col mb-6">
    <h4 class="text-2xl font-bold text-gray-800 text-left mb-4">{{ $title }}</h4>
    <div class="owl-carousel {{ $carouselClass }} owl-theme">
        @foreach($brokers as $broker)
            <x-broker-card :broker="$broker" />
        @endforeach
    </div>
</div>