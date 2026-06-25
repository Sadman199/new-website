@extends('front.layout.app')
@section('title', $bonus->meta_title ?? $bonus->title)
@section('meta_description', $bonus->meta_description ?? Str::limit(strip_tags($bonus->description), 150))
@section('meta_keywords', $bonus->meta_keywords ?? 'forex, trading, bonus, brokers')
@section('main_content')

<div class="bg-white py-8 border-b border-gray-200 mb-12">
  <div class="container px-4 max-w-7xl mx-auto w-full pt-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
      <!-- Left-aligned heading -->
      <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
          <span class="text-yellow-500">{{ ucfirst($promo_type) }}</span> Promotion
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
            {{ ucfirst($promo_type) }}
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
  
<div class="container px-4 max-w-7xl mx-auto w-full">
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 sm:mb-8">
      <div class="bg-white rounded-xl overflow-hidden">
        <div class="bg-gray-50 p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
        <!-- Left Section: Icon and Title -->
        <div class="flex items-start sm:items-center space-x-3">
          <!-- Prize Icon -->
          <div class="bg-gray-200 p-2 rounded-md flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4H5z" />
            </svg>
          </div>

          <!-- Prize Title -->
          <div>
            <span class="text-xs font-medium text-gray-500 uppercase block">Trading Bonus</span>
            <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-800 leading-snug">
              {{ strip_tags($bonus->prize) }}
            </h3>
          </div>
        </div>
      </div>

  
      <!-- Main Content -->
      <div class="p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Image and Basic Info -->
        <div class="lg:col-span-1">
          <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 flex items-center justify-center h-48">
            @if ($bonus->feature_image)
              <img src="{{ asset($bonus->feature_image) }}" class="w-full h-full object-contain" alt="Bonus Offer" />
            @else
              <div class="text-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="mt-2 text-sm">No image available</p>
              </div>
            @endif
          </div>
          
          <!-- Quick Stats -->
          <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
              <p class="text-xs font-medium text-gray-500 mb-1">Min. Deposit</p>
              <p class="text-lg font-semibold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                ${{ strip_tags($bonus->min_deposit) }}
              </p>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
              <p class="text-xs font-medium text-gray-500 mb-1">Expires</p>
              <p class="text-lg font-semibold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ strip_tags($bonus->expiry_date) }}
              </p>
            </div>
          </div>
        </div>
        
        <!-- Middle Column - Description and Features -->
        <div class="lg:col-span-1">
          <div class="border-l-4 border-yellow-500 pl-4 mb-4">
            <h4 class="text-lg font-bold text-gray-800">Bonus Features</h4>
            <p class="text-sm text-gray-600 mt-1">Premium trading conditions with this limited-time offer</p>
          </div>
          
          <!-- Forex-themed features -->
          
          <ul class="space-y-3">
              @foreach(explode('</li>', $bonus->details) as $item)
                  @php
                      $cleanItem = trim(strip_tags($item));
                  @endphp
                  @if($cleanItem)
                      <li class="flex items-center group hover:bg-gray-50 rounded-lg p-2 transition-all duration-200">
                          <div class="w-4 h-4 rounded-full bg-emerald-100 flex-shrink-0 mr-3 flex items-center justify-center">
                              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                              </svg>
                          </div>
                          <span class="text-gray-700 text-sm font-medium group-hover:text-gray-900">{{ $cleanItem }}</span>
                      </li>
                  @endif
              @endforeach
          </ul>

        </div>
        
        <!-- Right Column - Meta Info -->
        <div class="lg:col-span-1">
          <div class="bg-gray-50 rounded-xl p-4 sm:p-5 border border-gray-200 h-full">
            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Broker Details
            </h4>
            
            <div class="space-y-4">
              <div>
                <p class="text-xs font-medium text-gray-500">Author Name</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">{{ strip_tags($bonus->author_name) }}</p>
              </div>
              
            
              <div>
                <p class="text-xs font-medium text-gray-500 mb-2">Offer Category</p>
                  <!-- Right Section: Promo Type Badge -->
                <span class="bg-gray-600 text-white text-xs font-medium px-2.5 py-1 rounded-md">
                  {{ strip_tags($bonus->promo_type) }}
                </span>
              </div>
              
              <div>
                <p class="text-xs font-medium text-gray-500">Offer Published</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">{{ strip_tags($bonus->publish_date) }}</p>
              </div>
            </div>
            
            <button class="mt-6 w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-all duration-200">
              Claim This Bonus
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
    @php
      $cards = [
        [
          'title' => 'Participation Details',
          'iconColor' => 'bg-yellow-100',
          'textColor' => 'text-yellow-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
          'content' => nl2br(e(strip_tags($bonus->how_to_participate)))
        ],
        [
          'title' => 'Bonus Offer Overview',
          'iconColor' => 'bg-indigo-100',
          'textColor' => 'text-indigo-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
          'content' => nl2br(e(strip_tags($bonus->bonus_type_details)))
        ],
        [
          'title' => 'General Terms',
          'iconColor' => 'bg-green-100',
          'textColor' => 'text-green-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
          'content' => nl2br(e(strip_tags($bonus->general_terms)))
        ],
        [
          'title' => 'Country Restrictions',
          'iconColor' => 'bg-red-100',
          'textColor' => 'text-red-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
          'content' => strip_tags($bonus->participate)
        ],
        [
          'title' => 'Eligibility Criteria',
          'iconColor' => 'bg-purple-100',
          'textColor' => 'text-purple-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />',
          'content' => nl2br(e(strip_tags($bonus->eligibility_criteria)))
        ],
        [
          'title' => 'Description',
          'iconColor' => 'bg-indigo-100',
          'textColor' => 'text-indigo-600',
          'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
          'content' => strip_tags($bonus->description)
        ],
      ];
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
      @foreach ($cards as $card)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 hover:shadow-sm transition-shadow duration-300">
          <div class="p-4 sm:p-6">
            <div class="flex items-start sm:items-center mb-4">
              <div class="{{ $card['iconColor'] }} p-2 sm:p-3 rounded-full mr-3 sm:mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 {{ $card['textColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  {!! $card['svg'] !!}
                </svg>
              </div>
              <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-800 leading-tight">
                {{ $card['title'] }}
              </h3>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg text-sm sm:text-base leading-relaxed text-gray-700">
              {!! $card['content'] !!}
            </div>
          </div>
        </div>
      @endforeach
    </div>
</div>
@endsection