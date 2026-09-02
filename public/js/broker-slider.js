(function () {
    'use strict';

    function initSlider(root) {
        var track = root.querySelector('.bcs__track');
        var nav = root.querySelector('[data-bcs-nav]');
        var prev = root.querySelector('[data-bcs-prev]');
        var next = root.querySelector('[data-bcs-next]');
        var dotsWrap = root.querySelector('[data-bcs-dots]');

        if (!track) {
            return;
        }

        function gap() {
            var styles = window.getComputedStyle(track);
            return parseFloat(styles.columnGap || styles.gap || '0') || 0;
        }

        function slideWidth() {
            var slide = track.querySelector('.bcs__slide');
            return slide ? slide.getBoundingClientRect().width : 0;
        }

        function step() {
            return slideWidth() + gap();
        }

        function visibleCount() {
            var width = slideWidth();
            if (!width) {
                return 1;
            }

            return Math.max(1, Math.round((track.clientWidth + gap()) / (width + gap())));
        }

        function totalSlides() {
            return track.querySelectorAll('.bcs__slide').length;
        }

        function pageCount() {
            var visible = visibleCount();
            var total = totalSlides();

            if (total <= visible) {
                return 0;
            }

            return Math.ceil(total / visible);
        }

        function pageOffset(page) {
            return page * visibleCount() * step();
        }

        function buildDots() {
            if (!dotsWrap) {
                return;
            }

            dotsWrap.innerHTML = '';
            var pages = pageCount();

            if (pages <= 1) {
                dotsWrap.hidden = true;
                return;
            }

            dotsWrap.hidden = false;

            for (var i = 0; i < pages; i += 1) {
                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'bcs__dot' + (i === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Go to page ' + (i + 1));
                dot.dataset.page = String(i);
                dot.addEventListener('click', function () {
                    var page = Number(this.dataset.page || 0);
                    track.scrollTo({ left: pageOffset(page), behavior: 'smooth' });
                });
                dotsWrap.appendChild(dot);
            }
        }

        function syncDots() {
            if (!dotsWrap || dotsWrap.hidden) {
                return;
            }

            var dots = dotsWrap.querySelectorAll('.bcs__dot');
            if (!dots.length) {
                return;
            }

            var pageSize = visibleCount() * step();
            var active = pageSize > 0 ? Math.round(track.scrollLeft / pageSize) : 0;
            active = Math.max(0, Math.min(active, dots.length - 1));

            dots.forEach(function (dot, index) {
                dot.classList.toggle('is-active', index === active);
            });
        }

        function sync() {
            var scrollable = track.scrollWidth - track.clientWidth > 4;
            var pages = pageCount();

            if (nav) {
                nav.hidden = !scrollable;
            }

            if (dotsWrap) {
                dotsWrap.hidden = pages <= 1;
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

            syncDots();
        }

        function scrollByPage(direction) {
            var pageSize = visibleCount() * step();
            track.scrollBy({ left: direction * pageSize, behavior: 'smooth' });
        }

        if (prev) {
            prev.addEventListener('click', function () {
                scrollByPage(-1);
            });
        }

        if (next) {
            next.addEventListener('click', function () {
                scrollByPage(1);
            });
        }

        track.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', function () {
            buildDots();
            sync();
        });

        if ('ResizeObserver' in window) {
            new ResizeObserver(function () {
                buildDots();
                sync();
            }).observe(track);
        }

        buildDots();
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
