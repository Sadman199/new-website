@php
    use App\Support\RichText;

    $prosArray = RichText::listItems($broker->pros ?? null);
    $consArray = RichText::listItems($broker->cons ?? null);
@endphp

<section class="br-section" id="key-stats">
    <div class="br-section__head">
        <h2 class="br-section__title">Pros & Cons</h2>
        <p class="br-section__desc">What we like and what could be improved about {{ $broker->name }}</p>
    </div>
    <div class="br-section__body">
        <div class="br-pros-cons">
            <div class="br-pros">
                <h3 class="br-pros__title">Pros</h3>
                <ul>
                    @forelse($prosArray as $pro)
                        @if($pro !== '')
                            <li>{{ $pro }}</li>
                        @endif
                    @empty
                        <li class="br-empty">No pros listed yet.</li>
                    @endforelse
                </ul>
            </div>
            <div class="br-cons">
                <h3 class="br-cons__title">Cons</h3>
                <ul>
                    @forelse($consArray as $con)
                        @if($con !== '')
                            <li>{{ $con }}</li>
                        @endif
                    @empty
                        <li class="br-empty">No cons listed yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        @if($broker->verdict)
        <div class="br-verdict">
            <div class="br-verdict__label">Our Verdict</div>
            <div class="br-verdict__text">{!! \App\Support\RichText::forDisplay($broker->verdict) !!}</div>
        </div>
        @endif
    </div>
</section>
