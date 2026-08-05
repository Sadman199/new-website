@if(!session()->get('session_short_name'))
    @php
    $current_short_name = $global_short_name;
    @endphp
@else
    @php
    $current_short_name = session()->get('session_short_name');
    @endphp
@endif
@php
$lang = \App\Models\Language::where('short_name',$current_short_name)->first();
$current_language_id = $lang ? $lang->id : 1;
@endphp

<div class="w-full space-y-6 mb-12">
  {{-- Promo Ad --}}
  <div class="bg-white rounded-xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-gray-100 overflow-hidden">
      <!-- Header with subtle gradient -->
      <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
          <div class="flex items-center justify-between">
              <h3 class="text-sm font-semibold text-gray-900 tracking-wide uppercase flex items-center">
                  <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                      <path d="M11 6a1 1 0 10-2 0v4a1 1 0 01-1 1H6a1 1 0 100 2h4a1 1 0 001-1V6z"/>
                  </svg>
                  Letest News
              </h3>
              <span class="text-xs font-medium text-yellow-600">• Updated</span>
          </div>
      </div>
      
      <!-- Compact news items -->
      <div class="divide-y divide-gray-100">
          @php
              $recent_news_data = \App\Models\Post::with('rSubCategory')
                  ->where('language_id', $current_language_id)
                  ->latest()
                  ->take(6)  // Showing more items since compact
                  ->get();
          @endphp
          
          @foreach($recent_news_data as $item)
              @php
                  $user_data = $item->author_id == 0 
                      ? \App\Models\Admin::find($item->admin_id) 
                      : \App\Models\Author::find($item->author_id);
                  $updated_date = $item->updated_at->format('M j, g:i A');
              @endphp
              
              <a href="{{ $item->rSubCategory ? route('news_detail', ['subcategory_slug' => $item->rSubCategory->slug, 'post_slug' => $item->slug]) : '#' }}" 
                class="block group px-4 py-3 hover:bg-yellow-50 transition-colors duration-150">
                  <div class="flex items-center gap-3">
                      <!-- 16x16px image with subtle frame -->
                      <div class="flex-shrink-0 w-12 h-12 overflow-hidden shadow-xs">
                        <img src="{{ asset('uploads/'.$item->post_photo) }}" 
                            alt="{{ $item->post_title }}"
                            class="w-full h-full object-cover rounded-md">
                      </div>

                      
                      <!-- Content -->
                      <div class="flex-1 min-w-0">
                          <h4 class="text-sm font-medium text-gray-900 mb-0.5 leading-tight group-hover:text-yellow-600 transition-colors duration-150">
                              {{ Str::limit($item->post_title, 60) }}
                          </h4>
                          
                          <!-- Meta inline -->
                          <div class="flex items-center text-xs text-gray-500 space-x-2">
                              <span class="truncate max-w-[80px]">{{ $user_data->name }}</span>
                              <span class="text-gray-300">•</span>
                              <time>{{ $updated_date }}</time>
                          </div>
                      </div>
                      
                      <!-- Chevron -->
                      <svg class="w-3 h-3 text-gray-400 group-hover:text-yellow-500 transition-colors duration-150" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                      </svg>
                  </div>
              </a>
          @endforeach
      </div>
      
      <!-- Compact footer -->
      <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 text-right">
          <a href="#" class="inline-flex items-center text-xs font-medium text-yellow-600 hover:text-yellow-800 transition-colors duration-150">
              View All
              <svg class="w-3 h-3 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
              </svg>
          </a>
      </div>
  </div>


</div>
