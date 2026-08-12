@php $d = $data ?? []; @endphp
<section class="cms-section cms-team">
    <div class="cms-wrap">
        @if(!empty($d['heading']))
            <h2 class="cms-section__title cms-section__title--center">{{ $d['heading'] }}</h2>
        @endif
        @if(!empty($d['subheading']))
            <p class="cms-section__lead cms-section__lead--center">{{ $d['subheading'] }}</p>
        @endif
        @if(!empty($d['items']))
            <div class="cms-team__grid">
                @foreach($d['items'] as $member)
                    <article class="cms-team__card">
                        @if(!empty($member['photo']))
                            <img src="{{ $member['photo'] }}" alt="{{ $member['name'] ?? '' }}" class="cms-team__photo" loading="lazy">
                        @endif
                        @if(!empty($member['name']))
                            <h3 class="cms-team__name">{{ $member['name'] }}</h3>
                        @endif
                        @if(!empty($member['role']))
                            <p class="cms-team__role">{{ $member['role'] }}</p>
                        @endif
                        @if(!empty($member['bio']))
                            <p class="cms-team__bio">{!! nl2br(e($member['bio'])) !!}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
