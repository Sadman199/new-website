@if(!empty($editorialTeam))
<section class="br-author" id="author-profile">
    <div class="br-author__inner">
        <div class="br-author__head">
            <h2 class="br-author__title">About the author</h2>
            <p class="br-author__desc">This review was researched, written, and verified by the BrokersCourt editorial team.</p>
        </div>
        <div class="br-author__grid">
            @foreach($editorialTeam as $member)
                <article class="br-author__card">
                    <div class="br-author__avatar">
                        @if(!empty($member['photo']))
                            <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}" loading="lazy">
                        @else
                            <span>{{ strtoupper(substr($member['name'], 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="br-author__content">
                        <p class="br-author__role">{{ $member['label'] }}</p>
                        <h3 class="br-author__name">{{ $member['name'] }}</h3>
                        @if(!empty($member['bio']))
                            <p class="br-author__bio">{{ $member['bio'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        @if(!empty($editorialCredits))
            <div class="br-author__credits">
                @foreach($editorialCredits as $credit)
                    <span><strong>{{ $credit['label'] }}:</strong> {{ $credit['name'] }}</span>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
