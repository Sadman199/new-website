<div class="py-8" id="insightsfeatures">
    <div class="text-center mb-10">
        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $broker->name }} Insights & Features</h3>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Discover what makes {{ $broker->name }} stand out with comprehensive tools and resources</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @php
            $insights = [
                'News & Analysis' => [
                    'content' => strip_tags($broker->news_and_analysis),
                    'icon' => '📰',
                    'color' => 'blue',
                    'description' => 'Market insights and analysis'
                ],
                'Top Feature' => [
                    'content' => strip_tags($broker->top_feature),
                    'icon' => '⭐',
                    'color' => 'amber',
                    'description' => 'Standout platform feature'
                ],
                'Research Tools' => [
                    'content' => strip_tags($broker->research_tools),
                    'icon' => '🔍',
                    'color' => 'green',
                    'description' => 'Advanced research capabilities'
                ],
                'Educational Resources' => [
                    'content' => strip_tags($broker->educational_resources),
                    'icon' => '🎓',
                    'color' => 'purple',
                    'description' => 'Learning materials and courses'
                ]
            ];
        @endphp

        @foreach($insights as $title => $data)
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-{{ $data['color'] }}-100 dark:bg-{{ $data['color'] }}-900/30 flex items-center justify-center text-lg">
                            {{ $data['icon'] }}
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                {{ $title }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $data['description'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="mt-4">
                    @if($data['content'])
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-left line-clamp-4">
                            {{ $data['content'] }}
                        </p>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 dark:text-gray-500 text-4xl mb-3">📝</div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No information available</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Check back later for updates</p>
                        </div>
                    @endif
                </div>

                <!-- Hover Effect Border -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-{{ $data['color'] }}-200 dark:group-hover:border-{{ $data['color'] }}-800 transition-all duration-300 pointer-events-none"></div>
            </div>
        @endforeach
    </div>
</div>