@extends('front.layout.app')
@section('title', $post_detail->meta_title ?? $post_detail->title)
@section('meta_description', $post_detail->meta_description ?? Str::limit(strip_tags($post_detail->description), 150))
@section('meta_keywords', $post_detail->meta_keywords)
@section('main_content')

<div class="bg-white border-b">
    <!-- Article Header -->
    <div class="container px-4 mx-auto max-w-7xl mt-12 pt-20">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight">
        {{ $post_detail->post_title }}
      </h1>
       <nav class="mb-4">
        <ol class="flex items-center text-sm text-gray-600 space-x-2">
          <li>
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
          </li>
          <li class="text-gray-400">/</li>
          <li>
            <a href="{{ route('category', $post_detail->rSubCategory->slug) }}" class="hover:text-blue-600 transition">
              {{ $post_detail->rSubCategory->sub_category_name }}
            </a>
          </li>
        </ol>
      </nav>
      <!-- Meta Line -->
      <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-4">
        <div class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
          </svg>
          {{ $user_data->name ?? 'Staff' }}
        </div>
        <span>•</span>
        <div>
          @php
            $ts = strtotime($post_detail->updated_at);
            $updated_date = date('d M, Y', $ts);
          @endphp
          {{ $updated_date }}
        </div>
        <span>•</span>
        <div class="flex items-center">
          <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
          </svg>
          {{ $post_detail->visitors }} views
        </div>
      </div>

      @if(!empty($editorialCredits))
      <div class="flex flex-wrap gap-2 mb-6">
        @foreach($editorialCredits as $credit)
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
            <span class="font-semibold mr-1">{{ $credit['label'] }}:</span> {{ $credit['name'] }}
          </span>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>

<div class="main py-8">
  <div class="container px-4 mx-auto max-w-7xl">
    <div class="flex flex-col lg:flex-row gap-8">
      <!-- Main Content -->
      <main class="lg:w-2/3">
        <!-- Featured Image -->
        <div class="mb-6 rounded-lg overflow-hidden">
          <img src="{{ asset('uploads/'.$post_detail->post_photo) }}" 
               alt="{{ $post_detail->post_title }}" 
               class="w-full h-auto">
        </div>

        <!-- Article Content -->
           <article class="rich-text max-w-4xl mx-auto mb-8">
              {!! $post_detail->post_detail !!}
          </article>

        <!-- Tags -->
        <div class="mb-8">
          <div class="flex flex-wrap gap-2">
            @foreach($tag_data as $item)
              <a href="{{ route('tag_posts_show', $item->tag_name) }}" 
                 class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm font-medium transition-colors">
                #{{ $item->tag_name }}
              </a>
            @endforeach
          </div>
        </div>

        <!-- Related News -->
        <section class="mb-8">
          <h2 class="text-xl font-bold border-b pb-2 mb-4">Related News</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($related_post_array as $item)
              @continue($item->id == $post_detail->id)
              @php
                $user_data = $item->author_id == 0
                    ? \App\Models\Admin::find($item->admin_id)
                    : \App\Models\Author::find($item->author_id);
                $updated_date = $item->updated_at->format('d M, Y');
              @endphp

              <article class="border rounded-md overflow-hidden hover:shadow-md transition">
                <a href="{{ route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) }}" class="block">
                  <div class="flex">
                    <div class="w-1/3">
                      <img src="{{ asset('uploads/'.$item->post_photo) }}" alt="{{ $item->post_title }}" class="w-full h-24 object-cover">
                    </div>
                    <div class="w-2/3 p-3">
                      <h3 class="font-semibold text-sm mb-1 line-clamp-2">{{ $item->post_title }}</h3>
                      <div class="text-xs text-gray-500">{{ $updated_date }}</div>
                    </div>
                  </div>
                </a>
              </article>
            @endforeach
          </div>
        </section>
      </main>

      <!-- Sidebar -->
      <aside class="lg:w-1/3">
        <div class="sticky top-24 space-y-6">
          @include('front.layout.sidebar')
        </div>
      </aside>
    </div>
  </div>
</div>

@endsection