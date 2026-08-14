/**
 * BrokersCourt — quiet asset warm-up (no progress bar, no overlay).
 * Does NOT prefetch full HTML documents (that wasted ~500KB+ idle bandwidth).
 * Optionally warms CSS/JS for high-traffic nav targets on hover/focus intent.
 */
(function (window, document) {
    'use strict';

    if (window.__bcNavPrefetch) {
        return;
    }

    var warmed = Object.create(null);

    var ROUTE_ASSETS = {
        '/broker-reviews': [
            '/css/broker-reviews-index.css',
            '/js/broker-reviews-index.js'
        ],
        '/awards': [
            '/css/awards-index.css',
            '/js/awards-index.js'
        ],
        '/trading-tools': [
            '/css/trading-tools.css',
            '/js/trading-tools.js'
        ]
    };

    function slowConnection() {
        if (!('connection' in navigator)) {
            return false;
        }
        var conn = navigator.connection;
        return conn.saveData || conn.effectiveType === 'slow-2g' || conn.effectiveType === '2g';
    }

    function warmAsset(href) {
        if (slowConnection() || warmed[href]) {
            return;
        }
        warmed[href] = true;

        var link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = href;
        if (/\.css(\?|$)/i.test(href)) {
            link.as = 'style';
        } else if (/\.js(\?|$)/i.test(href)) {
            link.as = 'script';
        }
        document.head.appendChild(link);
    }

    function assetsForHref(href) {
        try {
            var parsed = new URL(href, window.location.origin);
            if (parsed.origin !== window.location.origin) {
                return [];
            }
            var path = parsed.pathname.replace(/\/$/, '') || '/';
            return ROUTE_ASSETS[path] || [];
        } catch (error) {
            return [];
        }
    }

    function warmFromNode(node) {
        var href = node.getAttribute('href') || node.getAttribute('data-bc-nav-warm');
        if (!href) {
            return;
        }
        assetsForHref(href).forEach(warmAsset);
    }

    function bindIntentWarm() {
        document.querySelectorAll('[data-bc-nav-warm]').forEach(function (node) {
            var once = function () {
                warmFromNode(node);
                node.removeEventListener('mouseenter', once);
                node.removeEventListener('focus', once);
                node.removeEventListener('touchstart', once);
            };
            node.addEventListener('mouseenter', once, { passive: true });
            node.addEventListener('focus', once, { passive: true });
            node.addEventListener('touchstart', once, { passive: true });
        });
    }

    function init() {
        bindIntentWarm();
        window.__bcNavPrefetch = { warmAsset: warmAsset };
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
