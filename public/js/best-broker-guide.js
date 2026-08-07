(function () {
    const tocLinks = document.querySelectorAll('.bbg-toc__link');
    const sections = [];
    const scrollOffset = 88;

    tocLinks.forEach(function (link) {
        const id = link.getAttribute('href')?.replace('#', '');
        const section = id ? document.getElementById(id) : null;

        if (section) {
            sections.push({ link: link, section: section });
        }
    });

    if ('IntersectionObserver' in window && sections.length) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }

                sections.forEach(function (item) {
                    item.link.classList.toggle('is-active', item.section === entry.target);
                });

                const mobileSelect = document.querySelector('.bbg-mobile-toc__select');
                if (mobileSelect && entry.target.id) {
                    mobileSelect.value = entry.target.id;
                }
            });
        }, {
            rootMargin: '-15% 0px -55% 0px',
            threshold: 0,
        });

        sections.forEach(function (item) {
            observer.observe(item.section);
        });
    }

    function scrollToSection(id) {
        const target = document.getElementById(id);

        if (!target) {
            return;
        }

        const top = target.getBoundingClientRect().top + window.scrollY - scrollOffset;
        window.scrollTo({ top: top, behavior: 'smooth' });
        history.replaceState(null, '', '#' + id);
    }

    tocLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const id = link.getAttribute('href')?.replace('#', '');

            if (!id || !document.getElementById(id)) {
                return;
            }

            event.preventDefault();
            scrollToSection(id);
        });
    });

    const mobileSelect = document.querySelector('.bbg-mobile-toc__select');

    if (mobileSelect) {
        mobileSelect.addEventListener('change', function () {
            if (mobileSelect.value) {
                scrollToSection(mobileSelect.value);
            }
        });
    }

    document.querySelectorAll('.bbg-author-popover').forEach(function (popover) {
        const trigger = popover.querySelector('.bbg-author-popover__trigger');
        const card = popover.querySelector('.bbg-author-popover__card');

        if (!trigger || !card) {
            return;
        }

        let hideTimer = null;

        function showCard() {
            clearTimeout(hideTimer);
            popover.classList.add('is-open');
        }

        function hideCard() {
            hideTimer = setTimeout(function () {
                popover.classList.remove('is-open');
            }, 120);
        }

        trigger.addEventListener('mouseenter', showCard);
        trigger.addEventListener('mouseleave', hideCard);
        trigger.addEventListener('focus', showCard);
        trigger.addEventListener('blur', hideCard);
        card.addEventListener('mouseenter', showCard);
        card.addEventListener('mouseleave', hideCard);

        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            popover.classList.toggle('is-open');
        });

        document.addEventListener('click', function (event) {
            if (!popover.contains(event.target)) {
                popover.classList.remove('is-open');
            }
        });
    });

    const carouselTrack = document.querySelector('[data-bbg-carousel-track]');

    if (carouselTrack) {
        const prevBtn = document.querySelector('[data-bbg-carousel-prev]');
        const nextBtn = document.querySelector('[data-bbg-carousel-next]');
        const scrollAmount = 260;

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                carouselTrack.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                carouselTrack.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });
        }
    }
})();
