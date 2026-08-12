@extends('front.layout.app')
@section('title', $page_title)
@section('canonical', url()->current())
@section('main_content')
<section class="bg-white py-8 border-b">
    <div class="container px-4 max-w-7xl mx-auto w-full mt-12 py-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Heading aligned left -->
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    {{ $promo_type }} <span class="text-yellow-500">Promotions</span>
                </h1>
            </div>
            
            <!-- Navigation aligned right -->
            <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-home mr-2"></i>
                    Home
                </a>
                <span class="mx-2 text-gray-400"><i class="fas fa-chevron-right text-xs"></i></span>
                <span class="font-medium">
                    {{ ucfirst($promo_type) }}
                </span>
            </nav>
        </div>

        <!-- Featured Card -->
        <div class="mt-8">
            @include("front.bonuses.inc.b_hero_card")
        </div>
    </div>
</section>
<!-- Main Content -->
<div class="py-12">
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
    <div class="container px-4 max-w-7xl mx-auto w-full">
            <div class="mb-6 flex justify-end">
            <form method="GET">
                <div class="relative group">
                <div class="flex items-center space-x-2 cursor-pointer">
                    <span class="text-sm font-medium text-gray-500 group-hover:text-gray-700 transition-colors">
                    Sort by:
                    </span>
                    <div class="relative">
                    <select 
                        name="sort" 
                        id="sort" 
                        onchange="this.form.submit()"
                        class="appearance-none bg-transparent pl-3 pr-8 py-2 text-sm font-medium text-gray-900 border-b-2 border-gray-300 hover:border-blue-500 focus:border-blue-500 focus:outline-none transition-colors cursor-pointer"
                    >
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default</option>
                        <option value="low-to-high" {{ request('sort') == 'low-to-high' ? 'selected' : '' }}>Min Deposit ↑</option>
                        <option value="high-to-low" {{ request('sort') == 'high-to-low' ? 'selected' : '' }}>Min Deposit ↓</option>
                        <option value="expiring-soon" {{ request('sort') == 'expiring-soon' ? 'selected' : '' }}>Expiring Soon</option>
                        <option value="latest-expiry" {{ request('sort') == 'latest-expiry' ? 'selected' : '' }}>Latest Expiry</option>
                        <option value="recently-published" {{ request('sort') == 'recently-published' ? 'selected' : '' }}>Newest</option>
                        <option value="title-asc" {{ request('sort') == 'title-asc' ? 'selected' : '' }}>A → Z</option>
                        <option value="title-desc" {{ request('sort') == 'title-desc' ? 'selected' : '' }}>Z → A</option>
                    </select>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 group-hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 w-full h-0.5 bg-yellow-500 scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                </div>
            </form>
            </div>
             <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            
            
            <div class="lg:col-span-8">

                @if ($forexBonuses->isEmpty())
                
                    <!-- Empty State -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center">
                        <i class="fas fa-chart-line text-gray-400 text-xl mb-3"></i>
                        <h3 class="text-gray-800 font-semibold">No Trading Bonuses</h3>
                        <p class="text-gray-500 text-sm">New offers coming soon</p>
                    </div>
                
                @else
                
                <!-- Bonuses Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                
                @foreach ($forexBonuses as $bonus)
                
                @php
                    $expiryBadge = $bonus->expiryBadge();
                @endphp
                
                <!-- Bonus Card -->
                <div class="bg-white border border-gray-200 rounded-xl p-4 flex flex-col
                            hover:shadow-md hover:border-yellow-400 transition duration-300">
                
                    <!-- Image -->
                    @if ($bonus->feature_image)
                        <img src="{{ asset($bonus->feature_image) }}"
                             alt="{{ $bonus->title }}"
                             class="w-full h-28 object-cover rounded-lg mb-3">
                    @else
                        <div class="w-full h-28 bg-gradient-to-br from-blue-600 to-blue-700
                                    rounded-lg flex items-center justify-center mb-3">
                            <i class="fas fa-chart-candlestick text-white text-xl"></i>
                        </div>
                    @endif
                
                    <!-- Content -->
                    <div class="flex flex-col flex-grow">
                
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug mb-1">
                            {{ Str::limit($bonus->title, 45) }}
                        </h3>
                
                        @if($bonus->broker_name)
                            <p class="text-xs text-gray-500 mb-3">
                                {{ $bonus->broker_name }}
                            </p>
                        @endif
                
                        <!-- Meta -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs bg-blue-50 border border-blue-100
                                         text-blue-700 px-2 py-1 rounded-md font-medium">
                                {{ $bonus->bonus_category }}
                            </span>
                
                            @if($expiryBadge)
                                @include('front.partials.expiry_badge', ['badge' => $expiryBadge, 'pill' => false, 'class' => 'bc-expiry-badge text-xs font-bold'])
                            @endif
                        </div>
                
                        <!-- Actions -->
                        <div class="flex gap-2 mt-auto">
                            <a href="{!! $bonus->affiliate_link !!}" target="_blank"
                               class="flex-1 bg-yellow-400 hover:bg-yellow-500
                                      text-gray-900 text-xs font-bold py-2.5
                                      rounded-lg text-center transition">
                                CLAIM OFFER
                            </a>
                
                            <a href="{{ $bonus->cardUrl() }}"
                               class="w-10 h-10 flex items-center justify-center
                                      bg-gray-100 border border-gray-200
                                      hover:bg-gray-200 rounded-lg">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                
                    </div>
                
                </div>
                
                @endforeach
                
                </div>
                
                @endif
                
                @if ($forexBonuses instanceof \Illuminate\Pagination\LengthAwarePaginator && $forexBonuses->hasPages())
                    <div class="mt-8 flex items-center justify-center space-x-1 mb-12">
                
                        {{-- Previous --}}
                        @if ($forexBonuses->onFirstPage())
                            <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">&laquo;</span>
                        @else
                            <a href="{{ $forexBonuses->previousPageUrl() }}"
                               class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                                &laquo;
                            </a>
                        @endif
                
                        {{-- Pages --}}
                        @foreach ($forexBonuses->getUrlRange(1, $forexBonuses->lastPage()) as $page => $url)
                            @if ($page == $forexBonuses->currentPage())
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
                
                        {{-- Next --}}
                        @if ($forexBonuses->hasMorePages())
                            <a href="{{ $forexBonuses->nextPageUrl() }}"
                               class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                                &raquo;
                            </a>
                        @else
                            <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">&raquo;</span>
                        @endif
                
                    </div>
                @endif
                                
                </div>
                            

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <div class="space-y-6 sticky top-6">
                    @include("front.site_banner.site_banner")
                    @include("front.brokers.recomended_broker_sidebar.recomended_broker_sidebar")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection