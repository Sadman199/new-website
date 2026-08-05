@extends('front.layout.app')
@section('title', 'BrokersCourt | ' . $awardName . ' Brokers')
@section('main_content')

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <div class="border-gray-200 pt-8">
            <div class="py-8">
                <nav class="flex justify-between items-center" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li class="flex items-center">
                            <a href="{{ url('/') }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                <i class="fas fa-home mr-2 text-gray-400 group-hover:text-blue-500"></i>
                                Home
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-xs text-gray-300 mx-2"></i>
                            <a href="{{ url('/brokers') }}" class="text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors duration-200">
                                Brokers
                            </a>
                        </li>
                        <li class="flex items-center" aria-current="page">
                            <i class="fas fa-chevron-right text-xs text-gray-300 mx-2"></i>
                           <span class="text-sm font-semibold text-gray-800 bg-gray-50 px-3 py-1 rounded-full border border-gray-400">
                                {{ \Illuminate\Support\Str::ucfirst($awardName) }}
                            </span>

                        </li>
                    </ol>

                    <div class="hidden lg:flex items-center space-x-2 bg-gray-50 px-4 py-3 rounded-3xl border border-gray-200">
                        <i class="fas fa-trophy text-gray-500 text-lg"></i>
                        <span class="text-sm font-medium text-gray-700">Award Winning Brokers</span>
                    </div>
                </nav>
            </div>
        </div>

      <div class="py-8">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
                <!-- Main Content -->
                <div class="flex-1">
                    <!-- Header with Enhanced Visual Hierarchy -->
                    <div class="flex items-start mb-8">
                        <div class="relative">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-600 rounded-2xl flex items-center justify-center mr-6 shadow-lg">
                                <i class="fas fa-trophy text-white text-3xl"></i>
                            </div>
                            <div class="absolute -top-2 -left-2 w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-700 mb-3">{{ $awardName }} Brokers</h1>
                            <p class="text-gray-600 font-semibold leading-relaxed">Expert-curated analysis and comprehensive broker comparisons for informed trading decisions</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Total Brokers -->
                        <div class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-building text-gray-500 text-lg"></i>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">{{ $paginatedBrokers->total() }}</div>
                                    <div class="text-xs text-gray-500 font-semibold">+5% this month</div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-700 mb-2">Total Brokers</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-400 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>

                        <!-- Top Rated -->
                        <div class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-chart-line text-gray-500 text-lg"></i>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">{{ $top_brokers->count() }}</div>
                                    <div class="text-xs text-gray-500 font-semibold">4.8+ Rating</div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-700 mb-2">Top Rated</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-400 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>

                        <!-- Featured -->
                        <div class="group bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-star text-gray-500 text-lg"></i>
                                </div>
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-gray-900">{{ $featured_brokers->count() }}</div>
                                    <div class="text-xs text-gray-500 font-semibold">Editor's Choice</div>
                                </div>
                            </div>
                            <div class="text-sm font-semibold text-gray-700 mb-2">Featured</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-400 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- Enhanced Sidebar -->
                <div class="lg:w-96">
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 shadow-xl border border-gray-700">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center mr-4">
                                <i class="fas fa-medal text-white text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white text-xl">Award Excellence</h3>
                                <p class="text-gray-300 text-sm">Premium Selection</p>
                            </div>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                                <div class="flex items-center">
                                    <span class="text-gray-300 font-medium">Category</span>
                                </div>
                                <span class="font-bold text-white text-lg">{{ Str::ucfirst(strtolower($awardName)) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                                <div class="flex items-center">
                                    <span class="text-gray-300 font-medium">Last Updated</span>
                                </div>
                                <span class="font-semibold text-gray-200">{{ now()->format('M d, Y') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center pb-4 border-b border-gray-700">
                                <div class="flex items-center">
                                    <span class="text-gray-300 font-medium">Verification</span>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-500/20 text-green-300 border border-green-500/30">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Certified
                                </span>
                            </div>
                            
                            <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-700">
                                <div class="flex items-center text-gray-100 mb-2">
                                    <i class="fas fa-lightbulb mr-2"></i>
                                    <span class="font-semibold text-sm">Pro Tip</span>
                                </div>
                                <p class="text-gray-300 text-sm leading-relaxed">
                                    Compare brokers based on your trading style and preferences for optimal results.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-12">
            {{-- Main Content --}}
            <div class="lg:col-span-8">
                {{-- All Brokers --}}
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">All {{ $awardName }} Brokers</h2>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                Showing {{ $paginatedBrokers->firstItem() ?? 0 }}-{{ $paginatedBrokers->lastItem() ?? 0 }} of {{ $paginatedBrokers->total() }}
                            </span>
                        </div>
                    </div>

                    @if($paginatedBrokers->count())
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        @foreach ($paginatedBrokers as $broker)
                            <x-broker-row :broker="$broker" />
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12 bg-white rounded-2xl border border-gray-200">
                        <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Brokers Found</h3>
                        <p class="text-gray-500 mb-4">We couldn't find any brokers matching this award category.</p>
                        <a href="{{ url('/brokers') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to All Brokers
                        </a>
                    </div>
                    @endif

                    {{-- Pagination --}}
                    @if($paginatedBrokers->hasPages())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        {{ $paginatedBrokers->links() }}
                    </div>
                    @endif
                </section>
                @php
                    $tabs = [
                        'Featured' => $featured_brokers,
                        'Top Rated' => $top_brokers
                    ];
                @endphp
                
                <div class="py-8">
                
                    {{-- Tab Buttons --}}
                    <div class="flex border-b border-gray-200 mb-6">
                        @foreach($tabs as $tabName => $brokers)
                            <button 
                                class="tab-btn px-4 py-2 text-gray-700 font-semibold focus:outline-none -mb-px border-b-2 border-transparent hover:text-gray-900"
                                data-tab="{{ Str::slug($tabName) }}">
                                {{ $tabName }}
                            </button>
                        @endforeach
                    </div>
                
                    {{-- Tab Contents --}}
                    @foreach($tabs as $tabName => $brokers)
                        <div class="tab-content hidden" id="{{ Str::slug($tabName) }}">
                
                            {{-- Title + Subtitle --}}
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                
                                    @if($tabName === 'Featured')
                                        <i class="fa fa-star text-yellow-500 mr-2"></i>
                                        Featured {{ $awardName }} Brokers
                                    @else
                                        <i class="fa fa-chart-line text-yellow-500 mr-2"></i>
                                        Top Rated in {{ $awardName }}
                                    @endif
                                </h2>
                
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full
                                    @if($tabName === 'Featured')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-green-100 text-yellow-800
                                    @endif">
                                    @if($tabName === 'Featured')
                                        Promoted
                                    @else
                                        Highest Rated
                                    @endif
                                </span>
                            </div>
                
                            {{-- Cards --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($brokers as $broker)
                                    <x-broker-row :broker="$broker" />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- jQuery Tab Switch --}}
                <script>
                    $(function() {
                
                        const $tabs = $('.tab-btn');
                        const $contents = $('.tab-content');
                
                        function activateTab(id) {
                            $contents.addClass('hidden');
                            $('#' + id).removeClass('hidden');
                
                            $tabs.removeClass('border-yellow-500 text-yellow-600');
                            $('.tab-btn[data-tab="' + id + '"]').addClass('border-yellow-500 text-yellow-600');
                        }
                
                        $tabs.on('click', function() {
                            activateTab($(this).data('tab'));
                        });
                
                        // Activate first tab initially
                        if ($tabs.length) {
                            activateTab($tabs.first().data('tab'));
                        }
                    });
                </script>
            </div>
           <div class="lg:col-span-4">
                <div class="sticky top-24 space-y-6">
                    <x-bonus-ad-card 
                        title="MT5 by OneRoyal – Live Now" 
                        badge="Now Available" 
                        :ads="$global_sidebar_top_ad" 
                    />
                    <!-- recommended brokers -->
                    @include("front.brokers.recomended_broker_sidebar.recomended_broker_sidebar") 



                     <!-- Award Summary Card -->
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-lg border border-gray-100 p-6">
                            <div class="flex items-center mb-5">
                                <div class="flex items-center justify-center mr-3">
                                    <i class="fas fa-award text-yellow-500"></i>
                                </div>
                                <h3 class="font-bold text-gray-700 text-lg">Award Summary</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">Category:</span>
                                    <span class="font-bold text-blue-700">{{ $awardName }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">Total Brokers:</span>
                                    <span class="font-semibold text-gray-900">{{ $paginatedBrokers->total() }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                    <span class="text-gray-600 font-medium">Featured:</span>
                                    <span class="font-semibold text-gray-900">{{ $featured_brokers->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 font-medium">Avg Rating:</span>
                                    <span class="font-semibold text-gray-900 flex items-center">
                                        @php
                                            $avgRating = $brokers->avg('rating');
                                            echo $avgRating ? number_format($avgRating, 1) : 'N/A';
                                        @endphp
                                        <i class="fas fa-star text-yellow-400 ml-1 text-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                    

                        <!-- Quick Comparison -->
                        <div class="bg-gray-50 rounded-2xl shadow-lg border border-gray-100 p-6">
                            <div class="flex items-center mb-5">
                                <div class="flex items-center justify-center mr-3">
                                    <i class="fas fa-chart-bar text-yellow-500"></i>
                                </div>
                                <h3 class="font-bold text-gray-900 text-lg">Quick Comparison</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="bg-white rounded-xl p-3 border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm font-medium">Minimum Deposit</span>
                                        <span class="font-bold text-gray-900 text-sm">
                                            @php
                                                $minDeposit = $brokers->min('min_deposit');
                                                echo $minDeposit ? '$' . number_format($minDeposit) : 'Varies';
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-3 border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm font-medium">Average Spread</span>
                                        <span class="font-bold text-gray-900 text-sm">
                                            @php
                                                $avgSpread = $brokers->avg('spread');
                                                echo $avgSpread ? number_format($avgSpread, 2) . ' pips' : 'Varies';
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-3 border border-gray-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm font-medium">Leverage Range</span>
                                        <span class="font-bold text-gray-900 text-sm">1:10 - 1:1000</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Alert -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-gray-800 text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 mb-1">Last Updated</h3>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        This award page was last updated on {{ now()->format('F d, Y') }}. Ratings and information are regularly reviewed.
                                    </p>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
         {{-- Award Description --}}
        <div class="bg-gray-50 rounded-3xl shadow-md border border-gray-200 p-8 mb-10 mt-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900">About {{ $awardName }} Award</h2>
            </div>

            <p class="text-gray-700 text-lg leading-relaxed mb-8">
                The <strong class="text-yellow-600">{{ $awardName }}</strong> award honors brokers who demonstrate exceptional performance, innovation, and reliability. 
                Selection is based on detailed evaluations including user reviews, platform testing, and regulatory compliance.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <span class="text-gray-800 font-medium">Comprehensive platform feature analysis</span>
                </div>
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <span class="text-gray-800 font-medium">Regulatory compliance verification</span>
                </div>
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <span class="text-gray-800 font-medium">User experience and satisfaction ratings</span>
                </div>
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition">
                    <span class="text-gray-800 font-medium">Trading condition comparisons</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection