<!-- resources/views/components/banner-ad.blade.php -->
@props(['adImage', 'adUrl', 'adStatus'])

@if($adStatus === 'Show')
    <div class="hidden lg:block w-[120px] flex-shrink-0">
        @if($adUrl)
            <a href="{{ $adUrl }}" target="_blank" rel="noopener noreferrer">
                <img class="w-[120px] h-[600px] object-cover rounded-lg border border-gray-700 hover:border-yellow-400 transition-all" src="{{ asset('uploads/' . $adImage) }}" alt="Banner Ad">
            </a>
        @else
            <img class="w-[120px] h-[600px] object-cover rounded-lg border border-gray-700" src="{{ asset('Uploads/' . $adImage) }}" alt="Banner Ad">
        @endif
    </div>
@endif