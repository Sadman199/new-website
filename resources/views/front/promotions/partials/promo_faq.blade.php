@if(!empty($guide['faqs']))
    <section class="bpr-faq-section" aria-labelledby="bprFaqTitle">
        <div class="bpr-faq-section__head">
            <p class="bpr-section__eyebrow">Questions</p>
            <h2 class="bpr-faq-section__title" id="bprFaqTitle">Broker promos FAQ</h2>
        </div>

        <div class="bpr-faq">
            @foreach($guide['faqs'] as $index => $faq)
                <div class="bpr-faq__item">
                    <button type="button"
                            class="bpr-faq__question"
                            aria-expanded="false"
                            aria-controls="bpr-faq-answer-{{ $index }}"
                            id="bpr-faq-question-{{ $index }}">
                        <span>{{ $faq['question'] }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                        </svg>
                    </button>
                    <div class="bpr-faq__answer"
                         id="bpr-faq-answer-{{ $index }}"
                         role="region"
                         aria-labelledby="bpr-faq-question-{{ $index }}"
                         hidden>
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
