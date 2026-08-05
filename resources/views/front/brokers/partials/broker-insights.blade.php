<section class="bc-section" id="insightsfeatures">
    <div class="bc-section__head">
        <h2 class="bc-section__title">Insights & Features</h2>
        <p class="bc-section__desc">Research, education, and standout capabilities</p>
    </div>
    <div class="bc-section__body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $insights = [
                    ['title' => 'News & Analysis', 'content' => $broker->news_and_analysis, 'icon' => 'fa-newspaper'],
                    ['title' => 'Top Feature', 'content' => $broker->top_feature, 'icon' => 'fa-star'],
                    ['title' => 'Research Tools', 'content' => $broker->research_tools, 'icon' => 'fa-search'],
                    ['title' => 'Education', 'content' => $broker->educational_resources, 'icon' => 'fa-graduation-cap'],
                ];
            @endphp
            @foreach($insights as $insight)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-200 transition-colors">
                    <h4 class="font-bold text-gray-900 flex items-center gap-2 mb-2">
                        <span class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-sm"><i class="fas {{ $insight['icon'] }}"></i></span>
                        {{ $insight['title'] }}
                    </h4>
                    @if(strip_tags($insight['content'] ?? ''))
                        <p class="text-sm text-gray-600 leading-relaxed">{{ strip_tags($insight['content']) }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Not available</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
