@extends('front.layout.app')
@section('title', $section_title . ' | Expert Forex Coverage | BrokersCourt')
@section('meta_description', 'Dive into ' . strtolower($section_title) . ' and stay updated with the most insightful articles and forex trends on BrokersCourt.')
@section('main_content')
<div class="bg-white py-8 border-b border-gray-200">
  <div class="container px-4 max-w-7xl mx-auto w-full pt-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
      <!-- Left-aligned heading -->
      <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
          Explore
          <span class="text-yellow-500">{{ $section_title }}</span>
        </h1>
      </div>

      <!-- Right-aligned breadcrumb -->
      <nav class="bg-gray-100 rounded-full px-4 py-2">
        <ol class="inline-flex items-center space-x-2 text-sm">
          <li class="flex items-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
              <i class="fas fa-home mr-1"></i>
              {{ __('Home') }}
            </a>
          </li>
          <li>
            <i class="fas fa-chevron-right text-xs text-gray-400 mx-1"></i>
          </li>
          <li class="text-gray-700 font-medium">
            {{ $section_title }}
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
@include("front.homepage.inc.top_ad")
<div id="post-list" class="pt-12">
  <div class="container px-4 max-w-7xl mx-auto w-full">
    <div class="space-y-8">
      @if($posts->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
          @foreach($posts as $item)
            @php
                $userData = $item->author_id == 0
                    ? \App\Models\Admin::find($item->admin_id)
                    : $item->author;
                $updatedDate = $item->updated_at->format('M j, Y');
            @endphp
            <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
          @endforeach
        </div>
      @else
        <p class="text-center text-gray-500 py-8">No articles found.</p>
      @endif
        <!-- pagination  -->
        @if ($posts->hasPages())
            <div class="mt-8 flex items-center justify-center space-x-1 pb-12">
                {{-- Previous Page Link --}}
                @if ($posts->onFirstPage())
                    <span class="px-3 py-1 rounded-md text-gray-400 cursor-not-allowed text-sm">
                        &laquo;
                    </span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
                        &laquo;
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                    @if ($page == $posts->currentPage())
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
                @if ($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}" class="px-3 py-1 rounded-md text-white bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-sm font-medium transition-all duration-200">
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
  </div>
</div>
@endsection
