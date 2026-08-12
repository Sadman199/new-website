/**
 * BrokersCourt — quiet link prefetch (no progress bar, no overlay).
 * Warms a few high-traffic routes in the background after idle.
 */
(function (window, document) {
    'use strict';

    if (window.__bcNavPrefetch) {
        return;
    }

    var prefetched = Object.create(null);

    var EXCLUDE = [
        /^\/admin(?:\/|$)/i,
        /^\/login(?:\/|$)/i,
        /^\/register(?:\/|$)/i,
        /^\/logout(?:\/|$)/i,
        /^\/auth\//i,
    ];

    function slowConnection() {
        if (!('connection' in navigator)) {
            return false;
        }
        var conn = navigator.connection;
        return conn.saveData || conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g';
    }

    function isPrefetchable(url) {
        try {
            var parsed = new URL(url, window.location.origin);
            if (parsed.origin !== window.location.origin) {
                return false;
            }
            return !EXCLUDE.some(function (pattern) {
                return pattern.test(parsed.pathname);
            });
        } catch (error) {
            return false;
        }
    }

    function prefetch(url) {
        if (slowConnection()) {
            return;
        }

        var normalized;
        try {
            var parsed = new URL(url, window.location.origin);
            parsed.hash = '';
            normalized = parsed.href;
        } catch (error) {
            return;
        }

        if (prefetched[normalized] || normalized === window.location.href.split('#')[0]) {
            return;
        }

        prefetched[normalized] = true;

        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = normalized;
        link.as = 'document';
        document.head.appendChild(link);
    }

    function warmMarkedRoutes() {
        document.querySelectorAll('[data-bc-nav-warm]').forEach(function (node) {
            var href = node.getAttribute('href') || node.getAttribute('data-bc-nav-warm');
            if (href && isPrefetchable(href)) {
                prefetch(href);
            }
        });
    }

    function init() {
        var run = function () {
            warmMarkedRoutes();
        };

        if ('requestIdleCallback' in window) {
            window.requestIdleCallback(run, { timeout: 2500 });
        } else {
            window.setTimeout(run, 1500);
        }

        window.__bcNavPrefetch = { prefetch: prefetch };
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
