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

    /* Star rating (text labels, no icon fonts) */
    var starWrap = document.getElementById('starRating');
    var ratingText = document.getElementById('ratingText');

    if (starWrap) {
        var labels = starWrap.querySelectorAll('label');
        var inputs = starWrap.querySelectorAll('input[type="radio"]');

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
})();
