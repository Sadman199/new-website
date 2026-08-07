/**
 * BrokersCourt navigation optimizer (prefetch mode)
 *
 * Uses native full-page navigation so every page gets a clean JS lifecycle
 * (tabs, sliders, owl carousel, page scripts all work normally).
 *
 * — Instant click feedback via top progress bar (persists across navigation)
 * — Hover / viewport prefetch via <link rel="prefetch"> (browser HTTP cache)
 * — No DOM swapping / no Turbo (avoids broken jQuery plugins)
 */
(function (window, document) {
    'use strict';

    if (window.__bcNavOptimizer) {
        return;
    }

    var CONFIG = {
        prefetchHover: true,
        prefetchViewport: true,
        hoverDelayMs: 80,
        viewportPrefetchLimit: 8,
        progress: true,
        veil: true,
        excludePath: [
            /^\/admin(?:\/|$)/i,
            /^\/login(?:\/|$)/i,
            /^\/register(?:\/|$)/i,
            /^\/logout(?:\/|$)/i,
            /^\/auth\//i,
        ],
    };

    var STORAGE_KEY = 'bc-nav-active';
    var prefetched = Object.create(null);
    var hoverTimer = null;
    var viewportObserver = null;
    var viewportSeen = 0;

    var progressEl = document.getElementById('bc-nav-progress');
    var progressBar = document.getElementById('bc-nav-progress__bar');
    var veilEl = document.getElementById('bc-nav-veil');

    function normalizeUrl(url) {
        try {
            var parsed = new URL(url, window.location.origin);
            parsed.hash = '';
            return parsed.href;
        } catch (error) {
            return url;
        }
    }

    function isExcludedPath(pathname) {
        return CONFIG.excludePath.some(function (pattern) {
            return pattern.test(pathname);
        });
    }

    function isNavigableLink(link, event) {
        if (!link || link.tagName !== 'A' || !link.href) {
            return false;
        }

        if (link.hasAttribute('download') || link.target === '_blank' || link.dataset.bcNoNav === 'true') {
            return false;
        }

        if (link.origin !== window.location.origin || isExcludedPath(link.pathname)) {
            return false;
        }

        if (event) {
            if (event.defaultPrevented || event.button !== 0) {
                return false;
            }
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                return false;
            }
        }

        if (link.pathname === window.location.pathname && link.search === window.location.search) {
            return false;
        }

        var rel = (link.getAttribute('rel') || '').toLowerCase();
        if (rel.indexOf('external') !== -1) {
            return false;
        }

        return true;
    }

    function shouldOptimize() {
        var mode = document.body && document.body.dataset.bcNav;
        return mode !== 'disabled' && mode !== 'off';
    }

    function startProgress() {
        if (!CONFIG.progress || !progressEl || !progressBar) {
            return;
        }
        progressEl.classList.remove('is-complete');
        progressEl.classList.add('is-active', 'is-loading');
        progressBar.style.width = '0%';
        requestAnimationFrame(function () {
            progressBar.style.width = '';
        });
        document.documentElement.classList.add('bc-nav-busy');
    }

    function finishProgress() {
        if (!CONFIG.progress || !progressEl) {
            document.documentElement.classList.remove('bc-nav-busy');
            return;
        }
        progressEl.classList.remove('is-loading');
        progressEl.classList.add('is-complete');
        window.setTimeout(function () {
            progressEl.classList.remove('is-active', 'is-complete');
            if (progressBar) {
                progressBar.style.width = '0%';
            }
            document.documentElement.classList.remove('bc-nav-busy');
        }, 220);
    }

    function showVeil() {
        if (CONFIG.veil && veilEl) {
            veilEl.classList.add('is-active');
        }
    }

    function hideVeil() {
        if (veilEl) {
            veilEl.classList.remove('is-active');
        }
    }

    function slowConnection() {
        if (!('connection' in navigator)) {
            return false;
        }
        var conn = navigator.connection;
        return conn.saveData || conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g';
    }

    function prefetch(url) {
        if (!shouldOptimize() || slowConnection()) {
            return;
        }

        var normalized = normalizeUrl(url);
        if (prefetched[normalized]) {
            return;
        }

        prefetched[normalized] = true;

        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = normalized;
        link.as = 'document';
        document.head.appendChild(link);
    }

    function onLinkClick(event) {
        var link = event.target.closest('a');
        if (!isNavigableLink(link, event) || !shouldOptimize()) {
            return;
        }

        try {
            sessionStorage.setItem(STORAGE_KEY, '1');
        } catch (error) {
            /* ignore storage errors */
        }

        startProgress();
        showVeil();
        /* Allow native navigation — do NOT preventDefault */
    }

    function onLinkHover(event) {
        if (!CONFIG.prefetchHover || !shouldOptimize()) {
            return;
        }
        var link = event.target.closest('a');
        if (!isNavigableLink(link)) {
            return;
        }
        window.clearTimeout(hoverTimer);
        hoverTimer = window.setTimeout(function () {
            prefetch(link.href);
        }, CONFIG.hoverDelayMs);
    }

    function setupViewportPrefetch() {
        if (!CONFIG.prefetchViewport || !shouldOptimize() || !('IntersectionObserver' in window)) {
            return;
        }

        if (!viewportObserver) {
            viewportObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting || viewportSeen >= CONFIG.viewportPrefetchLimit) {
                        return;
                    }
                    var link = entry.target;
                    if (!isNavigableLink(link)) {
                        return;
                    }
                    viewportSeen += 1;
                    prefetch(link.href);
                    viewportObserver.unobserve(link);
                });
            }, { rootMargin: '120px' });
        }

        document.querySelectorAll('a[href]').forEach(function (link) {
            if (link.dataset.bcViewportObserved === 'true' || !isNavigableLink(link)) {
                return;
            }
            link.dataset.bcViewportObserved = 'true';
            viewportObserver.observe(link);
        });
    }

    function warmCriticalRoutes() {
        document.querySelectorAll('[data-bc-nav-warm]').forEach(function (node) {
            var href = node.getAttribute('href') || node.getAttribute('data-bc-nav-warm');
            if (href) {
                window.setTimeout(function () {
                    prefetch(href);
                }, 1500);
            }
        });
    }

    function resumeProgressFromNavigation() {
        var resumed = false;
        try {
            resumed = sessionStorage.getItem(STORAGE_KEY) === '1';
            if (resumed) {
                sessionStorage.removeItem(STORAGE_KEY);
            }
        } catch (error) {
            resumed = false;
        }

        if (resumed) {
            startProgress();
            if (document.readyState === 'complete') {
                finishProgress();
                hideVeil();
            } else {
                window.addEventListener('load', function () {
                    finishProgress();
                    hideVeil();
                }, { once: true });
            }
        }
    }

    function bindEvents() {
        document.addEventListener('click', onLinkClick, true);
        document.addEventListener('mouseover', onLinkHover, true);
        document.addEventListener('touchstart', onLinkHover, { capture: true, passive: true });

        window.addEventListener('pageshow', function (event) {
            hideVeil();
            if (event.persisted) {
                finishProgress();
            }
        });

        document.addEventListener('submit', function () {
            if (!shouldOptimize()) {
                return;
            }
            try {
                sessionStorage.setItem(STORAGE_KEY, '1');
            } catch (error) {
                /* ignore */
            }
            startProgress();
            showVeil();
        }, true);
    }

    function init() {
        if (!shouldOptimize()) {
            return;
        }

        bindEvents();
        resumeProgressFromNavigation();
        setupViewportPrefetch();
        warmCriticalRoutes();

        window.__bcNavOptimizer = {
            prefetch: prefetch,
            config: CONFIG,
        };
    }

    window.bcOnNavigate = function (callback) {
        if (typeof callback === 'function') {
            callback();
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})(window, document);
