@if(!empty($editorialTeam))
<section class="bc-container pb-4">
    <div class="bc-editorial-team">
        <div class="bc-editorial-team__head">
            <h2 class="bc-editorial-team__title">Reviewed by our editorial team</h2>
            <p class="bc-editorial-team__desc">Every broker review is written, edited, and fact-checked before publication.</p>
        </div>
        <div class="bc-editorial-team__grid">
            @foreach($editorialTeam as $member)
                <div class="bc-editorial-team__card">
                    <div class="bc-editorial-team__avatar">
                        @if(!empty($member['photo']))
                            <img src="{{ $member['photo'] }}" alt="{{ $member['name'] }}">
                        @else
                            <span>{{ strtoupper(substr($member['name'], 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="bc-editorial-team__role">{{ $member['label'] }}</span>
                        <h3 class="bc-editorial-team__name">{{ $member['name'] }}</h3>
                        @if(!empty($member['bio']))
                            <p class="bc-editorial-team__bio">{{ $member['bio'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
