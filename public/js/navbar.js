document.addEventListener('DOMContentLoaded', function () {
    const brokersBtn = document.getElementById('brokersButton');
    const brokersMenu = document.getElementById('brokersMegaMenu');
    const brokersGroup = document.getElementById('brokersNavGroup');
    const propFirmsBtn = document.getElementById('propFirmsButton');
    const propFirmsMenu = document.getElementById('propFirmsMegaMenu');
    const propFirmsGroup = document.getElementById('propFirmsNavGroup');
    const reviewsGroup = document.getElementById('reviewsNavGroup');
    const reviewsMenu = document.getElementById('reviewsMegaMenu');
    const reviewsLink = document.getElementById('reviewsNavLink');
    const companyBtn = document.getElementById('companyButton');
    const companyMenu = document.getElementById('companyMenu');
    const companyGroup = document.getElementById('companyNavGroup');
    const toolsBtn = document.getElementById('toolsButton');
    const toolsMenu = document.getElementById('toolsMenu');
    const toolsGroup = document.getElementById('toolsNavGroup');
    const mobileBtn = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const bcNavBar = document.getElementById('bcNavBar');
    const desktopSearchToggle = document.getElementById('desktopSearchToggle');
    const desktopSearchPanel = document.getElementById('desktopSearchPanel');
    const searchWrap = document.getElementById('desktopSearchWrap');
    const navBarRow = document.getElementById('navBarRow');
    const menuIconOpen = document.getElementById('menuIconOpen');
    const menuIconClose = document.getElementById('menuIconClose');

    let brokersTimer, propFirmsTimer, reviewsTimer, companyTimer, toolsTimer;

    function setActive(el, on) {
        if (!el) return;
        el.classList.toggle('bc-nav-active', on);
        el.classList.toggle('is-active', on);
        el.querySelector('.chevron-icon, .reviews-chevron, .company-chevron, .tools-chevron, .pf-chevron')?.classList.toggle('rotate-180', on);
    }

    function closeBrokersMenu() {
        brokersMenu?.classList.remove('is-open');
        brokersMenu?.setAttribute('aria-hidden', 'true');
        setActive(brokersBtn, false);
        brokersBtn?.setAttribute('aria-expanded', 'false');
    }

    function openBrokersMenu() {
        closePropFirmsMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeToolsMenu();
        closeSearchPanel();
        closeCountryDrawer();
        brokersMenu?.classList.add('is-open');
        brokersMenu?.setAttribute('aria-hidden', 'false');
        setActive(brokersBtn, true);
        brokersBtn?.setAttribute('aria-expanded', 'true');
    }

    function closePropFirmsMenu() {
        propFirmsMenu?.classList.remove('is-open');
        propFirmsMenu?.setAttribute('aria-hidden', 'true');
        setActive(propFirmsBtn, false);
        propFirmsBtn?.setAttribute('aria-expanded', 'false');
    }

    function openPropFirmsMenu() {
        closeBrokersMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeToolsMenu();
        closeSearchPanel();
        closeCountryDrawer();
        propFirmsMenu?.classList.add('is-open');
        propFirmsMenu?.setAttribute('aria-hidden', 'false');
        setActive(propFirmsBtn, true);
        propFirmsBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeReviewsMenu() {
        reviewsMenu?.classList.add('hidden');
        setActive(reviewsLink, false);
    }

    function openReviewsMenu() {
        closeBrokersMenu();
        closePropFirmsMenu();
        closeCompanyMenu();
        closeToolsMenu();
        closeSearchPanel();
        closeCountryDrawer();
        reviewsMenu?.classList.remove('hidden');
        setActive(reviewsLink, true);
    }

    function closeCompanyMenu() {
        companyMenu?.classList.add('hidden');
        setActive(companyBtn, false);
        companyBtn?.setAttribute('aria-expanded', 'false');
    }

    function closeToolsMenu() {
        toolsMenu?.classList.add('hidden');
        setActive(toolsBtn, false);
        toolsBtn?.setAttribute('aria-expanded', 'false');
    }

    function openToolsMenu() {
        closeBrokersMenu();
        closePropFirmsMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeSearchPanel();
        closeCountryDrawer();
        toolsMenu?.classList.remove('hidden');
        setActive(toolsBtn, true);
        toolsBtn?.setAttribute('aria-expanded', 'true');
    }

    function openCompanyMenu() {
        closeBrokersMenu();
        closePropFirmsMenu();
        closeReviewsMenu();
        closeToolsMenu();
        closeSearchPanel();
        closeCountryDrawer();
        companyMenu?.classList.remove('hidden');
        setActive(companyBtn, true);
        companyBtn?.setAttribute('aria-expanded', 'true');
    }

    function closeMobileMenu() {
        mobileMenu?.classList.add('hidden');
        bcNavBar?.classList.remove('bc-nav-bar--menu-open');
        if (menuIconOpen) {
            menuIconOpen.classList.remove('is-hidden', 'hidden');
            menuIconOpen.style.display = 'block';
        }
        if (menuIconClose) {
            menuIconClose.classList.add('is-hidden', 'hidden');
            menuIconClose.style.display = 'none';
        }
        document.body.classList.remove('overflow-hidden');
    }

    function closeSearchPanel() {
        searchWrap?.classList.remove('is-expanded');
        navBarRow?.classList.remove('bc-nav-row--search-open');
        desktopSearchToggle?.classList.remove('is-active');
        desktopSearchToggle?.setAttribute('aria-expanded', 'false');
    }

    function closeCountryDrawer() {
        window.bcCountryDrawer?.close();
    }

    var accountBtn = document.getElementById('bcAccountMenuBtn');
    var accountPanel = document.getElementById('bcAccountMenuPanel');

    function closeAccountMenu() {
        if (!accountPanel) {
            return;
        }
        accountPanel.hidden = true;
        accountBtn?.setAttribute('aria-expanded', 'false');
    }

    function toggleAccountMenu() {
        if (!accountPanel) {
            return;
        }
        var willOpen = accountPanel.hidden;
        closeBrokersMenu();
        closePropFirmsMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeToolsMenu();
        closeSearchPanel();
        if (willOpen) {
            accountPanel.hidden = false;
            accountBtn?.setAttribute('aria-expanded', 'true');
        } else {
            closeAccountMenu();
        }
    }

    function initScrollToTop() {
        var btn = document.getElementById('scrollToTopBtn');
        if (!btn) {
            return;
        }
        var toggle = function () {
            btn.classList.toggle('is-visible', window.scrollY > 300);
        };
        window.addEventListener('scroll', toggle, { passive: true });
        toggle();
        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    function openSearchPanel() {
        closeBrokersMenu();
        closePropFirmsMenu();
        closeReviewsMenu();
        closeCompanyMenu();
        closeToolsMenu();
        closeMobileMenu();
        closeCountryDrawer();
        navBarRow?.classList.add('bc-nav-row--search-open');
        searchWrap?.classList.add('is-expanded');
        desktopSearchToggle?.classList.add('is-active');
        desktopSearchToggle?.setAttribute('aria-expanded', 'true');
        setTimeout(function () {
            document.getElementById('navBrokerSearch')?.focus();
        }, 30);
    }

    function bindHover(group, openFn, closeFn, timerKey) {
        if (!group) return;
        group.addEventListener('mouseenter', function () {
            if (timerKey === 'brokers') clearTimeout(brokersTimer);
            if (timerKey === 'propFirms') clearTimeout(propFirmsTimer);
            if (timerKey === 'reviews') clearTimeout(reviewsTimer);
            if (timerKey === 'company') clearTimeout(companyTimer);
            if (timerKey === 'tools') clearTimeout(toolsTimer);
            openFn();
        });
        group.addEventListener('mouseleave', function () {
            if (timerKey === 'brokers') brokersTimer = setTimeout(closeFn, 280);
            if (timerKey === 'propFirms') propFirmsTimer = setTimeout(closeFn, 280);
            if (timerKey === 'reviews') reviewsTimer = setTimeout(closeFn, 220);
            if (timerKey === 'company') companyTimer = setTimeout(closeFn, 220);
            if (timerKey === 'tools') toolsTimer = setTimeout(closeFn, 220);
        });
    }

    bindHover(brokersGroup, openBrokersMenu, closeBrokersMenu, 'brokers');
    bindHover(brokersMenu, openBrokersMenu, closeBrokersMenu, 'brokers');
    bindHover(propFirmsGroup, openPropFirmsMenu, closePropFirmsMenu, 'propFirms');
    bindHover(propFirmsMenu, openPropFirmsMenu, closePropFirmsMenu, 'propFirms');
    bindHover(reviewsGroup, openReviewsMenu, closeReviewsMenu, 'reviews');
    bindHover(companyGroup, openCompanyMenu, closeCompanyMenu, 'company');
    bindHover(companyMenu, openCompanyMenu, closeCompanyMenu, 'company');
    bindHover(toolsGroup, openToolsMenu, closeToolsMenu, 'tools');
    bindHover(toolsMenu, openToolsMenu, closeToolsMenu, 'tools');

    brokersBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        brokersMenu?.classList.contains('is-open') ? closeBrokersMenu() : openBrokersMenu();
    });

    propFirmsBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        propFirmsMenu?.classList.contains('is-open') ? closePropFirmsMenu() : openPropFirmsMenu();
    });

    companyBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        companyMenu?.classList.contains('hidden') ? openCompanyMenu() : closeCompanyMenu();
    });

    toolsBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        toolsMenu?.classList.contains('hidden') ? openToolsMenu() : closeToolsMenu();
    });

    reviewsLink?.addEventListener('click', function (e) {
        if (window.matchMedia('(min-width: 1024px)').matches && e.target.closest('.reviews-chevron, svg')) {
            e.preventDefault();
            reviewsMenu?.classList.contains('hidden') ? openReviewsMenu() : closeReviewsMenu();
        }
    });

    mobileBtn?.addEventListener('click', function () {
        const open = mobileMenu?.classList.contains('hidden');
        closeBrokersMenu(); closePropFirmsMenu(); closeReviewsMenu(); closeCompanyMenu(); closeToolsMenu(); closeSearchPanel(); closeCountryDrawer();
        if (open) {
            mobileMenu?.classList.remove('hidden');
            bcNavBar?.classList.add('bc-nav-bar--menu-open');
            if (menuIconOpen) {
                menuIconOpen.classList.add('is-hidden', 'hidden');
                menuIconOpen.style.display = 'none';
            }
            if (menuIconClose) {
                menuIconClose.classList.remove('is-hidden', 'hidden');
                menuIconClose.style.display = 'block';
            }
            document.body.classList.add('overflow-hidden');
        } else {
            closeMobileMenu();
        }
    });

    desktopSearchToggle?.addEventListener('click', function (e) {
        e.stopPropagation();
        if (!searchWrap?.classList.contains('is-expanded')) {
            openSearchPanel();
        } else {
            closeSearchPanel();
        }
    });

    document.getElementById('navBrokerSearch')?.addEventListener('focus', function () {
        closeBrokersMenu(); closePropFirmsMenu(); closeReviewsMenu(); closeCompanyMenu(); closeToolsMenu();
    });

    document.getElementById('navSearchForm')?.addEventListener('submit', function () {
        closeSearchPanel();
        closeMobileMenu();
    });

    document.getElementById('navSearchFormMobile')?.addEventListener('submit', function () {
        closeMobileMenu();
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#brokersNavGroup') && !e.target.closest('#brokersMegaMenu')) closeBrokersMenu();
        if (!e.target.closest('#propFirmsNavGroup') && !e.target.closest('#propFirmsMegaMenu')) closePropFirmsMenu();
        if (!e.target.closest('#reviewsNavGroup')) closeReviewsMenu();
        if (!e.target.closest('#companyNavGroup')) closeCompanyMenu();
        if (!e.target.closest('#toolsNavGroup')) closeToolsMenu();
        if (!e.target.closest('#desktopSearchWrap') && !e.target.closest('#mobileSearchRow')) closeSearchPanel();
        if (!e.target.closest('#bcAccountMenu')) closeAccountMenu();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeBrokersMenu(); closePropFirmsMenu(); closeReviewsMenu(); closeCompanyMenu(); closeToolsMenu(); closeMobileMenu(); closeSearchPanel(); closeCountryDrawer(); closeAccountMenu();
        }
    });

    window.addEventListener('bc:country-drawer-open', function () {
        closeBrokersMenu(); closePropFirmsMenu(); closeReviewsMenu(); closeCompanyMenu(); closeToolsMenu(); closeSearchPanel(); closeMobileMenu();
    });

    document.getElementById('mobileCountrySelectorBtn')?.addEventListener('click', function () {
        closeMobileMenu();
        window.bcCountryDrawer?.open();
    });

    document.querySelectorAll('.mobile-accordion-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = document.getElementById(btn.dataset.target);
            const chevron = btn.querySelector('.accordion-chevron');
            const isOpen = !target.classList.contains('hidden');
            document.querySelectorAll('.mobile-accordion [id^="mob-"]').forEach(function (el) {
                if (el.id !== btn.dataset.target) el.classList.add('hidden');
            });
            document.querySelectorAll('.accordion-chevron').forEach(function (c) {
                if (c !== chevron) c.classList.remove('rotate-180');
            });
            target.classList.toggle('hidden', isOpen);
            chevron?.classList.toggle('rotate-180', !isOpen);
        });
    });

    accountBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        toggleAccountMenu();
    });

    initScrollToTop();
});
