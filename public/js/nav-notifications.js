'use strict';

(function () {
    const bell = document.getElementById('bcNotifyBell');
    const panel = document.getElementById('bcNotifyPanel');
    const badge = document.getElementById('bcNotifyBadge');
    const list = document.getElementById('bcNotifyList');
    const markAllBtn = document.getElementById('bcNotifyMarkAll');
    const wrap = document.getElementById('bcNotifyWrap');

    if (!bell || !panel || !list) {
        return;
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const indexUrl = bell.dataset.indexUrl;
    const readAllUrl = bell.dataset.readAllUrl;
    const readBaseUrl = bell.dataset.readUrl || '/notifications/';

    let isOpen = false;

    function setBadge(count) {
        if (!badge) {
            return;
        }

        if (count > 0) {
            badge.textContent = count > 9 ? '9+' : String(count);
            badge.classList.remove('is-hidden');
        } else {
            badge.classList.add('is-hidden');
        }
    }

    function iconForType(type) {
        switch (type) {
            case 'review_approved':
                return '✓';
            case 'review_declined':
                return '✕';
            case 'review_pending':
            default:
                return '•';
        }
    }

    function renderItems(items, unread) {
        setBadge(unread);

        if (markAllBtn) {
            markAllBtn.disabled = unread === 0;
        }

        if (!items.length) {
            list.innerHTML = '<li class="bc-notify-empty">No notifications yet.</li>';
            return;
        }

        list.innerHTML = items.map(function (item) {
            const unreadClass = item.read ? '' : ' is-unread';
            const href = item.url || '#';
            return (
                '<li class="bc-notify-item">' +
                    '<a href="' + href + '" class="bc-notify-link' + unreadClass + '" data-id="' + item.id + '" data-read="' + (item.read ? '1' : '0') + '">' +
                        '<span class="bc-notify-title">' + iconForType(item.type) + ' ' + escapeHtml(item.title) + '</span>' +
                        '<span class="bc-notify-message">' + escapeHtml(item.message) + '</span>' +
                        '<span class="bc-notify-time">' + escapeHtml(item.time || '') + '</span>' +
                    '</a>' +
                '</li>'
            );
        }).join('');
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    async function fetchNotifications() {
        try {
            const response = await fetch(indexUrl, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                return;
            }

            const data = await response.json();
            renderItems(data.items || [], data.unread || 0);
        } catch (error) {
            // Silent fail — bell stays usable without blocking the page.
        }
    }

    async function markRead(id) {
        try {
            await fetch(readBaseUrl + id + '/read', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
            });
        } catch (error) {
            // Ignore — navigation still proceeds.
        }
    }

    async function markAllRead() {
        if (!readAllUrl) {
            return;
        }

        try {
            const response = await fetch(readAllUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
            });

            if (response.ok) {
                await fetchNotifications();
            }
        } catch (error) {
            // Ignore.
        }
    }

    function openPanel() {
        isOpen = true;
        panel.hidden = false;
        bell.setAttribute('aria-expanded', 'true');
        fetchNotifications();
    }

    function closePanel() {
        isOpen = false;
        panel.hidden = true;
        bell.setAttribute('aria-expanded', 'false');
    }

    bell.addEventListener('click', function (event) {
        event.stopPropagation();
        if (isOpen) {
            closePanel();
        } else {
            openPanel();
        }
    });

    if (markAllBtn) {
        markAllBtn.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            markAllRead();
        });
    }

    list.addEventListener('click', function (event) {
        const link = event.target.closest('.bc-notify-link');
        if (!link || link.dataset.read === '1') {
            return;
        }

        markRead(link.dataset.id);
    });

    document.addEventListener('click', function (event) {
        if (!isOpen) {
            return;
        }

        if (wrap && !wrap.contains(event.target)) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && isOpen) {
            closePanel();
        }
    });

    fetchNotifications();
})();
