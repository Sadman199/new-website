<section class="br-faqs" id="faqs">
    <div class="br-section br-section--faqs">
    <div class="br-section__head">
        <h2 class="br-section__title">Frequently Asked Questions</h2>
        <p class="br-section__desc">Common questions about {{ $broker->name ?? 'this broker' }}</p>
    </div>
    <div class="br-section__body">
        @if($faqs->isNotEmpty())
            @foreach($faqs as $faq)
                <div class="br-faq-item">
                    <button type="button" class="br-faq-q">
                        <span>{{ $faq->faq_title }}</span>
                        <span class="br-faq-q__indicator" aria-hidden="true"></span>
                    </button>
                    <div class="br-faq-a">{!! strip_tags($faq->faq_detail, '<a><b><strong><br><p><ul><ol><li>') !!}</div>
                </div>
            @endforeach
        @else
            <p class="br-empty">No FAQs available for this broker.</p>
        @endif
    </div>
    </div>
</section>
