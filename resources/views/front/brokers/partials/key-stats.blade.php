@php
    $prosRaw = preg_replace('/<\/?li>/', "\n", $broker->pros ?? '');
    $prosClean = strip_tags($prosRaw, '<a><b><strong><i><u><br>');
    $prosArray = array_filter(array_map('trim', explode("\n", $prosClean)));

    $consRaw = preg_replace('/<\/?li>/', "\n", $broker->cons ?? '');
    $consClean = strip_tags($consRaw, '<a><b><strong><i><u><br>');
    $consArray = array_filter(array_map('trim', explode("\n", $consClean)));
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
                            <li>{!! $pro !!}</li>
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
                            <li>{!! $con !!}</li>
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
            <div class="br-verdict__text">{!! $broker->verdict !!}</div>
        </div>
        @endif
    </div>
</section>
