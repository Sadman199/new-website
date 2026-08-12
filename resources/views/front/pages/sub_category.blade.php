@extends('front.layout.app')
@section('title', $sub_category_data->sub_category_name . ' | Explore Forex Topics | BrokersCourt')
@section('meta_description', 'Explore expert insights and resources on ' . $sub_category_data->sub_category_name . '. Stay informed with the latest updates, strategies, and guides in forex trading at BrokersCourt.')
@section('canonical', route('category', ['slug' => $sub_category_data->slug]))
@section('main_content')
<div class="bg-white py-8 border-b border-gray-200">
  <div class="container px-4 max-w-7xl mx-auto w-full mt-12 pt-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
      <!-- Left-aligned heading section -->
      <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
          Latest Insights on
          <span class="text-yellow-600">
            {{ $sub_category_data->sub_category_name ?? 'Subcategory not found' }}
          </span>
        </h1>
      </div>
      
      <!-- Right-aligned breadcrumb -->
      <nav class="text-sm bg-gray-100 rounded-full px-4 py-2">
        <ol class="inline-flex items-center space-x-2">
          <li class="flex items-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
              <i class="fas fa-home mr-1"></i>
              {{ HOME }}
            </a>
          </li>
          <li>
            <i class="fas fa-chevron-right text-xs text-gray-400 mx-1"></i>
          </li>
          <li class="font-medium text-gray-700">
            {{ $sub_category_data->sub_category_name }}
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
@include("front.homepage.inc.top_ad") 
<div class="pt-12">
    <div class="container px-4 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8">
                <div class="space-y-8">
                   @if($post_data->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($post_data as $item)
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
                        <p class="text-center text-gray-500 py-8">No posts found in this category.</p>
                    @endif

                    <div class="mt-8">
                        {{ $post_data->links() }}
                    </div>
                </div>
               
                
            </div>
           <div class="lg:col-span-4 pb-8">
                <div class="w-full space-y-6 sticky top-24">
                    <x-bonus-ad-card 
                        title="MT5 by OneRoyal – Live Now" 
                        badge="Now Available" 
                        :ads="$global_sidebar_top_ad" 
                    />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection