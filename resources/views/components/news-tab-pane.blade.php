<!-- resources/views/components/news-tab-pane.blade.php -->
@props(['id', 'newsData', 'isActive' => false])

<div class="tab-pane tab-{{ $id }} {{ $isActive ? 'active' : '' }}">
    @if($newsData->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($newsData as $item)
                @php
                    $userData = $item->author_id == 0 ? \App\Models\Admin::find($item->admin_id) : \App\Models\Author::find($item->author_id);
                    $updatedDate = $item->updated_at->diffForHumans();
                @endphp
                <x-news-card :item="$item" :user-data="$userData" :updated-date="$updatedDate" />
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
            </div>
            <h4 class="text-gray-700 font-medium mb-2">No News Found</h4>
            <p class="text-gray-500 text-sm max-w-md mx-auto">
                We couldn't find any news at this time. Please check back later.
            </p>
        </div>
    @endif
</div>