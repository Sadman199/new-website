@if(!empty($editorialTeam))
    <div class="bbg-editorial bbg-editorial--compact" id="page-author">
        <p class="bbg-editorial__updated">Updated {{ $guidePage['updated_at'] }}</p>

        <ul class="bbg-editorial__list">
            @foreach($editorialTeam as $index => $member)
                @if(!empty($member['name']))
                    <li class="bbg-editorial__item">
                        <span class="bbg-editorial__avatar" aria-hidden="true">
                            @if(!empty($member['photo']))
                                <img src="{{ $member['photo'] }}" alt="" loading="lazy" decoding="async">
                            @else
                                <span>{{ strtoupper(substr($member['name'], 0, 1)) }}</span>
                            @endif
                        </span>

                        <span class="bbg-editorial__label">{{ $member['label'] ?? 'Contributor' }}</span>

                        <span class="bbg-author-popover">
                            <button type="button"
                                    class="bbg-author-popover__trigger"
                                    aria-describedby="bbg-author-popover-{{ $index }}">
                                {{ $member['name'] }}
                            </button>

                            <span class="bbg-author-popover__card" id="bbg-author-popover-{{ $index }}" role="tooltip">
                                <span class="bbg-author-popover__card-head">
                                    @if(!empty($member['photo']))
                                        <img src="{{ $member['photo'] }}" alt="" class="bbg-author-popover__card-photo" loading="lazy" decoding="async">
                                    @endif
                                    <span>
                                        <strong class="bbg-author-popover__card-name">{{ $member['name'] }}</strong>
                                        <span class="bbg-author-popover__card-role">{{ $member['label'] ?? 'Contributor' }}</span>
                                    </span>
                                </span>

                                @if(!empty($member['bio']))
                                    <p class="bbg-author-popover__card-bio">{{ $member['bio'] }}</p>
                                @endif

                                @include('front.brokers.partials.best_guide_member_social', [
                                    'social' => $member['social'] ?? [],
                                    'name' => $member['name'],
                                ])
                            </span>
                        </span>

                        @include('front.brokers.partials.best_guide_member_social', [
                            'social' => $member['social'] ?? [],
                            'name' => $member['name'],
                        ])
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@else
    <p class="bbg-hero__byline-fallback">Updated {{ $guidePage['updated_at'] }}</p>
@endif
