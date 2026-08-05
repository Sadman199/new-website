{{-- Scroll / time / stay triggered popup ads --}}
@php
    $popupAdsPayload = collect($global_popup_ads ?? [])->map->toPopupPayload()->values();
@endphp

@if($popupAdsPayload->isNotEmpty())
<style>
    #bc-ad-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.72);
        z-index: 99990;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        transition: opacity .25s ease;
    }
    #bc-ad-overlay.bc-ad-visible {
        display: flex;
        opacity: 1;
    }
    #bc-ad-modal {
        position: relative;
        background: #fff;
        border-radius: 12px;
        max-width: 560px;
        width: 100%;
        max-height: 90vh;
        overflow: auto;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,.45);
        transform: translateY(12px) scale(.98);
        transition: transform .25s ease;
    }
    #bc-ad-overlay.bc-ad-visible #bc-ad-modal {
        transform: translateY(0) scale(1);
    }
    #bc-ad-close {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 999px;
        background: rgba(15,23,42,.85);
        color: #fff;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
    }
    #bc-ad-close:hover { background: #000; }
    .bc-ad-body { padding: 28px 24px 24px; text-align: center; }
    .bc-ad-body img { max-width: 100%; height: auto; border-radius: 8px; display: block; margin: 0 auto 14px; }
    .bc-ad-title { font-size: 1.35rem; font-weight: 700; color: #111827; margin: 0 0 8px; }
    .bc-ad-desc { color: #4b5563; font-size: .95rem; margin: 0 0 16px; line-height: 1.5; }
    .bc-ad-cta {
        display: inline-block;
        background: #f59e0b;
        color: #111 !important;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 8px;
        text-decoration: none !important;
    }
    .bc-ad-cta:hover { background: #d97706; }
    .bc-ad-video { position: relative; padding-bottom: 56.25%; height: 0; margin-bottom: 14px; }
    .bc-ad-video iframe, .bc-ad-video video {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; border-radius: 8px;
    }
    .bc-ad-html { text-align: left; margin-bottom: 12px; }
</style>

<div id="bc-ad-overlay" role="dialog" aria-modal="true" aria-hidden="true">
    <div id="bc-ad-modal">
        <button type="button" id="bc-ad-close" aria-label="Close">&times;</button>
        <div class="bc-ad-body" id="bc-ad-content"></div>
    </div>
</div>

<script>
(function () {
    var ads = @json($popupAdsPayload);
    if (!ads || !ads.length) return;

    var STORAGE_KEY = 'bc_popup_ads_seen';
    var overlay = document.getElementById('bc-ad-overlay');
    var content = document.getElementById('bc-ad-content');
    var closeBtn = document.getElementById('bc-ad-close');
    var shown = false;
    var activeAd = null;
    var path = window.location.pathname || '/';

    function getSeen() {
        try { return JSON.parse(sessionStorage.getItem(STORAGE_KEY) || '{}') || {}; }
        catch (e) { return {}; }
    }
    function markSeen(id) {
        var seen = getSeen();
        seen[String(id)] = Date.now();
        try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(seen)); } catch (e) {}
    }
    function wasSeen(id) {
        return !!getSeen()[String(id)];
    }

    function pathMatches(patterns) {
        if (!patterns || !patterns.length) return true;
        return patterns.some(function (raw) {
            var p = String(raw || '').trim();
            if (!p) return false;
            if (p === path) return true;
            // Wildcard: /broker-reviews*
            if (p.indexOf('*') !== -1) {
                var re = new RegExp('^' + p.replace(/[.+?^${}()|[\]\\]/g, '\\$&').replace(/\*/g, '.*') + '$');
                return re.test(path);
            }
            // Prefix match without trailing slash issues
            if (p.charAt(p.length - 1) === '/') {
                return path.indexOf(p) === 0;
            }
            return path === p || path.indexOf(p + '/') === 0;
        });
    }

    function eligibleAds() {
        return ads
            .filter(function (ad) {
                if (!pathMatches(ad.pages)) return false;
                if (!ad.repeatable && wasSeen(ad.id)) return false;
                return true;
            })
            .sort(function (a, b) { return (b.priority || 0) - (a.priority || 0); });
    }

    function youtubeEmbed(url) {
        if (!url) return null;
        var m = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{6,})/);
        if (m) return 'https://www.youtube.com/embed/' + m[1] + '?autoplay=0&rel=0';
        return null;
    }

    function renderAd(ad) {
        var html = '';
        if (ad.title) html += '<h3 class="bc-ad-title">' + escapeHtml(ad.title) + '</h3>';
        if (ad.image) {
            var img = '<img src="' + escapeAttr(ad.image) + '" alt="' + escapeAttr(ad.title || 'Advertisement') + '">';
            if (ad.link) html += '<a href="' + escapeAttr(ad.link) + '" target="_blank" rel="noopener sponsored">' + img + '</a>';
            else html += img;
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
        if (ad.html_code) html += '<div class="bc-ad-html">' + ad.html_code + '</div>';
        if (ad.description) html += '<p class="bc-ad-desc">' + escapeHtml(ad.description) + '</p>';
        if (ad.link) html += '<a class="bc-ad-cta" href="' + escapeAttr(ad.link) + '" target="_blank" rel="noopener sponsored">Learn more</a>';
        content.innerHTML = html || '<p class="bc-ad-desc">Sponsored</p>';
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }
    function escapeAttr(str) {
        return escapeHtml(str).replace(/`/g, '&#96;');
    }

    function openAd(ad) {
        if (shown) return;
        shown = true;
        activeAd = ad;
        renderAd(ad);
        overlay.classList.add('bc-ad-visible');
        overlay.setAttribute('aria-hidden', 'false');
        document.documentElement.style.overflow = 'hidden';
        if (!ad.repeatable) markSeen(ad.id);
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
        if (e.target === overlay) closeAd();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && shown) closeAd();
    });

    function armTriggers(queue) {
        if (!queue.length) return;
        var ad = queue[0];
        var type = ad.trigger_type || 'scroll';
        var value = parseInt(ad.trigger_value, 10);
        if (isNaN(value) || value < 0) value = type === 'scroll' ? 50 : (type === 'stay' ? 1 : 5);

        if (type === 'time') {
            setTimeout(function () { openAd(ad); }, value * 1000);
            return;
        }

        if (type === 'stay') {
            setTimeout(function () { openAd(ad); }, value * 60 * 1000);
            return;
        }

        // scroll (default)
        var pct = Math.min(100, Math.max(0, value));
        var fired = false;
        function onScroll() {
            if (fired || shown) return;
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { armTriggers(eligibleAds()); });
    } else {
        armTriggers(eligibleAds());
    }
})();
</script>
@endif
