<div class="bc-notify-wrap" id="bcNotifyWrap">
    <button type="button"
            id="bcNotifyBell"
            class="bc-nav-icon-btn bc-notify-bell"
            aria-label="Notifications"
            aria-expanded="false"
            aria-controls="bcNotifyPanel"
            data-index-url="{{ route('user.notifications.index') }}"
            data-read-all-url="{{ route('user.notifications.read_all') }}"
            data-read-url="{{ url('/notifications') }}/">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span class="bc-notify-badge is-hidden" id="bcNotifyBadge" aria-hidden="true">0</span>
    </button>

    <div id="bcNotifyPanel" class="bc-notify-panel" hidden>
        <div class="bc-notify-head">
            <span class="bc-notify-head__title">Notifications</span>
            <button type="button" id="bcNotifyMarkAll" class="bc-notify-mark-all">Mark all read</button>
        </div>
        <ul id="bcNotifyList" class="bc-notify-list" role="list"></ul>
        <div class="bc-notify-foot">
            <a href="{{ route('user.profile', ['tab' => 'overview']) }}">Open profile overview</a>
        </div>
    </div>
</div>
