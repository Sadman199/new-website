@extends('front.layout.app')
@section('title', ucfirst($type) . ' Brokers | Top Accounts Compared – BrokersCourt')
@section('meta_description', 'Discover top-rated ' . ucfirst($type) . ' brokers. Compare account features, spreads, leverage, and trading conditions to choose the best option for your trading style.')

@section('main_content')

<!-- Page Header with Breadcrumb -->
<div class="bg-white border-b border-gray-200">
    <div class="container px-4 mx-auto max-w-7xl py-8 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                   Best <span class="text-yellow-500">{{ ucfirst($type) }}</span> Forex Brokers {{ date('Y') }}
                </h1>
                <p class="mt-2 text-gray-600">
                    Compare regulated {{ strtolower($type) }} brokers with tight spreads and fast execution
                </p>
            </div>
            <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <span class="mx-2 text-gray-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <span class="font-medium text-gray-800">
                    {{ ucfirst($type) }} Brokers
                </span>
            </nav>

        </div>
    </div>
</div>


<!-- add banner -->
@include("front.homepage.inc.top_ad") 

<div class="min-h-screen py-12">
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


    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
             <div class="lg:col-span-8">
                @if($brokers->isNotEmpty())
                 <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl shadow-xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            
                            <div>
                                <h3 class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Brokers Court</h3>
                                <p class="text-gray-800 font-medium">
                                    <span class="text-2xl font-bold text-gray-700">{{ $brokers instanceof \Illuminate\Pagination\LengthAwarePaginator ? number_format($brokers->total()) : number_format($brokers->count()) }}</span>
                                     <strong class="text-yellow-500">{{ ucfirst($type) }}</strong> forex broker{{ $brokers->count() !== 1 ? 's' : '' }} available
                                </p>
                            </div>
                        </div>
                        
                        @if($brokers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="hidden sm:block px-3 py-1 bg-white rounded-full border border-gray-200">
                            <span class="text-xs font-medium text-gray-600">Viewing <span class="text-amber-600">{{ $brokers->firstItem() }}-{{ $brokers->lastItem() }}</span> of {{ $brokers->total() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                        @if ($brokers->count() > 0)
                    <div class="space-y-4">
                        <x-broker-table-header />
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                            @foreach($brokers as $broker)
                                <x-broker-row :broker="$broker" />
                            @endforeach
                        </div>
                
                        <!-- Pagination -->
                        @if ($brokers instanceof \Illuminate\Pagination\LengthAwarePaginator && $brokers->hasPages())
                            <div class="mt-8 flex items-center justify-center space-x-1 mb-12">
                                {{-- Previous Page Link --}}
                                @if ($brokers->onFirstPage())
                                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">&laquo;</span>
                                @else
                                    <a href="{{ $brokers->previousPageUrl() }}"
                                       class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                                        &laquo;
                                    </a>
                                @endif
                
                                {{-- Page Numbers --}}
                                @foreach ($brokers->getUrlRange(1, $brokers->lastPage()) as $page => $url)
                                    @if ($page == $brokers->currentPage())
                                        <span class="px-3 py-1 rounded-md bg-white text-yellow-600 border border-yellow-500 text-sm font-bold">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                           class="px-3 py-1 rounded-md text-gray-700 hover:bg-yellow-50 text-sm transition-all duration-200">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                
                                {{-- Next Page Link --}}
                                @if ($brokers->hasMorePages())
                                    <a href="{{ $brokers->nextPageUrl() }}"
                                       class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                                        &raquo;
                                    </a>
                                @else
                                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">&raquo;</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    <x-no-brokers-found />
                @endif


                @else
                    <div class="bg-white rounded-lg shadow-md p-6 md:p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No brokers found</h3>
                        <p class="mt-1 text-gray-500">There are no brokers matching your selected criteria.</p>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                     <x-bonus-ad-card 
                        title="MT5 by OneRoyal – Live Now" 
                        badge="Now Available" 
                        :ads="$global_sidebar_top_ad" 
                     />
                    <!-- recommended brokers -->
                    @include("front.brokers.recomended_broker_sidebar.recomended_broker_sidebar") 
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Section Header with Forex Badge -->
    <div class="relative pb-8 mb-8 border-b border-gray-700/20">
        <div class="absolute -left-4 top-0 h-full w-1 bg-gradient-to-b from-yellow-500 to-yellow-600 rounded-r"></div>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                    <span class="bg-gradient-to-r from-yellow-600 to-yellow-500 bg-clip-text text-transparent">TOP 5</span> 
                    <span class="uppercase">{{ $type }}</span> FOREX BROKERS
                </h2>
                <p class="text-sm text-gray-500 mt-1 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Updated: {{ now()->format('M j, Y H:i') }} UTC
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Verified Brokers
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H5a1 1 0 010-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                    FCA Regulated
                </span>
            </div>
        </div>
    </div>
    <!-- Brokers Table -->
    @include("front.brokers.broker_table.broker_table") 
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-12">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
            <div class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">
                Premium {{ ucfirst($type) }} Brokers
                <span class="text-sm font-normal text-yellow-500 ml-2">• Curated Selection</span>
            </h2>
            </div>
            <span class="bg-white text-gray-700 text-xs font-semibold px-3 py-1 rounded-full border border-yellow-400">
            REGULATED
            </span>
        </div>
        </div>

        <!-- Card Body -->
        <div class="grid md:grid-cols-3 divide-x divide-gray-200">
        <!-- Trading Conditions -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            Trading Conditions
            </h3>
            <ul class="space-y-3">
            <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 text-green-500 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                </div>
                <span class="text-sm text-gray-700">Spreads from <strong class="text-gray-900">0.0 pips</strong></span>
            </li>
            <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 text-green-500 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                </div>
                <span class="text-sm text-gray-700"><strong class="text-gray-900">Leverage</strong> up to 1:500</span>
            </li>
            <li class="flex items-start">
                <div class="flex-shrink-0 h-5 w-5 text-green-500 mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                </div>
                <span class="text-sm text-gray-700"><strong class="text-gray-900">Execution</strong> from 0.1s</span>
            </li>
            </ul>
        </div>

        <!-- Platforms -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
            </svg>
            Trading Platforms
            </h3>
            <div class="flex flex-wrap gap-2 mt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                MT4
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                MT5
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                cTrader
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Web Terminal
            </span>
            </div>
            <div class="mt-4 text-xs text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-1 h-3 w-3 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            Full EA/Robot support
            </div>
        </div>

        <!-- Regulation -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            Regulation & Security
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Segregated client funds</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Negative balance protection</span>
            </li>
            <li class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Compensation schemes</span>
            </li>
            </ul>
            <div class="mt-4 text-xs text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline mr-1 h-3 w-3 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            FCA, ASIC, CySEC regulated
            </div>
        </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                All brokers verified on 15 May 2024
                </div>
                <a href="{{ route('broker.comparison') }}">
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 
                                            rounded-md text-sm font-medium text-white transition-colors duration-200 shadow-sm hover:shadow-md"> Compare All Brokers <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-0.5 h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 
                                            7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 
                                            4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection