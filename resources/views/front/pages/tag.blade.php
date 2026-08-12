@extends('front.layout.app')
<title>{{ ucfirst($tag_name) }} | Forex Content by Tag | BrokersCourt</title>
@section('main_content')
<div class="bg-white py-8 border-b border-gray-200">
  <div class="container px-4 max-w-7xl mx-auto w-full pt-20">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
      <!-- Left-aligned heading with icon -->
      <div class="mb-4 md:mb-0 flex items-center">
          <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
            Discover All Posts Tagged
            <span class="text-yellow-500"> {{ $tag_name }} </span>
          </h1>
      </div>

      <!-- Right-aligned breadcrumb -->
      <nav class="bg-gray-100 rounded-full px-4 py-2">
        <ol class="inline-flex items-center space-x-2 text-sm">
          <li class="flex items-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
              <i class="fas fa-home mr-1"></i>
              {{ HOME }}
            </a>
          </li>
          <li>
            <i class="fas fa-chevron-right text-xs text-gray-400 mx-1"></i>
          </li>
          <li class="text-gray-700 font-medium">
            {{ $tag_name }}
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="py-12">
  <div class="container px-4 max-w-7xl mx-auto w-full">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Posts list -->
      <div class="lg:col-span-8 md:col-span-6">
        <div class="space-y-8">
          @if($all_posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              @foreach($all_posts as $item)
                @continue(!in_array($item->id, $all_post_ids))
                <div class="bg-white rounded-lg border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col sm:flex-row h-full">

                  <!-- Image and badge -->
                  <div class="w-full sm:w-2/5 relative">
                    <img src="{{ asset('uploads/' . $item->post_photo) }}" alt="{{ $item->post_title }}" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3">
                      <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-50 text-gray-800 rounded-full border border-yellow-400">
                        {{ $item->rSubCategory->sub_category_name ?? 'No sub-category available' }}
                      </span>
                    </div>
                  </div>

                  <!-- Content -->
                  <div class="w-full sm:w-3/5 p-4 flex flex-col">
                    <!-- Title -->
                    <h3 class="text-lg font-bold text-gray-900 mb-2 group">
                      <a href="{{ $item->rSubCategory ? route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) : '#' }}" class="group-hover:text-blue-700 transition-colors duration-200">
                        {{ Str::limit(strip_tags($item->post_title), 30) }}
                      </a>
                    </h3>

                    <!-- Excerpt or truncated detail -->
                    <p class="text-sm text-gray-600 mb-3 leading-snug">
                      {{ Str::limit(strip_tags($item->post_detail ?? ''), 70) }}
                    </p>

                    <!-- Metadata and iteration number -->
                    <div class="mt-auto flex items-center justify-between">
                      <div class="flex items-center text-xs text-gray-500">
                        @php
                          $author = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : $item->author;
                        @endphp
                        <span class="font-medium text-gray-700">{{ $author->name }}</span>
                        <span class="mx-1">•</span>
                        <time datetime="{{ $item->updated_at->toDateString() }}">{{ $item->updated_at->format('M j, Y') }}</time>
                      </div>
                      <span class="text-xs font-medium px-2 py-1 rounded-full {{ $loop->odd ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        #{{ $loop->iteration }}
                      </span>
                    </div>
                  </div>

                </div>
              @endforeach
            </div>
            @if($all_posts->hasPages())
                <div class="mt-8">{{ $all_posts->links() }}</div>
            @endif
          @else
            <span class="text-red-500 font-medium">{{ NO_POST_FOUND }}</span>
          @endif
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-4 md:col-span-6">
        @include('front.layout.sidebar')
      </div>

    </div>
  </div>
</div>
@endsection