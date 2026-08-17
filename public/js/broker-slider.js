(function () {
    'use strict';

    function initSlider(root) {
        var track = root.querySelector('.bcs__track');
        var nav = root.querySelector('[data-bcs-nav]');
        var prev = root.querySelector('[data-bcs-prev]');
        var next = root.querySelector('[data-bcs-next]');

        if (!track) {
            return;
        }

        function step() {
            var slide = track.querySelector('.bcs__slide');
            if (!slide) {
                return track.clientWidth * 0.9;
            }
            var styles = window.getComputedStyle(track);
            var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;

            return slide.getBoundingClientRect().width + gap;
        }

        function sync() {
            var scrollable = track.scrollWidth - track.clientWidth > 4;

            if (nav) {
                nav.hidden = !scrollable;
            }
            if (!scrollable) {
                return;
            }
            if (prev) {
                prev.disabled = track.scrollLeft <= 4;
            }
            if (next) {
                next.disabled = track.scrollLeft >= track.scrollWidth - track.clientWidth - 4;
            }
        }

        function scrollBySlides(direction) {
            track.scrollBy({ left: direction * step(), behavior: 'smooth' });
        }

        if (prev) {
            prev.addEventListener('click', function () {
                scrollBySlides(-1);
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                scrollBySlides(1);
            });
        }

        track.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', sync);

        if ('ResizeObserver' in window) {
            new ResizeObserver(sync).observe(track);
        }

        sync();
    }

    function init() {
        document.querySelectorAll('[data-broker-slider]').forEach(initSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
