(function () {
    'use strict';

    var app = document.getElementById('bli-app');
    var feed = document.getElementById('bli-feed');

    if (!app || !feed) {
        return;
    }

    var baseUrl = app.getAttribute('data-blog-url') || '/blog';
    var controller = null;
    var tabsNav = app.querySelector('[data-blog-tabs]');
    var tabsTrack = app.querySelector('[data-blog-tabs-track]');
    var tabsPrev = app.querySelector('[data-blog-tabs-prev]');
    var tabsNext = app.querySelector('[data-blog-tabs-next]');

    function currentUrl() {
        return window.location.pathname + window.location.search;
    }

    function closest(el, selector) {
        if (!el) {
            return null;
        }
        if (el.nodeType !== 1) {
            el = el.parentElement;
        }
        return el && el.closest ? el.closest(selector) : null;
    }

    function setBusy(busy) {
        feed.classList.toggle('is-loading', busy);
        feed.setAttribute('aria-busy', busy ? 'true' : 'false');
    }

    function tabStep() {
        if (!tabsTrack) {
            return 240;
        }
        return Math.max(180, Math.round(tabsTrack.clientWidth * 0.72));
    }

    function updateTabsNav() {
        if (!tabsTrack || !tabsPrev || !tabsNext) {
            return;
        }

        var maxScroll = tabsTrack.scrollWidth - tabsTrack.clientWidth;
        var slop = 6;
        var overflow = maxScroll > slop;

        tabsNav.classList.toggle('has-overflow', overflow);
        tabsPrev.disabled = !overflow || tabsTrack.scrollLeft <= slop;
        tabsNext.disabled = !overflow || tabsTrack.scrollLeft >= maxScroll - slop;
    }

    function scrollActiveTabIntoView() {
        if (!tabsTrack) {
            return;
        }
        var active = tabsTrack.querySelector('.bli-tabs__tab.is-active');
        if (!active || typeof active.scrollIntoView !== 'function') {
            return;
        }
        active.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
    }

    function markTab(slug) {
        app.querySelectorAll('[data-blog-tab]').forEach(function (tab) {
            var active = tab.getAttribute('data-blog-tab') === slug;
            tab.classList.toggle('is-active', active);
            if (active) {
                tab.setAttribute('aria-current', 'page');
            } else {
                tab.removeAttribute('aria-current');
            }
        });
        window.requestAnimationFrame(function () {
            updateTabsNav();
            scrollActiveTabIntoView();
        });
    }

    function load(url, push) {
        if (controller) {
            controller.abort();
        }

        controller = window.AbortController ? new AbortController() : null;
        setBusy(true);

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            signal: controller ? controller.signal : undefined
        })
            .then(function (res) {
                if (!res.ok) {
                    throw new Error('Request failed');
                }
                return res.json();
            })
            .then(function (data) {
                feed.innerHTML = data.html || '';
                markTab(data.activeTab || 'all');
                if (data.title) {
                    document.title = data.title;
                }
                if (push) {
                    history.pushState({ blog: true }, data.title || '', url);
                }
                setBusy(false);
            })
            .catch(function (err) {
                if (err && err.name === 'AbortError') {
                    return;
                }
                setBusy(false);
                window.location.href = url;
            });
    }

    if (tabsPrev && tabsTrack) {
        tabsPrev.addEventListener('click', function () {
            tabsTrack.scrollBy({ left: -tabStep(), behavior: 'smooth' });
        });
    }

    if (tabsNext && tabsTrack) {
        tabsNext.addEventListener('click', function () {
            tabsTrack.scrollBy({ left: tabStep(), behavior: 'smooth' });
        });
    }

    if (tabsTrack) {
        tabsTrack.addEventListener('scroll', updateTabsNav, { passive: true });
        tabsTrack.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                tabsTrack.scrollBy({ left: -tabStep(), behavior: 'smooth' });
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                tabsTrack.scrollBy({ left: tabStep(), behavior: 'smooth' });
            }
        });
    }

    if (typeof ResizeObserver !== 'undefined' && tabsTrack) {
        new ResizeObserver(updateTabsNav).observe(tabsTrack);
    }

    window.addEventListener('resize', updateTabsNav);

    app.addEventListener('click', function (event) {
        var tab = closest(event.target, '[data-blog-tab]');
        if (tab && app.contains(tab)) {
            event.preventDefault();
            var slug = tab.getAttribute('data-blog-tab') || 'all';
            var url = slug === 'all' ? baseUrl : (baseUrl + '?category=' + encodeURIComponent(slug));
            if (url === currentUrl() && tab.classList.contains('is-active')) {
                return;
            }
            load(url, true);
            return;
        }

        var pageLink = closest(event.target, '.bli-pagination a');
        if (pageLink && feed.contains(pageLink)) {
            event.preventDefault();
            load(pageLink.getAttribute('href'), true);
        }
    });

    window.addEventListener('popstate', function () {
        load(currentUrl(), false);
    });

    if (!history.state || !history.state.blog) {
        history.replaceState({ blog: true }, document.title, currentUrl());
    }

    updateTabsNav();
    scrollActiveTabIntoView();
})();
