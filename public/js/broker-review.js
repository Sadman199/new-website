(function () {
    'use strict';

    /* Expand / collapse — "Read about more" sections */
    document.querySelectorAll('.br-read-more').forEach(function (btn) {
        var targetId = btn.getAttribute('data-br-target');
        var panel = targetId ? document.getElementById(targetId) : null;
        if (!panel) return;

        var showLabel = btn.querySelector('.br-read-more__show');
        var hideLabel = btn.querySelector('.br-read-more__hide');

        btn.addEventListener('click', function () {
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            var next = !expanded;

            btn.setAttribute('aria-expanded', next ? 'true' : 'false');
            panel.hidden = !next;

            if (showLabel) showLabel.hidden = next;
            if (hideLabel) hideLabel.hidden = !next;
        });
    });

    /* FAQ accordion */
    document.querySelectorAll('.br-faq-q').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var answer = btn.nextElementSibling;
            var isOpen = btn.classList.contains('is-open');

            document.querySelectorAll('.br-faq-q.is-open').forEach(function (openBtn) {
                if (openBtn !== btn) {
                    openBtn.classList.remove('is-open');
                    if (openBtn.nextElementSibling) {
                        openBtn.nextElementSibling.classList.remove('is-open');
                    }
                }
            });

            btn.classList.toggle('is-open', !isOpen);
            if (answer) {
                answer.classList.toggle('is-open', !isOpen);
            }
        });
    });

    /* Multi-group star ratings (Cost / Platforms / Support) */
    function paintStarGroup(wrap, value) {
        var labels = wrap.querySelectorAll('label');
        labels.forEach(function (label, index) {
            label.classList.toggle('is-active', index < value);
        });
    }

    function refreshScorePreview(form) {
        if (!form) return;

        var preview = form.querySelector('[data-br-score-value]');
        if (!preview) return;

        var groups = form.querySelectorAll('[data-br-star-group]');
        var total = 0;
        var rated = 0;

        groups.forEach(function (group) {
            var checked = group.querySelector('input[type="radio"]:checked');
            if (checked) {
                total += parseInt(checked.value, 10);
                rated += 1;
            }
        });

        preview.textContent = (groups.length && rated === groups.length)
            ? String(Math.round(total / groups.length) * 2)
            : '—';
    }

    document.querySelectorAll('[data-br-star-group]').forEach(function (starWrap) {
        var labels = starWrap.querySelectorAll('label');
        var textEl = starWrap.parentElement
            ? starWrap.parentElement.querySelector('[data-br-star-text]')
            : null;
        var ownerForm = starWrap.closest('form');

        labels.forEach(function (label, index) {
            label.addEventListener('mouseenter', function () {
                paintStarGroup(starWrap, index + 1);
            });

            label.addEventListener('click', function () {
                if (textEl) {
                    textEl.textContent = (index + 1) + '/5';
                }
                window.setTimeout(function () {
                    refreshScorePreview(ownerForm);
                }, 0);
            });
        });

        starWrap.addEventListener('mouseleave', function () {
            var checked = starWrap.querySelector('input[type="radio"]:checked');
            paintStarGroup(starWrap, checked ? parseInt(checked.value, 10) : 0);
        });

        var initial = starWrap.querySelector('input[type="radio"]:checked');
        if (initial) {
            paintStarGroup(starWrap, parseInt(initial.value, 10));
        }
    });

    /* Legacy single star widget fallback */
    var starWrap = document.getElementById('starRating');
    var ratingText = document.getElementById('ratingText');

    if (starWrap && !starWrap.hasAttribute('data-br-star-group')) {
        var labels = starWrap.querySelectorAll('label');

        function paintStars(value) {
            labels.forEach(function (label, index) {
                label.classList.toggle('is-active', index < value);
            });
        }

        labels.forEach(function (label, index) {
            label.addEventListener('mouseenter', function () {
                paintStars(index + 1);
            });

            label.addEventListener('click', function () {
                if (ratingText) {
                    ratingText.textContent = (index + 1) + '/5';
                }
            });
        });

        starWrap.addEventListener('mouseleave', function () {
            var checked = starWrap.querySelector('input[type="radio"]:checked');
            paintStars(checked ? parseInt(checked.value, 10) : 0);
        });

        var initial = starWrap.querySelector('input[type="radio"]:checked');
        if (initial) {
            paintStars(parseInt(initial.value, 10));
        }
    }

    /* Review filters — reload with query params */
    document.querySelectorAll('[data-br-filter]').forEach(function (select) {
        select.addEventListener('change', function () {
            var form = document.getElementById('brReviewFilters');
            if (!form) return;

            var params = new URLSearchParams(new FormData(form));
            ['score', 'length', 'account_type'].forEach(function (key) {
                if (params.get(key) === 'all') {
                    params.delete(key);
                }
            });

            var query = params.toString();
            window.location.href = form.action + (query ? '?' + query : '') + '#voices';
        });
    });

    /* Guest login modal */
    var reviewsRoot = document.getElementById('voices');
    var loginModal = document.getElementById('brReviewLoginModal');
    var isAuthenticated = reviewsRoot && reviewsRoot.getAttribute('data-br-authenticated') === '1';

    function openLoginModal() {
        if (!loginModal) return;
        loginModal.hidden = false;
        loginModal.classList.add('is-open');
        document.body.classList.add('br-modal-open');
    }

    function closeLoginModal() {
        if (!loginModal) return;
        loginModal.hidden = true;
        loginModal.classList.remove('is-open');
        document.body.classList.remove('br-modal-open');
    }

    document.querySelectorAll('[data-br-open-login]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openLoginModal();
        });
    });

    document.querySelectorAll('[data-br-modal-close]').forEach(function (el) {
        el.addEventListener('click', closeLoginModal);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLoginModal();
        }
    });

    document.querySelectorAll('[data-br-require-auth]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (isAuthenticated) return;
            e.preventDefault();
            e.stopPropagation();
            openLoginModal();
        }, true);
    });

    /* One-level reply composers */
    document.querySelectorAll('[data-br-reply-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!isAuthenticated) {
                openLoginModal();
                return;
            }

            var id = btn.getAttribute('data-br-reply-toggle');
            var panel = document.getElementById('br-reply-form-' + id);
            if (!panel) return;

            var willOpen = panel.hidden;
            document.querySelectorAll('.br-comment__reply-form').forEach(function (other) {
                other.hidden = true;
            });
            panel.hidden = !willOpen;
        });
    });

    document.querySelectorAll('[data-br-reply-cancel]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.getAttribute('data-br-reply-cancel');
            var panel = document.getElementById('br-reply-form-' + id);
            if (panel) panel.hidden = true;
        });
    });

    /* Sticky scroll nav (legacy — optional) */
    var nav = document.getElementById('bc-scroll-nav');
    if (nav) {
        var links = nav.querySelectorAll('.br-nav__link');
        var scrollBox = nav.querySelector('.br-nav__scroll');
        var leftBtn = document.getElementById('bc-nav-left');
        var rightBtn = document.getElementById('bc-nav-right');
        var sections = [];

        links.forEach(function (link) {
            var id = link.getAttribute('href').replace('#', '');
            var el = document.getElementById(id);
            if (el) sections.push({ id: id, el: el, link: link });
        });

        function setActive(id) {
            links.forEach(function (l) {
                l.classList.toggle('active', l.getAttribute('href') === '#' + id);
            });

            var active = nav.querySelector('.br-nav__link.active');
            if (active && scrollBox) {
                var left = active.offsetLeft - scrollBox.offsetWidth / 2 + active.offsetWidth / 2;
                scrollBox.scrollTo({ left: left, behavior: 'smooth' });
            }
        }

        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var id = link.getAttribute('href').replace('#', '');
                var target = document.getElementById(id);
                if (target) {
                    var top = target.getBoundingClientRect().top + window.pageYOffset - 120;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                    setActive(id);
                }
            });
        });

        window.addEventListener('scroll', function () {
            var pos = window.pageYOffset + 140;
            var current = sections.length ? sections[0].id : '';

            sections.forEach(function (section) {
                if (pos >= section.el.offsetTop) {
                    current = section.id;
                }
            });

            if (current) setActive(current);
        }, { passive: true });

        if (scrollBox && leftBtn && rightBtn) {
            leftBtn.addEventListener('click', function () {
                scrollBox.scrollBy({ left: -200, behavior: 'smooth' });
            });
            rightBtn.addEventListener('click', function () {
                scrollBox.scrollBy({ left: 200, behavior: 'smooth' });
            });
        }
    }

    /* Mobile sticky visit bar — appears after scrolling past hero */
    var mobileCta = document.getElementById('br-mobile-cta');
    var reviewHero = document.querySelector('.br-page .br-hero');

    if (mobileCta && reviewHero) {
        var mobileCtaMq = window.matchMedia('(max-width: 1023px)');

        function syncMobileCtaVisibility() {
            if (!mobileCtaMq.matches) {
                mobileCta.hidden = true;
                mobileCta.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('br-has-mobile-cta');
                return;
            }

            document.body.classList.add('br-has-mobile-cta');
        }

        if (typeof IntersectionObserver !== 'undefined') {
            var mobileCtaObserver = new IntersectionObserver(function (entries) {
                if (!mobileCtaMq.matches) {
                    return;
                }

                var heroVisible = entries[0] && entries[0].isIntersecting;
                mobileCta.hidden = heroVisible;
                mobileCta.setAttribute('aria-hidden', heroVisible ? 'true' : 'false');
            }, { threshold: 0, rootMargin: '-4rem 0px 0px 0px' });

            mobileCtaObserver.observe(reviewHero);
        }

        syncMobileCtaVisibility();
        if (typeof mobileCtaMq.addEventListener === 'function') {
            mobileCtaMq.addEventListener('change', syncMobileCtaVisibility);
        } else if (typeof mobileCtaMq.addListener === 'function') {
            mobileCtaMq.addListener(syncMobileCtaVisibility);
        }
    }

    function readSavedIds() {
        try {
            return JSON.parse(localStorage.getItem('savedBrokers') || '[]').map(String);
        } catch (e) {
            return [];
        }
    }

    function writeSavedIds(ids) {
        localStorage.setItem('savedBrokers', JSON.stringify(ids.map(String)));
    }

    function paintSaveButton(btn, isSaved) {
        btn.classList.toggle('is-saved', isSaved);
        btn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
        btn.textContent = isSaved ? 'Saved' : 'Save';
    }

    document.querySelectorAll('[data-br-save]').forEach(function (btn) {
        var id = String(btn.getAttribute('data-broker-id') || '');
        if (!id) {
            return;
        }

        paintSaveButton(btn, readSavedIds().indexOf(id) !== -1);

        btn.addEventListener('click', function () {
            var list = readSavedIds();
            var isSaved = list.indexOf(id) !== -1;
            list = isSaved ? list.filter(function (item) { return item !== id; }) : list.concat(id);
            writeSavedIds(list);
            document.querySelectorAll('[data-br-save][data-broker-id="' + id + '"]').forEach(function (el) {
                paintSaveButton(el, !isSaved);
            });

            if (window.bcSyncSavedBroker) {
                window.bcSyncSavedBroker(id, !isSaved);
            }
        });
    });
})();
