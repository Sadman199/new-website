@extends('front.layout.app')
@section('title', 'Regulated Forex Brokers | Safe and Trusted Trading Options')
@section('main_content')
<div class="bg-white border-b border-gray-200">
    <div class="container px-4 mx-auto max-w-7xl py-8 mt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Discover the <span class="text-yellow-500">Best Regulated</span> Forex Brokers for Safe Trading
                </h1>
                <p class="mt-2 text-gray-600">
                    Explore top-tier brokers offering secure platforms, tight spreads, and trusted regulations.
                </p>
            </div>
            <nav class="text-sm bg-gray-100 rounded-full px-4 py-2 inline-flex items-center">
                <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <span class="mx-2 text-gray-400">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
                <span class="font-medium text-gray-800">Regulated Brokers</span>
            </nav>
        </div>
    </div>
</div>
@include("front.homepage.inc.top_ad") 
<!-- Regulated Broker -->
<section class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
            
        <div class="bg-white rounded-2xl p-8 mb-12 border border-gray-200 shadow-sm max-w-7xl mx-auto">
            <div class="space-y-6">
                <h3 class="text-3xl font-bold text-gray-800 tracking-tight">
                    Why Trade with a Regulated Forex Broker
                </h3>

                <p class="text-md text-gray-600 leading-relaxed">
                    Security and transparency are essential in forex trading. A regulated broker provides the framework for safe trading in the world's largest financial market.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <!-- Security Features -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-blue-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Fund Security</h3>
                            <p class="text-gray-500 text-sm mt-1">Segregated accounts & negative balance protection</p>
                        </div>
                    </div>
                    
                    <!-- Trading Features -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-green-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Fast Execution</h3>
                            <p class="text-gray-500 text-sm mt-1">99.9% of orders executed in under 50ms</p>
                        </div>
                    </div>
                    
                    <!-- Market Access -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-purple-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Global Markets</h3>
                            <p class="text-gray-500 text-sm mt-1">50+ currency pairs including exotics</p>
                        </div>
                    </div>
                    
                    <!-- Tools -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-amber-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Advanced Tools</h3>
                            <p class="text-gray-500 text-sm mt-1">Professional charts & indicators</p>
                        </div>
                    </div>
                    
                    <!-- Support -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-teal-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">24/5 Support</h3>
                            <p class="text-gray-500 text-sm mt-1">Multilingual assistance</p>
                        </div>
                    </div>
                    
                    <!-- Pricing -->
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex-shrink-0 mt-1">
                            <div class="bg-indigo-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Transparent Pricing</h3>
                            <p class="text-gray-500 text-sm mt-1">Raw spreads from liquidity providers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="regulated_broker_section">
            <h2 class="text-3xl font-bold text-gray-800 text-left mb-8">
                Trade with Confidence on Regulated Platforms
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($regulatedBrokers as $broker)
                @include("front.brokers.regulated_card.regulated_card") 
            @endforeach
        </div>
        
        <div class="mt-8 flex justify-center">
            <nav aria-label="Pagination" class="inline-flex space-x-1">
                <a @class(['px-3 py-1 rounded-md', 'text-gray-700 bg-gray-200 cursor-not-allowed' => $regulatedBrokers->onFirstPage(), 'text-gray-700 hover:bg-yellow-500 hover:text-white' => !$regulatedBrokers->onFirstPage()])
                href="{{ $regulatedBrokers->onFirstPage() ? '#' : $regulatedBrokers->previousPageUrl() }}">«</a>

                @foreach ($regulatedBrokers->links()->elements[0] as $page => $url)
                    <a @class(['px-3 py-1 rounded-md', 'bg-yellow-500 text-white font-semibold' => $page == $regulatedBrokers->currentPage(), 'text-gray-700 hover:bg-yellow-500 hover:text-white' => $page != $regulatedBrokers->currentPage()])
                    href="{{ $url }}">{{ $page }}</a>
                @endforeach

                <a @class(['px-3 py-1 rounded-md', 'text-gray-700 bg-gray-200 cursor-not-allowed' => !$regulatedBrokers->hasMorePages(), 'text-gray-700 hover:bg-yellow-500 hover:text-white' => $regulatedBrokers->hasMorePages()])
                href="{{ $regulatedBrokers->hasMorePages() ? $regulatedBrokers->nextPageUrl() : '#' }}">»</a>
            </nav>
        </div>
    </div>
</section>
@endsection