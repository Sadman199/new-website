@if(!empty($editorialTeam))
<section class="br-guide-author-panel" aria-labelledby="guide-author-heading">
    <div class="br-guide-author-panel__head">
        <h2 class="br-guide-author-panel__title" id="guide-author-heading">Written &amp; reviewed by</h2>
        @if(!empty($guidePageMeta['updated_at']))
            <p class="br-guide-author-panel__updated">
                <i class="far fa-clock" aria-hidden="true"></i>
                Updated {{ $guidePageMeta['updated_at'] }}
            </p>
        @endif
    </div>

    <div class="br-guide-author-panel__grid">
        @foreach($editorialTeam as $member)
            @if(!empty($member['name']))
                <article class="br-guide-author-card">
                    <div class="br-guide-author-card__avatar">
                        @if(!empty($member['photo']))
                            <img src="{{ $member['photo'] }}" alt="" loading="lazy" decoding="async">
                        @else
                            <span>{{ strtoupper(substr($member['name'], 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="br-guide-author-card__body">
                        <p class="br-guide-author-card__role">{{ $member['label'] ?? 'Contributor' }}</p>
                        <h3 class="br-guide-author-card__name">{{ $member['name'] }}</h3>
                        @if(!empty($member['bio']))
                            <p class="br-guide-author-card__bio">{{ Str::limit($member['bio'], 160) }}</p>
                        @endif
                        @include('front.brokers.partials.best_guide_member_social', [
                            'social' => $member['social'] ?? [],
                            'name' => $member['name'],
                        ])
                    </div>
                </article>
            @endif
        @endforeach
    </div>
</section>
@endif
