/**
 * BrokersCourt popup ads — scroll / time / stay triggers.
 * Expects window.__bcPopupAds = [...] payload from Blade.
 */
(function () {
    'use strict';

    var ads = window.__bcPopupAds;
    if (!ads || !ads.length) {
        return;
    }

    var STORAGE_KEY = 'bc_popup_ads_seen';
    var overlay = document.getElementById('bc-ad-overlay');
    var content = document.getElementById('bc-ad-content');
    var closeBtn = document.getElementById('bc-ad-close');
    if (!overlay || !content || !closeBtn) {
        return;
    }

    var shown = false;
    var activeAd = null;
    var path = window.location.pathname || '/';

    function getSeen() {
        try {
            return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}') || {};
        } catch (e) {
            return {};
        }
    }

    function markSeen(id) {
        var seen = getSeen();
        seen[String(id)] = Date.now();
        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(seen));
        } catch (e) {}
    }

    function wasSeen(id) {
        return !!getSeen()[String(id)];
    }

    function pathMatches(patterns) {
        if (!patterns || !patterns.length) {
            return true;
        }
        return patterns.some(function (raw) {
            var p = String(raw || '').trim();
            if (!p) {
                return false;
            }
            if (p === path) {
                return true;
            }
            if (p.indexOf('*') !== -1) {
                var re = new RegExp('^' + p.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*') + '$');
                return re.test(path);
            }
            if (p.charAt(p.length - 1) === '/') {
                return path.indexOf(p) === 0;
            }
            return path === p || path.indexOf(p + '/') === 0;
        });
    }

    function eligibleAds() {
        return ads
            .filter(function (ad) {
                if (!pathMatches(ad.pages)) {
                    return false;
                }
                if (!ad.repeatable && wasSeen(ad.id)) {
                    return false;
                }
                return true;
            })
            .sort(function (a, b) {
                return (b.priority || 0) - (a.priority || 0);
            });
    }

    function youtubeEmbed(url) {
        if (!url) {
            return null;
        }
        var m = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{6,})/);
        if (m) {
            return 'https://www.youtube.com/embed/' + m[1] + '?autoplay=0&rel=0';
        }
        return null;
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function escapeAttr(str) {
        return escapeHtml(str).replace(/`/g, '&#96;');
    }

    function renderAd(ad) {
        var html = '';
        if (ad.title) {
            html += '<h3 class="bc-ad-title">' + escapeHtml(ad.title) + '</h3>';
        }
        if (ad.image) {
            var img = '<img src="' + escapeAttr(ad.image) + '" alt="' + escapeAttr(ad.title || 'Advertisement') + '" loading="lazy" decoding="async">';
            if (ad.link) {
                html += '<a href="' + escapeAttr(ad.link) + '" target="_blank" rel="noopener sponsored">' + img + '</a>';
            } else {
                html += img;
            }
        }
        if (ad.video_url) {
            var yt = youtubeEmbed(ad.video_url);
            html += '<div class="bc-ad-video">';
            if (yt) {
                html += '<iframe src="' + escapeAttr(yt) + '" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>';
            } else {
                html += '<video src="' + escapeAttr(ad.video_url) + '" controls playsinline></video>';
            }
            html += '</div>';
        }
        if (ad.html_code) {
            html += '<div class="bc-ad-html">' + ad.html_code + '</div>';
        }
        if (ad.description) {
            html += '<p class="bc-ad-desc">' + escapeHtml(ad.description) + '</p>';
        }
        if (ad.link) {
            html += '<a class="bc-ad-cta" href="' + escapeAttr(ad.link) + '" target="_blank" rel="noopener sponsored">Learn more</a>';
        }
        content.innerHTML = html || '<p class="bc-ad-desc">Sponsored</p>';
    }

    function openAd(ad) {
        if (shown) {
            return;
        }
        shown = true;
        activeAd = ad;
        renderAd(ad);
        overlay.classList.add('bc-ad-visible');
        overlay.setAttribute('aria-hidden', 'false');
        document.documentElement.style.overflow = 'hidden';
        if (!ad.repeatable) {
            markSeen(ad.id);
        }
    }

    function closeAd() {
        overlay.classList.remove('bc-ad-visible');
        overlay.setAttribute('aria-hidden', 'true');
        document.documentElement.style.overflow = '';
        content.innerHTML = '';
        activeAd = null;
    }

    closeBtn.addEventListener('click', closeAd);
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closeAd();
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && shown) {
            closeAd();
        }
    });

    function armTriggers(queue) {
        if (!queue.length) {
            return;
        }
        var ad = queue[0];
        var type = ad.trigger_type || 'scroll';
        var value = parseInt(ad.trigger_value, 10);
        if (isNaN(value) || value < 0) {
            value = type === 'scroll' ? 50 : (type === 'stay' ? 1 : 5);
        }

        if (type === 'time') {
            setTimeout(function () {
                openAd(ad);
            }, value * 1000);
            return;
        }

        if (type === 'stay') {
            setTimeout(function () {
                openAd(ad);
            }, value * 60 * 1000);
            return;
        }

        var pct = Math.min(100, Math.max(0, value));
        var fired = false;
        function onScroll() {
            if (fired || shown) {
                return;
            }
            var doc = document.documentElement;
            var scrollTop = window.pageYOffset || doc.scrollTop || 0;
            var height = (doc.scrollHeight - doc.clientHeight) || 1;
            var progress = (scrollTop / height) * 100;
            if (progress >= pct) {
                fired = true;
                window.removeEventListener('scroll', onScroll);
                openAd(ad);
            }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    function init() {
        armTriggers(eligibleAds());
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
