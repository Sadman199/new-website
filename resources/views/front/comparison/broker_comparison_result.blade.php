
@extends('front.layout.app')
@section('title', 'Broker Comparison | Compare Forex Brokers and Find the Best Option')
@section('main_content')
<div class="bg-white py-8 border-b border-gray-200">
    <div class="container px-4 max-w-7xl mx-auto w-full pt-20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <!-- Left-aligned heading -->
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <span class="text-yellow-500">{{ $broker1->name }}</span> vs 
                    <span class="text-yellow-500">{{ $broker2->name }}</span>: Comparison
                </h1>
            </div>

            <!-- Right-aligned breadcrumb -->
            <nav class="bg-gray-100 rounded-full px-4 py-2">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li class="flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-home mr-1"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-xs text-gray-400 mx-1"></i>
                    </li>
                    <li class="text-gray-700 font-medium">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        Comparison
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="min-h-screen mb-12 pt-12">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <!-- Header -->
            <div class="bg-white p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Forex Broker Comparison</h2>
                        <p class="text-gray-600">Detailed side-by-side analysis</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <div class="flex items-center justify-center space-x-8">
                            <!-- Broker 1 -->
                            <div class="text-center w-32">
                                <div class="w-16 h-16 mx-auto mb-2">
                                    <img src="{{ asset($broker1->logo) }}" alt="{{ $broker1->name }}"
                                         class="w-full h-full object-contain" />
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $broker1->name }}</span>
                            </div>
                    
                            <!-- VS Badge -->
                            <div class="relative flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-md flex items-center justify-center border border-gray-200 shadow-sm">
                                    <span class="text-xs font-bold text-gray-600 tracking-wider">VS</span>
                                </div>
                            </div>
                    
                            <!-- Broker 2 -->
                            <div class="text-center w-32">
                                <div class="w-16 h-16 mx-auto mb-2">
                                    <img src="{{ asset($broker2->logo) }}" alt="{{ $broker2->name }}"
                                         class="w-full h-full object-contain" />
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $broker2->name }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Comparison Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                <!-- Sidebar Navigation -->
                @include('front.comparison.partials.sidebar')
                
                <!-- Comparison Content -->
                <div class="lg:col-span-9">
                    @include('front.comparison.partials.general')
                    @include('front.comparison.partials.trading')
                    @include('front.comparison.partials.accounts')
                    @include('front.comparison.partials.platforms')
                    @include('front.comparison.partials.payments')
                    @include('front.comparison.partials.support')
                </div>
            </div>

            <!-- Footer CTA -->
            @include('front.comparison.partials.footer_cta')
        </div>
    </div>
</div>
@endsection
