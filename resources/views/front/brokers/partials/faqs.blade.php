
<div id="faqs" class="faq-section space-y-6 p-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Frequently Asked Questions</h3>
    @if($faqs->isNotEmpty())
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="accordion-item bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden">
                    <div class="accordion-header cursor-pointer px-6 py-4 bg-gray-100 dark:bg-gray-200 text-gray-900 dark:text-gray-100 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" data-toggle="#faq{{ $faq->id }}">
                        {{ $faq->faq_title }}
                    </div>
                    <div class="accordion-content hidden px-6 py-4 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300" id="faq{{ $faq->id }}">
                        <p class="text-sm leading-relaxed">    {!! strip_tags($faq->faq_detail) !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 dark:text-gray-400">No FAQs available for this broker.</p>
    @endif
</div>
