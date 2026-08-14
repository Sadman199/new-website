  <!-- Promo Ad -->
<div class="bg-white rounded-xl shadow-md overflow-hidden border border-yellow-400 hover:shadow-sm transition-shadow duration-300">
    <!-- Header with badge -->
    <div class="relative bg-yellow-50 px-4 py-3 border-b border-yellow-400">
        <h2 class="text-lg font-bold text-gray-700 flex items-center">
            <i class="fas fa-gift text-yellow-500 mr-2"></i>
            Octa Deposit Bonus
            <span class="ml-auto bg-white/20 text-xs font-semibold px-2 py-1 rounded-full">Limited Offer</span>
        </h2>
    </div>

    <!-- Ad content -->
    <div class="p-4">
        @foreach($global_sidebar_top_ad as $row)
            <div class="mb-4 last:mb-0 group relative">
                @if($row->sidebar_ad_url == '')
                    <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="Octa Bonus Offer" class="w-full rounded-lg border border-gray-200" loading="lazy" decoding="async">
                @else
                    <a href="{{ $row->sidebar_ad_url }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="Octa Bonus Offer" class="w-full rounded-lg border border-gray-200 transition-transform duration-300 group-hover:scale-[1.02]" loading="lazy" decoding="async">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/10 transition-colors duration-300 rounded-lg"></div>
                    </a>
                @endif
                <div class="absolute top-2 right-2 bg-yellow-400 text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                    @if($loop->first) HOT @else NEW @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="px-4 py-2 bg-gray-50 text-center border-t border-gray-100">
        <p class="text-xs text-gray-500">Terms and conditions apply</p>
    </div>
</div>