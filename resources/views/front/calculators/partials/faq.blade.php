@if(($faqs ?? []) !== [])
    <section class="calc-faq" aria-labelledby="calc-faq-title">
        <h2 class="calc-related__title" id="calc-faq-title">Frequently asked questions</h2>
        <div class="calc-faq__list">
            @foreach($faqs as $faq)
                <details class="calc-faq__item">
                    <summary class="calc-faq__q">{{ $faq['question'] }}</summary>
                    <div class="calc-faq__a">{{ $faq['answer'] }}</div>
                </details>
            @endforeach
        </div>
    </section>
@endif
