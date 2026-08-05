(function () {
    const tocLinks = document.querySelectorAll('.bbg-toc__link');
    const sections = [];

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
            });
        }, {
            rootMargin: '-20% 0px -60% 0px',
            threshold: 0,
        });

        sections.forEach(function (item) {
            observer.observe(item.section);
        });
    }

    tocLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const id = link.getAttribute('href')?.replace('#', '');
            const target = id ? document.getElementById(id) : null;

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', '#' + id);
        });
    });
})();
