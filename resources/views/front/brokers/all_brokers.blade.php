@extends('front.layout.app')
@section('title', 'Find the Best Forex Brokers Today - ' . date('Y'))
@section('meta_description', 'Compare and choose from the top regulated forex brokers. Discover the best platforms, spreads, bonuses, and leverage options in ' . date('Y') . '.')
@section('main_content')
<div class="py-8 border-b">
    <div class="container max-w-7xl mx-auto w-full px-4 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Heading aligned left -->
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Find the <span class="text-yellow-500">Best Forex</span> Brokers Today
                </h1>
            </div>
            
            <!-- Navigation aligned right -->
            <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <span class="mx-2 text-gray-400"><i class="fas fa-chevron-right text-xs"></i></span>
                <span class="font-medium text-gray-800">
                    All Brokers
                </span>
            </nav>
        </div>
    </div>
</div>


<section class="main bg-gray-50">
    <!-- Left Image outside container -->
    @if(isset($global_sidebar_bottom_ad[0]))
        <div class="hidden lg:flex absolute top-0 left-0 h-full items-center pointer-events-none z-0 px-2 sm:px-4">
            @php $row = $global_sidebar_bottom_ad[0]; @endphp
            <div class="relative group">
                @if($row->sidebar_ad_url == '')
                    <div class="relative rounded-lg overflow-hidden shadow-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300">
                        <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-yellow-400 text-black text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded z-10">ADVERTISEMENT</div>
                        <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg">
                    </div>
                @else
                    <div class="relative rounded-lg overflow-hidden shadow-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300">
                        <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-yellow-400 text-black text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded z-10">ADVERTISEMENT</div>
                        <a href="{{ $row->sidebar_ad_url }}" class="pointer-events-auto">
                            <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <!-- Right Image outside container -->
    @if(isset($global_sidebar_bottom_ad[1]))
        <div class="hidden lg:flex absolute top-0 right-0 h-full items-center pointer-events-none z-0 px-2 sm:px-4">
            @php $row = $global_sidebar_bottom_ad[1]; @endphp
            <div class="relative group">
                @if($row->sidebar_ad_url == '')
                    <div class="relative rounded-lg overflow-hidden shadow-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300">
                        <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-yellow-400 text-black text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded z-10">ADVERTISEMENT</div>
                        <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg">
                    </div>
                @else
                    <div class="relative rounded-lg overflow-hidden shadow-lg border-2 border-gray-200 hover:border-blue-400 transition-all duration-300">
                        <div class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-yellow-400 text-black text-xs font-bold px-1.5 sm:px-2 py-0.5 sm:py-1 rounded z-10">ADVERTISEMENT</div>
                        <a href="{{ $row->sidebar_ad_url }}" class="pointer-events-auto">
                            <img src="{{ asset('uploads/'.$row->sidebar_ad) }}" alt="" class="w-24 sm:w-32 lg:w-48 h-auto object-contain pointer-events-auto rounded-lg">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="container max-w-7xl mx-auto w-full px-4 pt-12">
        <div class="space-y-4 mb-8">
           <div class="space-y-4 mb-8">
 
                <form id="filter-form" action="{{ route('all_brokers') }}" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Toggle Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-4 cursor-pointer" id="filter-toggle">
                    <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white">Filter Brokers</h3>
                        <p class="text-gray-300 text-sm mt-1">Refine your search results</p>
                    </div>
                    <div class="text-white transform transition-transform duration-300" id="toggle-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    </div>
                </div>
    
                <!-- Filter Content -->
                <div class="px-6 py-6 space-y-6" id="filter-content">
                    <!-- First Row - 3 Columns -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Minimum Deposit -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">Min. Deposit</label>
                        <div class="relative">
                        <select name="minimum_deposit" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 appearance-none cursor-pointer">
                            <option value="Any Amount">Any Amount</option>
                            @foreach ($min_deposits as $deposit)
                            <option value="{{ $deposit->minimum_deposit }}" {{ request('minimum_deposit') == $deposit->minimum_deposit ? 'selected' : '' }}>
                                ${{ number_format($deposit->minimum_deposit) }}+
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                            </svg>
                        </div>
                        </div>
                    </div>
    
                    <!-- Platform -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">Platform</label>
                        <div class="relative">
                        <select name="platform" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 appearance-none cursor-pointer">
                            <option value="All Platforms">All Platforms</option>
                            @foreach ($platforms as $platform)
                            <option value="{{ $platform->platforms }}" {{ request('platform') == $platform->platforms ? 'selected' : '' }}>
                                {{ $platform->platforms }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                            </svg>
                        </div>
                        </div>
                    </div>
    
                    <!-- Regulation -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">Regulation</label>
                        <div class="relative">
                            <select name="regulation" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all duration-200 appearance-none cursor-pointer">
                                <option value="All Regulators">All Regulators</option>
                    
                                @php
                                    $allRegulations = [
                                        // Top-Tier / Highly Trusted
                                        'FCA (UK)',
                                        'ASIC (Australia)',
                                        'CySEC (Cyprus)',
                                        'NFA/CFTC (USA)',
                                        'MAS (Singapore)',
                                        'JFSA (Japan)',
                                        'BaFin (Germany)',
                                        'FINMA (Switzerland)',
                                        'IIROC (Canada)',
                                        'SFC (Hong Kong)',
                    
                                        // Secondary / Regional
                                        'FSCA (South Africa)',
                                        'CONSOB (Italy)',
                                        'CNMV (Spain)',
                                        'FSC (Mauritius)',
                                        'FMA (New Zealand)',
                                        'MFSA (Malta)',
                                        'SCB (Bahrain)',
                                        'DFSA (Dubai, UAE)',
                    
                                        // Offshore / Lesser-Regulated
                                        'VFSC (Vanuatu)',
                                        'FSA (Seychelles)',
                                        'CIMA (Cayman Islands)',
                                        'FSA (Belize)',
                                        'FSA (St. Vincent & Grenadines)',
                                        'FSC (British Virgin Islands)'
                                    ];
                                @endphp
                    
                                @foreach ($allRegulations as $regulation)
                                    <option value="{{ $regulation }}" {{ request('regulation') == $regulation ? 'selected' : '' }}>
                                        {{ $regulation }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                   <div class="flex flex-wrap items-center justify-start gap-3 pt-5 border-t border-gray-100">
                        <!-- Apply Filters -->
                        <button 
                            type="submit" 
                            class="flex items-center justify-center gap-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-semibold text-sm px-5 py-2.5 rounded-full shadow-md hover:shadow-lg transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Apply
                        </button>
                    
                        <!-- Reset All -->
                        <a 
                            href="{{ route('all_brokers') }}" 
                            class="flex items-center justify-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium text-sm px-5 py-2.5 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Reset
                        </a>
                    </div>
                 </div>
                </form>
    
               <script>
                    $(function() {
                    let open = true;
                    $('#filter-toggle').on('click', function() {
                        $('#filter-content').toggleClass('hidden', open);
                        $('#toggle-icon').toggleClass('rotate-180', !open);
                        open = !open;
                    });
                    });
                    </script>
    
    
            </div>
        </div>
            @if ($all_brokers->count() > 0)
                <div class="space-y-4">
                    <x-broker-table-header />
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach($all_brokers as $broker)
                            <x-broker-row :broker="$broker" />
                        @endforeach
                    </div>
                </div>
            @else
                <x-no-brokers-found />
            @endif
        <!-- Pagination -->
        @if ($all_brokers->hasPages())
            <div class="mt-8 flex items-center justify-center space-x-1  mb-12">
                {{-- Previous Page Link --}}
                @if ($all_brokers->onFirstPage())
                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">
                        &laquo;
                    </span>
                @else
                    <a href="{{ $all_brokers->previousPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                        &laquo;
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($all_brokers->getUrlRange(1, $all_brokers->lastPage()) as $page => $url)
                    @if ($page == $all_brokers->currentPage())
                        <span class="px-3 py-1 rounded-md bg-white text-yellow-600 border border-yellow-500 text-sm font-bold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 rounded-md text-gray-700 hover:bg-yellow-50 text-sm transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($all_brokers->hasMorePages())
                    <a href="{{ $all_brokers->nextPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                        &raquo;
                    </a>
                @else
                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">
                        &raquo;
                    </span>
                @endif
            </div>
        @endif
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#filter-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-3').html(
                        $(response).find('.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-3').html()
                    );
                },
                error: function(xhr) {
                    console.error('Error applying filters:', xhr);
                    alert('An error occurred while applying filters. Please try again.');
                }
            });
        });
    });
</script>
@endsection
