(function () {
    'use strict';

    function closeAllDropdowns(except) {
        document.querySelectorAll('.adm-dropdown--open').forEach(function (el) {
            if (el !== except) {
                el.classList.remove('adm-dropdown--open');
                el.classList.add('adm-dropdown--closed');
            }
        });
        document.querySelectorAll('[aria-expanded="true"]').forEach(function (btn) {
            if (!except || !except.contains(btn)) {
                btn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    function toggleDropdown(panel, btn) {
        var isOpen = panel.classList.contains('adm-dropdown--open');
        closeAllDropdowns();
        if (!isOpen) {
            panel.classList.remove('adm-dropdown--closed');
            panel.classList.add('adm-dropdown--open');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        }
    }

    function initSearch(inputId, resultsId) {
        var input = document.getElementById(inputId);
        var results = document.getElementById(resultsId);
        if (!input || !results) return;

        var debounce;

        input.addEventListener('input', function () {
            clearTimeout(debounce);
            var query = input.value.trim();

            if (query.length < 2) {
                results.classList.remove('adm-dropdown--open');
                results.classList.add('adm-dropdown--closed');
                results.innerHTML = '';
                return;
            }

            debounce = setTimeout(function () {
                fetch('/admin/search?q=' + encodeURIComponent(query), {
                    headers: { Accept: 'application/json' },
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var items = data.results || [];
                        if (!items.length) {
                            results.innerHTML = '<div class="tw-py-6 tw-text-center tw-text-sm tw-text-slate-400">No results found</div>';
                        } else {
                            results.innerHTML = items.map(function (item) {
                                var type = item.type === 'broker' ? 'Broker' : 'Page';
                                return '<a href="' + item.url + '" class="adm-search-result-item">' +
                                    '<span>' + item.label + '</span><small>' + type + '</small></a>';
                            }).join('');
                        }
                        results.classList.remove('adm-dropdown--closed');
                        results.classList.add('adm-dropdown--open');
                    });
            }, 250);
        });

        input.addEventListener('focus', function () {
            if (results.innerHTML.trim()) {
                results.classList.add('adm-dropdown--open');
            }
        });
    }

    initSearch('admTopbarSearch', 'admTopbarSearchResults');
    initSearch('admTopbarSearchMobile', 'admTopbarSearchResultsMobile');

    var notifyBtn = document.getElementById('admNotifyBtn');
    var notifyPanel = document.getElementById('admNotifyPanel');
    if (notifyBtn && notifyPanel) {
        notifyBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown(notifyPanel, notifyBtn);
        });
    }

    var profileBtn = document.getElementById('admProfileBtn');
    var profilePanel = document.getElementById('admProfilePanel');
    if (profileBtn && profilePanel) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDropdown(profilePanel, profileBtn);
        });
    }

    var mobileSearchBtn = document.getElementById('admMobileSearchBtn');
    var mobileSearchBar = document.getElementById('admMobileSearchBar');
    if (mobileSearchBtn && mobileSearchBar) {
        mobileSearchBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileSearchBar.classList.toggle('adm-mobile-search--open');
            mobileSearchBar.classList.toggle('adm-mobile-search--closed');
            document.getElementById('admTopbarSearchMobile')?.focus();
        });
    }

    document.addEventListener('click', function () {
        closeAllDropdowns();
        mobileSearchBar?.classList.remove('adm-mobile-search--open');
        mobileSearchBar?.classList.add('adm-mobile-search--closed');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAllDropdowns();
    });
})();
