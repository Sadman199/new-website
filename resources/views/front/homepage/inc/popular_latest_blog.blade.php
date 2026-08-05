<section class="py-12 px-4 sm:px-6 lg:px-8 bg-white">
    <div class="max-w-7xl mx-auto">
        <!-- Latest News Section -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6 pb-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                  Latest Market Insights & Updates
                </h2>
                <a href="{{ route('news_latest') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    View All
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($recentNewsData as $item)
                    @php
                        $userData = $item->author_id == 0
                            ? \App\Models\Admin::find($item->admin_id)
                            : $item->author;
                        $updatedDate = $item->updated_at->format('M j, Y');
                    @endphp

                    <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
                @endforeach
            </div>
        </div>

        <!-- Popular News Section -->
        <div class="mt-16">
            <div class="flex items-center justify-between mb-6  pb-2">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Most Popular News & Top Stories

                </h2>
                <a href="{{ route('news_popular') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    View All
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($popularNewsData as $item)
                    @php
                        $userData = $item->author_id == 0
                            ? \App\Models\Admin::find($item->admin_id)
                            : $item->author;
                        $updatedDate = $item->updated_at->format('M j, Y');
                    @endphp

                    <x-news-card :item="$item" :userData="$userData" :updatedDate="$updatedDate" />
                @endforeach
            </div>
        </div>

    </div>
</section>
