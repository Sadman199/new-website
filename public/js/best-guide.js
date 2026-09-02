/**
 * Best broker guide interactions.
 *
 * Scoped to .bgx so it cannot fight with best-broker-guide.js, which still drives the
 * author popovers here and the whole TOC on the review, blog, and bonus pages.
 */
(function () {
    'use strict';

    var root = document.querySelector('.bgx');

    if (!root) {
        return;
    }

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function raf(fn) {
        return window.requestAnimationFrame ? window.requestAnimationFrame(fn) : fn();
    }

    /** Coalesce scroll/resize handlers into one frame. */
    function throttled(fn) {
        var queued = false;

        return function () {
            if (queued) {
                return;
            }

            queued = true;
            raf(function () {
                queued = false;
                fn();
            });
        };
    }

    /* ------------------------------------------------------------------ *
     * Section rail — scrollspy, sliding indicator, arrows, edge fades
     * ------------------------------------------------------------------ */

    (function rail() {
        var railEl = root.querySelector('[data-bgx-rail]');

        if (!railEl) {
            return;
        }

        var bar = railEl.querySelector('.bgx-rail__bar');
        var viewport = railEl.querySelector('[data-bgx-rail-viewport]');
        var track = railEl.querySelector('[data-bgx-rail-track]');
        var indicator = railEl.querySelector('[data-bgx-rail-indicator]');
        var prevBtn = railEl.querySelector('[data-bgx-rail-prev]');
        var nextBtn = railEl.querySelector('[data-bgx-rail-next]');
        var sentinel = root.querySelector('[data-bgx-rail-sentinel]');
        var chips = Array.prototype.slice.call(railEl.querySelectorAll('[data-bgx-rail-chip]'));

        if (!chips.length) {
            return;
        }

        var targets = chips
            .map(function (chip) {
                var el = document.getElementById(chip.getAttribute('data-bgx-target'));

                return el ? { chip: chip, el: el } : null;
            })
            .filter(Boolean);

        var activeChip = null;

        /** Distance from the top of the viewport that the pinned rail occupies. */
        function railOffset() {
            var stickyTop = parseFloat(window.getComputedStyle(railEl).top) || 0;

            return stickyTop + bar.offsetHeight;
        }

        function moveIndicator(chip) {
            if (!indicator || !chip) {
                return;
            }

            indicator.style.width = chip.offsetWidth + 'px';
            indicator.style.transform = 'translateX(' + chip.offsetLeft + 'px)';
            indicator.classList.add('is-visible');
        }

        function centreChip(chip) {
            var target = chip.offsetLeft - (viewport.clientWidth - chip.offsetWidth) / 2;
            var max = track.scrollWidth - track.clientWidth;

            track.scrollTo({
                left: Math.max(0, Math.min(target, max)),
                behavior: reduceMotion ? 'auto' : 'smooth',
            });
        }

        function setActive(chip, options) {
            if (!chip || chip === activeChip) {
                return;
            }

            if (activeChip) {
                activeChip.classList.remove('is-active');
            }

            activeChip = chip;
            chip.classList.add('is-active');
            moveIndicator(chip);

            if (!options || options.centre !== false) {
                centreChip(chip);
            }
        }

        /** The section whose top edge most recently passed under the rail wins. */
        function syncActive() {
            var offset = railOffset() + 24;
            var current = null;

            for (var i = 0; i < targets.length; i++) {
                if (targets[i].el.getBoundingClientRect().top - offset <= 0) {
                    current = targets[i].chip;
                }
            }

            // Above the first section: fall back to the first chip.
            setActive(current || targets[0].chip);
        }

        function syncArrows() {
            var max = track.scrollWidth - track.clientWidth;
            var canPrev = track.scrollLeft > 4;
            var canNext = track.scrollLeft < max - 4;

            viewport.classList.toggle('can-prev', canPrev);
            viewport.classList.toggle('can-next', canNext);

            if (prevBtn) {
                prevBtn.classList.toggle('is-hidden', !canPrev);
                prevBtn.tabIndex = canPrev ? 0 : -1;
            }

            if (nextBtn) {
                nextBtn.classList.toggle('is-hidden', !canNext);
                nextBtn.tabIndex = canNext ? 0 : -1;
            }
        }

        function nudge(direction) {
            track.scrollBy({
                left: direction * Math.max(160, viewport.clientWidth * 0.7),
                behavior: reduceMotion ? 'auto' : 'smooth',
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                nudge(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                nudge(1);
            });
        }

        track.addEventListener('scroll', throttled(syncArrows), { passive: true });

        chips.forEach(function (chip) {
            chip.addEventListener('click', function (event) {
                var target = document.getElementById(chip.getAttribute('data-bgx-target'));

                if (!target) {
                    return;
                }

                event.preventDefault();

                // CSS scroll-margin-top already accounts for the navbar and rail.
                target.scrollIntoView({
                    behavior: reduceMotion ? 'auto' : 'smooth',
                    block: 'start',
                });

                history.replaceState(null, '', '#' + target.id);
                setActive(chip);
                pulse(target);
            });
        });

        // Pin detection: the rail is CSS-sticky; this class only tightens the bar.
        function updateStuck() {
            var stickyTop = parseFloat(window.getComputedStyle(railEl).top) || 0;
            var pinned = sentinel.getBoundingClientRect().top <= stickyTop + 1;
            railEl.classList.toggle('is-stuck', pinned);
        }

        if (sentinel) {
            var onPin = throttled(updateStuck);
            window.addEventListener('scroll', onPin, { passive: true });
            window.addEventListener('resize', onPin);
            updateStuck();
        }

        var onScroll = throttled(syncActive);
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener(
            'resize',
            throttled(function () {
                syncArrows();
                if (activeChip) {
                    moveIndicator(activeChip);
                }
            })
        );

        // Fonts and logos change chip widths, so re-measure once everything settles.
        window.addEventListener('load', function () {
            syncArrows();
            if (activeChip) {
                moveIndicator(activeChip);
            }
        });

        syncArrows();
        syncActive();
    })();

    /** Make sure a jumped-to section is visible before we highlight it. */
    function revealSection(target) {
        var section = target && target.closest ? target.closest('.bgx-section') : null;

        if (section) {
            section.classList.add('is-revealed');
        }
    }

    /** Briefly highlight a jumped-to broker listing so the landing spot is obvious. */
    function pulse(target) {
        revealSection(target);

        if (reduceMotion || !target.classList.contains('bgx-review')) {
            return;
        }

        target.classList.remove('is-targeted');
        void target.offsetWidth;
        target.classList.add('is-targeted');
    }

    /* ------------------------------------------------------------------ *
     * Horizontal sliders (shortlist)
     * ------------------------------------------------------------------ */

    Array.prototype.forEach.call(root.querySelectorAll('[data-bgx-slider]'), function (slider) {
        var name = slider.getAttribute('data-bgx-slider');
        var track = slider.querySelector('[data-bgx-slider-track]');
        var prevBtn = root.querySelector('[data-bgx-slider-prev="' + name + '"]');
        var nextBtn = root.querySelector('[data-bgx-slider-next="' + name + '"]');
        var dotsHost = root.querySelector('[data-bgx-slider-dots="' + name + '"]');

        if (!track) {
            return;
        }

        var slides = Array.prototype.slice.call(track.children);

        function step() {
            if (!slides.length) {
                return track.clientWidth;
            }

            var gap = parseFloat(window.getComputedStyle(track).columnGap || '0') || 0;

            return slides[0].offsetWidth + gap;
        }

        function perPage() {
            return Math.max(1, Math.round(track.clientWidth / step()));
        }

        function pageCount() {
            return Math.max(1, Math.ceil(slides.length / perPage()));
        }

        function currentPage() {
            return Math.min(pageCount() - 1, Math.round(track.scrollLeft / (step() * perPage())));
        }

        function buildDots() {
            if (!dotsHost) {
                return;
            }

            var total = pageCount();

            dotsHost.innerHTML = '';

            if (total < 2) {
                return;
            }

            for (var i = 0; i < total; i++) {
                (function (index) {
                    var dot = document.createElement('button');
                    dot.type = 'button';
                    dot.className = 'bgx-slider__dot';
                    dot.setAttribute('aria-label', 'Go to slide group ' + (index + 1));
                    dot.addEventListener('click', function () {
                        track.scrollTo({
                            left: index * step() * perPage(),
                            behavior: reduceMotion ? 'auto' : 'smooth',
                        });
                    });
                    dotsHost.appendChild(dot);
                })(i);
            }
        }

        function syncState() {
            var max = track.scrollWidth - track.clientWidth;

            if (prevBtn) {
                prevBtn.disabled = track.scrollLeft <= 4;
            }

            if (nextBtn) {
                nextBtn.disabled = track.scrollLeft >= max - 4;
            }

            if (dotsHost) {
                var active = currentPage();

                Array.prototype.forEach.call(dotsHost.children, function (dot, index) {
                    dot.classList.toggle('is-active', index === active);
                });
            }
        }

        function page(direction) {
            track.scrollBy({
                left: direction * step() * perPage(),
                behavior: reduceMotion ? 'auto' : 'smooth',
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                page(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                page(1);
            });
        }

        // Anchor links inside the slider still need to jump to the review card.
        slides.forEach(function (slide) {
            slide.addEventListener('click', function (event) {
                var href = slide.getAttribute('href') || '';

                if (href.charAt(0) !== '#') {
                    return;
                }

                var target = document.getElementById(href.slice(1));

                if (!target) {
                    return;
                }

                event.preventDefault();
                target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
                history.replaceState(null, '', href);
                pulse(target);
            });
        });

        track.addEventListener('scroll', throttled(syncState), { passive: true });
        window.addEventListener(
            'resize',
            throttled(function () {
                buildDots();
                syncState();
            })
        );

        buildDots();
        syncState();
    });

    /* ------------------------------------------------------------------ *
     * Comparison tabs
     * ------------------------------------------------------------------ */

    (function tabs() {
        var group = root.querySelector('.bgx-tabs');

        if (!group) {
            return;
        }

        var buttons = Array.prototype.slice.call(group.querySelectorAll('[data-bgx-tab]'));
        var indicator = group.querySelector('[data-bgx-tab-indicator]');

        function moveIndicator(button) {
            if (!indicator) {
                return;
            }

            indicator.style.width = button.offsetWidth + 'px';
            indicator.style.transform = 'translateX(' + button.offsetLeft + 'px)';
        }

        function activate(button) {
            buttons.forEach(function (other) {
                var isActive = other === button;
                var panel = root.querySelector('[data-bgx-panel="' + other.getAttribute('data-bgx-tab') + '"]');

                other.classList.toggle('is-active', isActive);
                other.setAttribute('aria-selected', isActive ? 'true' : 'false');
                other.tabIndex = isActive ? 0 : -1;

                if (panel) {
                    panel.classList.toggle('is-hidden', !isActive);
                }
            });

            moveIndicator(button);
        }

        var ARROW_KEYS = {
            ArrowLeft: -1,
            ArrowRight: 1,
            Left: -1,
            Right: 1
        };

        buttons.forEach(function (button, index) {
            button.tabIndex = button.classList.contains('is-active') ? 0 : -1;

            button.addEventListener('click', function () {
                activate(button);
            });

            button.addEventListener('keydown', function (event) {
                var step = ARROW_KEYS[event.key];
                var target;

                if (step) {
                    target = buttons[(index + step + buttons.length) % buttons.length];
                } else if (event.key === 'Home') {
                    target = buttons[0];
                } else if (event.key === 'End') {
                    target = buttons[buttons.length - 1];
                }

                if (!target) {
                    return;
                }

                event.preventDefault();
                activate(target);
                target.focus();
            });
        });

        var initial = group.querySelector('.bgx-tabs__btn.is-active') || buttons[0];

        if (initial) {
            moveIndicator(initial);
            window.addEventListener('load', function () {
                moveIndicator(group.querySelector('.bgx-tabs__btn.is-active') || initial);
            });
            window.addEventListener(
                'resize',
                throttled(function () {
                    moveIndicator(group.querySelector('.bgx-tabs__btn.is-active') || initial);
                })
            );
        }
    })();

    /* ------------------------------------------------------------------ *
     * Scroll-triggered reveals and bar fills
     * ------------------------------------------------------------------ */

    (function reveals() {
        var sections = Array.prototype.slice.call(root.querySelectorAll('.bgx-section'));
        var bars = Array.prototype.slice.call(root.querySelectorAll('[data-bgx-bar]'));

        if (!('IntersectionObserver' in window) || reduceMotion) {
            bars.forEach(function (bar) {
                bar.classList.add('is-filled');
            });

            return;
        }

        sections.forEach(function (section) {
            section.classList.add('bgx-reveal');
        });

        var sectionObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                        sectionObserver.unobserve(entry.target);
                    }
                });
            },
            { rootMargin: '0px 0px -8% 0px', threshold: 0.04 }
        );

        sections.forEach(function (section) {
            sectionObserver.observe(section);
        });

        var barObserver = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-filled');
                        barObserver.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );

        bars.forEach(function (bar) {
            barObserver.observe(bar);
        });
    })();
})();
