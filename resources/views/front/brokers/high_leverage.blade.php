@extends('front.layout.app')
@section('title', 'BrokersCourt | High Leverage Brokers (1:1000+)')
@section('main_content')

{{-- Hero Section with Breadcrumb --}}
<section class="relative bg-gradient-to-b from-gray-900 to-blue-900/30 flex flex-col items-center justify-center text-center min-h-[calc(100vh-80px)] py-28 pt-40">
  <div class="relative container mx-auto px-6 z-10">
    <div class="max-w-4xl mx-auto text-center">
      <!-- Title with Icon -->
      <div class="flex justify-center items-center mb-6">
        <i class="fas fa-bolt text-3xl text-yellow-400 mr-3"></i>
        <h1 class="text-2xl md:text-4xl font-extrabold text-white drop-shadow-lg">
          High <span class="text-yellow-400">Leverage</span> Brokers
        </h1>
      </div>

      <!-- Subtitle -->
      <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
        Explore brokers offering ultra high leverage accounts for amplified trading potential.
      </p>

      <!-- Breadcrumb -->
      <nav aria-label="Breadcrumb" class="flex justify-center">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
          <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-blue-100 hover:text-white transition">
              <i class="fas fa-home mr-2"></i>
              Home
            </a>
          </li>
          <li>
            <i class="fas fa-chevron-right text-xs text-blue-200 mx-1"></i>
          </li>
          <li class="inline-flex items-center">
            <span class="text-sm font-semibold text-white">
              High Leverage Brokers
            </span>
          </li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Scrolling indicator -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
    <a href="#broker-list" class="animate-bounce inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white hover:bg-white/20 transition">
      <i class="fas fa-chevron-down"></i>
    </a>
  </div>
</section>

{{-- Broker Listing Section --}}
<section id="broker-list" class="py-12 bg-gray-50">
  <div class="container max-w-7xl mx-auto">
    <div>
        @if ($brokers->count() > 0)
            <div class="space-y-4">
                <x-broker-table-header />
                @foreach ($brokers as $broker)
                    <x-broker-row :broker="$broker" />
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($brokers->hasPages())
                <div class="mt-8 flex items-center justify-center space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($brokers->onFirstPage())
                        <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $brokers->previousPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                            &laquo;
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($brokers->getUrlRange(1, $brokers->lastPage()) as $page => $url)
                        @if ($page == $brokers->currentPage())
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
                    @if ($brokers->hasMorePages())
                        <a href="{{ $brokers->nextPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                            &raquo;
                        </a>
                    @else
                        <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">
                            &raquo;
                        </span>
                    @endif
                </div>
            @endif

        @else
            <x-no-brokers-found message="No high leverage brokers found at the moment." />
        @endif
    </div>
  </div>
</section>


@endsection
