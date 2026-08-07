@if(!empty($social))
    <span class="bbg-member-social" aria-label="{{ $name ?? '' }} social media">
        @foreach($social as $link)
            <a href="{{ $link['url'] }}"
               class="bbg-member-social__link"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ ($name ?? 'Team member') . ' on ' . $link['platform'] }}">
                <i class="{{ $link['icon'] }}" aria-hidden="true"></i>
            </a>
        @endforeach
    </span>
@endif
